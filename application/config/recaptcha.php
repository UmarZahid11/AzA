<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Recaptcha configuration settings
 *
 * recaptcha_sitekey: Recaptcha site key to use in the widget
 * recaptcha_secretkey: Recaptcha secret key which is used for communicating between your server to Google's
 * lang: Language code, if blank "en" will be used
 *
 * recaptcha_sitekey and recaptcha_secretkey can be obtained from https://www.google.com/recaptcha/admin/
 * Language code can be obtained from https://developers.google.com/recaptcha/docs/language
 *
 * @author Damar Riyadi <damar@tahutek.net>
 */

 // These key for only Localhost
// $config['recaptcha_sitekey'] = "6LdY1wcUAAAAANqOYRnm0tSbpvHL5kpejovIVdYj";
// $config['recaptcha_secretkey'] = "6LdY1wcUAAAAADB7ZD2Ug3DqJrcKe3OHh8F8s2L-";

// These key for only Demo Server
$config['recaptcha_sitekey'] = GOOGLE_CAPTCHA_SITE_KEY;
$config['recaptcha_secretkey'] = GOOGLE_CAPTCHA_SECRET_KEY;

$config['lang'] = "en";