<!-- banner start -->

<section class="banner inner-banner">

    <!-- <img src="<?= g('images_root') ?>inner-banner.jpg" alt="">

<div class="baner-cnt"> -->

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Job Listing' ?>

                    </h1>

                </div>

            </div>
            <div class="col-lg-6">
                <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            </div>

        </div>

    </div>

    <!-- </div> -->

</section>

<!-- banner end -->

<section class="expert-sec">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-4">

                <div class="filter-feld">

                    <select class="categorySelect" onchange="categorySwitch(this)">

                        <option value="0">Filter By Catagories</option>
                        <?php if (isset($job_category) && count($job_category) > 0) : ?>
                            <?php foreach ($job_category as $key => $val) : ?>
                                <option value="<?= $val['job_category_id']; ?>" <?= $selected_category == $val['job_category_id'] ? 'selected' : '' ?>><?= $val['job_category_name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <i class="fa-regular fa-angle-down"></i>

                </div>

            </div>

            <div class="col-lg-4">

                <form class="searchKeyword" method="POST" action="javascript:;">

                    <div class="filter-feld">

                        <input type="text" placeholder="Jobs search" id="filter-search" name="search" maxlength="500" value="<?= urldecode($search) ?? '' ?>" />

                        <i class="fa-light fa-magnifying-glass"></i>

                    </div>

                </form>

            </div>

            <div class="col-lg-4 text-right lsting">

                <span>Sort: Most Recent</span>

                <span>View:
                    <?php switch ($limit):
                        case 9:
                            echo '09 | <a href="' . l('job/index/') . $page . '/18/' . $selected_category  . '/' . $search . '">18</a>';
                            break;
                        case 18:
                            echo '<a href="' . l('job/index/') . $page . '/9/' . $selected_category . '/' . $search . '">09</a> | 18';
                            break;
                    endswitch;
                    ?>
                </span>

            </div>

        </div>

        <div class="row mt-5 wow fadeInUpBig">
            <?php if (isset($job) && count($job) > 0) : ?>
                <?php foreach ($job as $key => $value) : ?>
                    <div class="col-lg-4">

                        <div class="expert-box">

                            <div class="u-box">

                                <!-- <img src="<?= g('images_root') ?>u-img.png" alt=""> -->
                                <h1 class="m-0"><?= isset($value['job_title']) && $value['job_title'] ? strtoupper($value['job_title'][0]) : '&#183;' ?></h1>

                            </div>

                            <a href="<?= l('job/detail/') . (isset($value['job_slug']) ? $value['job_slug'] : '') ?>">

                                <h3><?= isset($value['job_title']) ? $value['job_title'] : '..' ?></h3>

                            </a>

                            <p><?= isset($value['job_short_detail']) ? $value['job_short_detail'] : '...' ?></p>

                            <div class="tag-ts">
                                <i class="fa-solid fa-circle-check"></i>
                                <?= isset($value['job_type']) ? $value['job_type'] : '..' ?>
                            </div>

                            <div class="badges">

                                <span><i class="fa-light fa-briefcase"></i>
                                    <?php if (isset($value['job_category']) && $value['job_category'] != NULL && @unserialize($value['job_category']) !== FALSE && is_array(unserialize($value['job_category']))) : ?>
                                        <?php foreach (unserialize($value['job_category']) as $ke => $val) : ?>
                                            <?= ($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '') ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        ...
                                    <?php endif; ?>
                                </span> <br>

                                <span><i class="fa-light fa-location-dot"></i>
                                    <?php if (isset($value['job_location'])) : ?>
                                        <a href="https://maps.google.com/?q=<?= $value['job_location'] ?>" target="_blank"> <?= explode(',', $value['job_location'])[0] ?? 'Na' ?></a>
                                    <?php else : ?>
                                        ...
                                    <?php endif; ?>
                                </span>

                                <span><i class="fa-light fa-circle-dollar-to-slot"></i>
                                    <?= (isset($value['job_salary_lower']) ? price($value['job_salary_lower']) : price(0)) . (isset($value['job_salary_upper']) ? (' - ' . price($value['job_salary_upper']) . ((isset($value['job_salary_interval']) ? ' / ' . $value['job_salary_interval'] : ''))) : '') ?>
                                </span>

                            </div>

                            <div class="d-flex">

                                <?php $job_tags = isset($value['job_tags']) ? explode(',', $value['job_tags']) : array(); ?>

                                <?php foreach ($job_tags as $ke1 => $val1) : ?>
                                    <div class="specify <?= $ke1 % 2 == 0 ? 'yll' : '' ?>"><?= $val1 ?></div>
                                <?php endforeach; ?>
                                <!-- <div class="specify yll">Urgent</div>

                <div class="specify">Internship</div> -->

                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                No Jobs Available!
            <?php endif; ?>
        </div>

        <?php if (isset($job) && count($job) > 0) : ?>
            <div class="row">
                <div class="col-lg-12">

                    <nav aria-label="Page navigation example mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($page <= 1) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" href="<?php if ($page <= 1) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo l('job/index/') . $prev . '/' . $limit . '/' . $selected_category . '/' . $search;
                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) {
                                                            echo 'active';
                                                        } ?>">
                                    <a class="page-link" href="<?= l('job/index/') . $i . '/' . $limit . '/' . $selected_category . '/' . $search; ?>"> <?= $i; ?> </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php if ($page >= $totalPages) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" href="<?php if ($page >= $totalPages) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo l('job/index/') . $next . '/' . $limit . '/' . $selected_category . '/' . $search;
                                                                        } ?>"><i class="far fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        <?php endif; ?>

    </div>

</section>

<script>
    // filter out by category
    function categorySwitch(ele) {
        if (ele.value != "") {
            window.location.href = '<?= l('job/index/') . $page . '/' . $limit ?>' + '/' + ele.value + '<?= '/' . $search ?>';
        }
    }

    // filter out by search keyword
    $('.searchKeyword').on('submit', function() {
        if ($('#filter-search').val() != "") {
            // job/index/{$page}/{$limit}/{category_filter}/{search}
            window.location.href = '<?= l('job/index/') . $page . '/' . $limit . '/' . $selected_category ?>' + '/' + $('#filter-search').val();
        }
    })
</script>