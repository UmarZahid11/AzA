 <!-- banner start -->

 <!--<section class="banner inner-banner">-->

 <!--    <div class="container">-->

 <!--        <div class="row justify-content-center">-->

 <!--            <div class="col-lg-6">-->

 <!--                <div class="banner-cont inner-banner-text wow fadeInLeft">-->

 <!--                    <h1>-->

 <!--                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Partners' ?>-->

 <!--                    </h1>-->

 <!--                </div>-->

 <!--            </div>-->
 <!--            <div class="col-lg-6">-->
 <!--                <div class="inner-banner">-->
 <!--                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
 <!--                </div>-->
 <!--            </div>-->

 <!--        </div>-->

 <!--    </div>-->

     <!-- </div> -->

 <!--</section>-->
 
 <section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription' ?></h2>
            </div>
            
        </div>
    </div>
</section>

 <!-- banner end -->

 <section class="blog-det-sec">

     <div class="container">
         <?php if (isset($partner_images) && count($partner_images) > 0) : ?>
             <?php foreach ($partner_images as $argk => $argv) : ?>
                 <div class="partner-slider wow slideInRight partnerImg">
                     <?php foreach ($argv as $key => $value) : ?>
                         <img data-src="<?= get_image($value['partner_image_path'], $value['partner_image_name']) ?>" src="<?= get_image($value['partner_image_path'], $value['partner_image_name']) ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                     <?php endforeach; ?>
                 </div>
             <?php endforeach; ?>
         <?php else : ?>
             <img src="<?= g('images_root') ?>pt1.jpg" alt="" />
             <img src="<?= g('images_root') ?>pt2.jpg" alt="" />
             <img src="<?= g('images_root') ?>pt3.jpg" alt="" />
             <img src="<?= g('images_root') ?>pt4.jpg" alt="" />
         <?php endif; ?>

     </div>

 </section>