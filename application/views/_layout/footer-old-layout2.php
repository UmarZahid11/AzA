<!-- footer start  -->
    <div class="footerSec">
        <!-- <div class="pricingBar">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <a href="<?= l('membership') ?>" class="PricingTxt">Pricing</a>
                    </div>
                    <div class="col-md-10">
                        
                    </div>
                </div>
            </div>
        </div> -->

        <div class="avaerzeBar">
            <!--container-->
            <div class="">
                <div class="row">
                    <div class="col-md-4 offset-4">
                        <p>AzAverze, A Patent Pending Platform.</p>
                        <p>
                            Made in Las Vegas, USA
                        </p>
                    </div>
                    <div class="col-md-3">
                        <div class="socialIconBox">
                            <?php if (g('db.admin.facebook')) : ?>
                                <a href="<?= g('db.admin.facebook') ?>" target="_blank" class="facebook">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (g('db.admin.instagram')) : ?>
                                <a href="<?= g('db.admin.instagram') ?>" target="_blank" class="instagram">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (g('db.admin.twitter')) : ?>
                                <a href="<?= g('db.admin.twitter') ?>" target="_blank" class="twitter">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (g('db.admin.linkedin')) : ?>
                                <a href="<?= g('db.admin.linkedin') ?>" target="_blank" class="linkedin">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (g('db.admin.youtube')) : ?>
                                <a href="<?= g('db.admin.youtube') ?>" target="_blank" class="youtube">
                                    <i><img src="<?= g('images_root') . 'youtubeImg.png' ?>" /></i>
                                </a>
                            <?php endif; ?>
                            <?php if (g('db.admin.phone_whatsapp')) : ?>
                                <?php $phone_whatsapp = preg_replace('/[^\dxX]/', '', g('db.admin.phone_whatsapp')); ?>
                                <a href="https://wa.me/<?= $phone_whatsapp ?>" target="_blank" class="whatsapp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-sm-4 col-4">
                        <p class="privacyTxt"><a href="<?= l('privacy') ?>">Privacy Policy</a></p>
                    </div>
                    <div class="col-sm-4 col-4">
                        <p class="azaText">@AzAverze</p>
                    </div>
                    <div class="col-sm-4 col-4">
                        <p class="termsTxt"><a href="<?= l('terms-and-conditions') ?>">Terms & Conditions</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="footer-btm">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-4">
                        <p class="privacyTxt"><a href="<?= l('privacy') ?>">Privacy Policy</a></p>
                    </div>
                    <div class="col-sm-4 col-4">
                        <p class="azaText">@AzAverze</p>
                    </div>
                    <div class="col-sm-4 col-4">
                        <p class="termsTxt"><a href="<?= l('terms-and-conditions') ?>">Terms & Conditions</a></p>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
<!-- footer end  -->

<script>
    $('.side-btn').click(function() {
        $('body').toggleClass('active-sd');
    })
</script>