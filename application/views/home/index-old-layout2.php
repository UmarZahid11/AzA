<!-- banner start -->
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-7 <?= ($this->userid != 0) ? 'offset-3' : '' ?>">
                <div class="bannerText">
                    <?php if(isset($banner)) : ?>
                        <img src="<?= get_image($banner['banner_image_path'], $banner['banner_image']) ?>" alt="img" />
                    <?php else: ?>
                        <img src="<?= g('images_root') . 'avaerze-logo.png' ?>" alt="img" />
                    <?php endif; ?>
                    <?php if(isset($banner)) : ?>
                        <?= html_entity_decode($banner['banner_sub_heading']) ?>
                    <?php else: ?>
                        <p>
                            Connnecting small businesses professional services to a
                            community and<br />
                            diving entrepreneurail success.
                        </p>
                    <?php endif; ?>
                    <div class="bannerBtnSec">
                        <!--<a href="<?//= isset($banner) ? $banner['banner_button_1_link'] : l('about') ?>" class="btn btn-warning leanBtn"><?//= isset($banner) ? $banner['banner_button_1'] : 'Learn More About AzAverze' ?></a>-->
                        <a href="<?= g('images_root') ?>cmso_vidnww.mp4" class="playBtn" data-fancybox="">
                            <i class="fa-solid fa-play"></i></a>
                            <h2 class="m-0">Play The Video</h2>
                        </a>
                    </div>
                </div>
            </div>
            <?php if ($this->userid == 0) : ?>
                <div class="col-md-5">
                    <?php $this->load->view("widgets/login/form"); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- banner end -->

<!-- account update section -->
<section class="accountSec">
    <!--container-->
    <div class="">
        <div class="row">
            <div class="col-sm-12">
                <div class="updateBox">
                    <?php if(isset($cms[0])) : ?>
                        <?= html_entity_decode($cms[0]['cms_page_content']) ?>
                    <?php else: ?>
                        <h2>
                            Entrepreneurs,<br />
                            Upgrade Your Free Customer Account to Access Professional
                            Applications!
                        </h2>
                        <p>
                            We at AzAverze believe in the power of building supportive
                            communities. As such, 1% of monthly subscription will be donated
                            to the Tunnel to Towers Foundation to support rebuilding lives
                            of our wounded military veterans. Click the link to learn more
                            about the Tunnel to Towers Foundation and further support our
                            veterans.
                        </p>
                    <?php endif; ?>
                    <!--class="upDateBtn"-->
                    <a href="https://t2t.org" target="_blank"><img src="<?= g('images_root') . 't2t.png' ?>" onerror="this.onerror=null;this.src='https://placehold.co/488x188/0000000/222e44?text=Tunnel%20to%20Towers%20Foundation%20T2T.org';" /></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- account update section -->

<!-- customer section -->
<section class="customerSec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="customerBox">
                    <?php if(isset($cms[1])) : ?>
                        <?= html_entity_decode($cms[1]['cms_page_content']) ?>
                    <?php else: ?>
                        <h2>Customers</h2>
                        <p>
                            Text "AzA" To 26786 To Receive, At Launch, A 1 Week Free Trial
                            of<br />
                            All AzAverze Business Applications!!
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- customer section -->

<?php if ($this->userid == 0) : ?>
    <?php $this->load->view("widgets/login/modal"); ?>
    <?php $this->load->view("widgets/login/script"); ?>
<?php endif; ?>