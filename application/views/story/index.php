<!-- banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Story' ?>

                    </h1>

                </div>

            </div>
            <div class="col-lg-6">
                <div class="inner-banner">

                    <img class="lazy" data-src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />

                </div>
            </div>

        </div>

    </div>

    <!-- </div> -->

</section>

<!-- banner end -->

<section class="blog-sec">

    <div class="container">

        <div class="row">

            <?php if (isset($story) && count($story) > 0) : ?>
                <?php foreach ($story as $key => $value) : ?>
                    <div class="col-lg-4 col-md-6">

                        <div class="succes-box">

                            <img class="lazy" data-src="<?= get_image($value['story_image_path'], $value['story_image']) ?>" src="<?= get_image($value['story_image_path'], $value['story_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />

                            <div class="ss-cont">

                                <span><?= $value['story_author'] ?> | Comment, 0 </span>

                                <span><?= date("d/m/Y", strtotime($value['story_createdon'])) ?></span>

                                <a href="<?= l('story/detail/') . $value['story_slug'] ?>">

                                    <h3><?= $value['story_title'] ?? "..." ?></h3>

                                </a>

                                <p><?= $value['story_short_detail'] ?? "..." ?></p>

                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <small>No stories available</small>
            <?php endif; ?>

        </div>

    </div>

    <?php if (isset($story) && count($story) > 0) : ?>
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
                                                                        echo l('story/index/') . $prev;
                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('story/index/') . $i; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php if ($page >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" href="<?php if ($page >= $totalPages) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo l('story/index/') . $next;
                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    <?php endif; ?>
</section>