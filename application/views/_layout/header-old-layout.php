<header>
    <!--<div class="top-marquee">-->
    <!--    <marquee behavior="scroll" direction="left" scrollamount="10"><?= g('db.admin.header_message') ?? 'AzAverze is a collaboration platform that connects people and technology to value creating life science opportunities.' ?></marquee>-->
    <!--</div>-->
    <div class="marquee-list">
        <ul>
            <li><?= g('db.admin.header_message') ?? 'AzAverze is a collaboration platform that connects people and technology to value creating life science opportunities.' ?></li>
            <li><?= g('db.admin.header_message') ?? 'AzAverze is a collaboration platform that connects people and technology to value creating life science opportunities.' ?></li>
            <li><?= g('db.admin.header_message') ?? 'AzAverze is a collaboration platform that connects people and technology to value creating life science opportunities.' ?></li>
            <li><?= g('db.admin.header_message') ?? 'AzAverze is a collaboration platform that connects people and technology to value creating life science opportunities.' ?></li>
        </ul>
    </div>
    <div class="header-in">
        <?php if($this->userid > 0 && $this->router->class != 'verification'): ?>
            <div class="side-btn"><i class="fa-light fa-bars-staggered"></i></div>
        <?php endif; ?>
        <div class="hd-logo">
            <a href="<?= l('') ?>">
                <img alt="" class="img-responsive lazy" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%200%200'%3E%3C/svg%3E" data-src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image']) ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
            </a>
        </div>
        <?php if($this->userid > 0 && $this->router->class != 'verification'): ?>
            <form class="searchJob d-flex w-100" method="POST" action="javascript:;">
                <div class="search-hd-box">
                    <button><i class="fa-light fa-magnifying-glass"></i></button>
                    <input type="text" class="border-0" name="search" placeholder="Search" value="<?= isset($search) ? ($search ?? '') : '' ?>" />
                </div>
            </form>
        <?php else: ?>
            <div class="d-flex w-100"></div>
        <?php endif; ?>

        <div class="user-aress">
            <?php if ($this->userid == 0) : ?>
                <a href="<?= l('signup') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''); ?>">Sign up</a>
                <span>Or</span>
                <a href="<?= l('signin') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''); ?>">Sign in</a>
            <?php else : ?>
                <!--<a href="<?= l('dashboard') ?>">Entrepreneur Dashboard</a> -->
                <?php if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                    <a href="javascript:;" id="socialForum" data-disabled="0" data-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="">
                        AzAverze Community
                    </a>
                    |
                <?php endif; ?>
                <a href="<?= l('logout') ?>">Logout</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if($this->userid > 0 && $this->router->class != 'verification'): ?>
        <div class="sidebar">
            <ul id="menu">

                <li class="home"><a href="<?= l('') ?>"><i class="fa-light fa-house"></i> <span><?= $config['title'] ?> </span></a></li>

                <!--<li class="dashboard"><a href="<?= l('dashboard') ?>"><img src="https://azaverze.com/stagging/assets/front_assets/images/s1.png"> <span>Entrepreneur Dashboard </span></a></li>-->

                <li class="nav-item membership"><a href="<?= l('membership') ?>"><i class="fa-light fa-memo-pad"></i><span>Subscription</span></a></li>

                    <?php //if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                        <!--<li class="nav-item"><a href="https://monday.com/" target="_blank"><i class="fa-light fa-screwdriver-wrench"></i> <span>Goto CRM dashboard</span></a></li>-->
                    <?php //endif; ?>
    
                    <li class="nav-item">
                        <a href="<?= l('dashboard') ?>"><i class="material-icons">&#xe0bf;</i> <span>Entrepreneur Dashboard</span></a>
                        <!--<a href="javascript:;" id="socialForum" data-disabled="0" data-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="">-->
                        <!--    <i class="material-icons">&#xe0bf;</i> <span>AzAverze Community</span>-->
                        <!--</a>-->
                    </li>

                <?php if ($this->userid > 0): ?>
                    <!--<li class="nav-item blog"><a href="<?= l('job') ?>"><i class="fa-light fa-tasks"></i> <span>Job</span></a></li>-->
                <?php endif; ?>

                <!--<li class="nav-item blog"><a href="<?= l('blog') ?>"><i class="fa-light fa-blog"></i> <span>Blog</span></a></li>-->

                <!--<li class="nav-item story"><a href="<?= l('story') ?>"><i class="fa-light fa-history"></i> <span>Stories</span></a></li>-->

                <li class="nav-item contact"><a href="<?= l('contact') ?>"><i class="fa-light fa-address-book"></i> <span>Contact</span></a></li>

                <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>
                    <li class="nav-item career"><a href="<?= l('career') ?>"><i class="fa-light fa-briefcase"></i><span>Careers</span></a></li>
                <?php endif; ?>

                <!--<li class="nav-item opportunities"><a href="<?= l('opportunity') ?>"><i class="fa-light fa-layer-plus"></i> <span>Announcements</span></a></li>-->
                <li class="nav-item opportunities"><a href="<?= l('announcement/listing') ?>"><i class="fa-light fa-layer-plus"></i> <span>Announcements</span></a></li>

                <!--<li class="nav-item support"><a href="<?= l('support') ?>"><i class="fa-light fa-square-question"></i> <span>Support</span></a></li>-->

                <li class="nav-item press"><a href="<?= l('press') ?>"><i class="fa-light fa-newspaper"></i><span>Press</span></a></li>

                <li class="nav-item partner"><a href="<?= l('partner') ?>"><i class="fa-light fa-handshake"></i> <span>Partners</span></a></li>

                <li class="nav-item investor_relation"><a href="<?= l('investor-relation') ?>"><i class="fa-light fa-circle-dollar"></i> <span>Investor Relations</span></a></li>

                <!--<li class="nav-item expert"><a href="<?= l('expert') ?>"><i class="fa-light fa-users"></i> <span>Experts</span></a></li>-->

            </ul>
        </div>
    <?php endif; ?>
</header>