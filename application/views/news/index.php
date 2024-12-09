<!-- banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'News' ?>

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

            <?php if (isset($news) && count($news) > 0) : ?>
                <?php foreach ($news as $key => $new) : ?>
                    <div class="col-lg-4 col-md-6">

                        <div class="succes-box">

                            <img class="lazy" data-src="<?= get_image($new['news_attachment_path'], $new['news_attachment']) ?>" src="<?= get_image($new['news_attachment_path'], $new['news_attachment']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />

                            <div class="ss-cont">

                                <span><?= $new['news_author'] ?></span>

                                <span class="float-right"><?= date("F d, Y", strtotime($new['news_createdon'])) ?></span>

                                <a href="<?= l('news/detail/') . $new['news_slug'] ?>">

                                    <h3><?= $new['news_title'] ?? "..." ?></h3>

                                </a>

                                <?php
                                $tags = $this->model_tag->find_all_active(
                                    array(
                                        'where' => array(
                                            'tag_reference_type' => REFERENCE_TYPE_NEWS,
                                            'tag_reference_id' => $new['news_id']
                                        )
                                    )
                                );
                                ?>

                                <?php if (isset($tags) && !empty($tags)) : ?>
                                    <?php foreach ($tags as $key => $tag) : ?>
                                        <span class="badge bg-custom"><?= $tag['tag_name'] ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <p><?= $new['news_short_desc'] ?? "..." ?></p>

                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <small>No news available</small>
            <?php endif; ?>

        </div>

    </div>

    <?php if (isset($news) && count($news) > 0) : ?>
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
                                                                        echo l('news/index/') . $prev;
                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('news/index/') . $i; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php if ($page >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" href="<?php if ($page >= $totalPages) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo l('news/index/') . $next;
                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    <?php endif; ?>
</section>