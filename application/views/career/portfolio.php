<!-- banner start
    ================================================== -->
    <section class="main_slider innerbanner">
      <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" alt="img" onerror="this.onerror=null;this.src='<?=g('images_root').'inner-banner.jpg'?>';"/>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="inner-banner-txt">
              <h1><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Portfolio' ?></h1>
            </div>
          </div>
        </div>
      </div>
    </section>
<!-- banner end
    ================================================ -->

     <!-- Gallery Start
    ================================================ -->

    <section class="build-hood-sec">
        <div class="container">

            <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 centerCol">
            <div class="about-txt prof-head">
                <?php if(isset($cms[0]['cms_page_content'])): ?>
                    <?= html_entity_decode($cms[0]['cms_page_content']); ?>
                <?php else: ?>
                    <h3>Our <span class="yellow">POrtfolio </span></h3>
                    <p>Lorem ipsum dolor sit amet, adipiscing elit, sed  eiusmod tempor incididunt  labore dolore magna aliqua.</p>
                <?php endif; ?>
            </div>
          </div>
        </div>

            <div class="row mt-8 wow fadeInUp">

                <?php foreach($portfolio_image as $key => $value): ?>
                    <div class="<?= (count($portfolio_image) % 3 == 0) ? 'col-md-4 col-lg-4' : (count($portfolio_image) % 4 == 0 ? 'col-md-3 col-lg-3' : 'col-md-2 col-lg-2') ?>">
                        <div class="gallery-img">
                            <img class="lazy" data-src="<?= get_image($value['portfolio_image_path'], $value['portfolio_image_name']) ?>" alt="" onerror="this.onerror=null;this.dataset.src='<?=g('images_root').'not-found.jpg'?>';">
                            <a href="#" fancybox="<?= get_image($value['portfolio_image_path'], $value['portfolio_image_name']) ?>"><i class="far fa-search"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- Gallery end
    ================================================ -->

    <script>
        $(function() {
            $('.lazy').lazy();
        });
    </script>