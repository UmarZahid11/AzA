<section class="after-log-sec">
	<div class="container">
		<div class="row">
			<!--<div class="col-md-1 p-0">-->
			<!--	<div class="sidebar-aft-lg">-->
			<!--		<a href=""><i class="fa-solid fa-grid-2"></i></a>-->
			<!--		<a href=""><i class="fa-solid fa-bell"></i> <span>1</span></a>-->
			<!--		<a href=""><i class="fa-solid fa-gear"></i></a>-->
			<!--		<a href=""><i class="fa-solid fa-left-long-to-line"></i></a>-->
			<!--	</div>-->
			<!--</div>-->
			<div class="col-md-11 p-0">
				<div class="headr-area">
					<div class="row">
						<div class="col-md-8">
							<h2>Welcome to <?= $config['site_name'] ?></h2>
						</div>
						<div class="col-md-4">
							<div class="riht-arad">
								<!--<form>-->
								<!--	<div class="seafrc-arera">-->
								<!--		<button><i class="fa-solid fa-magnifying-glass"></i></button>-->
								<!--		<input type="text" name="">-->
								<!--	</div>-->
								<!--</form>-->
								<!--<div class="profil-epic">-->
								<!--	<a href=""><img src="https://azaverze.my.canva.site/images/eb197c702671e2063cb023079383bf8c.jpg" alt="Profile Picture"></a>-->
								<!--</div>-->
							</div>
						</div>
					</div>
				</div>
				<div class="banner-scrntr">
					<div class="row">
						<div class="col-md-8">
							<h4><i class="fa-solid fa-crown"></i> No Ads & Exclusive Services</h4>
							<h2>Premium Membership</h2>
							<p>Want to take advantage of the full potential of the AzAverze platform? Opt for our premium membership and avail countless benefits to aid in your entrepreneurial journey. Upgrade from free to premium today and get access to more features, more functionality, and more of everything.</p>
							<div class="btn-flex">
								<a href="<?= l('membership') ?>" class="btn-dark-nn" target="_blank">Upgrade Now</a>
								<a href="<?= l('home/learn-more') ?>" class="btn-light-nn" target="_blank">Learn More</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="side-imgmban">
								<img src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image']) ?>" onerror="this.onerror=null;this.src='<?= base_url() . 'assets/uploads/logo/ezgifcom-video-to-gif169407386363.gif'; ?>';" alt="AZA Logo" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>