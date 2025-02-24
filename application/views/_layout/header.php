<?php if($this->router->class != 'verification'): ?>

<div class="sidebar d-none">
    <ul id="menu">

        <li class="home"><a href="<?= l('') ?>#home"><i class="fa-light fa-house"></i> <span><?= $config['title'] ?> </span></a></li>
        <!--<li class="home"><a href="<?= l('') ?>"><i class="fa-light fa-house"></i> <span><?= $config['title'] ?> </span></a></li>-->

        <!--<li class="nav-item about"><a href="<?= l('about') ?>"><i class="fa-light fa-info"></i> <span>About Us</span></a></li>-->

        <!--<li class="dashboard"><a href="<?= l('dashboard') ?>"><img src="https://azaverze.com/stagging/assets/front_assets/images/s1.png"> <span>Entrepreneur Dashboard </span></a></li>-->

        <!--<li class="nav-item membership"><a href="<?= l('membership') ?>"><i class="fa-light fa-money-check-alt"></i><span>Pricing</span></a></li>-->
        <li class="nav-item membership"><a href="<?= l('') ?>#membrshp"><i class="fa-light fa-money-check-alt"></i><span>Pricing</span></a></li>

            <?php //if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                <!--<li class="nav-item"><a href="https://monday.com/" target="_blank"><i class="fa-light fa-screwdriver-wrench"></i> <span>Goto CRM dashboard</span></a></li>-->
            <?php //endif; ?>

        <?php if ($this->userid > 0): ?>
            <li class="nav-item">
                <!--<a href="<?= l('dashboard') ?>"><i class="material-icons">&#xe0bf;</i> <span>Entrepreneur Dashboard</span></a>-->
                <a href="<?= l('dashboard') ?>"><i class="fa-light fa-gauge"></i> <span>Entrepreneur Dashboard</span></a>
                <!--<a href="javascript:;" id="socialForum" data-disabled="0" data-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="">-->
                <!--    <i class="material-icons">&#xe0bf;</i> <span>AzAverze Community</span>-->
                <!--</a>-->
            </li>
        <?php endif; ?>

        <?php if ($this->userid > 0): ?>
            <!--<li class="nav-item blog"><a href="<?= l('job') ?>"><i class="fa-light fa-tasks"></i> <span>Job</span></a></li>-->
        <?php endif; ?>

        <?php if ($this->userid > 0): ?>
            <li class="nav-item affiliate"><a href="<?= l('affiliate') ?>"><i class="fa-light fa-chart-simple"></i> <span>Become an Affiliate</span></a></li>
        <?php endif; ?>

        <!--<li class="nav-item blog"><a href="<?= l('blog') ?>"><i class="fa-light fa-blog"></i> <span>Blog</span></a></li>-->

        <!--<li class="nav-item story"><a href="<?= l('story') ?>"><i class="fa-light fa-history"></i> <span>Stories</span></a></li>-->

        <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>
            <li class="nav-item career"><a href="<?= l('career') ?>"><i class="fa-light fa-briefcase"></i><span>Careers</span></a></li>
        <?php endif; ?>

        <!--<li class="nav-item opportunities"><a href="<?= l('opportunity') ?>"><i class="fa-light fa-layer-plus"></i> <span>Announcements</span></a></li>-->
        <!--<li class="nav-item opportunities"><a href="<?= l('announcement/listing') ?>"><i class="fa-light fa-layer-plus"></i> <span>Announcements</span></a></li>-->

        <!--<li class="nav-item support"><a href="<?= l('support') ?>"><i class="fa-light fa-square-question"></i> <span>Support</span></a></li>-->

        <!--<li class="nav-item press"><a href="<?= l('press') ?>"><i class="fa-light fa-newspaper"></i><span>Press</span></a></li>-->

        <!--<li class="nav-item partner"><a href="<?= l('partner') ?>"><i class="fa-light fa-handshake"></i> <span>Partners</span></a></li>-->

        <?php if ($this->userid > 0): ?>
            <li class="nav-item donation"><a href="<?= l('donation') ?>"><i class="fa-light fa-donate"></i> <span>Fundraising</span></a></li>
        <?php endif; ?>

        <!--<li class="nav-item investor_relation"><a href="<?= l('investor-relation') ?>"><i class="fa-light fa-circle-dollar"></i> <span>Investor Relations</span></a></li>-->

        <!--<li class="nav-item expert"><a href="<?= l('expert') ?>"><i class="fa-light fa-users"></i> <span>Experts</span></a></li>-->
        <?php if ($this->userid == 0): ?>
            <li class="nav-item"><a href="<?= l('') ?>#signup"><i class="fa-light fa-user"></i> <span>Sign up & Sign in</span></a></li>
        <?php endif; ?>
        <!--<li class="nav-item contact"><a href="<?= l('contact') ?>"><i class="fa-light fa-address-book"></i> <span>Contact</span></a></li>-->
        <li class="nav-item contact"><a href="<?= l('') ?>#contact"><i class="fa-light fa-address-book"></i> <span>Contact</span></a></li>

        <?php if ($this->userid > 0): ?>
            <li class="nav-item"><a href="<?= l('logout') ?>"><i class="fa-light fa-sign-out"></i> <span>Logout</span></a></li>
        <?php endif; ?>

    </ul>
</div>
<?php endif; ?>

<?php if($this->router->class == 'home' && $this->router->method == 'index'): ?>
<section class="banner-nbew">
    <img src="<?= g('images_root') ?>side-shape.png" alt="" />
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2">
                <div class="logoas">
                    <a href="<?= l('') ?>">
                        <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="">
                    </a>
                </div>  
            </div>
            <div class="col-md-5">
                <div class="nav-links-top">
                    <a href="<?= l('') ?>">Home</a>
                    <a href="#features">Features</a>
                    <a href="#membrshp"><i class="fa-solid fa-tag"></i> Pricing</a>
                </div>
            </div>
            <div class="col-md-5">
                <div class="side-action-top">
                    <?php if ($this->userid > 0): ?>
                        <a href="<?= l('dashboard') ?>"><i class="fa-solid fa-user"></i> Dashboard</a>
                        <a href="#signup" class="btn-1">Logout <i class="fa-light fa-arrow-right-to-bracket"></i></a>
                    <?php else: ?>
                        <a href="<?= l('login') ?>"><i class="fa-solid fa-user"></i> Login</a>
                        <a href="<?= l('logout') ?>" class="btn-1">Sign Up <i class="fa-light fa-arrow-right-to-bracket"></i></a>
                    <?php endif; ?>
                    <?php if(g('db.admin.discovery_call_link')): ?>
                        <a class="discovery_call_link btn-1" href="<?= g('db.admin.discovery_call_link') ?>" target="_blank">Book A Discovery Call</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="content-wrapp-bne">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h2>
                        Empower Your Business with AzAverze: <br /> Your <span>All-in-One Platform</span> for Success
                    </h2>
                    <p>
                        Streamline Operations, Boost Efficiency, and Connect Directly with Customers—all in one place.
                    </p>
                    <a href="#signup" class="btn-1">
                        Get Started Today
                    </a>
                    <a href="<?= l('') ?>coming-soon/v3/images/video.mp4" data-fancybox class="btn-paly">
                        <i class="fa-solid fa-play"></i> 
                        Learn more
                    </a>
                    <p>
                        No separate subscriptions needed.
                    </p>
                </div>
                <div class="col-md-6">
                    <img src="<?= g('images_root') ?>screen-load.png" alt="" class="sceresa" />
                </div>
            </div>
        </div>
    </div>
    <div class="barsd1">
        <img src="<?= g('images_root') ?>barrs.svg" alt="" />
    </div>
    <div class="barsd2">
        <img src="<?= g('images_root') ?>barrs.svg" alt="" />
    </div>
    <div class="shapersimg">
        <img src="<?= g('images_root') ?>shape1.png" alt="" />
    </div>
</section>
<?php endif; ?>