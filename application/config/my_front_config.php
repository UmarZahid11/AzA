<?php
//Get already set config variables from other files.
$config = $this->config;

//Set your own Configurations...
$config['base_url'];
$config['base_url_portal'] = $config['base_url'] . "customer-portal/";
$config['base_url_dashboard'] = $config['base_url'] . "dashboard/";
$config['assets_root'] = $config['base_url'] . "assets/front_assets/";
$config['css_root'] = $config['assets_root'] . "css/";
$config['js_root'] = $config['assets_root'] . "js/";
$config['images_root'] = $config['assets_root'] . "images/";
$config['slider_root'] = $config['images_root'] . "slider/";
$config['font_root'] = $config['assets_root'] . "font/";
$config['dashboard_images_root'] = $config['assets_root'] . "dashboard/images/";
// Prepare JSCONFIg
$config['js_config'] = $config;
