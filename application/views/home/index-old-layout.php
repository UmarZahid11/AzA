<!-- banner start -->

<section class="banner">

    <div class="container">

        <div class="row align-items-center justify-content-center">

            <div class="col-12 text-center">

                <div class="banner-cont wow fadeInLeft">
                    <?php if (isset($banner['banner_id'])) : ?>
                        <!--<div class="loader-wrapper">-->
                        <!--             <div class="loader-wrapper-cell">-->
                        <!--                 <div>-->
                        <!--                     <div style="margin: 0 0 30px">-->
                        <!--                         <div class="loader-text-heading" style="width: 400px;"> </div>-->
                        <!--                         <div class="loader-text-heading" style="width: 600px;"> </div>-->
                        <!--                         <div class="loader-text-heading" style="width: 450px;"> </div>-->
                        <!--                         <div class="loader-text-heading" style="width: 400px;"> </div>-->
                        <!--                     </div>-->
                        <!--                     <div style="    margin-bottom: 1rem;">-->
                        <!--                         <div class="loader-text-line" style="width: 600px;"></div>-->
                        <!--                         <div class="loader-text-line" style="width: 590px;"></div>-->
                        <!--                         <div class="loader-text-line" style="width: 580px;"></div>-->
                        <!--                     </div>-->
                        <!--                 </div>-->
                        <!--             </div>-->
                        <!--         </div>-->
                        <?= html_entity_decode($banner['banner_sub_heading']) ?>
                        <?= html_entity_decode($banner['banner_description']) ?>
                    <?php else : ?>
                        <h1>
                            Hire Freelancer <span> Biotechnology</span> & Healthcare <span> Experts</span>
                            <!--<img src="<?= g('images_root') ?>baner-left.png" alt="" />-->
                        </h1>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <?php endif; ?>


                    <!--<form class="searchJob" method="POST" action="javascript:;">-->

                    <!--    <div class="banr-btn-flex">-->

                    <!--        <div class="search-bx">-->

                    <!--            <input type="text" placeholder="Search" name="search">-->

                    <!--            <button><i class="fa-regular fa-magnifying-glass"></i></button>-->

                    <!--        </div>-->

                    <!--        <button type="submit" class="btn-1">Apply Now</button>-->

                    <!--    </div>-->

                    <!--</form>-->

                </div>

            </div>

            <div class="col-12 text-center">

                <div class="baner-img wow fadeInUpBig">
                    <?php if (isset($banner['banner_image'])) : ?>
                        <img class="lazy" data-src="<?= get_image($banner['banner_image_path'], $banner['banner_image']) ?>" src="https://via.placeholder.com/596x336?text=..." alt="" onerror="this.onerror=null;this.src='https://via.placeholder.com/596x336?text=...';" />
                    <?php else : ?>
                        <img src="<?= g('images_root') ?>banner-sd.png" alt="" />
                    <?php endif; ?>

                </div>
                <a href="<?= l('signup') ?>" class="btn-1 w-50">Join us</a>

            </div>

        </div>

    </div>

</section>

<!-- banner end -->

<!--<div class="partner-sec">-->

<!--    <div class="containesr">-->

<!--        <div class="partner-slider wow slideInRight">-->

<!--            <?php if (isset($partner_image) &&  count($partner_image) > 0) : ?>-->
<!--                <?php foreach ($partner_image  as $key => $value) : ?>-->
<!--                    <img data-lazy="<?= get_image($value['partner_image_path'], $value['partner_image_name']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--                <?php endforeach; ?>-->
<!--            <?php else : ?>-->

<!--                <img src="<?= g('images_root') ?>part1.png" alt="">-->

<!--                <img src="<?= g('images_root') ?>part2.png" alt="">-->

<!--                <img src="<?= g('images_root') ?>part3.png" alt="">-->

<!--                <img src="<?= g('images_root') ?>part4.png" alt="">-->

<!--                <img src="<?= g('images_root') ?>part5.png" alt="">-->

<!--                <img src="<?= g('images_root') ?>part6.png" alt="">-->

<!--                <img src="<?= g('images_root') ?>part1.png" alt="">-->

<!--            <?php endif; ?>-->

<!--        </div>-->

<!--    </div>-->

<!--</div>-->

<section class="section-padd-rep abouut">
    <div class="container">
        <div class="row align-items-center justify-content-center">

            <div class="col-md-12">
                <div class="abt-bx-st">
                    <!--<h3><?= isset($cms[0]['cms_page_title']) ? $cms[0]['cms_page_title'] : 'About Us'; ?></h3>-->
                    <!-- <h3>What is <span class="hd-stlse">AzAverze</span></h3> -->
                    <?php if (isset($cms[0]['cms_page_content'])) : ?>
                        <?= html_entity_decode($cms[0]['cms_page_content']); ?>
                        <a href="<?= l('signup') ?>" class="btn-1 w-75">Join us</a>
                    <?php else : ?>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="icoo-boxx">
                                    <img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" />
                                    <h4>Title goes here</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icoo-boxx">
                                    <img src="https://azaverze.com/stagging/assets/front_assets/images/icoo.jpg" alt="Icon" />
                                    <h4>Title goes here</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!--<div class="col-md-6">-->
            <!--	<img src="https://via.placeholder.com/636x580?text=..." data-src="https://azaverze.com/stagging/assets/front_assets/images/Image20240329233419.jpg" class="w-100 lazy" alt="Side Image" onerror="this.onerror=null;this.src='https://via.placeholder.com/636x580?text=...';" />-->
            <!--</div>-->
        </div>
    </div>
</section>

<!-- <section class="conecnt-sec">

  <div class="container">

    <div class="row align-items-end">

      <div class="col-lg-6 wow bounceIn">

        <img class="lazy" data-src="<? //= get_image($cms[1]['cms_page_image_path'], $cms[1]['cms_page_image'])
                                    ?>" src="<? //= get_image($cms[1]['cms_page_image_path'], $cms[1]['cms_page_image'])
                                                ?>" alt="" onerror="this.onerror=null;this.src='<? //= g('images_root') . 'dummy-image.jpg'
                                                                                                ?>';" />

      </div>

      <div class="col-lg-6 wow slideInRight">

        <?php //if (isset($cms[1]['cms_page_content'])) :
        ?>
          <? //= html_entity_decode($cms[1]['cms_page_content'])
            ?>
        <?php //else :
        ?>
          <p>Are you an individual with life sciences skills and experience who is looking to flexibly support efforts to develop life changing, transformational novel, medicines? </p>
          <p>Are you a life sciences organization looking for talent to flexibly support the development and execution of your value creating projects?</p>
          <p>If so, come join the AzAverze platform to meet, interact and engage with life science professionals and work on life science projects that may ultimately improve the quality of lives.</p>
        <?php //endif;
        ?>
      </div>

    </div>

  </div>

</section> -->

<!-- <section class="succes-story-sec">

  <div class="container">

    <div class="row align-items-center wow fadeInLeft">

      <div class="col-lg-6">

        <?php //if (isset($cms[2]['cms_page_content'])) :
        ?>
          <? //= html_entity_decode($cms[2]['cms_page_content'])
            ?>
        <?php //else :
        ?>
          <h2>Our Succes <span> Stories</span></h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid <br> idunt ut labore et dolore magna aliqua ut enim ad minim veniam.</p>
        <?php // endif;
        ?>
      </div>

      <div class="col-lg-6 wow bounceIn">

        <div class="slid-numbr">
          <?php //if (count($story) > 0) :
            ?>
            01 / <span> <? //= 0 . count($story)
                        ?></span>
          <?php //else :
            ?>
            0
          <?php //endif;
            ?>

        </div>

      </div>

    </div>

    <div class="succes-slider wow fadeInUpBig">

      <?php //if (isset($story) && count($story)) :
        ?>
        <?php //foreach ($story as $key => $value) :
        ?>
          <div class="succes-box">
            <img class="lazy" data-src="<? //= get_image($value['story_image_path'], $value['story_image'])
                                        ?>" src="<? //= get_image($value['story_image_path'], $value['story_image'])
                                                    ?>" alt="" onerror="this.onerror=null;this.src='<? //= g('images_root') . 'dummy-image.jpg'
                                                                                                    ?>';" />

            <div class="ss-cont">

              <span><? //= $value['story_author'] ?? 'Anonymous'
                    ?> | Comment, 0 </span>

              <span><? //= date("d/m/Y", strtotime($value['story_createdon']))
                    ?></span>

              <a href="<? //= l('blog/detail/') . $value['story_slug'] . '/1'
                        ?>">

                <h3><? //= $value['story_title'] ?? "..."
                    ?></h3>

              </a>

              <p><? //= $value['story_short_detail'] ?? "..."
                    ?></p>

            </div>
          </div>
        <?php //endforeach;
        ?>
      <?php //endif;
        ?>

    </div>

  </div>

</section>

<section class="faqs-sec">

  <div class="container">

    <div class="row">

      <div class="col-lg-6 wow bounceIn">

        <img class="lazy" data-src="<? //= get_image($cms[3]['cms_page_image_path'], $cms[3]['cms_page_image'])
                                    ?>" src="<? //= get_image($cms[3]['cms_page_image_path'], $cms[3]['cms_page_image'])
                                                ?>" alt="" onerror="this.onerror=null;this.src='<? //= g('images_root') . 'dummy-image.jpg'
                                                                                                ?>';" />

      </div>

      <div class="col-lg-6 wow fadeInUp">

        <?php //if (isset($cms[3]['cms_page_content'])) :
        ?>
          <? //= html_entity_decode($cms[3]['cms_page_content'])
            ?>
        <?php // else :
        ?>
          <h2>Faq’s</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <?php // endif;
        ?>

        <div class="accordion mt-5" id="accordionExample">

          <?php // if (isset($faq) && count($faq) > 0) :
            ?>
            <?php // foreach ($faq as $key => $value) :
            ?>
              <div class="accordion-item">

                <h2 class="accordion-header" id="headingOne<? //= $key
                                                            ?>">

                  <button class="accordion-button <? //= $key == 0 ? '' : 'collapsed'
                                                    ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<? //= $key
                                                                                                                            ?>" aria-expanded="true" aria-controls="collapseOne">

                    <? //= $value['faq_title'] ?? '...'
                    ?>

                    <i class="fa-regular fa-plus"></i>

                    <i class="fa-regular fa-minus"></i>

                  </button>

                </h2>

                <div id="collapseOne<? //= $key
                                    ?>" class="accordion-collapse collapse <? //= $key == 0 ? ' show' : ''
                                                                            ?>" aria-labelledby="headingOne<? //= $key
                                                                                                            ?>" data-bs-parent="#accordionExample">

                  <div class="accordion-body">

                    <? //= $value['faq_content'] ?? '...'
                    ?>

                  </div>

                </div>

              </div>
            <?php //endforeach;
            ?>
          <?php //else :
            ?>
            <div class="accordion-item">

              <h2 class="accordion-header" id="headingOne">

                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                  Your Heading Here!

                  <i class="fa-regular fa-plus"></i>

                  <i class="fa-regular fa-minus"></i>

                </button>

              </h2>

              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

                <div class="accordion-body">

                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor

                  in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.

                </div>

              </div>

            </div>
          <?php // endif;
            ?>


        </div>

      </div>

    </div>

  </div>

</section>

<section class="two-box-sec">

  <div class="container">

    <div class="row">

      <div class="col-lg-6 wow bounceInDown">

        <div class="srv-box">

          <?php //if (isset($cms[6]['cms_page_content'])) :
            ?>
            <? //= html_entity_decode($cms[6]['cms_page_content'])
            ?>
          <?php //else :
            ?>
            <h2>Healthcare</h2>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

              Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis

              unde omnis iste natus error sit voluptatem accusantium doloremque.</p>

          <?php //endif;
            ?>

          <a href="" class="btn-1">Find An Expert</a>

        </div>

      </div>

      <div class="col-lg-6 wow bounceInDown">

        <div class="srv-box invert">

          <?php //if (isset($cms[6]['cms_page_content_2'])) :
            ?>
            <? //= html_entity_decode($cms[6]['cms_page_content_2'])
            ?>
          <?php //else :
            ?>

            <h2>Healthcare</h2>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

              Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis

              unde omnis iste natus error sit voluptatem accusantium doloremque.</p>

          <?php //endif;
            ?>

          <a href="" class="btn-1">Find An Expert</a>

        </div>

      </div>

    </div>

  </div>

</section> -->

<!-- testiom-sec -->
<!--
<section class="testiom-sec themes-padd">

  <div class="container">

    <div class="row">

      <div class="col-lg-12 text-center wow fadeInUp">

        <?php //if (isset($cms[4]['cms_page_content'])) :
        ?>
          <? //= html_entity_decode($cms[4]['cms_page_content'])
            ?>
        <?php //else :
        ?>
          <h2 class="themes-h2-w">What Our Client Say</h2>

          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor <br> incididunt ut labore et dolore magna aliqua.</p>
        <?php //endif;
        ?>

      </div>

    </div>

    <div class="row">

      <div class="col-lg-12">

        <div class="test-sli">
          <?php //foreach ($testimonial as $key => $value) :
            ?>
            <div>

              <div class="testiom-box">

                <ul>

                  <li>

                    <div class="img-b">

                      <img class="lazy" src="<? //= get_image($value['testimonial_image_path'], $value['testimonial_image'])
                                                ?>" data-src="<? //= get_image($value['testimonial_image_path'], $value['testimonial_image'])
                                                                ?>" alt="images" onerror="this.onerror=null;this.src='<? //= g('images_root') . 'user.png'
                                                                                                                        ?>';" />

                    </div>

                  </li>

                  <li>

                    <h5><? //= $value['testimonial_name']
                        ?></h5>

                    <h6><? //= $value['testimonial_designation']
                        ?></h6>

                  </li>

                </ul>

                <p><? //= $value['testimonial_description']
                    ?></p>

              </div>

            </div>

          <?php //endforeach;
            ?>
        </div>

      </div>

    </div>

  </div>

</section>

<div class="container mt-5 mb-5 pb-5">

  <div class="row justify-content-center wow fadeInUp">

    <div class="col-lg-6 text-center">

      <?php //if (isset($cms[5]['cms_page_content'])) :
        ?>
        <? //= html_entity_decode($cms[5]['cms_page_content'])
        ?>
      <?php //else :
        ?>
        <h2>Ready to work with the best?</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      <?php //endif;
        ?>

      <a href="" class="btn-2">View All Freelancers</a>

    </div>

  </div>

</div> -->

<!-- testiom-sec -->

<!--<section class="visit-also-sec">-->
<!--    <div class="container">-->
<!--        <h2 class="text-center mb-5">Visit Also</h2>-->
<!--        <div class="row">-->
<!--            <div class="col-md-6">-->
<!--                <a href="" class="visit-box">-->
<!--                    <i class="fa-thin fa-globe"></i>-->
<!--                    <div>-->
<!--                        <h4>Science HR</h4>-->
<!--                        <i class="fa-light fa-arrow-right"></i>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="" class="visit-box">-->
<!--                    <i class="fa-thin fa-layer-group"></i>-->
<!--                    <div>-->
<!--                        <h4>Science Events</h4>-->
<!--                        <i class="fa-light fa-arrow-right text-custom"></i>-->
<!--                    </div>-->
<!--                </a>-->
<!--            </div>-->
<!--            <div class="col-md-6">-->
<!--                <a href="" class="visit-box revs">-->
<!--                    <i class="fa-thin fa-star-of-life"></i>-->
<!--                    <div>-->
<!--                        <h4>Engineering HR</h4>-->
<!--                        <i class="fa-light fa-arrow-right"></i>-->
<!--                    </div>-->
<!--                </a>-->
<!--                <a href="" class="visit-box revs">-->
<!--                    <i class="fa-thin fa-shapes"></i>-->
<!--                    <div>-->
<!--                        <h4>Research Elements</h4>-->
<!--                        <i class="fa-light fa-arrow-right"></i>-->
<!--                    </div>-->
<!--                </a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->