<?php

global $config;

$menu_links = array(
    array(
        "title" => "Website", "icon" => "clip-world", "action" => "", "link" => "",
    ),
    array(
        "title" => "Dashboard", "icon" => "clip-home-3", "action" => "home", "link" => "home",
    ),
    array(
        "title" => "Coaching Management",
        "icon" => ' fa fa-graduation-cap',
        "action" => array("coaching", "coaching_cost"),
        "additionals" => array(
            array("link" => "coaching", "title" => "All coachings", "icon" => " fa fa-comments"),
            array("link" => "coaching_cost", "title" => "Cost management", "icon" => " fa fa-comments"),
        ),
    ),
    array(
        "title" => "Quickbooks",
        "svg" => '<svg style="color: rgb(232, 151, 255); width:18px;" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>QuickBooks</title><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm.642 4.1335c.9554 0 1.7296.776 1.7296 1.7332v9.0667h1.6c1.614 0 2.9275-1.3156 2.9275-2.933 0-1.6173-1.3136-2.9333-2.9276-2.9333h-.6654V7.3334h.6654c2.5722 0 4.6577 2.0897 4.6577 4.667 0 2.5774-2.0855 4.6666-4.6577 4.6666H12.642zM7.9837 7.333h3.3291v12.533c-.9555 0-1.73-.7759-1.73-1.7332V9.0662H7.9837c-1.6146 0-2.9277 1.316-2.9277 2.9334 0 1.6175 1.3131 2.9333 2.9277 2.9333h.6654v1.7332h-.6654c-2.5725 0-4.6577-2.0892-4.6577-4.6665 0-2.5771 2.0852-4.6666 4.6577-4.6666Z" fill="#e897ff"></path></svg>',
        "action" => array("quickbook_activity", "quickbook_account", "quickbook_account_request"),
        "additionals" => array(
            array("link" => "quickbook_activity", "title" => "Quickbooks activity", "icon" => " fa fa-comments"),
            array("link" => "quickbook_account", "title" => "Quickbooks accounts", "icon" => " fa fa-comments"),
            array("link" => "quickbook_account_request", "title" => "Quickbooks accounts requests", "icon" => " fa fa-comments"),
        ),
    ),
    // array(
    //     "title" => "Box",
    //     "svg" => '<svg style="color: rgb(232, 151, 255); width:18px;" class="logo-box" id="Layer_1" viewBox="0 0 40 21.6" xmlns="http://www.w3.org/2000/svg"><path class="box-logo-svg" d="M39.7 19.2c.5.7.4 1.6-.2 2.1-.7.5-1.7.4-2.2-.2l-3.5-4.5-3.4 4.4c-.5.7-1.5.7-2.2.2-.7-.5-.8-1.4-.3-2.1l4-5.2-4-5.2c-.5-.7-.3-1.7.3-2.2.7-.5 1.7-.3 2.2.3l3.4 4.5L37.3 7c.5-.7 1.4-.8 2.2-.3.7.5.7 1.5.2 2.2L35.8 14l3.9 5.2zm-18.2-.6c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c-.1 2.6-2.2 4.6-4.7 4.6zm-13.8 0c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c0 2.6-2.1 4.6-4.7 4.6zM21.5 6.4c-2.9 0-5.5 1.6-6.8 4-1.3-2.4-3.9-4-6.9-4-1.8 0-3.4.6-4.7 1.5V1.5C3.1.7 2.4 0 1.6 0 .7 0 0 .7 0 1.5v12.6c.1 4.2 3.5 7.5 7.7 7.5 3 0 5.6-1.7 6.9-4.1 1.3 2.4 3.9 4.1 6.8 4.1 4.3 0 7.8-3.4 7.8-7.7.1-4.1-3.4-7.5-7.7-7.5z"></path></svg>',
    //     "action" => array("box"),
    //     "additionals" => array(
    //         array("link" => "box", "title" => "Box users", "icon" => " fa fa-comments"),
    //     ),
    // ),
    array(
        "title" => "Zoom",
        "svg" => '<svg style="width:18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><style>.st0{fill:#fff}</style><path stroke-width=".714" d="M256 128C256 57.325 198.675 0 128 0S0 57.325 0 128s57.325 128 128 128c70.746 0 128-57.325 128-128z" style="fill:#e897ff"/><path stroke-width=".714" d="M45.26 81.383h94.305s22.987 2.284 23.772 20.703v71.317H72.031s-26.057 3.07-26.842-23.772V81.383z" class="st0"/><path d="m168.617 108.94 40.62-27.556v92.092l-40.62-26.914z" class="st0"/></svg>',
        "action" => array("meeting", "webinar"),
        "additionals" => array(
            array("link" => "meeting", "title" => "Meetings", "icon" => " fa fa-comments"),
            array("link" => "webinar", "title" => "Webinars", "icon" => " fa fa-comments"),
        ),
    ),
    array(
        "title" => "Layout", "icon" => "clip-screen", "action" => array("logo", "cms_page", "faq", "testimonial"),
        "additionals" => array(
            array("link" => "logo/add/1", "title" => "Logo management", "icon" => "folder"),
            array("link" => "cms_page", "title" => "CMS content", "icon" => "docs"),
            // array("link" => "faq", "title" => "Manage FAQs", "icon" => "question"),
            array("link" => "testimonial", "title" => "Testimonials", "icon" => "speech"),
        ),
    ),
    array(
        "title" => "Banner", "icon" => " fa fa-image", "action" => array("banner", "inner_banner"),
        "additionals" => array(
            array("link" => "banner", "title" => "Home Page Banner", "icon" => " fa fa-picture-o"),
            array("link" => "inner_banner", "title" => "Inner Banner", "icon" => "folder"),
        ),
    ),
    array(
        "title" => "Jobs", "icon" => " fa fa-briefcase", "action" => array("job", "job_category", "job_type", "job_application", "job_question", "job_milestone_payment", "job_testimonial_request"),
        "additionals" => array(
            array("link" => "job", "title" => "Jobs", "icon" => "speech"),
            array("link" => "job_category", "title" => "Categories", "icon" => "speech"),
            array("link" => "job_type", "title" => "Types", "icon" => "speech"),
            array("link" => "job_application", "title" => "Applications", "icon" => "speech"),
            array("link" => "job_question", "title" => "Job questions", "icon" => "speech"),
            array("link" => "job_milestone_payment", "title" => "Milestone payment", "icon" => "speech"),
            array("link" => "job_testimonial_request", "title" => "Testimonial bypassing requests", "icon" => "speech"),
        ),
    ),
    array(
        "title" => "Inquiries", "icon" => "fa fa-envelope-o", "action" => array("inquiry"),
        "additionals" => array(
            array("link" => "inquiry", "title" => "Respond to inquiries", "icon" => " fa fa-comments"),
        ),
    ),
    array(
        "title" => "Announcement", "icon" => "fa fa-bullhorn", "action" => array("announcement"),
        "additionals" => array(
            array("link" => "announcement", "title" => "Announcement", "icon" => " fa fa-comments"),
        ),
    ),
    array(
        "title" => "Membership", "icon" => " fa fa-gift", "action" => array("membership_interval", "membership", "membership_section", "membership_attribute_identifier", "membership_attribute", "membership_pivot"), "link" => "membership",
        "additionals" => array(
            array("link" => "membership_interval", "title" => "Intervals", "icon" => " fa fa-user-plus"),
            array("link" => "membership", "title" => "Types", "icon" => " fa fa-user-plus"),
            array("link" => "membership_section", "title" => "Section", "icon" => "envelope"),
            array("link" => "membership_attribute_identifier", "title" => "Attribute Identifiers", "icon" => "envelope"),
            array("link" => "membership_attribute", "title" => "Attributes", "icon" => "envelope"),
            array("link" => "membership_pivot", "title" => "Values", "icon" => "envelope"),
        ),
    ),
    array(
        "title" => "Product", "icon" => " fa fa-product-hunt", "action" => array("product"), "link" => "product",
        "additionals" => array(
            array("link" => "product", "title" => "Products", "icon" => " fa fa-user-plus"),
        ),
    ),
    array(
        "title" => "Order", "icon" => " fa fa-shopping-cart", "action" => array("order"), "link" => "order",
        "additionals" => array(
            array("link" => "order", "title" => "Orders", "icon" => " fa fa-user-plus"),
        ),
    ),
    array(
        "title" => "Blog & Stories", "icon" => " fa fa-circle-o-notch", "action" => array("blog", "story"), "link" => "blog",
        "additionals" => array(
            array("link" => "blog", "title" => "Blogs", "icon" => " fa fa-user-plus"),
            array("link" => "story", "title" => "Stories", "icon" => " fa fa-user-plus"),
        ),
    ),
    array(
        "title" => "Partner", "icon" => " fa fa-handshake-o", "action" => array("partner"), "link" => "partner",
        "additionals" => array(
            array("link" => "partner", "title" => "Partners", "icon" => " fa fa-user-plus"),
        ),
    ),
    array(
        "title" => "Career", "icon" => " fa fa-tasks", "action" => array("career"), "link" => "career",
        "additionals" => array(
            array("link" => "career", "title" => "Careers", "icon" => " fa fa-user-plus"),
        ),
    ),
    array(
        "title" => "User", "icon" => " fa fa-user-circle-o", "action" => array("signup_index", "signup_coming_soon", "signup_affiliate", "signup_log", "signup_promotion"),
        "additionals" => array(
            array("link" => "signup/index", "title" => "Signups", "icon" => "  fa fa-user-o"),
            array("link" => "signup/coming_soon", "title" => "Users Queries", "icon" => "  fa fa-user-o"),
            array("link" => "signup/affiliate", "title" => "Affiliate Signup", "icon" => "  fa fa-user-o"),
            array("link" => "signup_log", "title" => "Login logs", "icon" => "  fa fa-user-o"),
            array("link" => "signup_promotion", "title" => "Promotions", "icon" => "  fa fa-user-o"),
        ),
    ),
    array(
        "title" => "Administrator", "icon" => "clip-users-3", "action" => "admins",
        "additionals" => array(
            array("link" => "admins", "title" => "Admin", "icon" => " fa fa-user"),
        ),
    ),
    array(
        "title" => "Donations", "icon" => " fa fa-usd", "action" => array("fundraising", "donation"), "link" => "career",
        "additionals" => array(
            array("link" => "fundraising", "title" => "Add Activity", "icon" => " fa fa-user-plus"),
            array("link" => "donation", "title" => "Donation", "icon" => " fa fa-user-plus"),
        ),
    ),
    array(
        "title" => "Setting", "icon" => " fa fa-edit", "action" => array("config"),
        "additionals" => array(
            array("link" => "config/update", "title" => "General configuration", "icon" => " fa fa-edit"),
        ),
    ),
    array(
        "title" => "Logs", "icon" => " fa fa-history", "action" => array("log"),
        "additionals" => array(
            array("link" => "log", "title" => "View logs", "icon" => " fa fa-edit"),
        ),
    ),
);
?>

<div class="navbar-content">
    <!-- start: SIDEBAR -->
    <div class="main-navigation navbar-collapse collapse">
        <!-- start: MAIN MENU TOGGLER BUTTON -->
        <div class="navigation-toggler">
            <i class="clip-chevron-left"></i>
            <i class="clip-chevron-right"></i>
        </div>
        <!-- end: MAIN MENU TOGGLER BUTTON -->
        <!-- start: MAIN NAVIGATION MENU -->
        <ul class="main-navigation-menu">
            <?
            foreach ($menu_links as $key => $menu) {
                if (has_value($config['ci_class'], $menu['action']) || has_value($config['ci_index_page'], $menu['action'])) {
                    $active = "active";
                    $open = "open";
                    //$selected = '<span class="selected"></span>';
                } else {
                    $open = "";
                    $active = "";
                    //$selected = "";
                }
            ?>
                <?php
                if ($key == 0) { ?>
                    <li class="">
                        <a href="<?php echo $config['base_url'] ?>" target="_blank"><i class="<?= $menu['icon'] ?>"></i>
                            <span class="title"> <?= $menu['title'] ?> </span><?php if (isset($menu['additionals']) && is_array($menu['additionals']) && array_filled($menu['additionals'])) { ?> <i class="icon-arrow"></i> <?php } ?>
                            <span class="selected"></span>
                        </a>
                        <?php
                        if (isset($menu['additionals']) && is_array($menu['additionals']) && array_filled($menu['additionals'])) { ?>
                            <ul class="sub-menu">
                                <?php
                                foreach ($menu['additionals'] as $add) {
                                    if (has_value($config['ci_class'], $add['link']) || ($config['ci_class'] == 'logo' && $add['link'] == 'logo/add/1') || ($config['ci_class'] == 'config' && $add['link'] == 'config/update')) {
                                        $active1 = "active";
                                        $open1 = "open";
                                    } else {
                                        $active1 = "";
                                        $open1 = "";
                                    }
                                ?>
                                    <li class="<?php echo $active1; ?> <?php echo $open1; ?>">
                                        <a href="<?php echo $config['base_url'] . "admin/" . $add['link']; ?>">
                                            <span class="title"> <?php echo $add['title']; ?> </span>
                                            <!--<span class="badge badge-new">new</span>-->
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                <?php } else { ?>
                    <li class="<?php echo $active; ?> <?php echo $open; ?>">
                        <a href="<?php echo (isset($menu['additionals']) && is_array($menu['additionals']) && array_filled($menu['additionals'])) ? 'javascript:void' : $config['base_url'] . "admin/" . $menu['link'] ?>">
                            <?php if (isset($menu['icon'])) : ?>
                                <i class="<?= $menu['icon'] ?>"></i>
                            <?php elseif (isset($menu['svg'])) : ?>
                                <?php echo $menu['svg']; ?>
                            <?php elseif (isset($menu['img'])) : ?>
                                <img src="<?= $menu['img'] ?>" />
                            <?php endif; ?>
                            <span class="title"> <?= $menu['title'] ?> </span><?php if (isset($menu['additionals']) && is_array($menu['additionals']) && array_filled($menu['additionals'])) { ?> <i class="icon-arrow"></i> <?php } ?>
                            <span class="selected"></span>
                        </a>
                        <?php
                        if (isset($menu['additionals']) && is_array($menu['additionals']) && array_filled($menu['additionals'])) {
                        ?>
                            <ul class="sub-menu">
                                <?php
                                foreach ($menu['additionals'] as $add) {
                                    if ((has_value($config['ci_class'], $add['link']) || (($config['ci_class'] . '/' . $config['ci_method']) == $add['link'])) || ($config['ci_class'] == 'logo' && $add['link'] == 'logo/add/1') || ($config['ci_class'] == 'config' && $add['link'] == 'config/update')) {
                                        $active1 = "active";
                                        $open1 = "open";
                                    } else {
                                        $active1 = "";
                                        $open1 = "";
                                    }
                                ?>
                                    <li class="<?php echo $active1; ?> <?php echo $open1; ?>">
                                        <a href="<?php echo $config['base_url'] . "admin/" . $add['link']; ?>">
                                            <span class="title"> <?php echo $add['title']; ?> </span>
                                            <?php switch ($add['link']) {
                                                case 'job_testimonial_request' :
                                                    echo '<span class="badge badge-new">'. $this->model_job_testimonial_request->find_count_active(array('where' => array('job_testimonial_request_seen' => 0))) . '</span>';
                                                    break;
                                                case 'inquiry':
                                                    echo '<span class="badge badge-new">'. $this->model_inquiry->find_count_active(array('where' => array('inquiry_status' => 1))) . '</span>';
                                                    break;
                                                case 'blog':
                                                    echo '<span class="badge badge-new">'. $this->model_blog->find_count_active(array('where' => array('blog_approved' => 0))) . '</span>';
                                                    break;
                                                case 'signup/index':
                                                    echo '<span class="badge badge-new">'. $this->model_signup->find_count_active(array('where' => array('signup_is_approved' => 0))) . '</span>';
                                                    break;
                                            } ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                <?php } ?>
            <?php } ?>

        </ul>
        <!-- end: MAIN NAVIGATION MENU -->
    </div>
    <!-- end: SIDEBAR -->
</div>

<script>
    var $windowWidth;
    var $windowHeight;
    var $pageArea;
    var isMobile = false;
    $(function() {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            isMobile = true;
        }
        runElementsPosition();
        runNavigationToggler();
    });
    $('.main-navigation-menu li.active').addClass('open');
    $('.main-navigation-menu > li a').on('click', function() {
        if ($(this).parent().children('ul').hasClass('sub-menu') && ((!$('body').hasClass('navigation-small') || $windowWidth < 767) || !$(this).parent().parent().hasClass('main-navigation-menu'))) {
            if (!$(this).parent().hasClass('open')) {
                $(this).parent().addClass('open');
                $(this).parent().parent().children('li.open').not($(this).parent()).not($('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200);
                $(this).parent().children('ul').slideDown(200, function() {
                    runContainerHeight();
                });
            } else {
                if (!$(this).parent().hasClass('active')) {
                    $(this).parent().parent().children('li.open').not($('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200, function() {
                        runContainerHeight();
                    });
                } else {
                    $(this).parent().parent().children('li.open').removeClass('open').children('ul').slideUp(200, function() {
                        runContainerHeight();
                    });
                }
            }
        }
    });

    //function to adapt the Main Content height to the Main Navigation height
    var runContainerHeight = function() {
        mainContainer = $('.main-content > .container');
        mainNavigation = $('.main-navigation');
        if ($pageArea < 760) {
            $pageArea = 760;
        }
        if (mainContainer.outerHeight() < mainNavigation.outerHeight() && mainNavigation.outerHeight() > $pageArea) {
            mainContainer.css('min-height', mainNavigation.outerHeight());
        } else {
            mainContainer.css('min-height', $pageArea);
        }
        if ($windowWidth < 768) {
            mainNavigation.css('min-height', $windowHeight - $('body > .navbar').outerHeight());
        }
        //$("#page-sidebar .sidebar-wrapper").css('height', $windowHeight - $('body > .navbar').outerHeight()).scrollTop(0).perfectScrollbar('update');
    };

    //function to adjust the template elements based on the window size
    var runElementsPosition = function() {
        $windowWidth = $(window).width();
        $windowHeight = $(window).height();
        $pageArea = $windowHeight - $('body > .navbar').outerHeight() - $('body > .footer').outerHeight();
        if (!isMobile) {
            $('.sidebar-search input').removeAttr('style').removeClass('open');
        }
        runContainerHeight();

    };

    //function to reduce the size of the Main Menu
    var runNavigationToggler = function() {
        $('.navigation-toggler').on('click', function() {
            if (!$('body').hasClass('navigation-small')) {
                $('body').addClass('navigation-small');
            } else {
                $('body').removeClass('navigation-small');
            };
        });
    };
</script>