<section class="footer-pnw" id="contact">
    <div class="container">
        <img src="<?= g('images_root') ?>logo-hopri.png" alt="" width="180">
        <h2>QUESTIONS?</h2>
        <div class="row align-items-center">
            <div class="col-md-7">
                <h3>We're here to help.</h3>
            </div>
            <div class="col-md-3">
                <p>Contact our support team anytime for assistance, or explore our FAQ section for more information.</p>
            </div>
        </div>
        <div class="row mt-5 pt-5">
            <div class="col-md-4">
                <div class="aomnisah">
                    <h6>contact us</h6>
                    <!--<a href=""><span><?= g('db.admin.phone_local') ?></span></a>-->
                    <a href="mailto:<?= g('db.admin.support_email') ?>"><span><?= g('db.admin.support_email') ?></span></a>
                </div>
            </div>
            <!--<div class="col-md-8">-->
            <!--    <div class="aomnisah">-->
            <!--        <h6>Our location</h6>-->
            <!--        <span><?= g('db.admin.address') ?></span>-->
            <!--    </div>-->
            <!--</div>-->
            <div class="col-md-8">
                <div class="aomnisah">
                    <h6>follow us on</h6>
                    <!--<a href=""><span>@azaverze</span></a>-->
                    <div class="socail-linkss">
                        <a href="<?= g('db.admin.linkedin') ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="<?= g('db.admin.facebook') ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="<?= g('db.admin.instagram') ?>" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                        <a href="<?= g('db.admin.twitter') ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="<?= g('db.admin.youtube') ?>" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="ciptu"><?= (str_replace('{year}', date('Y'), g('db.admin.copyright'))) ?></div>
    </div>
</section>