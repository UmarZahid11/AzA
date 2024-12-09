<?php

// Get already set config
$config = $this->config;

// title value overriden by config setting
$config['admin_title'] = "Azaverze";

$config['admin_base_url'] = $config['base_url'] . "admin/";
$config['admin_assets_root'] = $config['base_url'] . "assets/admin/";
$config['admin_css_root'] = $config['admin_assets_root'] . "css/";
$config['admin_tools_root'] = $config['admin_assets_root'] . "assets/";
$config['admin_widgets_root'] = $config['admin_assets_root'] . "widgets/";
$config['admin_js_root'] = $config['admin_assets_root'] . "scripts/";
$config['admin_images_root'] = $config['admin_assets_root'] . "img/";
$config['admin_plugins_root'] = $config['admin_assets_root'] . "plugins/";
$config['admin_plugins_root'] = $config['admin_assets_root'] . "plugins/";
$config['admin_fonts_css_root'] = $config['admin_assets_root'] . "assets/font-awesome/css/";
$config['admin_portfolio'] = $config['base_url'] . "/portfolio";
$config['image_not_found'] = $config['base_url'] . 'assets/global/images/dummy-image.jpg';
