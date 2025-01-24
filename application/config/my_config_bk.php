<?php
//Get already set config variables from other files.
$config = $this->config;

// title value overriden by config setting
$config['title'] = "AzAverze";
$config['site_name'] = "AzAverze";
//

//Set your own Configurations...
$config['site_assets_root'] = $config['base_url'] . "assets/";
$config['site_global_root'] = $config['site_assets_root'] . "global/";
$config['plugins_root'] = $config['site_global_root'] . "plugins/";
$config['site_global_images_root'] = $config['site_global_root'] . "images/";
$config['site_global_emojis_root'] = $config['site_global_root'] . "images/emojis/";
$config['site_css_root'] = $config['site_assets_root'] . "css/";
$config['site_widgets_root'] = $config['site_assets_root'] . "widgets/";
$config['site_front_assets'] = $config['site_assets_root'] . "front_assets/";
$config['site_js_root'] = $config['site_assets_root'] . "js/";
$config['site_images_root'] = $config['site_assets_root'] . "images/";
$config['site_categories_images_root'] = $config['site_images_root'] . "categories/";
$config['site_brochures_root'] = $config['site_assets_root'] . "images/brochures/";

//
$config['ci_paginate'] = array();
$config['ci_paginate']['uri'] = "paginate";
$config['ci_paginate']['update_status_uri'] = "update_status";

// Store the Configuration from ABove for use in JS_CONFIG
$config['js_config'] = $config;

//Upload Roots
$config['site_upload_img_root'] = "assets/images/";

// UPLOADS
$config['site_upload_default'] = "assets/uploads/";
$config['site_upload_brand'] = $config['site_upload_default'] . "brand/";
$config['site_upload_logo'] = $config['site_upload_default'] . "logo/";
$config['site_upload_company'] = $config['site_upload_default'] . "company/";
$config['site_upload_portfolio'] = $config['site_upload_default'] . "portfolio/";
$config['site_upload_banner'] = $config['site_upload_default'] . "banner/";
$config['site_upload_gallery'] = $config['site_upload_default'] . "gallery/";
$config['site_upload_cms_image'] = $config['site_upload_default'] . "cms/";
$config['site_upload_services'] = $config['site_upload_default'] . "services/";
$config['site_upload_signup'] = $config['site_upload_default'] . "user/";
$config['site_upload_resume'] = $config['site_upload_default'] . "resume/";
$config['site_upload_csv'] = $config['site_upload_default'] . "csv/";
$config['excel_upload'] = $config['site_upload_default'] . "excel/";
$config['site_portfolio'] = $config['base_url'] . "portfolio";

//PHPExcel External Class
$config['PHPExcel_Path'] = $config['base_url'] . "assets/admin/PHPExcel/";

//Site LINKS
$config['currency'] = "$";
$config['currency_rate'] = "1.00";

//
if (!defined('MAX_FILE_SIZE')) define("MAX_FILE_SIZE", 10485760);
if (!defined('ERROR_UPLOAD_LIMIT_EXCEED')) define("ERROR_UPLOAD_LIMIT_EXCEED", "The file exceeds upload limit");

// TIMEZONE FOR DB - LEAVE EMPTY STRING IF NOT REQUIRED
// TIMEZONE START

if (ENVIRONMENT == 'development') {
    define("MYSQL_TIME_ZONE", "+0:00");
    define("PHP_TIME_ZONE", "America/Los_Angeles");
} elseif (ENVIRONMENT == 'testing') {
    define("MYSQL_TIME_ZONE", "+0:00");
    define("PHP_TIME_ZONE", "America/Los_Angeles");
} else {
    define("MYSQL_TIME_ZONE", "+0:00");
    define("PHP_TIME_ZONE", "America/Los_Angeles");
}

// TIMEZONE END

//
if (!defined('DEFAULT_CURRENCY_SYMBOL')) define('DEFAULT_CURRENCY_SYMBOL', '$');
if (!defined('DEFAULT_COUNTRY_CODE')) define('DEFAULT_COUNTRY_CODE', 'US');
if (!defined('DEFAULT_CURRENCY_CODE')) define('DEFAULT_CURRENCY_CODE', 'USD');

//
if (!defined('CI_ENCRYPTION_SECRET')) define('CI_ENCRYPTION_SECRET', 'mUBoiREb1xjiW2lUAc8AkI85BQJyTp8lDGH3OZG8n1qOHyURwdIVapyXSUVQAeGN');
if (!defined('CI_ENCRYPTION_PUBLIC')) define('CI_ENCRYPTION_PUBLIC', 'A38D4F9DA279D9466F8B395EDD9C3');

//
define("ACCESS_PUBLIC", 1);
define("ACCESS_PRIVATE", 2);

//
define("INVITE_SENT", 1);
define("INVITE_ACCEPTED", 2);
define("INVITE_DENIED", 0);

//
if (!defined('STATUS_ACTIVE')) define("STATUS_ACTIVE", 1);
if (!defined('STATUS_INACTIVE')) define("STATUS_INACTIVE", 0);
if (!defined('STATUS_DELETE')) define("STATUS_DELETE", 2);
if (!defined('STATUS_AVAILED')) define("STATUS_AVAILED", 3);
if (!defined('STATUS_REJECTED')) define("STATUS_REJECTED", 4);

//
if (!defined('STATUS_TRUE')) define("STATUS_TRUE", TRUE);
if (!defined('STATUS_FALSE')) define("STATUS_FALSE", FALSE);

//
define("YES", 1);
define("NO", 0);

//
define('VENDOR', 1);
define('ADMIN', 1);

// ORDER CONFIGS START

define("ORDER_NO_MASK", "AZA-INV-0%0");

//
if (!defined('ORDER_PAYMENT_PENDING')) define('ORDER_PAYMENT_PENDING', 0);
if (!defined('ORDER_PAYMENT_PAID')) define('ORDER_PAYMENT_PAID', 1);
if (!defined('ORDER_PAYMENT_CANCELLED')) define('ORDER_PAYMENT_CANCELLED', 2);
if (!defined('ORDER_PAYMENT_TRIALING')) define('ORDER_PAYMENT_TRIALING', 3);
if (!defined('ORDER_PAYMENT_FAILED')) define('ORDER_PAYMENT_FAILED', 4);
if (!defined('ORDER_PAYMENT_ESCROW')) define('ORDER_PAYMENT_ESCROW', 5);
//
if (!defined('ORDER_REFERENCE_MEMBERSHIP')) define('ORDER_REFERENCE_MEMBERSHIP', 1);
if (!defined('ORDER_REFERENCE_PRODUCT')) define('ORDER_REFERENCE_PRODUCT', 2);
if (!defined('ORDER_REFERENCE_TECHNOLOGY')) define('ORDER_REFERENCE_TECHNOLOGY', 3);
if (!defined('ORDER_REFERENCE_SERVICE')) define('ORDER_REFERENCE_SERVICE', 4);
if (!defined('ORDER_REFERENCE_JOB')) define('ORDER_REFERENCE_JOB', 5);
if (!defined('ORDER_REFERENCE_TECHNOLOGY_LISTING')) define('ORDER_REFERENCE_TECHNOLOGY_LISTING', 6);
if (!defined('ORDER_REFERENCE_COACHING')) define('ORDER_REFERENCE_COACHING', 7);

//
if (!defined('ORDER_PAID')) define('ORDER_PAID', 'purchased');
if (!defined('ORDER_UNPAID')) define('ORDER_UNPAID', 'pending');
//
if (!defined('ORDER_DECLINED')) define('ORDER_DECLINED', 'declined');
if (!defined('ORDER_DECLINED_MESSAGE')) define('ORDER_DECLINED_MESSAGE', 'The order payment has beed declined.');
if (!defined('ORDER_SUCCESS')) define('ORDER_SUCCESS', 'success');
if (!defined('ORDER_SUCCESS_MESSAGE')) define('ORDER_SUCCESS_MESSAGE', 'The order payment was successful.');
if (!defined('ORDER_FAILED')) define('ORDER_FAILED', 'failed');
if (!defined('ORDER_FAILED_MESSAGE')) define('ORDER_FAILED_MESSAGE', 'The order payment has failed.');

// ORDER CONFIGS END

// PRODUCT START

if (!defined('PRODUCT_REFERENCE_MEMBERSHIP')) define('PRODUCT_REFERENCE_MEMBERSHIP', 'membership');
if (!defined('PRODUCT_REFERENCE_PRODUCT')) define('PRODUCT_REFERENCE_PRODUCT', 'product');
if (!defined('PRODUCT_REFERENCE_TECHNOLOGY')) define('PRODUCT_REFERENCE_TECHNOLOGY', 'technology');
if (!defined('PRODUCT_REFERENCE_TECHNOLOGY_LISTING')) define('PRODUCT_REFERENCE_TECHNOLOGY_LISTING', 'technology-listing');
if (!defined('PRODUCT_REFERENCE_SERVICE')) define('PRODUCT_REFERENCE_SERVICE', 'service');
if (!defined('PRODUCT_REFERENCE_JOB')) define('PRODUCT_REFERENCE_JOB', 'job');
if (!defined('PRODUCT_REFERENCE_COACHING')) define('PRODUCT_REFERENCE_COACHING', 'coaching');

// PRODUCT END

// PAYPAL CONFIGS START

define("PAYMENT_ORDER_CANCEL_STATUS", '2');
define("PAYMENT_ORDER_CANCEL_REASON", 'Transaction Cancelled by User');

define("PAYMENT_ORDER_SUCCESS_STATUS", '1');
define("PAYMENT_ORDER_SUCCESS_REASON", 'Payment Successfully Transfered');

define("PAYMENT_ORDER_GIFT_STATUS", '1');
define("PAYMENT_ORDER_GIFT_REASON", 'Credits Gift By Admin');

define("PAYMENT_ORDER_ADMIN_REFUND_STATUS", '8');
define("PAYMENT_ORDER_ADMIN_REFUND_REASON", 'Reversed/Refunded by Admin');

define("PAYMENT_ORDER_COMPLETE_STATUS", '3');
define("PAYMENT_ORDER_COMPLETE_REASON", 'Transaction complete - Redirected from Payment Gateway');

define("PAYMENT_ORDER_PENDING_STATUS", '0');
define("PAYMENT_ORDER_PENDING_REASON", 'Transaction pending. User not yet visited Payment Gateway');

// PAYPAL CONFIGS END

// CONFIGS START

define("CONFIG_ADMIN", 'admin');
define("CONFIG_SYSTEM", 'system');

// CONFIGS END

// GOOGLE CAPTCHA START

define("GOOGLE_CAPTCHA_SITE_KEY", "6LehiMAeAAAAAMPi0nVXW9mGlVS9CaI9sdpW4pz4");
define("GOOGLE_CAPTCHA_SECRET_KEY", "6LehiMAeAAAAAELnDa3pRLHY9MHxmBmFiMexpIEr");

// GOOGLE CAPTCHA END

//
$config['appId']   = '437437646454367';
$config['secret']  = '1564932dd4a202a9dc8801ebf1e8f5f3';

// GOOGLE MAP START

define("GOOGLE_MAP_API", "AIzaSyCHF3gvFxOlMLRsPGoBSKTlpRQhJy9l3zY");
define("RADIUS", 10);

// GOOGLE MAP END

// LOGIN ATTEMPT START

if (!defined('LOGIN_ATTEMPT_LIMIT')) define('LOGIN_ATTEMPT_LIMIT', 5);
if (!defined('LOGIN_ATTEMPT_TIME_LIMIT')) define('LOGIN_ATTEMPT_TIME_LIMIT', 10); // in minutes

// LOGIN ATTEMPT END

// PAYPAL START

if (ENVIRONMENT == 'development') {
    // if (!defined('PAYPAL_CLIENTID')) define('PAYPAL_CLIENTID', 'AXEZqsKp6N67e5FXvjfusss_jjeeJC1Uc5mWSw5vL70Vu2rhUk46pbiPIN5mjgZ4GcUCXf5vb8k94-AW');
    // if (!defined('PAYPAL_SECRETKEY')) define('PAYPAL_SECRETKEY', 'EKASm2J6NnIZHDeNHr09Xh9IdKOB5gq07It4vAWihgzLG4yo8b81EuV5pwk53HVBEWDOnY1mZXHyyOgB');

    if (!defined('PAYPAL_CLIENTID')) define('PAYPAL_CLIENTID', 'AV5vTX81YaunjdOKLNNQzcClzJaGuADckIL2P94AJiSPL690o193Hwq13-Ur1nSVT7ZcCjdBUWnbdG6q');
    if (!defined('PAYPAL_SECRETKEY')) define('PAYPAL_SECRETKEY', 'EOiRk2TwkNUngQtIm8X5rf2uwjMed8bLDtg2oMkWgfLtFuz-lG-arNOfHNz-Awy9ywg8-hp6e1GO__XD');
    if (!defined('PAYPAL_URL')) define('PAYPAL_URL', 'https://api-m.sandbox.paypal.com');
} elseif (ENVIRONMENT == 'testing') {
    // if (!defined('PAYPAL_CLIENTID')) define('PAYPAL_CLIENTID', 'AfJjC8afmlyrJmIP3HtFF6FyM2rqOtEbOF0VNZ7sjTwrWXPKOZBvYnUkIxuPjnMlj0knK0Qe-TzvEypg');
    // if (!defined('PAYPAL_SECRETKEY')) define('PAYPAL_SECRETKEY', 'EKoj1l_JLi1PRpWTf1DzlKowBMp3JmhwKqWvVqah2nzUbOleqpUXlAOYfRrTN25-6_dbPYQSwsXz0gly');

    if (!defined('PAYPAL_CLIENTID')) define('PAYPAL_CLIENTID', 'AV5vTX81YaunjdOKLNNQzcClzJaGuADckIL2P94AJiSPL690o193Hwq13-Ur1nSVT7ZcCjdBUWnbdG6q');
    if (!defined('PAYPAL_SECRETKEY')) define('PAYPAL_SECRETKEY', 'EOiRk2TwkNUngQtIm8X5rf2uwjMed8bLDtg2oMkWgfLtFuz-lG-arNOfHNz-Awy9ywg8-hp6e1GO__XD');
    if (!defined('PAYPAL_URL')) define('PAYPAL_URL', 'https://api-m.sandbox.paypal.com');
} else {
    // if (!defined('PAYPAL_CLIENTID')) define('PAYPAL_CLIENTID', 'AXEZqsKp6N67e5FXvjfusss_jjeeJC1Uc5mWSw5vL70Vu2rhUk46pbiPIN5mjgZ4GcUCXf5vb8k94-AW');
    // if (!defined('PAYPAL_SECRETKEY')) define('PAYPAL_SECRETKEY', 'EKASm2J6NnIZHDeNHr09Xh9IdKOB5gq07It4vAWihgzLG4yo8b81EuV5pwk53HVBEWDOnY1mZXHyyOgB');
    if (!defined('PAYPAL_CLIENTID')) define('PAYPAL_CLIENTID', 'AV5vTX81YaunjdOKLNNQzcClzJaGuADckIL2P94AJiSPL690o193Hwq13-Ur1nSVT7ZcCjdBUWnbdG6q');
    if (!defined('PAYPAL_SECRETKEY')) define('PAYPAL_SECRETKEY', 'EOiRk2TwkNUngQtIm8X5rf2uwjMed8bLDtg2oMkWgfLtFuz-lG-arNOfHNz-Awy9ywg8-hp6e1GO__XD');
    if (!defined('PAYPAL_URL')) define('PAYPAL_URL', 'https://api-m.paypal.com');
}

if (!defined('PAYPAL_AUTH_URL')) define('PAYPAL_AUTH_URL', '/v1/oauth2/token');
if (!defined('PAYPAL_CHECKOUT_URL')) define('PAYPAL_CHECKOUT_URL', '/v2/checkout/orders');
if (!defined('PAYPAL_WEBHOOK_URL')) define('PAYPAL_WEBHOOK_URL', '/v1/notifications/webhooks');
if (!defined('PAYPAL_REFERRAL_URL')) define('PAYPAL_REFERRAL_URL', '/v2/customer/partner-referrals');
if (!defined('PAYPAL_PRODUCT_URL')) define('PAYPAL_PRODUCT_URL', '/v1/catalogs/products');
if (!defined('PAYPAL_PLAN_URL')) define('PAYPAL_PLAN_URL', '/v1/billing/plans');
if (!defined('PAYPAL_SUBSCRIPTION_URL')) define('PAYPAL_SUBSCRIPTION_URL', '/v1/billing/subscriptions');
if (!defined('PAYPAL_SUBSCRIPTION_CANCEL_URL')) define('PAYPAL_SUBSCRIPTION_CANCEL_URL', '/v1/billing/subscriptions/{subscription_id}/cancel');
if (!defined('PAYPAL_AUTHORIZATION_CAPTURE_URL')) define('PAYPAL_AUTHORIZATION_CAPTURE_URL', '/v2/payments/authorizations/{orderId}/capture');
if (!defined('PAYPAL_PAYMENT_URL')) define('PAYPAL_PAYMENT_URL', '/v1/payments/payment');

// PAYPAL END

// TWILIO START

if (ENVIRONMENT == 'development') {
    if (!defined('TWILIO_ENVIRONMENT')) define('TWILIO_ENVIRONMENT', 'development');
} elseif (ENVIRONMENT == 'testing') {
    if (!defined('TWILIO_ENVIRONMENT')) define('TWILIO_ENVIRONMENT', 'development');
} else {
    if (!defined('TWILIO_ENVIRONMENT')) define('TWILIO_ENVIRONMENT', 'production');
}

// if (!defined('TWILIO_SERVICE_SID')) define('TWILIO_SERVICE_SID', 'MGbd19120f7aaa9786d1e791ffa089b7ed');
if (!defined('TWILIO_SERVICE_SID')) define('TWILIO_SERVICE_SID', 'MGbd19120f7aaa9786d1e791ffa089b7ed');

// if (!defined('TWILIO_ACCOUNT_SID')) define('TWILIO_ACCOUNT_SID', 'ACd515dc1ccc7365734683a126dba3739d');
if (!defined('TWILIO_ACCOUNT_SID')) define('TWILIO_ACCOUNT_SID', 'ACd515dc1ccc7365734683a126dba3739d');

// if (!defined('TWILIO_AUTH_TOKEN')) define('TWILIO_AUTH_TOKEN', '9378262f6c730f8ee7681eb6dc36977c');
if (!defined('TWILIO_AUTH_TOKEN')) define('TWILIO_AUTH_TOKEN', '9067e80c58781a348ddc507daaad4395');

// +18556351015

// TWILIO END

//
if (!defined('MAP_BOX_API_KEY')) define('MAP_BOX_API_KEY', 'pk.eyJ1IjoiaGFtemEwMDkwIiwiYSI6ImNrdjA5MHh3MzVudGMyb29mMm45Nmo2ajYifQ.pT77m3zrBhJmNHJ6594LdQ');
// if (!defined('MAP_BOX_API_KEY')) define('MAP_BOX_API_KEY', 'pk.eyJ1IjoiZXhhbXBsZXMiLCJhIjoiY2p0MG01MXRqMW45cjQzb2R6b2ptc3J4MSJ9.zA2W0IkI0c6KaAhJfk9bWg');

// //
// if (!defined('USER_POSTFIX')) define('USER_POSTFIX', '_USER');
// if (!defined('MEMBERSHIP_POSTFIX')) define('MEMBERSHIP_POSTFIX', '_MEMBERSHIP');

// //
// if (!defined('GENERAL_USER')) define('GENERAL_USER', 1);
// if (!defined('ASSOCIATE_USER')) define('ASSOCIATE_USER', 2);
// if (!defined('ORGANIZATION_USER')) define('ORGANIZATION_USER', 3);

// //
// if (!defined('GENERAL_MEMBERSHIP')) define('GENERAL_MEMBERSHIP', 1);
// if (!defined('ASSOCIATE_MEMBERSHIP')) define('ASSOCIATE_MEMBERSHIP', 2);
// if (!defined('ORGANIZATION_MEMBERSHIP')) define('ORGANIZATION_MEMBERSHIP', 3);

// //
// if (!defined('GENERAL')) define('GENERAL', 'General');
// if (!defined('ASSOCIATE')) define('ASSOCIATE', 'Associate');
// if (!defined('ORGANIZATION')) define('ORGANIZATION', 'Organization');

//
if (!defined('RAW_ROLE_PREFIX')) define('RAW_ROLE_PREFIX', 'RAW_ROLE_');
//
// names need to be changed from admin management as well, those names are rendered on membership page
if (!defined('RAW_ROLE_0')) define('RAW_ROLE_0', 'Administrator');
if (!defined('RAW_ROLE_1')) define('RAW_ROLE_1', 'Customer');
if (!defined('RAW_ROLE_3')) define('RAW_ROLE_3', 'Entrepreneur');
if (!defined('RAW_ROLE_4')) define('RAW_ROLE_4', 'Innovator');
if (!defined('RAW_ROLE_5')) define('RAW_ROLE_5', 'Leader');
//
if (!defined('ROLE_PREFIX')) define('ROLE_PREFIX', 'ROLE_');
//
if (!defined('ROLE_0')) define('ROLE_0', 0); // Administrator
if (!defined('ROLE_1')) define('ROLE_1', 1);
if (!defined('ROLE_3')) define('ROLE_3', 3);
if (!defined('ROLE_4')) define('ROLE_4', 4);
if (!defined('ROLE_5')) define('ROLE_5', 5);

//
if (!defined('MEMBERSHIP_PREFIX')) define('MEMBERSHIP_PREFIX', 'MEMBERSHIP_');
//
if (!defined('MEMBERSHIP_1')) define('MEMBERSHIP_1', 1);
if (!defined('MEMBERSHIP_2')) define('MEMBERSHIP_2', 2);
if (!defined('MEMBERSHIP_3')) define('MEMBERSHIP_3', 3);


//
if (!defined('SUBSCRIPTION_INACTIVE')) define('SUBSCRIPTION_INACTIVE', 0);
if (!defined('SUBSCRIPTION_ACTIVE')) define('SUBSCRIPTION_ACTIVE', 1);
if (!defined('SUBSCRIPTION_CANCELLED')) define('SUBSCRIPTION_CANCELLED', 2);
if (!defined('SUBSCRIPTION_TRIAL')) define('SUBSCRIPTION_TRIAL', 3);

// Cost per month attribute, table: fb_membership_attribute (for membership)
if (!defined('COST_ATTRIBUTE')) define('COST_ATTRIBUTE', 2);
// Fee for Accepted Work Listing (by Associate) of Associate (by Organization) attribute, table: fb_membership_attribute (for membership)
if (!defined('FEE_ATTRIBUTE')) define('FEE_ATTRIBUTE', 3);

//
if (!defined('FAIL')) define('FAIL', 0);
if (!defined('SUCCESS')) define('SUCCESS', 1);

//
if (!defined('JOB_PENDING')) define('JOB_PENDING', 0);
if (!defined('JOB_APPROVED')) define('JOB_APPROVED', 1);
if (!defined('JOB_DECLINED')) define('JOB_DECLINED', 2);

//
if (!defined('JOB_PENDING_VALUE')) define('JOB_PENDING_VALUE', 'Pending');
if (!defined('JOB_APPROVED_VALUE')) define('JOB_APPROVED_VALUE', 'Approved');
if (!defined('JOB_DECLINED_VALUE')) define('JOB_DECLINED_VALUE', 'Declined');

//
// 0. incomplete, 1. completed, 2. revision, 3. processing
if (!defined('MILESTONE_INCOMPLETE')) define('MILESTONE_INCOMPLETE', 0);
if (!defined('MILESTONE_COMPLETE')) define('MILESTONE_COMPLETE', 1);
if (!defined('MILESTONE_REVISION')) define('MILESTONE_REVISION', 2);
if (!defined('MILESTONE_PROCESSING')) define('MILESTONE_PROCESSING', 3);

if (!defined('MILESTONE_PAYMENT_PENDING')) define('MILESTONE_PAYMENT_PENDING', 0);
if (!defined('MILESTONE_PAYMENT_PAID')) define('MILESTONE_PAYMENT_PAID', 1);
if (!defined('MILESTONE_PAYMENT_ESCROW')) define('MILESTONE_PAYMENT_ESCROW', 2);

//
if (!defined('MILESTONE_STRIPE_PAYMENT')) define('MILESTONE_STRIPE_PAYMENT', 'stripe');
if (!defined('MILESTONE_PLAID_PAYMENT')) define('MILESTONE_PLAID_PAYMENT', 'plaid');

// NOTIFICATION START

if (!defined('NOTIFICATION_WELCOME')) define('NOTIFICATION_WELCOME', 'WELCOME');
if (!defined('NOTIFICATION_WELCOME_COMMENT')) define('NOTIFICATION_WELCOME_COMMENT', 'welcome to ' . $config['title'] . '.');

if (!defined('NOTIFICATION_SETTING_UPDATE')) define('NOTIFICATION_SETTING_UPDATE', 'SETTING_UPDATE');
if (!defined('NOTIFICATION_SETTING_UPDATE_COMMENT')) define('NOTIFICATION_SETTING_UPDATE_COMMENT', 'your settings has been updated.');

if (!defined('NOTIFICATION_PROFILE_UPDATE')) define('NOTIFICATION_PROFILE_UPDATE', 'PROFILE_UPDATE');
if (!defined('NOTIFICATION_PROFILE_UPDATE_COMMENT')) define('NOTIFICATION_PROFILE_UPDATE_COMMENT', 'your profile has been updated.');

if (!defined('NOTIFICATION_PROFILE_IMAGE_UPDATE')) define('NOTIFICATION_PROFILE_IMAGE_UPDATE', 'PROFILE_IMAGE_UPDATE');
if (!defined('NOTIFICATION_PROFILE_IMAGE_UPDATE_COMMENT')) define('NOTIFICATION_PROFILE_IMAGE_UPDATE_COMMENT', 'your profile image has been updated.');

if (!defined('NOTIFICATION_PROFILE_VIDEO_UPDATE')) define('NOTIFICATION_PROFILE_VIDEO_UPDATE', 'PROFILE_VIDEO_UPDATE');
if (!defined('NOTIFICATION_PROFILE_VIDEO_UPDATE_COMMENT')) define('NOTIFICATION_PROFILE_VIDEO_UPDATE_COMMENT', 'your profile video has been saved.');

if (!defined('NOTIFICATION_COMPANY_PROFILE_UPDATE')) define('NOTIFICATION_COMPANY_PROFILE_UPDATE', 'COMPANY_PROFILE_UPDATE');
if (!defined('NOTIFICATION_COMPANY_PROFILE_UPDATE_COMMENT')) define('NOTIFICATION_COMPANY_PROFILE_UPDATE_COMMENT', 'your company profile has been updated.');

if (!defined('NOTIFICATION_COMPANY_PROFILE_IMAGE_UPDATE')) define('NOTIFICATION_COMPANY_PROFILE_IMAGE_UPDATE', 'COMPANY_PROFILE_IMAGE_UPDATE');
if (!defined('NOTIFICATION_COMPANY_PROFILE_IMAGE_UPDATE_COMMENT')) define('NOTIFICATION_COMPANY_PROFILE_IMAGE_UPDATE_COMMENT', 'your company profile image has been updated.');

if (!defined('NOTIFICATION_MEMBERSHIP_SUCCESS')) define('NOTIFICATION_MEMBERSHIP_SUCCESS', 'MEMBERHSIP_SUCCESS');
if (!defined('NOTIFICATION_MEMBERSHIP_SUCCESS_COMMENT')) define('NOTIFICATION_MEMBERSHIP_SUCCESS_COMMENT', 'your requested membership has been activated.');

if (!defined('NOTIFICATION_MEMBERSHIP_CANCELLED')) define('NOTIFICATION_MEMBERSHIP_CANCELLED', 'MEMBERSHIP_CANCELLED');
if (!defined('NOTIFICATION_MEMBERSHIP_CANCELLED_COMMENT')) define('NOTIFICATION_MEMBERSHIP_CANCELLED_COMMENT', 'your membership has been cancelled.');

if (!defined('NOTIFICATION_TESTIMONIAL_ADDED')) define('NOTIFICATION_TESTIMONIAL_ADDED', 'TESTIMONIAL_ADDED');
if (!defined('NOTIFICATION_TESTIMONIAL_ADDED_COMMENT')) define('NOTIFICATION_TESTIMONIAL_ADDED_COMMENT', 'testimonial has been added successfully.');

if (!defined('NOTIFICATION_TESTIMONIAL_REMOVED')) define('NOTIFICATION_TESTIMONIAL_REMOVED', 'TESTIMONIAL_REMOVED');
if (!defined('NOTIFICATION_TESTIMONIAL_REMOVED_COMMENT')) define('NOTIFICATION_TESTIMONIAL_REMOVED_COMMENT', 'testimonial has been removed successfully.');

if (!defined('NOTIFICATION_EMAIL')) define('NOTIFICATION_EMAIL', 'EMAIL');
if (!defined('NOTIFICATION_EMAIL_COMMENT')) define('NOTIFICATION_EMAIL_COMMENT', 'has sent you an email.');

if (!defined('NOTIFICATION_MESSAGE')) define('NOTIFICATION_MESSAGE', 'MESSAGE');
if (!defined('NOTIFICATION_MESSAGE_COMMENT')) define('NOTIFICATION_MESSAGE_COMMENT', 'has sent you a message.');

if (!defined('NOTIFICATION_WEBINAR')) define('NOTIFICATION_WEBINAR', 'WEBINAR');
if (!defined('NOTIFICATION_WEBINAR_COMMENT')) define('NOTIFICATION_WEBINAR_COMMENT', 'has scheduled a new webinar.');

if (!defined('NOTIFICATION_WEBINAR_SCHEDULED')) define('NOTIFICATION_WEBINAR_SCHEDULED', 'WEBINAR_SCHEDULED');
if (!defined('NOTIFICATION_WEBINAR_SCHEDULED_COMMENT')) define('NOTIFICATION_WEBINAR_SCHEDULED_COMMENT', 'has successfully scheduled a new webinar.');

if (!defined('NOTIFICATION_WEBINAR_UPDATED')) define('NOTIFICATION_WEBINAR_UPDATED', 'WEBINAR_UPDATED');
if (!defined('NOTIFICATION_WEBINAR_UPDATED_COMMENT')) define('NOTIFICATION_WEBINAR_UPDATED_COMMENT', 'the webinar has been updated.');

if (!defined('NOTIFICATION_FOLLOW')) define('NOTIFICATION_FOLLOW', 'FOLLOW');
if (!defined('NOTIFICATION_FOLLOW_COMMENT')) define('NOTIFICATION_FOLLOW_COMMENT', 'has started following you.');

if (!defined('NOTIFICATION_FOLLOW_PRODUCT')) define('NOTIFICATION_FOLLOW_PRODUCT', 'FOLLOW_PRODUCT');
if (!defined('NOTIFICATION_FOLLOW_PRODUCT_COMMENT')) define('NOTIFICATION_FOLLOW_PRODUCT_COMMENT', 'has started following your product.');

if (!defined('NOTIFICATION_FOLLOW_PRODUCT_UPDATE')) define('NOTIFICATION_FOLLOW_PRODUCT_UPDATE', 'FOLLOW_PRODUCT_UPDATE');
if (!defined('NOTIFICATION_FOLLOW_PRODUCT_UPDATE_COMMENT')) define('NOTIFICATION_FOLLOW_PRODUCT_UPDATE_COMMENT', 'has updated the product you\'re following.');

if (!defined('NOTIFICATION_FOLLOW_SERVICE')) define('NOTIFICATION_FOLLOW_SERVICE', 'FOLLOW_SERVICE');
if (!defined('NOTIFICATION_FOLLOW_SERVICE_COMMENT')) define('NOTIFICATION_FOLLOW_SERVICE_COMMENT', 'has started following your service.');

if (!defined('NOTIFICATION_FOLLOW_TECHNOLOGY')) define('NOTIFICATION_FOLLOW_TECHNOLOGY', 'FOLLOW_TECHNOLOGY');
if (!defined('NOTIFICATION_FOLLOW_TECHNOLOGY_COMMENT')) define('NOTIFICATION_FOLLOW_TECHNOLOGY_COMMENT', 'has started following your technology.');

if (!defined('NOTIFICATION_JOB_POST')) define('NOTIFICATION_JOB_POST', 'JOB_POST');
if (!defined('NOTIFICATION_JOB_POST_COMMENT')) define('NOTIFICATION_JOB_POST_COMMENT', 'have a new job post available.');

if (!defined('NOTIFICATION_JOB_POST_INSERT')) define('NOTIFICATION_JOB_POST_INSERT', 'JOB_POST_INSERT');
if (!defined('NOTIFICATION_JOB_POST_INSERT_COMMENT')) define('NOTIFICATION_JOB_POST_INSERT_COMMENT', 'requested job has been posted.');

if (!defined('NOTIFICATION_JOB_POST_UPDATE')) define('NOTIFICATION_JOB_POST_UPDATE', 'JOB_POST_UPDATE');
if (!defined('NOTIFICATION_JOB_POST_UPDATE_COMMENT')) define('NOTIFICATION_JOB_POST_UPDATE_COMMENT', 'requested job has been updated.');

if (!defined('NOTIFICATION_JOB_POST_DELETE')) define('NOTIFICATION_JOB_POST_DELETE', 'JOB_POST_DELETE');
if (!defined('NOTIFICATION_JOB_POST_DELETE_COMMENT')) define('NOTIFICATION_JOB_POST_DELETE_COMMENT', 'requested job has been deleted.');

if (!defined('NOTIFICATION_JOB_POSTED')) define('NOTIFICATION_JOB_POSTED', 'JOB_POSTED');
if (!defined('NOTIFICATION_JOB_POSTED_COMMENT')) define('NOTIFICATION_JOB_POSTED_COMMENT', 'posted a new job');

if (!defined('NOTIFICATION_JOB_BID')) define('NOTIFICATION_JOB_BID', 'JOB_BID');
if (!defined('NOTIFICATION_JOB_BID_COMMENT')) define('NOTIFICATION_JOB_BID_COMMENT', 'JOB_BID');

if (!defined('NOTIFICATION_BLOG_REVIEW')) define('NOTIFICATION_BLOG_REVIEW', 'BLOG_REVIEW');
if (!defined('NOTIFICATION_BLOG_REVIEW_COMMENT')) define('NOTIFICATION_BLOG_REVIEW_COMMENT', 'BLOG_REVIEW');

if (!defined('NOTIFICATION_BLOG_REACTION')) define('NOTIFICATION_BLOG_REACTION', 'BLOG_REACTION');
if (!defined('NOTIFICATION_BLOG_REACTION_COMMENT')) define('NOTIFICATION_BLOG_REACTION_COMMENT', 'BLOG_REACTION');

if (!defined('NOTIFICATION_PASSWORD_RESET')) define('NOTIFICATION_PASSWORD_RESET', 'PASSWORD_RESET');
if (!defined('NOTIFICATION_PASSWORD_RESET_COMMENT')) define('NOTIFICATION_PASSWORD_RESET_COMMENT', 'PASSWORD_RESET');

if (!defined('NOTIFICATION_MEETING')) define('NOTIFICATION_MEETING', 'MEETING');
if (!defined('NOTIFICATION_MEETING_COMMENT')) define('NOTIFICATION_MEETING_COMMENT', 'has sent a meeting request.');

if (!defined('NOTIFICATION_JOB_APPLICATION')) define('NOTIFICATION_JOB_APPLICATION', 'JOB_APPLICATION');
if (!defined('NOTIFICATION_JOB_APPLICATION_COMMENT')) define('NOTIFICATION_JOB_APPLICATION_COMMENT', 'has sent a job application.');

if (!defined('NOTIFICATION_MILESTONE')) define('NOTIFICATION_MILESTONE', 'MILESTONE');
if (!defined('NOTIFICATION_MILESTONE_COMMENT')) define('NOTIFICATION_MILESTONE_COMMENT', 'has added a new milestone.');

if (!defined('NOTIFICATION_MILESTONE_UPDATE')) define('NOTIFICATION_MILESTONE_UPDATE', 'MILESTONE_UPDATE');
if (!defined('NOTIFICATION_MILESTONE_UPDATE_COMMENT')) define('NOTIFICATION_MILESTONE_UPDATE_COMMENT', 'has updated a milestone.');

if (!defined('NOTIFICATION_MILESTONE_APPROVED')) define('NOTIFICATION_MILESTONE_APPROVED', 'MILESTONE_APPROVED');
if (!defined('NOTIFICATION_MILESTONE_APPROVED_COMMENT')) define('NOTIFICATION_MILESTONE_APPROVED_COMMENT', 'has approved a milestone.');

if (!defined('NOTIFICATION_MILESTONE_DECLINED')) define('NOTIFICATION_MILESTONE_DECLINED', 'MILESTONE_DECLINED');
if (!defined('NOTIFICATION_MILESTONE_DECLINED_COMMENT')) define('NOTIFICATION_MILESTONE_DECLINED_COMMENT', 'has declined a milestone.');

if (!defined('NOTIFICATION_MILESTONE_STARTED')) define('NOTIFICATION_MILESTONE_STARTED', 'MILESTONE_STARTED');
if (!defined('NOTIFICATION_MILESTONE_STARTED_COMMENT')) define('NOTIFICATION_MILESTONE_STARTED_COMMENT', 'has started a milestone.');

if (!defined('NOTIFICATION_MILESTONE_SUBMITTED')) define('NOTIFICATION_MILESTONE_SUBMITTED', 'MILESTONE_SUBMITTED');
if (!defined('NOTIFICATION_MILESTONE_SUBMITTED_COMMENT')) define('NOTIFICATION_MILESTONE_SUBMITTED_COMMENT', 'has submitted a milestone.');

if (!defined('NOTIFICATION_MILESTONE_ACTION')) define('NOTIFICATION_MILESTONE_ACTION', 'MILESTONE_ACTION');
if (!defined('NOTIFICATION_MILESTONE_ACTION_COMMENT')) define('NOTIFICATION_MILESTONE_ACTION_COMMENT', 'has updated milestone status.');

if (!defined('NOTIFICATION_MILESTONE_DELETED')) define('NOTIFICATION_MILESTONE_DELETED', 'MILESTONE_DELETED');
if (!defined('NOTIFICATION_MILESTONE_DELETED_COMMENT')) define('NOTIFICATION_MILESTONE_DELETED_COMMENT', 'has deleted a milestone.');

if (!defined('NOTIFICATION_JOB_APPLICATION_APPROVED')) define('NOTIFICATION_JOB_APPLICATION_APPROVED', 'JOB_APPLICATION_APPROVED');
if (!defined('NOTIFICATION_JOB_APPLICATION_APPROVED_COMMENT')) define('NOTIFICATION_JOB_APPLICATION_APPROVED_COMMENT', 'has approved job application request.');

if (!defined('NOTIFICATION_JOB_APPLICATION_DECLINED')) define('NOTIFICATION_JOB_APPLICATION_DECLINED', 'JOB_APPLICATION_DECLINED');
if (!defined('NOTIFICATION_JOB_APPLICATION_DECLINED_COMMENT')) define('NOTIFICATION_JOB_APPLICATION_DECLINED_COMMENT', 'has approved job application request.');

if (!defined('NOTIFICATION_JOB_APPLICATION_PENDING')) define('NOTIFICATION_JOB_APPLICATION_PENDING', 'JOB_APPLICATION_PENDING');
if (!defined('NOTIFICATION_JOB_APPLICATION_PENDING_COMMENT')) define('NOTIFICATION_JOB_APPLICATION_PENDING_COMMENT', 'has updated job application request to pending.');

if (!defined('NOTIFICATION_PRODUCT_REQUEST')) define('NOTIFICATION_PRODUCT_REQUEST', 'PRODUCT_REQUEST');
if (!defined('NOTIFICATION_PRODUCT_REQUEST_COMMENT')) define('NOTIFICATION_PRODUCT_REQUEST_COMMENT', 'has requested for the {item}.');

if (!defined('NOTIFICATION_PRODUCT_RESPONSE')) define('NOTIFICATION_PRODUCT_RESPONSE', 'PRODUCT_RESPONSE');
if (!defined('NOTIFICATION_PRODUCT_RESPONSE_COMMENT')) define('NOTIFICATION_PRODUCT_RESPONSE_COMMENT', 'has updated your request status for the {item}.');

if (!defined('NOTIFICATION_MEETING_REQUEST')) define('NOTIFICATION_MEETING_REQUEST', 'MEETING_REQUEST');
if (!defined('NOTIFICATION_MEETING_REQUEST_COMMENT')) define('NOTIFICATION_MEETING_REQUEST_COMMENT', 'has requested for the {item}.');

if (!defined('NOTIFICATION_MEETING_RESPONSE')) define('NOTIFICATION_MEETING_RESPONSE', 'MEETING_RESPONSE');
if (!defined('NOTIFICATION_MEETING_RESPONSE_COMMENT')) define('NOTIFICATION_MEETING_RESPONSE_COMMENT', 'has updated your request status for the {item}.');

if (!defined('NOTIFICATION_PHONE_VERIFIED')) define('NOTIFICATION_PHONE_VERIFIED', 'PHONE_VERIFIED');
if (!defined('NOTIFICATION_PHONE_VERIFIED_COMMENT')) define('NOTIFICATION_PHONE_VERIFIED_COMMENT', 'your phone number has been verified.');

if (!defined('NOTIFICATION_TESTIMONIAL_REQUEST_ACCEPTED')) define('NOTIFICATION_TESTIMONIAL_REQUEST_ACCEPTED', 'TESTIMONIAL_REQUEST_ACCEPTED');
if (!defined('NOTIFICATION_TESTIMONIAL_REQUEST_ACCEPTED_COMMENT')) define('NOTIFICATION_TESTIMONIAL_REQUEST_ACCEPTED_COMMENT', 'your testimonial request has been accepted.');

if (!defined('NOTIFICATION_TESTIMONIAL_REQUEST_REJECTED')) define('NOTIFICATION_TESTIMONIAL_REQUEST_REJECTED', 'TESTIMONIAL_REQUEST_REJECTED');
if (!defined('NOTIFICATION_TESTIMONIAL_REQUEST_REJECTED_COMMENT')) define('NOTIFICATION_TESTIMONIAL_REQUEST_REJECTED_COMMENT', 'your testimonial request has been rejected.');

if (!defined('NOTIFICATION_TESTIMONIAL_REQUEST_EXTENDED')) define('NOTIFICATION_TESTIMONIAL_REQUEST_EXTENDED', 'TESTIMONIAL_REQUEST_EXTENDED');
if (!defined('NOTIFICATION_TESTIMONIAL_REQUEST_EXTENDED_COMMENT')) define('NOTIFICATION_TESTIMONIAL_REQUEST_EXTENDED_COMMENT', 'your testimonial request has been extended.');

if (!defined('NOTIFICATION_STRIPE_CONNECTED')) define('NOTIFICATION_STRIPE_CONNECTED', 'STRIPE_CONNECTED');
if (!defined('NOTIFICATION_STRIPE_CONNECTED_COMMENT')) define('NOTIFICATION_STRIPE_CONNECTED_COMMENT', 'stripe has been connected successfully.');

if (!defined('NOTIFICATION_PRODUCT_ADDED')) define('NOTIFICATION_PRODUCT_ADDED', 'PRODUCT_ADDED');
if (!defined('NOTIFICATION_PRODUCT_ADDED_COMMENT')) define('NOTIFICATION_PRODUCT_ADDED_COMMENT', '{item} has been added successfully.');

if (!defined('NOTIFICATION_PRODUCT_UPDATED')) define('NOTIFICATION_PRODUCT_UPDATED', 'PRODUCT_UPDATED');
if (!defined('NOTIFICATION_PRODUCT_UPDATED_COMMENT')) define('NOTIFICATION_PRODUCT_UPDATED_COMMENT', '{item} has been updated successfully.');

if (!defined('NOTIFICATION_ORDER_SAVED')) define('NOTIFICATION_ORDER_SAVED', 'ORDER_SAVED');
if (!defined('NOTIFICATION_ORDER_SAVED_COMMENT')) define('NOTIFICATION_ORDER_SAVED_COMMENT', 'order has been saved successfully.');

if (!defined('NOTIFICATION_PAYMENT_COMPLETED')) define('NOTIFICATION_PAYMENT_COMPLETED', 'PAYMENT_COMPLETED');
if (!defined('NOTIFICATION_PAYMENT_COMPLETED_COMMENT')) define('NOTIFICATION_PAYMENT_COMPLETED_COMMENT', 'order payment has been completed successfully.');

if (!defined('NOTIFICATION_EMPLOYMENT_CONFIRMED')) define('NOTIFICATION_EMPLOYMENT_CONFIRMED', 'EMPLOYMENT_CONFIRMED');
if (!defined('NOTIFICATION_EMPLOYMENT_CONFIRMED_COMMENT')) define('NOTIFICATION_EMPLOYMENT_CONFIRMED_COMMENT', 'your employment has been confirmed.');

if (!defined('NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED')) define('NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED', 'EMPLOYMENT_CONFIRMED_FAILED');
if (!defined('NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED_COMMENT')) define('NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED_COMMENT', 'your employment confirmation has failed.');

if (!defined('NOTIFICATION_ENDORSEMENT_RECEIVED')) define('NOTIFICATION_ENDORSEMENT_RECEIVED', 'ENDORSEMENT_RECEIVED');
if (!defined('NOTIFICATION_ENDORSEMENT_RECEIVED_COMMENT')) define('NOTIFICATION_ENDORSEMENT_RECEIVED_COMMENT', 'sent an endorsement.');

if (!defined('NOTIFICATION_TESTIMONIAL_RECEIVED')) define('NOTIFICATION_TESTIMONIAL_RECEIVED', 'TESTIMONIAL_RECEIVED');
if (!defined('NOTIFICATION_TESTIMONIAL_RECEIVED_COMMENT')) define('NOTIFICATION_TESTIMONIAL_RECEIVED_COMMENT', 'added a testimonial to your profile.');

if (!defined('NOTIFICATION_NEW_PROMOTION')) define('NOTIFICATION_NEW_PROMOTION', 'NEW_PROMOTION');
if (!defined('NOTIFICATION_NEW_PROMOTION_COMMENT')) define('NOTIFICATION_NEW_PROMOTION_COMMENT', 'a new promotion offer is available.');

if (!defined('NOTIFICATION_COACHING_REQUEST_SENT')) define('NOTIFICATION_COACHING_REQUEST_SENT', 'COACHING_REQUEST_SENT');
if (!defined('NOTIFICATION_COACHING_REQUEST_SENT_COMMENT')) define('NOTIFICATION_COACHING_REQUEST_SENT_COMMENT', 'your coaching participation request has been sent.');

if (!defined('NOTIFICATION_COACHING_REQUEST_COMPLETED')) define('NOTIFICATION_COACHING_REQUEST_COMPLETED', 'COACHING_REQUEST_COMPLETED');
if (!defined('NOTIFICATION_COACHING_REQUEST_COMPLETED_COMMENT')) define('NOTIFICATION_COACHING_REQUEST_COMPLETED_COMMENT', 'your coaching participation request has been completed.');

// NOTIFICATION END

//
if (!defined('SESSION_EXPIRY')) define('SESSION_EXPIRY', (ENVIRONMENT == "development") ? 14400 : 7200);
if (!defined('REDIRECT_AFTER_LOGOUT')) define('REDIRECT_AFTER_LOGOUT', '');

//
if (!defined('SUCCESS_MESSAGE')) define('SUCCESS_MESSAGE', 'Your request has been processed successfully.');
if (!defined('SUCCESS_MESSAGE_BOX_AUTHORIZATION')) define('SUCCESS_MESSAGE_BOX_AUTHORIZATION', 'Box authorization successfull.');
if (!defined('SUCCESS_MESSAGE_BOX_AUTHORIZATION_ACTIVE')) define('SUCCESS_MESSAGE_BOX_AUTHORIZATION_ACTIVE', 'Box authorization active.');
if (!defined('EMAIL_CONFIRMATION_TEXT')) define('EMAIL_CONFIRMATION_TEXT', 'Account email confirmation required. A confirmation email has already been sent to your email address');
//
if (!defined('ERROR_MESSAGE')) define('ERROR_MESSAGE', 'An error occurred while trying to process your request.');
if (!defined('ERROR_MESSAGE_AUTHENTICATION')) define('ERROR_MESSAGE_AUTHENTICATION', 'Authentication failed.');
if (!defined('ERROR_MESSAGE_BOX_AUTHORIZATION')) define('ERROR_MESSAGE_BOX_AUTHORIZATION', 'Box authorization failed.');
if (!defined('ERROR_MESSAGE_BOX_AUTHORIZATION_REQUIRED')) define('ERROR_MESSAGE_BOX_AUTHORIZATION_REQUIRED', 'Box authorization required.');
if (!defined('ERROR_MESSAGE_CART_EMPTY')) define('ERROR_MESSAGE_CART_EMPTY', 'Your cart is currently empty.');
if (!defined('ERROR_MESSAGE_CART_INSERT')) define('ERROR_MESSAGE_CART_INSERT', 'An error occurred while trying to add the product to the cart.');
if (!defined('ERROR_MESSAGE_CART_INSERT_TYPE')) define('ERROR_MESSAGE_CART_INSERT_TYPE', 'An error occurred while trying to add the product to the cart. Try checking out already added product.');
if (!defined('ERROR_MESSAGE_PRODUCT_EXISTS')) define('ERROR_MESSAGE_PRODUCT_EXISTS', 'An error occurred while trying to add the product to the cart. Reason: The product already exists in the cart.');
if (!defined('ERROR_MESSAGE_CSRF')) define('ERROR_MESSAGE_CSRF', 'Cross-Site Request Forgery token mismatch.');
if (!defined('ERROR_MESSAGE_FORBIDDEN')) define('ERROR_MESSAGE_FORBIDDEN', 'Authorization failure.');
if (!defined('ERROR_MESSAGE_FILE_UPLOAD')) define('ERROR_MESSAGE_FILE_UPLOAD', 'An error occurred while trying to upload the requested file.');
if (!defined('ERROR_MESSAGE_FILE_EXCEED_LIMIT')) define('ERROR_MESSAGE_FILE_EXCEED_LIMIT', 'The requested file exceeds the upload size limit.');
if (!defined('ERROR_MESSAGE_INSERT')) define('ERROR_MESSAGE_INSERT', 'An error occurred while trying to insert the record.');
if (!defined('ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE')) define('ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE', 'An error occurred while processing your request. Reason: Insufficient privileges.');
if (!defined('ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL')) define('ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL', 'An error occurred while processing your request. Reason: Insufficient privileges.');
if (!defined('ERROR_MESSAGE_INVALID_PAYLOAD')) define('ERROR_MESSAGE_INVALID_PAYLOAD', 'The payload is invalid.');
if (!defined('ERROR_MESSAGE_INVALID_STRIPE_TOKEN')) define('ERROR_MESSAGE_INVALID_STRIPE_TOKEN', 'The stripe token is invalid.');
if (!defined('ERROR_MESSAGE_LINK_EXPIRED')) define('ERROR_MESSAGE_LINK_EXPIRED', 'The link has expired, try refreshing the page.');
if (!defined('ERROR_MESSAGE_LOGIN')) define('ERROR_MESSAGE_LOGIN', 'You need to log in to perform this action.');
if (!defined('ERROR_MESSAGE_REFRESH_REQUIRED')) define('ERROR_MESSAGE_REFRESH_REQUIRED', 'An error occurred while processing your request, try refreshing the page.');
if (!defined('ERROR_MESSAGE_RESOURCE_NOT_FOUND')) define('ERROR_MESSAGE_RESOURCE_NOT_FOUND', 'The requested resource doesn\'t exists.');
if (!defined('ERROR_MESSAGE_STRIPE_ERROR')) define('ERROR_MESSAGE_STRIPE_ERROR', 'A stripe error occured while to perform this action.');
if (!defined('ERROR_MESSAGE_STRIPE_CONNECT_ERROR')) define('ERROR_MESSAGE_STRIPE_CONNECT_ERROR', 'A stripe connection is required to perform this action.');
if (!defined('ERROR_MESSAGE_STRIPE_CONNECT_OWNER_ERROR')) define('ERROR_MESSAGE_STRIPE_CONNECT_OWNER_ERROR', 'The requested resource owner doesn\'t have stripe connection.');
if (!defined('ERROR_MESSAGE_PAYPAL_CONNECT_OWNER_ERROR')) define('ERROR_MESSAGE_PAYPAL_CONNECT_OWNER_ERROR', 'The requested resource owner doesn\'t have paypal connection.');
if (!defined('ERROR_MESSAGE_UPDATE')) define('ERROR_MESSAGE_UPDATE', 'An error occurred while trying to update th record.');
if (!defined('ERROR_MESSAGE_UPTODATE')) define('ERROR_MESSAGE_UPTODATE', 'The record is already up to date.');
if (!defined('ERROR_MESSAGE_VERIFICATION')) define('ERROR_MESSAGE_VERIFICATION', 'Identity verification is required to perform this action.');
if (!defined('ERROR_MESSAGE_ZOOM_UNAVAILABLE')) define('ERROR_MESSAGE_ZOOM_UNAVAILABLE', 'Zoom functionality is currently unavailable.');
if (!defined('ERROR_MESSAGE_BOX_UNAVAILABLE')) define('ERROR_MESSAGE_BOX_UNAVAILABLE', 'Box funcationality is currently unavailable.');
if (!defined('ERROR_MESSAGE_BOX_UNAUTHORIZED')) define('ERROR_MESSAGE_BOX_UNAUTHORIZED', 'Box authorization failed, please re-authorize box.');
if (!defined('ERROR_MESSAGE_SUBSCRIPTION')) define('ERROR_MESSAGE_SUBSCRIPTION', 'Upgrade your subscription to access this feature');
if (!defined('ERROR_MESSAGE_PRODUCT_DIFFERNT_OWNER')) define('ERROR_MESSAGE_PRODUCT_DIFFERNT_OWNER', 'Cannot add different owner\'s product to the cart. Checkout first and try adding then.');
if (!defined('ERROR_MESSAGE_CAPTCHA_FAILED')) define('ERROR_MESSAGE_CAPTCHA_FAILED', 'Recaptcha verification failed.');

//
if (!defined('PER_PAGE')) define('PER_PAGE', 10);

//
if (!defined('REACTION_LIKE')) define('REACTION_LIKE', 'like');
if (!defined('REACTION_LOVE')) define('REACTION_LOVE', 'love');
if (!defined('REACTION_HAHA')) define('REACTION_HAHA', 'haha');
if (!defined('REACTION_WOW')) define('REACTION_WOW', 'wow');
if (!defined('REACTION_SAD')) define('REACTION_SAD', 'sad');
if (!defined('REACTION_ANGRY')) define('REACTION_ANGRY', 'angry');

//
if (!defined('EMOJI_LIKE')) define('EMOJI_LIKE', '👍');
if (!defined('EMOJI_LOVE')) define('EMOJI_LOVE', '❤️');
if (!defined('EMOJI_HAHA')) define('EMOJI_HAHA', '😂');
if (!defined('EMOJI_WOW')) define('EMOJI_WOW', '😲');
if (!defined('EMOJI_SAD')) define('EMOJI_SAD', '😢');
if (!defined('EMOJI_ANGRY')) define('EMOJI_ANGRY', '😡');

// Comment reference
if (!defined('REFERENCE_TYPE_JOB')) define('REFERENCE_TYPE_JOB', 'job');
if (!defined('REFERENCE_TYPE_BLOG')) define('REFERENCE_TYPE_BLOG', 'blog');
if (!defined('REFERENCE_TYPE_STORY')) define('REFERENCE_TYPE_STORY', 'story');
if (!defined('REFERENCE_TYPE_PRODUCT')) define('REFERENCE_TYPE_PRODUCT', 'product');
if (!defined('REFERENCE_TYPE_ANNOUNCEMENT')) define('REFERENCE_TYPE_ANNOUNCEMENT', 'announcement');
if (!defined('REFERENCE_TYPE_NEWS')) define('REFERENCE_TYPE_NEWS', 'news');
if (!defined('REFERENCE_TYPE_ENDORSEMENT')) define('REFERENCE_TYPE_ENDORSEMENT', 'endorsement');
if (!defined('REFERENCE_TYPE_TESTIMONIAL')) define('REFERENCE_TYPE_TESTIMONIAL', 'testimonial');
// Comment reference

//
if (!defined('ANALYTICS_TYPE_VIEW')) define('ANALYTICS_TYPE_VIEW', 'view');

//
if (!defined('APPLICATION_PENDING')) define('APPLICATION_PENDING', 'pending');
if (!defined('APPLICATION_ASSIGNED')) define('APPLICATION_ASSIGNED', 'assigned');
if (!defined('APPLICATION_REJECTED')) define('APPLICATION_REJECTED', 'rejected');

//
if (!defined('NOT_AVAILABLE')) define('NOT_AVAILABLE', 'Not available.');
if (!defined('NA')) define('NA', 'Not available.');

//
if (!defined('SLOT_AVAILABLE')) define('SLOT_AVAILABLE', 'SLOT_AVAILABLE');
if (!defined('SLOT_AVAILABLE_COLOR')) define('SLOT_AVAILABLE_COLOR', '#2c3e50');

if (!defined('SLOT_LOCKED')) define('SLOT_LOCKED', 'SLOT_LOCKED');
if (!defined('SLOT_LOCKED_COLOR')) define('SLOT_LOCKED_COLOR', '#014e96');

//
if (!defined('FOLLOWER')) define('FOLLOWER', 'Follower');
if (!defined('FOLLOWEE')) define('FOLLOWEE', 'Following');
//
if (!defined('TYPE_FOLLOWER')) define('TYPE_FOLLOWER', 1);
if (!defined('TYPE_FOLLOWEE')) define('TYPE_FOLLOWEE', 2);
//
if (!defined('FOLLOW_REFERENCE_SIGNUP')) define('FOLLOW_REFERENCE_SIGNUP', 'signup');
if (!defined('FOLLOW_REFERENCE_PRODUCT')) define('FOLLOW_REFERENCE_PRODUCT', 'product');
if (!defined('FOLLOW_REFERENCE_TECHNOLOGY')) define('FOLLOW_REFERENCE_TECHNOLOGY', 'technology');
if (!defined('FOLLOW_REFERENCE_SERVICE')) define('FOLLOW_REFERENCE_SERVICE', 'service');

// ZOOM START

// JWT
if (!defined('ZOOM_API_KEY')) define('ZOOM_API_KEY', 'JZ0Le6vmTx-JF9L6ZF7r7A');
if (!defined('ZOOM_API_SECRET')) define('ZOOM_API_SECRET', 'jYnM4YolCmKeHRc0DQdaWXk28XtnNXPZAHdg');

if (!defined('ZOOM_IM_CHAT_HISTORY_TOKEN')) define('ZOOM_IM_CHAT_HISTORY_TOKEN', 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1XzRMVkpsYVN1eWVKOWxZc25yNDF3In0.R9356tvDq2UDKMgHNM7h1SwPjuKKmNmDApM4CeOgf6c');
if (!defined('ZOOM_JWT_TOKEN')) define('ZOOM_JWT_TOKEN', 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IkpaMExlNnZtVHgtSkY5TDZaRjdyN0EiLCJleHAiOjE2NzIzMDA5NzgsImlhdCI6MTY3MjIxNDU3OH0.q_JBqTOX8TJPwyOxrnt-NeUyMyQuEoc8FfpQf1K-oTQ');

if (!defined('ZOOM_JWT_SECRET_TOKEN')) define('ZOOM_JWT_SECRET_TOKEN', 'streLfzVTu-xe-x-whc64A');
if (!defined('ZOOM_JWT_VERIFICATION_TOKEN')) define('ZOOM_JWT_VERIFICATION_TOKEN', 'qbMm9f3wSKCIL1KCmMWPfQ');

// OAUTH
if (!defined('ZOOM_CLIENT_ID')) define('ZOOM_CLIENT_ID', '1zn6E5gSRiWdZ4Kt0l5q6Q');
if (!defined('ZOOM_CLIENT_SECRET')) define('ZOOM_CLIENT_SECRET', '1Ya319FMPlUtkxeA4wB3CtaoQYXieQsh');

// This secret token is used to verify event notifications sent by Zoom.
if (!defined('ZOOM_OAUTH_SECRET_TOKEN')) define('ZOOM_OAUTH_SECRET_TOKEN', 'ZPKtvTvtTo-9dndSTabvsg');
if (!defined('ZOOM_OAUTH_VERIFICATION_TOKEN')) define('ZOOM_OAUTH_VERIFICATION_TOKEN', 'GdZqNI-CSuKbWQPkS1uh1Q');

// URL
if (!defined('ZOOM_API_URL')) define('ZOOM_API_URL', 'https://api.zoom.us/v2');
if (!defined('ZOOM_OAUTH_REDIRECT_URL')) define('ZOOM_OAUTH_REDIRECT_URL', $config['base_url'] . 'oauth2/redirect');
if (!defined('ZOOM_OAUTH_AUTHORIZE_URL')) define('ZOOM_OAUTH_AUTHORIZE_URL', 'https://zoom.us/oauth/authorize?response_type=code&client_id=' . ZOOM_CLIENT_ID . '&redirect_uri=' . ZOOM_OAUTH_REDIRECT_URL);
if (!defined('ZOOM_OAUTH_TOKEN_URL')) define('ZOOM_OAUTH_TOKEN_URL', 'https://zoom.us/oauth/token');
if (!defined('ZOOM_DE_OAUTH_URL')) define('ZOOM_DE_OAUTH_URL', $config['base_url'] . 'oauth2/de-auth');

//
if (!defined('ZOOM_CREATE_MEETING_URL')) define('ZOOM_CREATE_MEETING_URL', ZOOM_API_URL . '/users/{userId}/meetings');
if (!defined('ZOOM_MEETING_URL')) define('ZOOM_MEETING_URL', ZOOM_API_URL . '/meetings/{meetingId}');
if (!defined('ZOOM_GET_MEETING_RECORDING')) define('ZOOM_GET_MEETING_RECORDING', ZOOM_API_URL . '/meetings/{meetingId}/recordings');
//
if (!defined('ZOOM_GET_USERS_URL')) define('ZOOM_GET_USERS_URL', ZOOM_API_URL . '/users');
if (!defined('ZOOM_CHECK_USER_URL')) define('ZOOM_CHECK_USER_URL', ZOOM_API_URL . '/users/email');
//
if (!defined('ZOOM_CREATE_WEBINAR_URL')) define('ZOOM_CREATE_WEBINAR_URL', ZOOM_API_URL . '/users/{userId}/webinars');
if (!defined('ZOOM_WEBINAR_URL')) define('ZOOM_WEBINAR_URL', ZOOM_API_URL . '/webinars/{webinarId}');

// fb_config table config_id for zoom: access_token, refresh_token, token_expire_time
if (!defined('ZOOM_CONFIG_ACCESS_TOKEN')) define('ZOOM_CONFIG_ACCESS_TOKEN', '790');
if (!defined('ZOOM_CONFIG_REFRESH_TOKEN')) define('ZOOM_CONFIG_REFRESH_TOKEN', '791');
if (!defined('ZOOM_CONFIG_TOKEN_EXPIRY')) define('ZOOM_CONFIG_TOKEN_EXPIRY', '792');

//
if (!defined('MEETING_REFERENCE_APPLICATION')) define('MEETING_REFERENCE_APPLICATION', 'application');
if (!defined('MEETING_REFERENCE_PRODUCT')) define('MEETING_REFERENCE_PRODUCT', 'product');

//
if (!defined('MEETING_REQUEST_REFERENCE_APPLICATION')) define('MEETING_REQUEST_REFERENCE_APPLICATION', 'application');
if (!defined('MEETING_REQUEST_REFERENCE_PRODUCT')) define('MEETING_REQUEST_REFERENCE_PRODUCT', 'product');

//
if (!defined('ZOOM_MEETING_PENDING')) define('ZOOM_MEETING_PENDING', '0');
if (!defined('ZOOM_MEETING_STARTED')) define('ZOOM_MEETING_STARTED', '1');
if (!defined('ZOOM_MEETING_ENDED')) define('ZOOM_MEETING_ENDED', '2');

//
if (!defined('ZOOM_WEBINAR_PENDING')) define('ZOOM_WEBINAR_PENDING', '0');
if (!defined('ZOOM_WEBINAR_STARTED')) define('ZOOM_WEBINAR_STARTED', '1');
if (!defined('ZOOM_WEBINAR_ENDED')) define('ZOOM_WEBINAR_ENDED', '2');

//
if (!defined('COACHING_PENDING')) define('COACHING_PENDING', '0');
if (!defined('COACHING_STARTED')) define('COACHING_STARTED', '1');
if (!defined('COACHING_ENDED')) define('COACHING_ENDED', '2');

// FOR GET, DELETE, PUT, PATCH
if (!defined('REQUEST_POST')) define('REQUEST_POST', 'POST');
if (!defined('REQUEST_GET')) define('REQUEST_GET', 'GET');
if (!defined('REQUEST_PUT')) define('REQUEST_PUT', 'PUT');
if (!defined('REQUEST_PATCH')) define('REQUEST_PATCH', 'PATCH');
if (!defined('REQUEST_DELETE')) define('REQUEST_DELETE', 'DELETE');

//
if (!defined('ZOOM_TYPE_START_WEBINAR')) define('ZOOM_TYPE_START_WEBINAR', 1);
if (!defined('ZOOM_TYPE_JOIN_WEBINAR')) define('ZOOM_TYPE_JOIN_WEBINAR', 2);

if (!defined('ZOOM_TYPE_ORGANIZER')) define('ZOOM_TYPE_ORGANIZER', 'organizer');
if (!defined('ZOOM_TYPE_ATTENDEE')) define('ZOOM_TYPE_ATTENDEE', 'attendee');

// ZOOM END

// PLAID START

// old local
if (!defined('PLAID_CLIENT_ID')) define('PLAID_CLIENT_ID', '63ef2e56a12c4500122b7d95');
// client's
// if (!defined('PLAID_CLIENT_ID')) define('PLAID_CLIENT_ID', '6494abf8cd8c8a0014913048');
if (!defined('PLAID_REDIRECT_URL')) define('PLAID_REDIRECT_URL', $config['base_url'] . 'plaid/oauth2');

if (ENVIRONMENT == 'development') {
    if (!defined('PLAID_ENVIRONMENT')) define('PLAID_ENVIRONMENT', 'sandbox');
    if (!defined('PLAID_API_URL')) define('PLAID_API_URL', 'https://sandbox.plaid.com/');
    // old local
    if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', '117812c3110c8f729e9650150c6155');
    // other
    // if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', '294021c5660c6d2d3a773215f281a4');
    // if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', 'd0d9fbb065a48a9b47fd44a8e99fba');
    // if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', 'c882438f2a23220ab6117639426437');
} elseif (ENVIRONMENT == 'testing') {
    if (!defined('PLAID_ENVIRONMENT')) define('PLAID_ENVIRONMENT', 'sandbox');
    if (!defined('PLAID_API_URL')) define('PLAID_API_URL', 'https://sandbox.plaid.com/');
    // if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', '117812c3110c8f729e9650150c6155');
    if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', 'c882438f2a23220ab6117639426437');
} else {
    if (!defined('PLAID_ENVIRONMENT')) define('PLAID_ENVIRONMENT', 'production');
    if (!defined('PLAID_API_URL')) define('PLAID_API_URL', 'https://production.plaid.com/');
    if (!defined('PLAID_CLIENT_SECRET')) define('PLAID_CLIENT_SECRET', 'a4b54ff80c371aa80047a609024db3');
}

//
if (!defined('PLAID_CREATE_LINK_TOKEN')) define('PLAID_CREATE_LINK_TOKEN', 'link/token/create');
if (!defined('PLAID_PUBLIC_TOKEN_EXCHANGE')) define('PLAID_PUBLIC_TOKEN_EXCHANGE', 'item/public_token/exchange');
if (!defined('PLAID_ENTITY_TYPE')) define('PLAID_ENTITY_TYPE', ['accounts', 'bank_transfers', 'categories', 'institutions', 'items', 'liabilities', 'transactions', 'investments', 'assets', 'identity']);

//
if (!defined('PLAID_GET_TRANSACTION')) define('PLAID_GET_TRANSACTION', 'transactions/get');
if (!defined('PLAID_GET_LIABILITY')) define('PLAID_GET_LIABILITY', 'liabilities/get');
if (!defined('PLAID_GET_IDENTITY')) define('PLAID_GET_IDENTITY', 'identity/get');
if (!defined('PLAID_CREATE_TRANSFER')) define('PLAID_CREATE_TRANSFER', 'transfer/create');
if (!defined('PLAID_GET_TRANSFER')) define('PLAID_GET_TRANSFER', 'transfer/get');
if (!defined('PLAID_GET_TRANSFER_EVENT_LIST')) define('PLAID_GET_TRANSFER_EVENT_LIST', 'transfer/event/list');
if (!defined('PLAID_SIMULATE_TRANSFER')) define('PLAID_SIMULATE_TRANSFER', 'sandbox/transfer/simulate');
if (!defined('PLAID_FIRE_TRANSFER_WEBHOOK')) define('PLAID_FIRE_TRANSFER_WEBHOOK', 'sandbox/transfer/fire_webhook');

//
if (!defined('PLAID_CREATE_USER')) define('PLAID_CREATE_USER', 'user/create');
if (!defined('PLAID_CLIENT_PREFIX')) define('PLAID_CLIENT_PREFIX', 'PLAID-USER-');

//
if (!defined('PLAID_GET_BANK_INCOME')) define('PLAID_GET_BANK_INCOME', 'credit/bank_income/get');
if (!defined('PLAID_GET_PAYROLL_INCOME')) define('PLAID_GET_PAYROLL_INCOME', 'credit/payroll_income/get');
if (!defined('PLAID_GET_EMPLOYMENT')) define('PLAID_GET_EMPLOYMENT', 'credit/employment/get');
if (!defined('PLAID_GET_SESSION')) define('PLAID_GET_SESSION', 'credit/sessions/get');
if (!defined('PLAID_GET_ACCOUNTS')) define('PLAID_GET_ACCOUNTS', 'accounts/get');
if (!defined('PLAID_TRANSFER_AUTHORIZATION')) define('PLAID_TRANSFER_AUTHORIZATION', 'transfer/authorization/create');
if (!defined('PLAID_TRANSFER_INTENT')) define('PLAID_TRANSFER_INTENT', 'transfer/intent/create');
if (!defined('PLAID_GET_TRANSFER')) define('PLAID_GET_TRANSFER', 'transfer/intent/get');
if (!defined('PLAID_GET_WEBHOOK_VERIFICATION_KEY')) define('PLAID_GET_WEBHOOK_VERIFICATION_KEY', 'webhook_verification_key/get');

//
if (!defined('PLIAD_WEBHOOK_CODE')) define('PLIAD_WEBHOOK_CODE', ["INCOME_VERIFICATION"]);

//
if (!defined('PLAID_TYPE_AUTH')) define('PLAID_TYPE_AUTH', 'auth');
if (!defined('PLAID_TYPE_INCOME')) define('PLAID_TYPE_INCOME', 'income');
if (!defined('PLAID_TYPE_TRANSFER')) define('PLAID_TYPE_TRANSFER', 'transfer');
if (!defined('PLAID_LINK_TYPE')) define('PLAID_LINK_TYPE', [PLAID_TYPE_AUTH, PLAID_TYPE_INCOME, PLAID_TYPE_TRANSFER]);

//
if (!defined('PLAID_BANK_INCOME')) define('PLAID_BANK_INCOME', 'bank');
if (!defined('PLAID_PAYROLL_INCOME')) define('PLAID_PAYROLL_INCOME', 'payroll');
if (!defined('PLAID_INCOME_TYPE')) define('PLAID_INCOME_TYPE', [PLAID_PAYROLL_INCOME, PLAID_BANK_INCOME]);

//
if (!defined('PLAID_TRANSFER_MODE_PAYMENT')) define('PLAID_TRANSFER_MODE_PAYMENT', 'PAYMENT'); // Transfers funds from an end user's account to your business account.
if (!defined('PLAID_TRANSFER_MODE_DISBURSEMENT')) define('PLAID_TRANSFER_MODE_DISBURSEMENT', 'DISBURSEMENT'); //Transfers funds from your business account to an end user's account.

// PLAID END

// QUICKBOOK START

if (!defined('QUICKBOOK_CLIENT_ID')) define('QUICKBOOK_CLIENT_ID', 'ABtB0BUglBMtXPIft027AVlwbkn87877xKJF50iLLv9l1k6N0D');
if (!defined('QUICKBOOK_SECRET')) define('QUICKBOOK_SECRET', 'q2ZwrMZUY42NCzrATciaMS1rJPI07FWYqX1iN8ha');
if (!defined('QUICKBOOK_AUTH_MODE')) define('QUICKBOOK_AUTH_MODE', 'oauth2');

if (ENVIRONMENT == 'development') {
    if (!defined('QUICKBOOK_SANDBOX_ENV')) define('QUICKBOOK_SANDBOX_ENV', '1');
    //
    if (!defined('QUICKBOOK_BASEURL')) define('QUICKBOOK_BASEURL', 'development');
    if (!defined('QUICKBOOK_DOWNLOAD_PATH')) define('QUICKBOOK_DOWNLOAD_PATH', 'D:\Xampp New\htdocs\azaverze\quickbook_downloads');
    if (!defined('QUICKBOOK_DOWNLOAD_REPLACE_PATH')) define('QUICKBOOK_DOWNLOAD_REPLACE_PATH', 'D:\Xampp New\htdocs\azaverze\\');
} elseif (ENVIRONMENT == 'testing') {
    if (!defined('QUICKBOOK_SANDBOX_ENV')) define('QUICKBOOK_SANDBOX_ENV', '1');
    //
    if (!defined('QUICKBOOK_BASEURL')) define('QUICKBOOK_BASEURL', 'development');
    if (!defined('QUICKBOOK_DOWNLOAD_PATH')) define('QUICKBOOK_DOWNLOAD_PATH', '/home/azaverze/public_html/stagging/quickbook_downloads');
    if (!defined('QUICKBOOK_DOWNLOAD_REPLACE_PATH')) define('QUICKBOOK_DOWNLOAD_REPLACE_PATH', '/home/azaverze/public_html/stagging/');
} else {
    if (!defined('QUICKBOOK_SANDBOX_ENV')) define('QUICKBOOK_SANDBOX_ENV', '1');
    // if (!defined('QUICKBOOK_SANDBOX_ENV')) define('QUICKBOOK_SANDBOX_ENV', '0');
    //
    if (!defined('QUICKBOOK_BASEURL')) define('QUICKBOOK_BASEURL', 'development');
    // if (!defined('QUICKBOOK_BASEURL')) define('QUICKBOOK_BASEURL', 'production');
    // update upon going live
    if (!defined('QUICKBOOK_DOWNLOAD_PATH')) define('QUICKBOOK_DOWNLOAD_PATH', '/home/azaverze/public_html/stagging/quickbook_downloads');
    if (!defined('QUICKBOOK_DOWNLOAD_REPLACE_PATH')) define('QUICKBOOK_DOWNLOAD_REPLACE_PATH', '/home/azaverze/public_html/stagging/');
}

if (!QUICKBOOK_SANDBOX_ENV) {
    if (!defined('QUICKBOOK_BASE_URL')) define('QUICKBOOK_BASE_URL', 'https://quickbooks.api.intuit.com');
} else {
    if (!defined('QUICKBOOK_BASE_URL')) define('QUICKBOOK_BASE_URL', 'https://sandbox-quickbooks.api.intuit.com');
}

if (!defined('QUICKBOOK_REDIRECT_URL')) define('QUICKBOOK_REDIRECT_URL', $config['base_url'] . 'quickbook/redirect');

if (!defined('QUICKBOOK_OAUTH_SCOPE')) define('QUICKBOOK_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting openid profile email phone address');

if (!defined('QUICKBOOK_AUTHORIZATION_URL')) define('QUICKBOOK_AUTHORIZATION_URL', 'https://appcenter.intuit.com/connect/oauth2');
if (!defined('QUICKBOOK_TOKEN_URL')) define('QUICKBOOK_TOKEN_URL', 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer');
if (!defined('QUICKBOOK_COMPANY_ID')) define('QUICKBOOK_COMPANY_ID', '4620816365279786910');
if (!defined('QUICKBOOK_ENTITY_TYPE')) define('QUICKBOOK_ENTITY_TYPE', ['account', 'bill', 'billpayment', 'cashflow', 'class', 'company', 'customer', 'department', 'employee', 'invoice', 'quickbookclass', 'item', 'taxcode', 'term', 'timeactivity', 'vendor']);
if (!defined('QUICKBOOK_ENTITY_TYPE_LIST')) define(
    'QUICKBOOK_ENTITY_TYPE_LIST',
    [
        'account' => 'Account',
        'bill' => 'Bill',
        'billpayment' => 'Bill Payment',
        'cashflow' => 'Cash Flow',
        'class' => 'Class',
        'company' => 'Company',
        'customer' => 'Customer',
        'department' => 'Department',
        'employee' => 'Employee',
        'invoice' => 'Invoice',
        'item' => 'Item',
        'taxcode' => 'Tax Code',
        'term' => 'Term',
        'timeactivity' => 'Time Activity',
        'vendor' => 'Vendor'
    ]
);

// QUICKBOOK END

// VOUCHED START

if (!defined('VOUCHED_CALLBACK_URL')) define('VOUCHED_CALLBACK_URL', $config['base_url'] . 'vouched/index');

// SET FALSE FOR PRODUCTION
if (!defined('VOUCHED_SANDBOX_ENV')) define('VOUCHED_SANDBOX_ENV', '0');

if (!VOUCHED_SANDBOX_ENV) {
    // - PRODUCTION
    if (!defined('VOUCHED_PUBLIC_KEY')) define('VOUCHED_PUBLIC_KEY', 'i-FUg~J5X!LDc0yA.GR4_ik76HLSQY');
    if (!defined('VOUCHED_PRIVATE_KEY')) define('VOUCHED_PRIVATE_KEY', 'huoVgb3qQiKwp.#EHB_~G2tf*.3sPT');
} else {
    // - SANDBOX
    if (!defined('VOUCHED_PUBLIC_KEY')) define('VOUCHED_PUBLIC_KEY', 'Vp7j.Ng!jQ-0gX06zt-!~aZdUsig8t');
    if (!defined('VOUCHED_PRIVATE_KEY')) define('VOUCHED_PRIVATE_KEY', 's3C@mf*JtkpLn~VYe-taQ#ZzczETG*');
}

if (!defined('VOUCHED_SIGNATURE_KEY')) define('VOUCHED_SIGNATURE_KEY', 'pt4sFd@wN2F~VS86*GYOA!.Fv6Ta~S');

// VOUCHED END

// MONDAY START

if (!defined('MONDAY_API_TOKEN')) define('MONDAY_API_TOKEN', 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjM4NzY4NDM5MywiYWFpIjoxMSwidWlkIjo2Mzg1MDczNCwiaWFkIjoiMjAyNC0wNy0yM1QwNjoyNzowMS4wMDBaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6MjQ1NjA2ODAsInJnbiI6ImFwc2UyIn0.0eU_YR4OCTCgGQLVLd8cRrtA0AS7NABUU9zv6gLdzMA');

// MONDAY END

// ESCROW START

// if (!defined('ESCROW_BASE_URL')) define('ESCROW_BASE_URL', 'https://api.escrow.com/2017-09-01/');
if (!defined('ESCROW_BASE_URL')) define('ESCROW_BASE_URL', 'https://api.escrow-sandbox.com/2017-09-01/');
// if (!defined('ESCROW_API_KEY')) define('ESCROW_API_KEY', '15998_OI7vNYhZobj18TdErY7zpTk989kaxHuE4oosg4D2MLVVVTHvax5wCINTw7dNrvQc');
if (!defined('ESCROW_API_KEY')) define('ESCROW_API_KEY', '3306_1Tx7vGPkf6319hQMgiSBgEcMuIpjw0NQvZWahwMrdfm84uzwN7RODzWg5khRBnfi');
if (!defined('ESCROW_EMAIL')) define('ESCROW_EMAIL', 'infodemolink1@gmail.com');
if (!defined('ESCROW_CUSTOMER')) define('ESCROW_CUSTOMER', 'customer');
if (!defined('ESCROW_TRANSACTION')) define('ESCROW_TRANSACTION', 'transaction');

if (!defined('ESCROW_ITEM_TYPE')) define(
    'ESCROW_ITEM_TYPE',
    array(
        'broker_fee' => 'broker_fee',
        'domain_name' => 'domain_name',
        'domain_name_holding' => 'domain_name_holding',
        'general_merchandise' => 'general_merchandise',
        'milestone' => 'milestone',
        'motor_vehicle' => 'motor_vehicle',
        'partner_fee' => 'partner_fee',
        'shipping_fee' => 'shipping_fee',
    )
);

//
if (!defined('ESCROW_INSPECTION_PERIOD')) define('ESCROW_INSPECTION_PERIOD', 259200); // 3 days, 86400 = 1 days, 86400 * 3 = 259200


// ESCROW END

// STRIPE START

if (ENVIRONMENT == 'development') {
    if (!defined('STRIPE_ENDPOINT_SECRET')) define('STRIPE_ENDPOINT_SECRET', 'whsec_cHPLK8VVXSUTx5Xy1a17M0UMaJ929ack');
    //
    if (!defined('STRIPE_PUBLISHABLE_KEY')) define('STRIPE_PUBLISHABLE_KEY', 'pk_test_8ZdDLf80e4PcXdiSWP6GUSzJ');
    if (!defined('STRIPE_SECRET_KEY')) define('STRIPE_SECRET_KEY', 'sk_test_KqckVfv2AduSKKT5Hj39EKc2');
} elseif (ENVIRONMENT == 'testing') {
    if (!defined('STRIPE_ENDPOINT_SECRET')) define('STRIPE_ENDPOINT_SECRET', 'whsec_qavCXxMiSky7RJ6szygRVVi0SCDhftFJ');
    //
    if (!defined('STRIPE_PUBLISHABLE_KEY')) define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51OMx7aJiGTjA8hah8LdoFKgU8cdCn3BDuvZBDFp7Ele8hkto8u7i5SRE8sofraCMDAB3Vx66IRGMyDdTGvzUhF7z00JdAyPeb7');
    if (!defined('STRIPE_SECRET_KEY')) define('STRIPE_SECRET_KEY', 'sk_test_51OMx7aJiGTjA8hahzU6ZPtGHf3qKAngofUQbFXXvqVomb5WbxlcWp6FtNe0k2A9Fq8HGwl3DivXFV8cmadbCCOcz00EFIiIFu4');
} else {
    if (!defined('STRIPE_ENDPOINT_SECRET')) define('STRIPE_ENDPOINT_SECRET', 'whsec_oiEmvST6NEYXzzhp07krfckfZKGmezLz');
    //
    // if (!defined('STRIPE_PUBLISHABLE_KEY')) define('STRIPE_PUBLISHABLE_KEY', 'pk_live_51L5eQ9AC0rmO4R6sVrtUrBiKQlSesqqWjeL6LERC9ocGrGaL1ISNkeIIx0M8QtOKQ7k3SCGSve2QFFp77yZHD3Gp003ivQdMdW');
    // if (!defined('STRIPE_SECRET_KEY')) define('STRIPE_SECRET_KEY', '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');
    if (!defined('STRIPE_PUBLISHABLE_KEY')) define('STRIPE_PUBLISHABLE_KEY', 'pk_live_51OMx7aJiGTjA8hahNGZLvlztr9IBDOAskWs6SC2uFFxKKIk77ELJeBEZiBESR9OZWRJWzKzQYanCDI22lpkidGGg00UKCkv9xQ');
    if (!defined('STRIPE_SECRET_KEY')) define('STRIPE_SECRET_KEY', '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');
}

if (!defined('STRIPE_ENTITY_TYPE')) define('STRIPE_ENTITY_TYPE', ['accounts', 'return']);

if (!defined('STRIPE_REFRESH_URL')) define('STRIPE_REFRESH_URL', $config['base_url'] . 'dashboard/home/stripe/accounts');
if (!defined('STRIPE_RETURN_URL')) define('STRIPE_RETURN_URL', $config['base_url'] . 'dashboard/home/stripe/return');

if (!defined('STRIPE_ACCOUNT_ONBOARDING')) define('STRIPE_ACCOUNT_ONBOARDING', 'account_onboarding');
if (!defined('STRIPE_ACCOUNT_UPDATE')) define('STRIPE_ACCOUNT_UPDATE', 'account_update');

if (!defined('STRIPE_TRANSFER_INTERVAL')) define('STRIPE_TRANSFER_INTERVAL', 'days');
if (!defined('STRIPE_TRANSFER_DELAY')) define('STRIPE_TRANSFER_DELAY', '5');

//
if (!defined('STRIPE_FEE_PERECENTAGE')) define('STRIPE_FEE_PERECENTAGE', 2.9);
if (!defined('STRIPE_FEE_EXTRA_CENTS')) define('STRIPE_FEE_EXTRA_CENTS', 30);

if (!defined('STRIPE_TRIAL_PERIOD_DAYS')) define('STRIPE_TRIAL_PERIOD_DAYS', 14);

// STRIPE END

// LOG START

//
if (!defined('LOG_TYPE_GENERAL')) define('LOG_TYPE_GENERAL', 'GENERAL');
if (!defined('LOG_TYPE_PAYMENT')) define('LOG_TYPE_PAYMENT', 'PAYMENT');
if (!defined('LOG_TYPE_API')) define('LOG_TYPE_API', 'API');
if (!defined('LOG_TYPE_SERVER_API')) define('LOG_TYPE_SERVER_API', 'SERVER_API');

//
if (!defined('LOG_SOURCE_SERVER')) define('LOG_SOURCE_SERVER', 'SERVER');
if (!defined('LOG_SOURCE_STRIPE')) define('LOG_SOURCE_STRIPE', 'STRIPE');
if (!defined('LOG_SOURCE_CRON')) define('LOG_SOURCE_CRON', 'CRON');
if (!defined('LOG_SOURCE_CURL')) define('LOG_SOURCE_CURL', 'CURL');
if (!defined('LOG_SOURCE_TWILIO')) define('LOG_SOURCE_TWILIO', 'TWILIO');
if (!defined('LOG_SOURCE_QUICKBOOK')) define('LOG_SOURCE_QUICKBOOK', 'QUICKBOOK');
if (!defined('LOG_SOURCE_PLAID')) define('LOG_SOURCE_PLAID', 'PLAID');
if (!defined('LOG_SOURCE_ZOOM')) define('LOG_SOURCE_ZOOM', 'ZOOM');
if (!defined('LOG_SOURCE_ZOOM_CRON')) define('LOG_SOURCE_ZOOM_CRON', 'ZOOM_CRON');
if (!defined('LOG_SOURCE_BOX_CRON')) define('LOG_SOURCE_BOX_CRON', 'BOX_CRON');

//
if (!defined('LOG_LEVEL_ERROR')) define('LOG_LEVEL_ERROR', 'ERROR');
if (!defined('LOG_LEVEL_INFO')) define('LOG_LEVEL_INFO', 'INFO');
if (!defined('LOG_LEVEL_WARNING')) define('LOG_LEVEL_WARNING', 'WARNING');

// LOG END

// PROFILE CREDENTIALS START

if (!defined('SIGNUP_CREDENTIAL_EXPERIENCE')) define('SIGNUP_CREDENTIAL_EXPERIENCE', 'experience');
if (!defined('SIGNUP_CREDENTIAL_EDUCATION')) define('SIGNUP_CREDENTIAL_EDUCATION', 'education');
if (!defined('SIGNUP_CREDENTIAL_LICENSE')) define('SIGNUP_CREDENTIAL_LICENSE', 'license');
if (!defined('SIGNUP_CREDENTIAL_CERTIFICATE')) define('SIGNUP_CREDENTIAL_CERTIFICATE', 'certificate');
if (!defined('SIGNUP_CREDENTIAL_PUBLICATION')) define('SIGNUP_CREDENTIAL_PUBLICATION', 'publication');

// PROFILE CREDENTIALS END

// REVIEW START

if (!defined('REVIEW_TYPE_SIGNUP')) define('REVIEW_TYPE_SIGNUP', 'signup');
if (!defined('REVIEW_TYPE_JOB')) define('REVIEW_TYPE_JOB', 'job');

// REVIEW END

//
if (!defined('CREATE')) define('CREATE', 'create');
if (!defined('INSERT')) define('INSERT', 'insert');
if (!defined('UPDATE')) define('UPDATE', 'update');
//

if (!defined('CATEGORY_TYPE')) define('CATEGORY_TYPE', ['Life Sciences', 'Healthcare', 'Technology', 'Software', 'Construction', 'Other']);

// BOX START

if (!defined('BOX_CLIENT_ID')) define('BOX_CLIENT_ID', '387j9zp5qp81o0ex8kodmreau8rr1edf');
if (!defined('BOX_CLIENT_SECRET')) define('BOX_CLIENT_SECRET', '7hzVP9FN5YFompPNGNwgPPQSjYYEL9td');

if (!defined('BOX_OAUTH_STATE_PARAM')) define('BOX_OAUTH_STATE_PARAM', 'box_state');
if (!defined('BOX_OAUTH_AUTHORIZE_URL')) define('BOX_OAUTH_AUTHORIZE_URL', 'https://account.box.com/api/oauth2/authorize');
if (!defined('BOX_OAUTH_REDIRECT_URL')) define('BOX_OAUTH_REDIRECT_URL', $config['base_url'] . 'box/redirect');
if (!defined('BOX_OAUTH_AUTHORIZATION_URL')) define('BOX_OAUTH_AUTHORIZATION_URL', BOX_OAUTH_AUTHORIZE_URL . '?redirect_uri=' . BOX_OAUTH_REDIRECT_URL . '&client_id=' . BOX_CLIENT_ID . '&response_type=code&state=' . BOX_OAUTH_STATE_PARAM);
if (!defined('BOX_OAUTH_TOKEN_URL')) define('BOX_OAUTH_TOKEN_URL', 'https://api.box.com/oauth2/token');

if (!defined('BOX_OAUTH_REVOKE_URL')) define('BOX_OAUTH_REVOKE_URL', 'https://api.box.com/oauth2/revoke');
if (!defined('BOX_UPLOAD_FILE_URL')) define('BOX_UPLOAD_FILE_URL', 'https://upload.box.com/api/2.0/files/content');

if (!defined('BOX_API_URL')) define('BOX_API_URL', 'https://api.box.com/2.0');

// FOLDER
if (!defined('BOX_FOLDER_URL')) define('BOX_FOLDER_URL', BOX_API_URL . '/folders');
if (!defined('BOX_FOLDER_GET_URL')) define('BOX_FOLDER_GET_URL', BOX_FOLDER_URL . '/{folder_id}');
if (!defined('BOX_FOLDER_GET_ITEMS_URL')) define('BOX_FOLDER_GET_ITEMS_URL', BOX_FOLDER_GET_URL . '/items');

// FILE
if (!defined('BOX_FILE_URL')) define('BOX_FILE_URL', BOX_API_URL . '/files');
if (!defined('BOX_FILE_GET_URL')) define('BOX_FILE_GET_URL', BOX_FILE_URL . '/{file_id}');

// USER
if (!defined('BOX_USER_URL')) define('BOX_USER_URL', BOX_API_URL . '/users');

if (ENVIRONMENT == 'development') {
    if (!defined('BOX_DOWNLOAD_PATH')) define('BOX_DOWNLOAD_PATH', 'D:\Xampp New\htdocs\azaverze');
} elseif (ENVIRONMENT == 'testing') {
    if (!defined('BOX_DOWNLOAD_PATH')) define('BOX_DOWNLOAD_PATH', '/home/azaverze/public_html/stagging');
} else {
    // update upon going live
    if (!defined('BOX_DOWNLOAD_PATH')) define('BOX_DOWNLOAD_PATH', '');
}

if (!defined('BOX_CONFIG_ACCESS_TOKEN')) define('BOX_CONFIG_ACCESS_TOKEN', '798');
if (!defined('BOX_CONFIG_REFRESH_TOKEN')) define('BOX_CONFIG_REFRESH_TOKEN', '799');
if (!defined('BOX_CONFIG_TOKEN_EXPIRY')) define('BOX_CONFIG_TOKEN_EXPIRY', '800');

if (!defined('BOX_STATUS')) define('BOX_STATUS', ['active', 'inactive', 'cannot_delete_edit', 'cannot_delete_edit_upload']);

// BOX END

// ERROR CODES START

if (!defined('CODE_OK')) define('CODE_OK', 200);
if (!defined('CODE_CREATED')) define('CODE_CREATED', 201);
if (!defined('CODE_NO_CONTENT')) define('CODE_NO_CONTENT', 204);
if (!defined('CODE_BAD_REQUEST')) define('CODE_BAD_REQUEST', 400);
if (!defined('CODE_UNAUTHORIZED')) define('CODE_UNAUTHORIZED', 401);
if (!defined('CODE_FORBIDDEN')) define('CODE_FORBIDDEN', 403);
if (!defined('CODE_NOT_FOUND')) define('CODE_NOT_FOUND', 404);
if (!defined('CODE_SERVER_ERROR')) define('CODE_SERVER_ERROR', 500);
if (!defined('CODE_SERVICE_UNAVAILABLE')) define('CODE_SERVICE_UNAVAILABLE', 503);

// ERROR CODES END

// CHAT START

if (!defined('CHAT_REFERENCE_EMAIL')) define('CHAT_REFERENCE_EMAIL', 'email');
if (!defined('CHAT_REFERENCE_MESSAGE')) define('CHAT_REFERENCE_MESSAGE', 'message');

// CHAT END

// PRODUCT REQUEST START

if (!defined('REQUEST_PENDING')) define('REQUEST_PENDING', 0);
if (!defined('REQUEST_ACCEPTED')) define('REQUEST_ACCEPTED', 1);
if (!defined('REQUEST_REJECTED')) define('REQUEST_REJECTED', 2);
if (!defined('REQUEST_COMPLETE')) define('REQUEST_COMPLETE', 3);
if (!defined('REQUEST_INCOMPLETE')) define('REQUEST_INCOMPLETE', 4);
if (!defined('REQUEST_UPDATED')) define('REQUEST_UPDATED', 5);
if (!defined('REQUEST_EXTENDED')) define('REQUEST_EXTENDED', 6);
if (!defined('REQUEST_STATUS')) define('REQUEST_STATUS', [
    REQUEST_PENDING => 'pending',
    REQUEST_ACCEPTED => 'accepted',
    REQUEST_REJECTED => 'rejected',
    REQUEST_COMPLETE => 'completed',
    REQUEST_INCOMPLETE => 'incomplete',
    REQUEST_UPDATED => 'update',
    REQUEST_EXTENDED => 'extended',
]);


// PRODUCT REQUEST START

//

if (!defined('UPLOAD_GUIDELINES_PROFILE_IMAGE')) define('UPLOAD_GUIDELINES_PROFILE_IMAGE', 'Upload your image with file format in {width} x {height} pixels ratio, JPG or PNG only. No more than 1MB recommended.');
if (!defined('GENERAL_ATTACHMENT_SIZE_LIMIT')) define('GENERAL_ATTACHMENT_SIZE_LIMIT', 10485760); // in Bytes
if (!defined('MAX_ATTACHMENT_SIZE_LIMIT')) define('MAX_ATTACHMENT_SIZE_LIMIT', 52428800); // in Bytes
if (!defined('JOB_ATTACHMENT_SIZE_DESCIPTION')) define('JOB_ATTACHMENT_SIZE_DESCIPTION', 'The size limit for the attachment is 10 MB');
if (!defined('GENERAL_ATTACHMENT_SIZE_DESCIPTION')) define('GENERAL_ATTACHMENT_SIZE_DESCIPTION', 'The size limit for the attachment is 10 MB');
if (!defined('UPGRADE_MEMBERSHIP_DESCRIPTION')) define('UPGRADE_MEMBERSHIP_DESCRIPTION', 'Upgrade your membership to view this content');

if (!defined('MEMBERSHIP_PRODUCT_CUSTOMER_TITLE')) define('MEMBERSHIP_PRODUCT_CUSTOMER_TITLE', 'AzAverze Customer Subscription');
if (!defined('MEMBERSHIP_PRODUCT_ENTREPRENEUR_TITLE')) define('MEMBERSHIP_PRODUCT_ENTREPRENEUR_TITLE', 'AzAverze Entrepreneur Subscription');
if (!defined('MEMBERSHIP_PRODUCT_INNOVATOR_TITLE')) define('MEMBERSHIP_PRODUCT_INNOVATOR_TITLE', 'AzAverze Innovator Subscription');
if (!defined('MEMBERSHIP_PRODUCT_LEADER_TITLE')) define('MEMBERSHIP_PRODUCT_LEADER_TITLE', 'AzAverze Leader Subscription');
//

// SUBSCRIPTION START

if (!defined('SUBSCRIPTION_INTERVAL_TYPE')) define('SUBSCRIPTION_INTERVAL_TYPE', 'month');
if (!defined('SUBSCRIPTION_JOB_INTERVAL_TYPE')) define('SUBSCRIPTION_JOB_INTERVAL_TYPE', 'week');

// interval display names
if (!defined('SUBSCRIPTION_INTERVAL_TITLE_1')) define('SUBSCRIPTION_INTERVAL_TITLE_1', 'Monthly');
if (!defined('SUBSCRIPTION_INTERVAL_TITLE_2')) define('SUBSCRIPTION_INTERVAL_TITLE_2', 'Quaterly');
if (!defined('SUBSCRIPTION_INTERVAL_TITLE_3')) define('SUBSCRIPTION_INTERVAL_TITLE_3', 'Yearly');

// number of months
if (!defined('SUBSCRIPTION_INTERVAL_1')) define('SUBSCRIPTION_INTERVAL_1', 1);
if (!defined('SUBSCRIPTION_INTERVAL_2')) define('SUBSCRIPTION_INTERVAL_2', 4);
if (!defined('SUBSCRIPTION_INTERVAL_3')) define('SUBSCRIPTION_INTERVAL_3', 12);

if (!defined('SUBSCRIPTION_INTERVAL_2_COST')) define('SUBSCRIPTION_INTERVAL_2_COST', '28.99');
if (!defined('SUBSCRIPTION_INTERVAL_3_COST')) define('SUBSCRIPTION_INTERVAL_3_COST', '119.99');

// SUBSCRIPTION END

//

if (!defined('MINIMUM_SIGNUP_TESTIMONIAL')) define('MINIMUM_SIGNUP_TESTIMONIAL', 3);
if (!defined('TESTIMONIAL_ALERT')) define('TESTIMONIAL_ALERT', 'Your account lacks the required number of testimonials to apply for this job. Add testomonials to your profile or send request to administrator for the extention of period or the admin may permanently allow you to apply jobs without the need of testimonials.');

//

//

if (!defined('CALENDAR_TYPE_WEBINAR')) define('CALENDAR_TYPE_WEBINAR', 'webinar');
if (!defined('CALENDAR_TYPE_MEETING')) define('CALENDAR_TYPE_MEETING', 'meeting');
if (!defined('CALENDAR_TYPE_COACHING')) define('CALENDAR_TYPE_COACHING', 'coaching');
if (!defined('CALENDAR_TYPE_SLOT')) define('CALENDAR_TYPE_SLOT', 'slot');

if (!defined('COACHING_ORGANIZER')) define('COACHING_ORGANIZER', 'Jeysen Yogratnam');
//

//

if (!defined('TUTORIAL_PATH')) define('TUTORIAL_PATH', '/Tutorial/video/');

//

// TUTORIAL URLS START

if (!defined('LOGIN_TUTORIAL')) define('LOGIN_TUTORIAL', 'signup-login-membership-tutorial.mp4');
if (!defined('SIGNUP_TUTORIAL')) define('SIGNUP_TUTORIAL', 'signup-login-membership-tutorial.mp4');
if (!defined('MEMBERSHIP_TUTORIAL')) define('MEMBERSHIP_TUTORIAL', 'signup-login-membership-tutorial.mp4');

if (!defined('PROFILE_TUTORIAL')) define('PROFILE_TUTORIAL', 'profile-n-company-profile-tutorial.mp4');
if (!defined('COMPANY_PROFILE_TUTORIAL')) define('COMPANY_PROFILE_TUTORIAL', 'profile-n-company-profile-tutorial.mp4');

if (!defined('QUICKBOOKS_TUTORIAL')) define('QUICKBOOKS_TUTORIAL', 'quickbooks-plaid-tutorial.mp4');
if (!defined('PLAID_TUTORIAL')) define('PLAID_TUTORIAL', 'quickbooks-plaid-tutorial.mp4');
if (!defined('BOX_TUTORIAL')) define('BOX_TUTORIAL', 'box-tutorial.mp4');
if (!defined('CALENDAR_TUTORIAL')) define('CALENDAR_TUTORIAL', 'calendar-tutorial.mp4');
if (!defined('WEBINAR_TUTORIAL')) define('WEBINAR_TUTORIAL', 'webinar-tutorial.mp4');
//
if (!defined('PRODUCT_TUTORIAL')) define('PRODUCT_TUTORIAL', 'product-tutorial.mp4');
if (!defined('SERVICE_TUTORIAL')) define('SERVICE_TUTORIAL', 'service-tutorial.mp4');
if (!defined('TECHNOLOGY_TUTORIAL')) define('TECHNOLOGY_TUTORIAL', 'technology-tutorial.mp4');

if (!defined('POST_JOB_TUTORIAL')) define('POST_JOB_TUTORIAL', 'posting-job-tutorial.mp4');
if (!defined('APPLY_JOB_TUTORIAL')) define('APPLY_JOB_TUTORIAL', 'applying-job-creating-meeting-tutorial.mp4');
if (!defined('TESTIMONIAL_TUTORIAL')) define('TESTIMONIAL_TUTORIAL', 'testimonial-tutorial.mp4');
if (!defined('SENDING_PAYING_MILESTONE_TUTORIAL')) define('SENDING_PAYING_MILESTONE_TUTORIAL', 'sending-paying-milestone-tutorial.mp4');

if (!defined('ADMIN_TUTORIAL_1')) define('ADMIN_TUTORIAL_1', 'admin-tutorial-1.mp4');
if (!defined('ADMIN_TUTORIAL_2')) define('ADMIN_TUTORIAL_2', 'admin-tutorial-2.mp4');
if (!defined('ADMIN_TUTORIAL_3')) define('ADMIN_TUTORIAL_3', 'admin-tutorial-3.mp4');

if (!defined('ADMIN_BYPASS_TUTORIAL_1')) define('ADMIN_BYPASS_TUTORIAL_1', 'admin-bypass-privileges-tutorial-1.mp4');
if (!defined('ADMIN_BYPASS_TUTORIAL_2')) define('ADMIN_BYPASS_TUTORIAL_2', 'admin-bypass-privileges-tutorial-2.mp4');

// TUTORIAL URLS END

// PRIVILEGE START

if (!defined('PRIVILEGE_TYPE_IDENTITY')) define('PRIVILEGE_TYPE_IDENTITY', 1);
if (!defined('PRIVILEGE_TYPE_APPROVAL')) define('PRIVILEGE_TYPE_APPROVAL', 2);
if (!defined('PRIVILEGE_TYPE_EMAIL')) define('PRIVILEGE_TYPE_EMAIL', 3);
if (!defined('PRIVILEGE_TYPE_PHONE')) define('PRIVILEGE_TYPE_PHONE', 4);
if (!defined('PRIVILEGE_TYPE_TESTIMONIAL')) define('PRIVILEGE_TYPE_TESTIMONIAL', 5);
if (!defined('PRIVILEGE_TYPE_VERIFICATION')) define('PRIVILEGE_TYPE_VERIFICATION', 6);

if (!defined('PRIVILEGE_TYPE')) define('PRIVILEGE_TYPE', [PRIVILEGE_TYPE_IDENTITY, PRIVILEGE_TYPE_APPROVAL, PRIVILEGE_TYPE_EMAIL, PRIVILEGE_TYPE_PHONE, PRIVILEGE_TYPE_TESTIMONIAL, PRIVILEGE_TYPE_VERIFICATION]);

if (!defined('PRIVILEGE_TYPE_LABEL')) define('PRIVILEGE_TYPE_LABEL', [PRIVILEGE_TYPE_IDENTITY => 'identity', PRIVILEGE_TYPE_APPROVAL => 'approval', PRIVILEGE_TYPE_EMAIL => 'email', PRIVILEGE_TYPE_PHONE => 'phone', PRIVILEGE_TYPE_TESTIMONIAL => 'testimonial', PRIVILEGE_TYPE_VERIFICATION => 'Mark as verified']);

// PRIVILEGE END

// API NINJA TAX START

if (!defined('X_API_KEY')) define('X_API_KEY', 'Yu5m/toyPw78fd5pyxVKCw==sjHCd71ITchuKb94');
if (!defined('TAX_API_URL')) define('TAX_API_URL', 'https://api.api-ninjas.com/v1/salestax');

// API NINJA TAX END

// SIGNUP

if (!defined('SIGNUP_PRIVACY_PUBLIC')) define('SIGNUP_PRIVACY_PUBLIC', 'public');
if (!defined('SIGNUP_PRIVACY_PRIVATE')) define('SIGNUP_PRIVACY_PRIVATE', 'private');
if (!defined('SIGNUP_PRIVACY_FOLLOWER')) define('SIGNUP_PRIVACY_FOLLOWER', 'follower');

// SIGNUP

if (!defined('ADMIN_EMAIL_CONFIG_ID')) define('ADMIN_EMAIL_CONFIG_ID', 6);

if (!defined('PAYMENT_STATUS_PENDING')) define('PAYMENT_STATUS_PENDING', 0);
if (!defined('PAYMENT_STATUS_COMPLETED')) define('PAYMENT_STATUS_COMPLETED', 1);
if (!defined('PAYMENT_STATUS_CANCELLED')) define('PAYMENT_STATUS_CANCELLED', 2);
if (!defined('PAYMENT_STATUS_TRIAL')) define('PAYMENT_STATUS_TRIAL', 3);
if (!defined('PAYMENT_STATUS_FAILED')) define('PAYMENT_STATUS_FAILED', 4);
if (!defined('PAYMENT_STATUS_ESCROW')) define('PAYMENT_STATUS_ESCROW', 5);

if (!defined('PAYMENT_STATUS')) define('PAYMENT_STATUS', array(
    PAYMENT_STATUS_PENDING => 'pending',
    PAYMENT_STATUS_COMPLETED => 'completed',
    PAYMENT_STATUS_CANCELLED => 'cancelled',
    PAYMENT_STATUS_TRIAL => 'trialing',
    PAYMENT_STATUS_FAILED => 'failed',
    PAYMENT_STATUS_ESCROW => 'in escrow'
));

//
if (!defined('FREE')) define('FREE', 'FREE');
if (!defined('STRIPE')) define('STRIPE', 'STRIPE');
if (!defined('PAYPAL')) define('PAYPAL', 'PAYPAL');
if (!defined('PLAID')) define('PLAID', 'PLAID');

if (!defined('STRIPE_LOG_REFERENCE_SIGNUP')) define('STRIPE_LOG_REFERENCE_SIGNUP', 'signup');
if (!defined('STRIPE_LOG_REFERENCE_JOB')) define('STRIPE_LOG_REFERENCE_JOB', 'job');
if (!defined('STRIPE_LOG_REFERENCE_TECHNOLOGY')) define('STRIPE_LOG_REFERENCE_TECHNOLOGY', 'technology');
if (!defined('STRIPE_LOG_REFERENCE_COACHING')) define('STRIPE_LOG_REFERENCE_COACHING', 'coaching');
if (!defined('STRIPE_LOG_RESOURCE_TYPE')) define(
    'STRIPE_LOG_RESOURCE_TYPE',
    [
        'charges' => 'charges',
        'subscriptions' => 'subscriptions',
        'checkout_sessions' => 'checkout_sessions',
        'customers' => 'customers',
        'accounts' => 'accounts',
        'payment_intents' => 'payment_intents',
    ]
);
//

if (!defined('INVOICE_SUBSCRIPTION')) define('INVOICE_SUBSCRIPTION', 'subscription');
if (!defined('INVOICE_PRODUCT')) define('INVOICE_PRODUCT', 'product');
if (!defined('INVOICE_SERVICE')) define('INVOICE_SERVICE', 'service');
if (!defined('INVOICE_SERVICE_PROVIDED')) define('INVOICE_SERVICE_PROVIDED', 'service-provided');
if (!defined('INVOICE_JOB')) define('INVOICE_JOB', 'job');
if (!defined('INVOICE_TECHNOLOGY')) define('INVOICE_TECHNOLOGY', 'technology');
if (!defined('INVOICE_COACHING')) define('INVOICE_COACHING', 'coaching');

//
if (!defined('TRANSFER_UNPAID')) define('TRANSFER_UNPAID', 0);
if (!defined('TRANSFER_PAID')) define('TRANSFER_PAID', 1);

//
if (!defined('CONFIG_SUPPORT_EMAIL')) define('CONFIG_SUPPORT_EMAIL', 13);

if (!defined('TRIAL_EMAIL_DAYS')) define('TRIAL_EMAIL_DAYS', 7);

//
if (!defined('DONATION_GENERAL')) define('DONATION_GENERAL', 'general');
if (!defined('DONATION_FUNDRAISING')) define('DONATION_FUNDRAISING', 'fundraising');

if (ENVIRONMENT == 'development') {
    if (!defined('CAPTCHA_SECRET_KEY')) define('CAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
    if (!defined('CAPTCHA_SECRET_KEY')) define('CAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
} else {
    if (!defined('CAPTCHA_SECRET_KEY')) define('CAPTCHA_SITE_KEY', '6Lf9AigqAAAAACZ71G3XPBCPkn8QbqxjnD35Mkpl');
    if (!defined('CAPTCHA_SECRET_KEY')) define('CAPTCHA_SECRET_KEY', '6Lf9AigqAAAAAND3i4SGXQPhiIttKwpGMaSwgTPL');
}

if (!defined('FREE_COST_KEYWORD')) define('FREE_COST_KEYWORD', 'Dreamer');