<!--banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Experts' ?>

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

</section>

<!-- banner end -->

<section class="expert-sec">

    <div class="container px-5">

        <div class="row align-items-center">

            <div class="col-lg-6 wow slideInLeft">
                <?php if (isset($cms[0]['cms_page_content'])) : ?>
                    <?= html_entity_decode($cms[0]['cms_page_content']) ?>
                <?php else : ?>
                    <h2>Find The <span> Experts</span></h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid <br> idunt ut labore et dolore magna aliqua ut enim ad minim veniam.</p>
                <?php endif; ?>
            </div>

            <div class="col-lg-6 wow bounceIn">

                <div class="btn-wrap">

                    <a href="" class="btn-1">Biotechnology</a>

                    <a href="" class="btn-1 yl">Health Care</a>

                </div>

            </div>

        </div>

        <div class="row mt-5 wow fadeInUpBig">
            <?php if (isset($job) && count($job) > 0) : ?>
                <?php foreach ($job as $key => $value) : ?>
                    <div class="col-lg-4">

                        <div class="expert-box">

                            <div class="u-box">

                                <a href="<?= l('job/detail/') . (isset($value['job_slug']) ? $value['job_slug'] : '') ?>">
                                    <h1 class="m-0">
                                        <?= isset($value['job_title']) && $value['job_title'] ? strtoupper($value['job_title'][0]) : '&#183;' ?>
                                    </h1>
                                </a>

                            </div>

                            <a href="<?= l('job/detail/') . (isset($value['job_slug']) ? $value['job_slug'] : '') ?>">

                                <h3><?= isset($value['job_title']) ? $value['job_title'] : '..' ?></h3>

                            </a>

                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                <p><?= isset($value['job_short_detail']) ? $value['job_short_detail'] : '...' ?></p>
                            <?php endif; ?>

                            <div class="tag-ts"><i class="fa-solid fa-circle-check"></i> <?= isset($value['job_type']) ? $value['job_type'] : '..' ?></div>

                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>

                                <div class="badges">

                                    <span><i class="fa-light fa-briefcase"></i>
                                        <?php if (isset($value['job_category']) && $value['job_category'] != NULL && @unserialize($value['job_category']) !== FALSE && is_array(unserialize($value['job_category']))) : ?>
                                            <?php foreach (unserialize($value['job_category']) as $ke => $val) : ?>
                                                <?= trim(($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '')) ?>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            ...
                                        <?php endif; ?>
                                    </span> <br>

                                    <span><i class="fa-light fa-location-dot"></i> <a href="https://maps.google.com/?q=<?= $value['job_location'] ?>" target="_blank"> <?= explode(',', $value['job_location'])[0] ?? 'Na' ?> </a></span>

                                    <span>
                                        <i class="fa-light fa-circle-dollar-to-slot"></i>
                                        <?= (isset($value['job_salary_lower']) ? price($value['job_salary_lower']) : price(0)) . (isset($value['job_salary_upper']) ? (' - ' . price($value['job_salary_upper']) . ((isset($value['job_salary_interval']) ? ' / ' . $value['job_salary_interval'] : ''))) : '') ?>
                                    </span>

                                </div>

                            <?php endif; ?>

                            <div class="d-flex">

                                <?php $job_tags = isset($value['job_tags']) ? explode(',', $value['job_tags']) : array(); ?>

                                <?php foreach ($job_tags as $ke1 => $val1) : ?>
                                    <div class="specify <?= $ke1 % 2 == 0 ? 'yll' : '' ?>"><?= $val1 ?></div>
                                <?php endforeach; ?>

                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>

                <?php if (isset($additional_job) && count($additional_job) > 0) : ?>
                    <div class="col-12">

                        <div class="load-more mt-5">

                            <a href="javascript:;" class="btn-1">Load More</a>

                            <img src="<?= g('images_root') ?>loader.png" alt="" />

                        </div>

                    </div>
                <?php endif; ?>

                <?php if (isset($additional_job) && count($additional_job) > 0) : ?>
                    <?php foreach ($additional_job as $key => $value) : ?>

                        <div class="col-lg-4 additional_job d-none">

                            <div class="expert-box">

                                <div class="u-box">

                                    <a href="<?= l('job/detail/') . (isset($value['job_slug']) ? $value['job_slug'] : '') ?>">
                                        <h1 class="m-0">
                                            <?= isset($value['job_title']) && $value['job_title'] ? strtoupper($value['job_title'][0]) : '&#183;' ?>
                                        </h1>
                                    </a>

                                </div>

                                <a href="<?= l('job/detail/') . (isset($value['job_slug']) ? $value['job_slug'] : '') ?>">

                                    <h3><?= isset($value['job_title']) ? $value['job_title'] : '..' ?></h3>

                                </a>

                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <p><?= isset($value['job_short_detail']) ? $value['job_short_detail'] : '...' ?></p>
                                <?php endif; ?>

                                <div class="tag-ts"><i class="fa-solid fa-circle-check"></i> <?= isset($value['job_type']) ? $value['job_type'] : '..' ?></div>

                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>

                                    <div class="badges">

                                        <span><i class="fa-light fa-briefcase"></i>
                                            <?php if (isset($value['job_category']) && $value['job_category'] != NULL && @unserialize($value['job_category']) !== FALSE && is_array(unserialize($value['job_category']))) : ?>
                                                <?php foreach (unserialize($value['job_category']) as $ke => $val) : ?>
                                                    <?= trim(($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '')) ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                ...
                                            <?php endif; ?>
                                        </span> <br>

                                        <span><i class="fa-light fa-location-dot"></i> <a href="https://maps.google.com/?q=<?= $value['job_location'] ?>" target="_blank"> <?= explode(',', $value['job_location'])[0] ?? 'Na' ?> </a></span>

                                        <span>
                                            <i class="fa-light fa-circle-dollar-to-slot"></i>
                                            <?= (isset($value['job_salary_lower']) ? price($value['job_salary_lower']) : price(0)) . (isset($value['job_salary_upper']) ? (' - ' . price($value['job_salary_upper']) . ((isset($value['job_salary_interval']) ? ' / ' . $value['job_salary_interval'] : ''))) : '') ?>
                                        </span>

                                    </div>

                                <?php endif; ?>

                                <div class="d-flex">

                                    <?php $job_tags = isset($value['job_tags']) ? explode(',', $value['job_tags']) : array(); ?>

                                    <?php foreach ($job_tags as $ke1 => $val1) : ?>
                                        <div class="specify <?= $ke1 % 2 == 0 ? 'yll' : '' ?>"><?= $val1 ?></div>
                                    <?php endforeach; ?>

                                </div>

                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    No more jobs available!
                <?php endif; ?>
        </div>
    <?php endif; ?>

    </div>

</section>
