<?php
    $param = array();
    $param['where']['cms_page_name'] = 'footer';
    $cms_page_footer =  $this->model_cms_page->find_all_active($param);
?>

<!-- footer start  -->

<!--<section class="section-foootr-part">-->
<!--	<div class="container">-->
<!--		<div class="col-md-7">-->
<!--			<div class="footr-boox">-->
<!--				<div class="footr-flxx">-->
<!--					<div class="images-prt">-->
<!--						<img src="https://enlightio.com/wp-content/uploads/2023/05/why-are-investors-important.jpg" alt="Image" />-->
<!--					</div>-->
<!--					<div class="contact-info">-->
<!--						<h3>GET IN <br> TOUCH <br> WITH US</h3>-->
<!--						<a href="tel:<?= g('db.admin.phone_local') ?>"><i class="fa-solid fa-phone"></i> <?= g('db.admin.phone_local') ?></a>-->
<!--						<a href="mailto:<?= g('db.admin.email') ?>"><i class="fa-solid fa-envelope"></i> <?= g('db.admin.email') ?></a>-->
<!--						<a href="https://maps.google.com/?q=<?= g('db.admin.address') ?>" target="_blank"><i class="fa-sharp fa-solid fa-location-dot"></i> <?= g('db.admin.address') ?></a>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="aat-sport">-->
<!--					@azaverze.com-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</section>-->


<style>
    .container.footer-menu .ftsocial-icons {
        margin-bottom: 0;
        margin-top: 0;
    }
</style>

<div class="footerSec">

    <div class="container">

        <div class="row align-items-center justify-content-center ">

            <div class="col-lg-4 col-md-4">
                <div class="ft-logo">
                    <a href="<?= l('') ?>">
                        <img src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image_footer']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                    </a>
                </div>
                
                <!--<h5>About us</h5>-->
                <!--<?php if (isset($cms_page_footer[0]['cms_page_content'])) : ?>-->
                <!--    <?= html_entity_decode($cms_page_footer[0]['cms_page_content']) ?>-->
                <!--<?php else : ?>-->
                <!--    <p>AzAverze is a collaboration platform that connects people and technology to value creating life science opportunities.</p>-->
                <!--<?php endif; ?>-->

            </div>
            
            <div class="col-lg-5">
                <div class="ft-nwlogos">
                    <a href="javascript:void(0)">
                        <img src="<?= g('images_root') ?>ft-logo3.png" alt="" />
                    </a>
                    <a href="javascript:void(0)">
                        <img src="<?= g('images_root') ?>ft-logo2.png" alt="" />
                    </a>
                </div>
            </div>

            <!--<div class="col-lg-2 col-md-2 offset-1">-->

            <!--    <h5>Quick Links</h5>-->

            <!--    <ul class="linkList">-->

            <!--        <?php if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>-->
            <!--            <li><a href="https://monday.com/" target="_blank"><i class="fa-regular fa-arrow-right"></i>CRM dashboard</a></li>-->
            <!--        <?php endif; ?>-->

            <!--        <?php if ($this->userid > 0): ?>-->
            <!--            <li><a href="<?= l('job') ?>"><i class="fa-regular fa-arrow-right"></i> Job </a></li>-->
            <!--        <?php endif; ?>-->

            <!--        <li><a href="<?= l('news') ?>"><i class="fa-regular fa-arrow-right"></i> News </a></li>-->

            <!--        <li><a href="<?= l('blog') ?>"><i class="fa-regular fa-arrow-right"></i> Blog </a></li>-->

            <!--        <li><a href="<?= l('press') ?>"><i class="fa-regular fa-arrow-right"></i> Press </a></li>-->

            <!--        <li><a href="<?= l('investor-relation') ?>"><i class="fa-regular fa-arrow-right"></i> Investor Relations </a></li>-->

            <!--    </ul>-->

            <!--</div>-->

            <!--<div class="col-lg-3 col-md-3 offset-1">-->

            <!--    <h5>Information for</h5>-->

            <!--    <ul class="linkList">-->

            <!--        <li><a href="<?= l('membership') ?>"><i class="fa-regular fa-arrow-right"></i> Subscription </a></li>-->

            <!--        <li><a href="<?= l('about') ?>"><i class="fa-regular fa-arrow-right"></i> About Us </a></li>-->

            <!--        <?php if ($this->userid > 0): ?>-->
            <!--            <li><a href="<?= l('announcement/listing') ?>"><i class="fa-regular fa-arrow-right"></i> Announcements </a></li>-->
            <!--        <?php endif; ?>-->

            <!--        <li><a href="<?= l('contact') ?>"><i class="fa-regular fa-arrow-right"></i> Contact </a></li>-->

            <!--        <li><a href="<?= l('career') ?>"><i class="fa-regular fa-arrow-right"></i> Careers </a></li>-->

                    <!--<li><a href="<?= l('support') ?>"><i class="fa-regular fa-arrow-right"></i> Support </a></li>-->

                    <!-- <li><a href="<?= l('partner') ?>"><i class="fa-regular fa-arrow-right"></i> Partners </a></li> -->

            <!--    </ul>-->

            <!--</div>-->

            <div class="col-lg-3 col-md-3">

                <h5>Contact Us</h5>

                <div class="contact-lst">

                    <!--<a href="https://maps.google.com/?q=<?= g('db.admin.address') ?>" target="_blank"><i class="fa-light fa-location-dot"></i> <?= g('db.admin.address') ?></a>-->

                    <a href="tel:<?= g('db.admin.phone_local') ?>"><i class="fa-light fa-phone-arrow-up-right"></i> <?= g('db.admin.phone_local') ?></a>

                    <a href="mailto:<?= g('db.admin.email') ?>"><i class="fa-light fa-envelope"></i> <?= g('db.admin.email') ?></a>

                    

                </div>

            </div>

        </div>

        <div class="container footer-menu">
            <div class="row mt-5">
                <div class="col-lg-4">
                    <div class="translate-dv">
                         <div id="google_translate_element"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="f-menu">
                        <?php //if ($this->userid == 0) : ?>
                            <!--<a href="<?//= l('signup') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''); ?>">Sign up </a>-->
                            <!--<a href="<?//= l('signin') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''); ?>">Sign in </a>-->
                        <?php //else : ?>
                            <!--<a href="<?//= l('dashboard') ?>">Dashboard</a>-->
                        <?php //endif; ?>
        
                        <a href="<?= l('terms-and-conditions') ?>">Terms & Conditions </a>
        
                        <a href="<?= l('privacy') ?>">Privacy Policy</a>
        
                        <?php if ($this->userid > 0) : ?>
                            <a href="<?= l('logout') ?>">Logout</a>
                        <?php endif; ?>
        
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ftsocial-icons">
                        <?php if (g('db.admin.facebook')) : ?>
                            <a href="<?= g('db.admin.facebook') ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if (g('db.admin.instagram')) : ?>
                            <a href="<?= g('db.admin.instagram') ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (g('db.admin.twitter')) : ?>
                            <a href="<?= g('db.admin.twitter') ?>" target="_blank"><i class="fab fa-x-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (g('db.admin.linkedin')) : ?>
                            <a href="<?= g('db.admin.linkedin') ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (g('db.admin.youtube')) : ?>
                            <a href="<?= g('db.admin.youtube') ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

</div>

<div class="top-marquee">
    A Patent Pending Platform
</div>

<div class="copy-right">
    <p class="mb-0 wow fadeInLeft" data-wow-duration="3s"><?= g('db.admin.copyright') ? (str_replace('{year}', date('Y'), g('db.admin.copyright'))) : ('Copyright © ' . date('Y') . ' AzAverze. All rights reserved.') ?></p>
</div>

<!-- footer end  -->

<script>
    window.onload = function() {
        var pathName = window.location.pathname.split("/").pop();
        console.log(pathName)
        if (pathName == '') // home default active, therefore for no string after "/" (home li tag is shown active)
        {
            pathName = "home";
        } else if (isFinite(pathName)) // check if number then get url string before the last slash
        {
            var path = window.location.pathname;
            var parts = path.split('/');
            pathName = parts[parts.length - 2];
            if (!$('.nav-item.' + pathName).length) {
                pathName = parts[parts.length - 3];
            }
        } else if (!$('.nav-item.' + pathName).length) {
            var path = window.location.pathname;
            var parts = path.split('/');
            pathName = parts[parts.length - 1];
        }

        var x = document.getElementsByClassName(pathName);
        $('#menu li.active').removeClass('active');
        for (var i = 0; i < x.length; i++) {
            x[i].className += ' active';
        }
    }
</script>




<script>
    $('.side-btn').click(function() {
        $('body').toggleClass('active-sd');
    })
</script>

<script>
    // search job by title
    $('.searchJob').on('submit', function() {
        if ($('input[name=search]').val() != "") {
            // job/index/{$page}/{$limit}/{category_filter}/{search}
            window.location.href = '<?= l('job/index/1/9/0/') ?>' + $('input[name=search]').val();
        }
    })
</script>

<script>

    function replaceContactUsWithImage() {
        var imageHTML = '<img src="<?= base_url() ?>assets/front_assets/images/aza-txt1.png" class="az-img" alt="Azaimg">';
        var replacedHTML = $('body').html();
        replacedHTML = replacedHTML.replace(/AzAverze/g, imageHTML);
        $('body').html(replacedHTML);
    }

    $(document).ready(function() {
        // replaceContactUsWithImage();
    });

</script>