<!-- banner start -->

<section class="banner inner-banner">

    <!-- <img src="<?= g('images_root') ?>inner-banner.jpg" alt="">

<div class="baner-cnt"> -->

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?=  isset($status) ? strtoupper($status) : 'Unknown' ?>

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

<section class="member-ship-sec">

    <div class="container">
        <?php switch($status):
            case 'success':
                echo '<label class="text-success">Donation Successful!</label>';
                break;

            case 'failed':
                echo '<label class="text-danger">Oops! An error occurred while processing payment!</label>';
                break;
                
            case 'In-process':
                echo '<label class="text-success">Donation in currently process, you will get an email soon!</label>';
                break;
        endswitch; ?>
    </div>

</section>