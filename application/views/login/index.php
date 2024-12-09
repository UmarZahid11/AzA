<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Login' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="canvs-sec">
	<div class="container">
		<div class="row">
			<div class="col-md-5 mt-1">
                <?php $this->load->view("widgets/login/form"); ?>
			</div>
			<!-- <div class="col-md-7"> -->
			    <!-- <div class="banner-img"> -->
			        <!-- <img class="lazy" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%200%200'%3E%3C/svg%3E" data-src="<?= base_url() ?>assets/front_assets/images/signinimg.png" alt="Aza banner image" /> -->
			    <!-- </div> -->
			<!-- </div> -->
		</div>
	</div>
</section>

<?php $this->load->view("widgets/login/modal"); ?>

<?php $this->load->view("widgets/login/script"); ?>
