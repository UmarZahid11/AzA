<!-- header start -->
    <header>
        <div class="topSec">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="marquee-list">
                            <marquee>
                                <ul>
                                    <li>
                                        <?= g('db.admin.header_message') ?>
                                    </li>
                                    <li>
                                        <?= g('db.admin.header_message') ?>
                                    </li>
                                    <li>
                                        <?= g('db.admin.header_message') ?>
                                    </li>
                                </ul>
                            </marquee>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="menuSection">
            <div class="">
                <div class="row">

                    <?php if($this->userid > 0 && $this->router->class != 'verification'): ?>
                        <!--<div class="col-lg-1 col-md-1 col-sm-1">-->
                        <!--    <div class="side-btn">-->
                        <!--        <i class="fa-light fa-bars-staggered"></i>-->
                        <!--    </div>-->
                        <!--</div>-->
                    <?php else: ?>
                        <!--<div class="col-lg-1 col-md-1 col-sm-1">-->
                        <!--</div>-->
                    <?php endif; ?>
                    <div class="col-lg-2 col-md-2 col-sm-4 d-flex">
                        <?php if($this->userid > 0 && $this->router->class != 'verification'): ?>
                            <!--<div class="side-btn">-->
                            <!--    <i class="fa-light fa-bars-staggered"></i>-->
                            <!--</div>-->
                        <?php endif; ?>
                        <div class="header-logo">
                            <a href="<?= l('') ?>">
                                <img 
                                    class="lazy" 
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%200%200'%3E%3C/svg%3E" 
                                    data-src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image']) ?>" 
                                    onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" 
                                    alt="" 
                                />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7 d-none d-md-block offset-1">
                        <div class="menuSec">
                            <h4>
                                Free For Customers! <br />
                                Entrepreneurs, Enjoy 30 Days Free Trial!
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <div class="headerLinks mt-5">
                            <!--<a href="<?= l('membership') ?>" class="PricingTxt">Pricing</a>-->
                            <!--<span>|</span>-->
                            <!--<a href="<?= l('about') ?>" class="btn btn-warning leanBtn">About Us</a>-->
                            <?php if ($this->userid == 0) : ?>
                            <?php else : ?>
                                <?php if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                                    <!--<span>|</span>-->
                                    <a href="javascript:;" id="socialForum" data-disabled="0" data-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="">
                                        AzAverze Community
                                    </a>
                                    <span>|</span>
                                <?php endif; ?>
                                <a href="<?= l('logout') ?>">Logout</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($this->userid > 0 && $this->router->class != 'verification'): ?>

            <div class="sidebar">
                <ul id="menu">

                    <li class="home"><a href="<?= l('') ?>"><i class="fa-light fa-house"></i> <span><?= $config['title'] ?> </span></a></li>

                    <!--<li class="nav-item about"><a href="<?= l('about') ?>"><i class="fa-light fa-info"></i> <span>About Us</span></a></li>-->

                    <!--<li class="dashboard"><a href="<?= l('dashboard') ?>"><img src="https://azaverze.com/stagging/assets/front_assets/images/s1.png"> <span>Entrepreneur Dashboard </span></a></li>-->

                    <li class="nav-item membership"><a href="<?= l('membership') ?>"><i class="fa-light fa-money-check-alt"></i><span>Pricing</span></a></li>

                        <?php //if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                            <!--<li class="nav-item"><a href="https://monday.com/" target="_blank"><i class="fa-light fa-screwdriver-wrench"></i> <span>Goto CRM dashboard</span></a></li>-->
                        <?php //endif; ?>
        
                        <li class="nav-item">
                            <!--<a href="<?= l('dashboard') ?>"><i class="material-icons">&#xe0bf;</i> <span>Entrepreneur Dashboard</span></a>-->
                            <a href="<?= l('dashboard') ?>"><i class="fa-light fa-gauge"></i> <span>Entrepreneur Dashboard</span></a>
                            <!--<a href="javascript:;" id="socialForum" data-disabled="0" data-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="">-->
                            <!--    <i class="material-icons">&#xe0bf;</i> <span>AzAverze Community</span>-->
                            <!--</a>-->
                        </li>

                    <?php if ($this->userid > 0): ?>
                        <!--<li class="nav-item blog"><a href="<?= l('job') ?>"><i class="fa-light fa-tasks"></i> <span>Job</span></a></li>-->
                    <?php endif; ?>

                    <li class="nav-item affiliate"><a href="<?= l('affiliate') ?>"><i class="fa-light fa-chart-simple"></i> <span>Become an Affiliate</span></a></li>

                    <!--<li class="nav-item blog"><a href="<?= l('blog') ?>"><i class="fa-light fa-blog"></i> <span>Blog</span></a></li>-->

                    <!--<li class="nav-item story"><a href="<?= l('story') ?>"><i class="fa-light fa-history"></i> <span>Stories</span></a></li>-->

                    <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>
                        <li class="nav-item career"><a href="<?= l('career') ?>"><i class="fa-light fa-briefcase"></i><span>Careers</span></a></li>
                    <?php endif; ?>

                    <!--<li class="nav-item opportunities"><a href="<?= l('opportunity') ?>"><i class="fa-light fa-layer-plus"></i> <span>Announcements</span></a></li>-->
                    <li class="nav-item opportunities"><a href="<?= l('announcement/listing') ?>"><i class="fa-light fa-layer-plus"></i> <span>Announcements</span></a></li>

                    <!--<li class="nav-item support"><a href="<?= l('support') ?>"><i class="fa-light fa-square-question"></i> <span>Support</span></a></li>-->

                    <li class="nav-item press"><a href="<?= l('press') ?>"><i class="fa-light fa-newspaper"></i><span>Press</span></a></li>

                    <li class="nav-item partner"><a href="<?= l('partner') ?>"><i class="fa-light fa-handshake"></i> <span>Partners</span></a></li>

                    <li class="nav-item donation"><a href="<?= l('donation') ?>"><i class="fa-light fa-donate"></i> <span>Fundraising</span></a></li>

                    <li class="nav-item investor_relation"><a href="<?= l('investor-relation') ?>"><i class="fa-light fa-circle-dollar"></i> <span>Investor Relations</span></a></li>

                    <!--<li class="nav-item expert"><a href="<?= l('expert') ?>"><i class="fa-light fa-users"></i> <span>Experts</span></a></li>-->

                    <li class="nav-item contact"><a href="<?= l('contact') ?>"><i class="fa-light fa-address-book"></i> <span>Contact</span></a></li>

                </ul>
            </div>
        <?php endif; ?>
    </header>
<!-- header end -->