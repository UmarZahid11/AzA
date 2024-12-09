<section class="section-padd-rep">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6">
				<h2>Premium Profile</h2>
				<p>Transition from our free tier to the premium membership today and gain exclusive access to countless enhanced features, expanded functionality, and an abundance of resources that will revolutionize the way you conduct business. Our premium membership paves the way for amplified opportunities, providing you with the tools and support necessary to thrive in the dynamic landscape of online commerce. Don't miss out on maximizing your capabilities – elevate your AzAverze experience with our premium membership offering and embark on a path toward unparalleled success.</p>
			</div>
			<div class="col-md-6">
				<img src="https://azaverze.my.canva.site/images/90ad1614cc987667d5c2891980c0165f.png" class="w-100" alt="Side Image" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
			</div>
		</div>
	</div>
</section>

<section class="section-padd-rep">
	<div class="container">
		<h2 class="text-center"><?php echo (isset($cms[0]['cms_page_title'])) ? $cms[0]['cms_page_title'] : 'Premium Services' ?></h2>
		<div class="row align-items-center">
			<div class="col-md-6">
				<img src="<?= base_url() ?>assets/front_assets/images/sd2.jpg" class="w-100" alt="Side Image" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
			</div>
			<div class="col-md-6">
			    <?php if(isset($cms[0]['cms_page_content'])): ?>
			        <?= html_entity_decode($cms[0]['cms_page_content']) ?>
			    <?php else: ?>
    				<h3><b>Premium</b> Listing</h3>
    				<p class="primary-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section class="section-padd-rep">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-md-6">
				<img src="<?= base_url() ?>assets/front_assets/images/sd3.jpg" class="w-100" alt="Side Image" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
			</div>
			<div class="col-md-5">
				<h3><?php echo (isset($cms[1]['cms_page_title'])) ? $cms[1]['cms_page_title'] : 'Premium Calander'; ?></h3>
			    <?php if(isset($cms[1]['cms_page_content'])): ?>
			        <?= html_entity_decode($cms[1]['cms_page_content']) ?>
			    <?php else: ?>
    				<p class="primary-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<section class="section-padd-rep">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-md-6">
				<img src="<?= base_url() ?>assets/front_assets/images/sd4.jpg" class="w-100" alt="Side Image">
			</div>
			<div class="col-md-5">
				<h3><?php echo (isset($cms[4]['cms_page_title'])) ? $cms[4]['cms_page_title'] : 'Premium Products'; ?></h3>
			    <?php if(isset($cms[4]['cms_page_content'])): ?>
			        <?= html_entity_decode($cms[4]['cms_page_content']) ?>
			    <?php else: ?>
    				<p class="primary-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				<?php endif; ?>
				<a href="<?= l('contact') ?>" class="btn-nnr">Learn More</a>
			</div>
		</div>
	</div>
</section>

<!--<section class="section-padd-rep">-->
<!--	<div class="container">-->
<!--		<div class="row align-items-center">-->
<!--			<div class="col-md-6">-->
<!--				<h3><?php echo (isset($cms[2]['cms_page_title'])) ? $cms[2]['cms_page_title'] : 'Products Purchased'; ?></h3>-->
<!--			    <?php if(isset($cms[2]['cms_page_content'])): ?>-->
<!--			        <?= html_entity_decode($cms[2]['cms_page_content']) ?>-->
<!--			    <?php else: ?>-->
<!--    				<p class="primary-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod-->
<!--    				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
<!--    				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo-->
<!--    				consequat. Duis aute irure dolor. <br><br> in reprehenderit in voluptate velit esse-->
<!--    				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non-->
<!--    				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>-->
<!--    				<div class="row">-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    				</div>-->
<!--				<?php endif; ?>-->
<!--			</div>-->
<!--			<div class="col-md-6">-->
<!--				<img src="<?= base_url() ?>assets/front_assets/images/sd5.jpg" class="w-100" alt="Side Image" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</section>-->

<!--<section class="section-padd-rep">-->
<!--	<div class="container">-->
<!--		<div class="row align-items-center">-->
<!--			<div class="col-md-6">-->
<!--				<h3><?php echo (isset($cms[3]['cms_page_title'])) ? $cms[3]['cms_page_title'] : 'Manage <br> Service Requests'; ?></h3>-->
<!--			    <?php if(isset($cms[3]['cms_page_content'])): ?>-->
<!--			        <?= html_entity_decode($cms[3]['cms_page_content']) ?>-->
<!--			    <?php else: ?>-->
<!--    				<p class="primary-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod-->
<!--    				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
<!--    				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo-->
<!--    				consequat. Duis aute irure dolor. <br><br> in reprehenderit in voluptate velit esse-->
<!--    				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non-->
<!--    				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>-->
<!--    				<div class="row">-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    					<div class="col-md-6">-->
<!--    						<div class="icoo-boxx">-->
<!--    							<img src="<?= base_url() ?>assets/front_assets/images/icoo.jpg" alt="Icon" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--    							<h4>Title goes here</h4>-->
<!--    							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>-->
<!--    						</div>-->
<!--    					</div>-->
<!--    				</div>-->
<!--				<?php endif; ?>-->
<!--			</div>-->
<!--			<div class="col-md-6">-->
<!--				<img src="<?= base_url() ?>assets/front_assets/images/sd6.jpg" class="w-100" alt="Side Image" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</section>-->