<div class="sidebar-toggle-box">
    <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
</div>

<!--logo start-->
<a href="<?= base_url() . 'admin' ?>" class="logo"><?= $config['admin_title'] ?><span> Admin Panel</span></a>
<!--logo end-->

<div class="top-nav">
    <ul class="nav pull-right top-menu">
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img style="max-width:75px;background:black;" alt="" src="<?= $config['admin_images_root']; ?>logo.png">
                <span class="username"><? ucfirst($this->session_data['username']); ?></span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <div class="log-arrow-up"></div>
                <li><a href="<?php echo site_url("admin/logout") ?>"><i class="icon-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
</div>