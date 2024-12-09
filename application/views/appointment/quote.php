<!-- banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">
                <div class="banner-cont inner-banner-text wow fadeInLeft">
                    <h1>
                        <?= isset($banner['inner_banner_title']) ? 'Quote' : 'Quote' ?>
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