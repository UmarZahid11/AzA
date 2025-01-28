<?php

ini_set('display_errors', DISPLAY_ERRORS);
ini_set('display_startup_errors', DISPLAY_ERRORS);
if(DISPLAY_ERRORS) {
    error_reporting(E_ALL);
}

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH . 'libraries/vendor/autoload.php');
require_once(APPPATH . 'libraries/stripe-php/init.php');
require_once(APPPATH . '/libraries/JWT.php');

//Include Admin Wrapper. Break down things abit
include_once(APPPATH . "core/MY_Controller_Admin.php");

use QuickBooksOnline\API\DataService\DataService;
use TomorrowIdeas\Plaid\Plaid;

/**
 * Controller Wrapper Class.
 *
 * @package
 * @author
 * @version     1.0
 * @since       2023
 *
 */
class MY_Controller extends MY_Controller_Admin
{

    /**
     * layout
     *
     * @var mixed
     */
    protected $layout;

    /**
     * layout_data
     *
     * @var array
     */
    public $layout_data = array();

    /**
     * view_pre
     *
     * @var mixed
     */
    public $view_pre;

    /**
     * router
     *
     * @var mixed
     */
    public $router;

    /**
     * is_admin
     *
     * @var mixed
     */
    public $is_admin;

    /**
     * userid
     *
     * @var mixed
     */
    public $userid;

    /**
     * user_info
     *
     * @var mixed
     */
    public $user_info;

    /**
     * signup_info
     *
     * @var mixed
     */
    public $signup_info;

    /**
     * user_data
     *
     * @var mixed
     */
    public $user_data;

    /**
     * user_type
     *
     * @var mixed
     */
    public $user_type;

    /**
     * admin_current
     *
     * @var mixed
     */
    public $admin_current;

    /**
     * admin_path
     *
     * @var mixed
     */
    public $admin_path;

    /**
     * agent
     *
     * @var mixed
     */
    public $agent;

    /**
     * dataService
     *
     * @var mixed
     */
    protected $dataService;

    /**
     * plaid
     *
     * @var mixed
     */
    protected $plaid;

    /**
     * plaid_token
     *
     * @var mixed
     */
    public $plaid_token;

    /**
     * csrf
     *
     * @var mixed
     */
    protected $csrf;

    /**
     * stripe
     *
     * @var mixed
     */
    protected $stripe;

    /**
     * stripe_v2020
     *
     * @var mixed
     */
    protected $stripe_v2020;

    /**
     * paypalAccessToken
     *
     * @var mixed
     */
    protected $paypalAccessToken;

    /**
     * paypalAccessTokenExpiry
     *
     * @var mixed
     */
    protected $paypalAccessTokenExpiry;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        global $config;

        parent::__construct();

        // As soon as controller starts, configure timezone if set in tkd_config.php
        $this->set_time_zone();

        // Commmon helpers
        $this->load->library('form_validation');
        $this->load->library('unit_test');
        $this->lang->load('information', 'english');

        // Load DB Config Parameters in GLOBAL $config['db']
        $config['db'] = $this->model_config->load_config();

        $this->layout_data['modals'] = array();

        if (isset($_REQUEST['msg_error']) && $_REQUEST['msg_error']) {
            $this->layout_data['msg']['error'] = $_REQUEST['msg_error'];
        }

        //
        $this->stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
        $this->stripe_v2020 = new \Stripe\StripeClient([
            "api_key" => STRIPE_SECRET_KEY,
            "stripe_version" => "2020-08-27"
        ]);

        // FOR ADMIN
        if ($this->router->directory == "admin/") {
            $this->is_admin = true;

            /** Get Logo **/
            $this->layout_data['logo'] = $this->model_logo->find_all_active();

            $this->layout = "admin/admin_main";
            $this->view_pre = "admin/" . $this->router->class . "/";

            // If not logged in, redirect to login page.
            $this->login_redirect_check("logged_in", "is_admin");

            $title = $config['admin_title'] . " - Admin Panel";
            $meta_data = array("keywords" => "$title", "description" => "$title", "robots" => "noindex, follow");

            $this->layout_data['css_files'] = array(
                "components.css",
                "plugins.css",
                "layout.css",
                "main-responsive.css",
                "clip-style.css",
                "custom.css",
                "toastr.min.css",
            );
            $this->layout_data['js_files'] = array(
                // "jquery.min.js",
                "jquery-migrate.min.js",
                "metronic.js",
                "layout.js",
                "quick-sidebar.js",
                "demo.js",
                "jquery.blockui.min.js",
                "jquery.cokie.min.js",
                "jquery.pulsate.min.js",
                "jquery.sparkline.min.js",
                "tkd_script.js",
                "toastr.min.js",
                "ui-alert-dialog-api.js",
            );
        } else {

            // redirect to home with redirect url
            $this->front_login_redirect_check("userdata");

            // check session
            $this->userid = 0;
            $this->user_type = 0;
            $this->signup_info = array();
            $this->plaid = NULL;
            $this->plaid_token = NULL;

            if (isset($this->session->userdata['userdata'])) {
                //
                $this->userid = intval((isset($this->session->userdata['userdata']['signup_id'])) ? $this->session->userdata['userdata']['signup_id'] : $this->session->userdata['userdata']['kid_id']);
                $this->user_info = $this->session->userdata['userdata'];

                // important to get company columns
                $this->user_data = $this->model_signup->find_one_active(
                    array(
                        'where' => array(
                            'signup_id' => $this->userid,
                            'signup_isdeleted' => STATUS_INACTIVE
                        ),
                        'joins' => array(
                            0 => array(
                                'table' => 'signup_company',
                                'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                                'type'  => 'left'
                            )
                        )
                    )
                );

                //
                $this->signup_info = $this->model_signup_info->find_one_active(
                    array(
                        'where' => array(
                            'signup_info_signup_id' => $this->userid
                        )
                    )
                );

                //
                if (
                    isset($this->user_data['signup_is_phone_confirmed']) &&
                    isset($this->user_data['signup_is_confirmed']) &&
                    (
                        // skip these controller classes
                        (
                            (
                                $this->router->directory == "dashboard/") &&
                            !in_array(
                                $this->router->class,
                                ['profile', 'custom']
                            )
                        ) ||
                        (
                            (
                                $this->router->directory != "dashboard/") &&
                            !in_array($this->router->class, ['signup', 'login', 'home', 'verification', 'user', 'contact', 'about', 'membership', 'v1', 'terms_and_conditions', 'privacy', 'donation'])
                        )
                    ) &&
                    !$this->model_signup->hasRole(ROLE_0) &&
                    (
                        (
                            !$this->user_data['signup_is_phone_confirmed'] &&
                            $this->getConfigValueByVariable('phone_verification') &&
                            ($this->userid && !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_PHONE, TRUE))
                        ) ||
                        (
                            !$this->user_data['signup_is_confirmed'] &&
                            $this->getConfigValueByVariable('email_confirmation')) &&
                        (!$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_EMAIL, TRUE)
                        )
                    )
                ) {
                    redirect(l('verification/profile'));
                }

                $signup_plaid_token = isset($this->user_data['signup_plaid_token']) && $this->user_data['signup_plaid_token'] ? $this->user_data['signup_plaid_token'] : '';
                if ($signup_plaid_token) {
                    $this->plaid = new Plaid(
                        PLAID_CLIENT_ID,
                        PLAID_CLIENT_SECRET,
                        PLAID_ENVIRONMENT
                    );
                    try {
                        $this->plaid_token = JWT::decode($signup_plaid_token, CI_ENCRYPTION_SECRET);
                    } catch (\Exception $e) {
                        log_message('ERROR', $e->getMessage());
                    }
                }

                // set user active - update session expiry time
                if ($this->userid > 0 && isset($this->signup_info['signup_info_isonline']) && $this->signup_info['signup_info_isonline'] == 0) {
                    $this->model_signup_info->setSignupOnline($this->userid, TRUE);
                }

                // set user inactive
                if (isset($this->signup_info['signup_info_isonline']) && $this->userid > 0 && $this->signup_info['signup_info_isonline'] == 1 && (date("Y-m-d H:i:s") > $this->signup_info['signup_info_session_expiry'])) {
                    $this->model_signup_info->setSignupOffline($this->userid, true);
                    redirect($config['base_url_other'] . '/logout');
                }

                // instantiateQuickbookInstance
                if ($this->dataService == NULL) {
                    $this->instantiateQuickbookInstance();
                }

                //
                if (
                    $this->session->has_userdata('paypalAccessTokenResponse') && isset($this->session->userdata['paypalAccessTokenResponse']['status']) && $this->session->userdata['paypalAccessTokenResponse']['status']
                    && $this->session->has_userdata('paypalAccessTokenExpiry') && $this->session->userdata['paypalAccessTokenExpiry'] > strtotime(date('Y-m-d H:i:s'))
                ) {
                    //
                    $decoded_payload = json_decode($this->session->userdata['paypalAccessTokenResponse']['response']);
                    $this->paypalAccessToken = $decoded_payload->access_token;
                } else {
                    //
                    $this->session->set_userdata('paypalAccessTokenResponse', $this->generateAccessToken());
                    //
                    $decoded_payload = json_decode($this->session->userdata['paypalAccessTokenResponse']['response']);
                    if ($decoded_payload && property_exists($decoded_payload, 'expires_in') && property_exists($decoded_payload, 'access_token')) {
                        $this->session->set_userdata('paypalAccessTokenExpiry', strtotime(date('Y-m-d H:i:s', strtotime('+' . $decoded_payload->expires_in . ' ' . 'seconds'))));
                        $this->paypalAccessToken = $decoded_payload->access_token;
                    }
                }
            }

            // FRONT END>..
            // Autoloads specific to FRONT END;

            // FOR FRONTEND
            $this->is_admin = FALSE;
            $this->view_pre = "";
            $is_dashboard = FALSE;

            // For dashboard
            if ($this->router->directory == "dashboard/" || $this->router->directory == "dashboard/account/" || $this->router->directory == "dashboard/payment/") {
                $page_layout = "front_dashboard";
                $this->layout = $page_layout;
                // $this->view_pre = "dashboard/" . $this->router->class . "/";
                $this->view_pre = $this->router->directory . $this->router->class . "/";
                $is_dashboard = TRUE;
            } // For front end other pages
            else {
                $page_layout = "front_main";
                $this->layout = $page_layout;
                $this->view_pre = $this->router->class . "/";
            }

            $title = $config['title'];
            $meta_data = array(
                "keywords" => $title,
                "description" => "$title",
                "viewport" => "width=device-width, initial-scale=1, maximum-scale=1",
                "google-site-verification" => "",
            );

            // For dashboard
            if ($is_dashboard) {

                $this->layout_data['css_files'] = array(
                    "../dashboard/css/animate.css",
                    "../dashboard/slick/slick-theme.css",
                    "../dashboard/slick/slick.css",
                    "../dashboard/css/bootstrap.css",
                    "../dashboard/css/fancybox.css",
                    "../dashboard/css/custom.css",
                    "../dashboard/css/toastr.min.css",
                    "../dashboard/css/loader.css",
                    "../dashboard/css/full-calender.css",
                    "../dashboard/emoji-picker-main/lib/css/emoji.css",
                    "../common.css",
                    "../dashboard/css/dataTables.bootstrap5.min.css",
                    "jquery-ui.min.css",
                    "../dashboard/css/bootstrap-toggle.min.css",
                    "../dashboard/css/bootstrap-tagsinput.css",
                    "../dashboard/css/font-awesome.min.css",
                    "jquery-confirm.min.css",
                );

                $this->layout_data['js_files_init'] = array(
                    "../dashboard/js/jquery-3.6.0.min.js",
                );
                $this->layout_data['js_files'] = array(
                    "../dashboard/js/wow.js",
                    "../dashboard/slick/slick.js",
                    "../dashboard/slick/slick.min.js",
                    "../dashboard/js/jquery.slicknav.js",
                    "../dashboard/js/fancybox.js",
                    "../dashboard/js/bootstrap.js",
                    "../dashboard/js/font.js",
                    "../dashboard/js/custom.js",
                    "../dashboard/js/toastr.js",
                    "../dashboard/js/jquery.multi-select.js",
                    "../dashboard/js/full-calender.js",
                    "../dashboard/emoji-picker-main/lib/js/config.min.js",
                    "../dashboard/emoji-picker-main/lib/js/util.min.js",
                    "../dashboard/emoji-picker-main/lib/js/jquery.emojiarea.min.js",
                    "../dashboard/emoji-picker-main/lib/js/emoji-picker.min.js",
                    "../common.js",
                    "../dashboard/js/ckeditor.min.js",
                    "popper.min.js",
                    "../dashboard/js/waves.min.js",
                    "../dashboard/js/jquery.datatables.min.js",
                    "../dashboard/js/dataTables.bootstrap5.min.js",
                    "sweetalert.min.js",
                    "../dashboard/js/jquery-ui.min.js",
                    "../dashboard/js/bootstrap-tagsinput.min.js",
                    "../dashboard/js/Chart.min.js",
                    "../dashboard/js/jquery.mask.min.js",
                    "lazyload.min.js",
                    "../dashboard/js/bootstrap-toggle.min.js",
                    "jquery-confirm.min.js",
                    "../front_global/js/custom.js",
                    "../dashboard/js/moment.min.js",
                    "../dashboard/js/bootstrap.bundle.min.js",
                );

                $this->register_plugins(array('fancybox'));
                $this->register_plugins(array('slick'));
            } // For front end other pages
            else {
                
                $this->layout_data['css_files'] = array(
                    "all.min.css",
                    "bootstrap.css",
                    "animate.min.css",
                    "slick/slick.css",
                    "slick/slick-theme.css",
                    "slicknav.css",
                    "fancybox.css",
                    "custom.css",
                    "toastr.min.css",
                    "loader.css",
                    "../common.css",
                    "aos.css",
                    "jquery.fancybox.min.css",
                    "jquery-ui.min.css",
                    "jquery-ui.smoothness.min.css",
                    "all.css",
                    "jquery-confirm.min.css"
                );

                $this->layout_data['js_files_init'] = array(
                    "jquery-3.6.0.min.js",
                );
                $this->layout_data['js_files'] = array(
                    "wow.js",
                    "popper.min.js",
                    "bootstrap.js",
                    "slick/slick.js",
                    "slick/slick.min.js",
                    "jquery.slicknav.js",
                    "fancybox.js",
                    "font.js",
                    "toastr.js",
                    // "tkd_script.js",
                    "custom.js",
                    "jquery-ui.min.js",
                    "jquery.lazy.js",
                    "jquery.maskedinput.js",
                    "dom-to-image.min.js",
                    "../common.js",
                    "aos.min.js",
                    "jquery.fancybox.min.js",
                    "sweetalert.min.js",
                    "jquery.mask.min.js",
                    "lazyload.min.js",
                    "jquery-confirm.min.js",
                    "../front_global/js/custom.js",
                    "acctoolbar.min.js"
                );
            }

            /** Get social media **/
            $this->layout_data['config_info'] = $config['db'];

            /** Get Logo **/
            $this->layout_data['logo'] = $this->model_logo->find_one(
                array('where' => array('logo_status' => 1))
            );

            $title = (isset($cms_page['meta_title']) && $cms_page['meta_title']) ? $cms_page['meta_title'] : $title;
            $meta_data['keywords'] = (isset($cms_page['meta_keyword']) && $cms_page['meta_keyword']) ? $cms_page['meta_keyword'] : $meta_data['keywords'];
            $meta_data['description'] = (isset($cms_page['meta_description']) && $cms_page['meta_description']) ? $cms_page['meta_description'] : $meta_data['description'];

            // Save Agent
            $this->save_user_agent();
        }

        if (isset($menu))
            $this->layout_data['menu'] = $menu;

        $this->layout_data['title'] = $title;
        $this->layout_data['meta_data'] = $meta_data;
        $this->admin_path = $this->view_pre;
        $this->admin_current = $this->view_pre . $config['ci_method'] . "/";

        $this->layout_data['config'] = $config;

        $request = $this->router->class . '/' . $this->router->method;
        $this->layout_data['request_uri'] = $request;

        // Set class name and method
        $this->layout_data['class_name'] = $this->router->class;
        $this->layout_data['method_name'] = $this->router->method;
    }

    /**
     * Method curlRequest
     *
     * @param string $url
     * @param array $headers - header data array
     * @param array $post_fields - post data array
     * @param boolean $is_post - is request a post request
     * @param boolean $is_custom_request - is request a custom request
     * @param string $custom_request_type - [patch, delete]
     * @param boolean $build_post_query
     * @param string $user_pwd
     *
     * @return ?string - (encoded)
     */
    protected function curlRequest(string $url, array $headers, array $post_fields = array(), bool $is_post = FALSE, bool $is_custom_request = FALSE, string $custom_request_type = '', $build_post_query = FALSE, $user_pwd = ''): ?string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($user_pwd) {
            curl_setopt($ch, CURLOPT_USERPWD, $user_pwd);
        }
        if ($is_custom_request) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom_request_type);
        }
        if (!empty($post_fields)) {
            if ($build_post_query) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
            }
        }
        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, $is_post);
        }
        $response = curl_exec($ch);
        $err = curl_error($ch);

        //
        $this->session->set_userdata('last_http_status', curl_getinfo($ch, CURLINFO_HTTP_CODE));
        log_message('error', 'URL: ' . $url . ' - last_http_status: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE));

        curl_close($ch);

        if ($err) {
            log_message('error', "cURL Error #:" . $err);
            //
            $this->_log_message(
                LOG_TYPE_GENERAL,
                LOG_SOURCE_CURL,
                LOG_LEVEL_ERROR,
                $err,
                ''
            );
            return NULL;
        } else {
            return $response;
        }
    }

    /* ======================== ZOOM START ======================== */

    /**
     * Method getZoomBearerHeader - zoom
     *
     * @return array
     */
    public function getZoomBearerHeader(): array
    {
        $headers = array();
        if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
            if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) > (strtotime(date('Y-m-d H:i:s')))) {
                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->getConfigValue(ZOOM_CONFIG_ACCESS_TOKEN)
                );
            }
        }
        return $headers;
    }

    /**
     * Method getZoomBasicHeader - zoom
     *
     * @return array
     */
    public function getZoomBasicHeader(): array
    {
        $headers = [
            'Authorization: Basic ' . JWT::urlsafeB64Encode(ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET),
            'content-type: application/x-www-form-urlencoded',
        ];
        return $headers;
    }

    /**
     * Method refresh_zoom_access_token - zoom
     *
     * @param array $headers
     *
     * @return string
     */
    public function refreshZoomAccessToken(array $headers): ?string
    {
        $post_fields = array();
        if ($this->getConfigValue(ZOOM_CONFIG_REFRESH_TOKEN)) {

            $post_fields = array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->getConfigValue(ZOOM_CONFIG_REFRESH_TOKEN),
            );

            // refresh access token
            return $this->curlRequest(ZOOM_OAUTH_TOKEN_URL . '?' . http_build_query($post_fields), $headers, [], TRUE);
        }
        return NULL;
    }

    /**
     * Method setZoomConfigValue - zoom - cron - see class: oauth2 - not used
     *
     * @return void
     */
    public function updateZoomConfigValue(): bool
    {
        $response = $this->refreshZoomAccessToken($this->getZoomBasicHeader());
        $decoded_response = json_decode($response);
        $configArray = array();

        if ($decoded_response && property_exists($decoded_response, 'access_token') && property_exists($decoded_response, 'refresh_token')) {
            try {
                $configArray = array(
                    ZOOM_CONFIG_ACCESS_TOKEN => $decoded_response->access_token,
                    ZOOM_CONFIG_REFRESH_TOKEN => $decoded_response->refresh_token,
                    ZOOM_CONFIG_TOKEN_EXPIRY => ''
                );

                if (property_exists($decoded_response, 'expires_in') && $decoded_response->expires_in) {
                    $configArray[ZOOM_CONFIG_TOKEN_EXPIRY] = date('Y-m-d H:i:s', strtotime('+' . $decoded_response->expires_in . ' seconds'));
                }

                // update config value
                $updated = $this->model_config->update_config($configArray);
            } catch (\Exception $e) {
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_ZOOM_CRON,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
            }
            if ($updated) {
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_ZOOM_CRON,
                    LOG_LEVEL_INFO,
                    'Zoom config has been updated',
                    ''
                );
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Method getZoomMeeting
     *
     * @return ?string
     */
    public function getZoomMeeting($meetingId): ?string
    {
        $response = NULL;
        if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
            if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) > (strtotime(date('Y-m-d H:i:s')))) {

                $headers = $this->getZoomBearerHeader();
                $url = str_replace('{meetingId}', $meetingId, ZOOM_MEETING_URL);
                $response = $this->curlRequest($url, $headers);
            } else {
                $this->updateZoomConfigValue();
                return $this->getZoomMeeting($meetingId);
            }
        }

        return $response;
    }

    /**
     * Method getZoomUsers
     *
     * @return ?string
     */
    public function getZoomUsers(): ?string
    {
        $response = NULL;
        if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
            if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) > (strtotime(date('Y-m-d H:i:s')))) {
                $headers = $this->getZoomBearerHeader();
                $response = $this->curlRequest(ZOOM_GET_USERS_URL, $headers);
            }
        }

        return $response;
    }

    /**
     * Method getZoomMeetingRecording
     *
     * @param string $meetingId
     *
     * @return ?string
     */
    public function getZoomMeetingRecording(string $meetingId = ''): ?string
    {
        $response = NULL;
        if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
            $headers = $this->getZoomBearerHeader();
            $url = str_replace('{meetingId}', $meetingId, ZOOM_GET_MEETING_RECORDING);
            if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) > (strtotime(date('Y-m-d H:i:s')))) {
                $response = $this->curlRequest($url, $headers);
            } else {
                $this->updateZoomConfigValue();
                $response = $this->curlRequest($url, $headers);
            }
        }

        return $response;
    }

    /* ======================== ZOOM END ======================== */

    /* ======================== CONFIG START ======================== */

    /**
     * Method getConfigValue
     *
     * @param int $config_id
     *
     * @return ?string
     */
    public function getConfigValue($config_id = 0): ?string
    {
        if ($config_id) {
            $config_detail = $this->model_config->find_by_pk($config_id);
            if (!empty($config_detail) && isset($config_detail['config_value'])) {
                return $config_detail['config_value'];
            }
            return NULL;
        }
        return NULL;
    }

    /**
     * Method getConfigValueByVariable
     *
     * @param string $config_variable
     *
     * @return ?string
     */
    public function getConfigValueByVariable($config_variable = ''): ?string
    {
        if ($config_variable) {
            $config_detail = $this->model_config->find_one_active(
                array(
                    'where' => array(
                        'config_variable' => $config_variable
                    )
                )
            );
            if (!empty($config_detail) && isset($config_detail['config_value'])) {
                return $config_detail['config_value'];
            }
            return NULL;
        }
        return NULL;
    }

    /* ======================== CONFIG END ======================== */

    /* ======================== QUICKBOOKS START ======================== */

    /**
     * Method instantiateQuickbookInstance - quickbook
     *
     * @return object
     */
    public function instantiateQuickbookInstance(): object
    {
        // Create Quickbook SDK instance
        $instance = DataService::Configure(array(
            'auth_mode' => QUICKBOOK_AUTH_MODE,
            'ClientID' => QUICKBOOK_CLIENT_ID,
            'ClientSecret' =>  QUICKBOOK_SECRET,
            'RedirectURI' => QUICKBOOK_REDIRECT_URL,
            'scope' => QUICKBOOK_OAUTH_SCOPE,
            'baseUrl' => QUICKBOOK_BASEURL ? QUICKBOOK_BASEURL : 'development'
        ));

        return $instance;
    }

    /**
     * Method getEntityById - quickbook
     *
     * @param string $entity
     * @param int $id
     *
     * @return ?object
     */
    function getEntityById($entity = NULL, int $id = 0): ?object
    {
        $entityData = NULL;
        if ($id && $entity && ($entity && in_array($entity, QUICKBOOK_ENTITY_TYPE))) {
            $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);
            $entityData = $this->dataService->FindbyId($entity, $id);
        }
        return $entityData;
    }

    /**
     * Method getEntity - quickbook
     *
     * @param string $entity
     * @param int $per_page
     *
     * @return array
     */
    function getEntity($entity = NULL, $per_page = PER_PAGE): ?array
    {
        $entityData = NULL;
        if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) && $entity && ($entity && in_array($entity, QUICKBOOK_ENTITY_TYPE))) {
            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
                    $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);
                    $entityData = $this->dataService->FindAll($entity, 0, $per_page);

                    $error = $this->dataService->getLastError();
                    if ($error) {
                        log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());
                    }
                }
            }
        }
        return ($entityData);
    }

    /* ======================== QUICKBOOKS END ======================== */

    /* ======================== VOUCHED START ======================== */

    /**
     * Method getVouchedJob
     *
     * @return ?object
     */
    public function getVouchedJob(): ?object
    {
        $jobData = NULL;
        if ($this->userid > 0 && $this->user_data['signup_vouched_response']) {
            $jobData = json_decode($this->user_data['signup_vouched_response']);
        }
        return $jobData;
    }

    /**
     * Method getVouchedJobVar - $this->getVouchedJobVar('id');
     *
     * @return void
     */
    public function getVouchedJobVar($var = '')
    {
        $jobData = $this->getVouchedJob();
        if ($jobData && property_exists($jobData, $var)) {
            return $jobData->{$var};
        }
        return NULL;
    }

    /* ======================== VOUCHED END ======================== */

    /* ======================== HELPER FUNCTION ======================== */

    /**
     * Method hasRole
     *
     * @param int $role
     * @param array $user_data
     *
     * @return void
     */
    public function hasRole(int $role, array $user_data = NULL)
    {
        $user_data = $user_data ?? $this->user_data;

        if ($this->userid == 0 || !isset($user_data['signup_type'])) {
            return false;
        }
        return (((int) $user_data['signup_type'] === (int) $role) && $user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE);
    }

    /**
     * Method _log_message
     *
     * @param string $log_type
     * @param string $log_source
     * @param string $log_level
     * @param string $log_message
     * @param string $log_text
     *
     * @return int
     */
    public function _log_message(string $log_type = 'ERROR', string $log_source = '', string $log_level = '', string $log_message = '', string $log_text = ''): int
    {
        return $this->model_log->insert_record(
            array(
                'log_type' => $log_type,
                'log_source' => $log_source,
                'log_level' => $log_level,
                'log_message' => $log_message,
                'log_text' => $log_text,
            )
        );
    }

    /* ======================== HELPER FUNCTION ======================== */

    /* ======================== IMPORTANT FUNCTION ======================== */

    /**
     * method login_redirect_check - Redirect If not logged in.
     *
     * @param mixed $session
     * @param bool $is_admin
     *
     * @return void
     */
    public function login_redirect_check($session = "", $is_admin = "")
    {
        global $config;
        $class = $this->router->class;
        $login_session = $this->session->userdata($session);
        if (!in_array($class, array('login', 'register'))) {

            $redirect_url = $config['base_url'] . $this->uri->uri_string;
            if ((!$login_session) && ($class != 'logout')) {
                redirect("/admin/login?redirect_url=" . urlencode($redirect_url));
                exit();
            } elseif ($is_admin && !$login_session[$is_admin]) {
                redirect("/admin/login");
                exit();
            }
        }
    }

    /**
     * Redirect If not logged in. for frontend
     *
     * @param string $session
     *
     * @return void
     */
    public function front_login_redirect_check($session = "userdata")
    {
        global $config;

        $class = $this->router->class;
        $login_session = $this->session->userdata($session);
        $directory = ($this->router->directory);

        if (in_array($directory, array("dashboard/", "dashboard/account/", "dashboard/payment/"))) {

            $referer = '';

            if(isset($_SERVER['HTTP_REFERER'])) {
                if (ENVIRONMENT == "development") {
                    $referer = explode('http://', $_SERVER['HTTP_REFERER'])[1];
                } elseif (ENVIRONMENT == "testing") {
                    $referer = explode('https://', $_SERVER['HTTP_REFERER'])[1];
                } elseif (ENVIRONMENT == "production") {
                    $referer = explode('https://', $_SERVER['HTTP_REFERER'])[1];
                }
            }

            if(str_contains($referer, base_url())) {
                $errorMessage = 'You need to log in to perform this action.';
            } else {
                $errorMessage = 'Your session has expired, please login again.';
            }

            $redirect_url = $config['base_url'] . $this->uri->uri_string;

            if ((!$login_session) && ($class != 'logout')) {
                if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    echo json_encode(['status' => STATUS_FALSE, 'txt' => $errorMessage]);
                } else {
                    $this->session->set_flashdata('error', __($errorMessage));
                    redirect("/login?redirect_url=" . urlencode($redirect_url));
                }
                exit();
            }
        }
    }

    /**
     * Method load_view - Load View for Template
     *
     * @param string $view_file - mst exist within class folder inside view(admin/product/view_file.php). If not , will search in default folder. Elese throws error
     * @param array $view_data
     * @param bool render - Render output.
     * @param bool use_template  Render template
     *
     * @return ?mixed
     */
    public function load_view($view_file, $view_data = array(), $render = false, $use_template = true)
    {
        global $config;

        $view = $this->view_pre . $view_file;
        $view = view_exists($view, $this->router->class);

        //adding layout data array
        $view_data['layout_data'] = $this->layout_data;
        $view_data['cms_content'] = isset($this->layout_data['cms_content']) ? $this->layout_data['cms_content'] : array();
        $view_data['session_data'] = $this->session->userdata('logged_in');

        // adding layout data array
        if ($use_template) {
            $this->layout_data['content_block'] = $this->load->view($view, $view_data, true);
            // Load Layout
            $this->load->view("_layout/" . $this->layout, $this->layout_data);
        } else {
            return $this->load->view($view, $view_data, $render);
        }
    }

    /* ======================== IMPORTANT FUNCTION ======================== */

    /* ======================== CUSTOM VALIDATION FUNCTION ======================== */

    // Validations ----- callback_is_slug
    public function is_slug($str, $attr)
    {
        $match = preg_match('/^([a-zA-Z0-9\-_]+)$/', $str);
        if (!$match) {
            $this->form_validation->set_message('is_slug', 'The field can only contain alphanums and "-" and "_"');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // Validations ----- callback_alpha_space
    public function alpha_space($str)
    {
        return !preg_match('/^[a-z .,\-]+$/i', $str) ? false : true;
    }

    // Validations ----- callback_unique_email
    public function unique_email($str)
    {
        // list($email, $type) = explode('||', $str);
        $param = array();
        $param['where']['signup_email'] = $str;
        // $param['where']['signup_type'] = $type;
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        if (empty($this->model_signup->find_one_active($param))) {
            return true;
        } else {
            return false;
        }
    }

    // Validations ----- callback_unique_phone
    public function unique_phone(string $str): bool
    {
        $param = array();
        $param['where']['signup_phone'] = $str;
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        if (empty($this->model_signup->find_one_active($param))) {
            return true;
        } else {
            return false;
        }
    }

    // Validations ----- callback_unique_phone
    public function bcrypt($str)
    {
        return password_hash($str, PASSWORD_BCRYPT);
    }

    /* ======================== CUSTOM VALIDATION FUNCTION ======================== */

    /* ======================== OTHER FUNCTION ======================== */

    /**
     * Method save_user_agent - Only for Home page
     *
     * @return void
     */
    private function save_user_agent()
    {
        $method = $this->router->fetch_method();
        $class  = $this->router->fetch_class();
        $type = '';
        $agent = '';
        if (($this->router->directory == '') && ($class == 'home') && ($method == 'index')) {
            if ($this->agent->is_mobile()) {
                $type = "mobile";
                $agent = $this->agent->mobile();
            } elseif ($this->agent->is_browser()) {
                $type = "desktop";
                $agent = $this->agent->browser();
            }

            $data = array(
                'agt_name' => $agent,
                'agt_type' => $type,
                'agt_status' => STATUS_ACTIVE
            );
            $this->model_agent->set_attributes($data);
            $this->model_agent->save();
        }
    }

    /**
     * Method get_site_information
     *
     * @param array $config_info
     *
     * @return void
     */
    public function get_site_information($config_info)
    {
        $config_value = array();
        if (count($config_info) > 0) {
            foreach ($config_info as $key => $value) {
                $config_value[$value['config_variable']][] = $value;
            }
        }
        return $config_value;
    }

    /**
     * Method chk_currency - Set Currency setup for config
     *
     * @return void
     */
    public function chk_currency()
    {
        global $config;
        $currency_conf = $this->session->userdata('currency');
        if ($currency_conf) {
            $config['currency'] = $currency_conf['currency'] ? $currency_conf['currency'] : $config['currency'];
            $config['currency_rate'] = $currency_conf['currency_rate'] ? $currency_conf['currency_rate'] : $config['currency_rate'];
        }
    }

    /**
     * Method add_script - Adds Script
     * @param file (mixed)        File name/ Relevant to CSS/JS folder
     * @param filetype    js OR css
     *
     * @return void
     */
    public function add_script($files = [], $file_type = "css")
    {
        $file_type .= '_files';
        // If array is passed, push all
        if (array_filled($files)) {
            foreach ($files as $file)
                $this->layout_data[$file_type][] = $file;
        } // Else if single file is pass, push it in
        elseif ($files)
            $this->layout_data[$file_type][] = $files;
        else return "empty";
    }

    /**
     * Method set_meta
     *
     * @param array $meta_data
     *
     * @return void
     */
    public function set_meta($meta_data = '')
    {
        // If array is passed, push all
        if (array_filled($meta_data)) {
            $this->layout_data['meta_data'] = $this->layout_data['meta_data'] + $meta_data;
        }
    }

    /**
     * Method register_plugins - Register Plugins
     *
     * @param   file (mixed)        File name/ Relevant to CSS/JS folder
     * @param   filetype    js OR css
     *
     * @return void
     */
    public function register_plugins($plugins = [])
    {
        // If array is passed, push all
        if (array_filled($plugins)) {
            foreach ($plugins as $plg)
                $this->layout_data['additional_tools'][$plg] = $plg;
        } // Else if single file is pass, push it in
        elseif ($plugins)
            $this->layout_data['additional_tools'][$plugins] = $plugins;
        else false;
    }

    /**
     * Method UN-REGISTER Plugins
     *
     * @param   file (mixed)        File name/ Relevant to CSS/JS folder
     * @param   filetype    js OR css
     *
     * @return void
     */
    public function unregister_plugins($plugins = [])
    {
        // If array is passed, push all
        if (array_filled($plugins)) {
            foreach ($plugins as $plg)
                unset($this->layout_data['additional_tools'][$plg]);
        } // Else if single file is pass, push it in
        elseif ($plugins)
            unset($this->layout_data['additional_tools'][$plugins]);
        else false;
    }

    /**
     * Method set_time_zone
     * Sets Default Php timezone for Projects
     * $dit PHP_TIME_ZONE constaint from tkd_config.php
     *
     * @return void
     */
    private function set_time_zone()
    {
        if (PHP_TIME_ZONE)
            date_default_timezone_set(PHP_TIME_ZONE);
    }

    /**
     * Method validate
     *
     * @param class $model
     * @param array $custom_rules
     *
     * @return void
     */
    public function validate($model, $custom_rules = array())
    {
        $rules = $this->$model->get_rules();
        // Append custom rules if has
        if (array_filled($custom_rules)) {
            foreach ($custom_rules as $key => $value) :
                $rules[$key]['field'] = $value['field'];
                $rules[$key]['label'] = $value['label'];
                $rules[$key]['rules'] = $value['rules'];
            endforeach;
        }
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters("<span for=\"%s\" style='color:#fff' class=\"has-error help-block\">", '</span><br/>');

        return $this->form_validation->run();
    }

    /**
     * Method custom_validate
     *
     * @param class $model
     * @param array $fields
     *
     * @return void
     */
    public function custom_validate($model)
    {
        $rules = $this->$model->get_rules();
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters("<span for=\"%s\" style='color:#fff' class=\"has-error help-block\">", '</span><br/>');

        return $this->form_validation->run();
    }

    /**
     * Method bulk_validate
     *
     * @param array $models
     *
     * @return void
     */
    public function bulk_validate($models)
    {
        if (array_filled($models)) {
            foreach ($models as $model) {
                if ($this->validate($model) !== true)
                    return false;
            }
            return true;
        }
    }

    /* ======================== OTHER FUNCTION ======================== */

    /* ======================== JWT ======================== */

    function jwt_decode($jwt, $key = '', $verify = TRUE, $return_header = FALSE)
    {
        return JWT::decode($jwt, $key, $verify, $return_header);
    }

    function jwt_encode($payload)
    {
        return JWT::encode($payload);
    }

    /* ======================== JWT ======================== */

    /**
     * Method getInfo
     *
     * @param string $type
     * @param bool $create_user
     * @param string $transfer_intent_id
     * @param bool $debug
     *
     * @return ?object
     */
    public function getInfo(string $type = '', $transfer_intent_id = '', $debug = FALSE): ?object
    {
        $data = NULL;
        if ($type && in_array($type, ['transfer'])) {
            if ($this->userid > 0) {
                $headers = array();
                $headers[] = 'Content-Type: application/json';

                $postArray = array(
                    "client_id" => PLAID_CLIENT_ID,
                    "secret" => PLAID_CLIENT_SECRET,
                );

                $url = '';
                switch ($type) {
                    case 'bank':
                        $url = PLAID_API_URL . PLAID_GET_BANK_INCOME;
                        break;
                    case 'payroll':
                        $url = PLAID_API_URL . PLAID_GET_PAYROLL_INCOME;
                        break;
                    case 'employment':
                        $url = PLAID_API_URL . PLAID_GET_EMPLOYMENT;
                        break;
                    case 'session':
                        $url = PLAID_API_URL . PLAID_GET_SESSION;
                        break;
                    case 'transfer':
                        $postArray['transfer_intent_id'] = $transfer_intent_id;
                        $url = PLAID_API_URL . PLAID_GET_TRANSFER;
                        break;
                }

                $response = $this->curlRequest($url, $headers, $postArray, TRUE);
                $decoded_response = json_decode($response);

                if ($decoded_response) {
                    $data = $decoded_response;
                } else {
                    //
                    $this->_log_message(
                        LOG_TYPE_API,
                        LOG_SOURCE_PLAID,
                        LOG_LEVEL_ERROR,
                        property_exists($decoded_response, 'error_message') ? $decoded_response->error_message : ERROR_MESSAGE,
                        ''
                    );
                    log_message('error', property_exists($decoded_response, 'error_message') ? $decoded_response->error_message : ERROR_MESSAGE);
                }
            }
        }
        if ($debug) {
            debug($data);
        }
        return $data;
    }

    /* ======================== STRIPE ======================== */

    /**
     * Method resource - get stripe resource
     * usage example:
     * http://localhost/azaverze/stripe/resource/customers/cus_OGHPxGAXkojNkx
     * http://localhost/azaverze/stripe/resource/invoices/in_1NTkr8ASwfulAoL3rW5CfmDJ
     *
     * @param string $resourceType
     * @param string $resourceId
     * @param bool $debug
     *
     * @return object
     */
    function resource(string $resourceType = '', string $resourceId = '', bool $debug = FALSE): ?object
    {
        $resourceDetail = NULL;
        if($resourceType) {
            try {
                $resourceDetail = $this->stripe->{$resourceType}->retrieve(
                    $resourceId,
                    []
                );
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
            }

            // if ($debug) {
            //     echo '<pre>';
            //     print_r($resourceDetail);
            //     echo '</pre>';
            // }
        }
        return $resourceDetail;
    }

    /* ======================== STRIPE ======================== */

    /* ======================== ESCROW ======================== */

    /**
     * Method getEscrowCustomer
     *
     * @param array $signup
     *
     * @return void
     */
    function getEscrowCustomer($signup = array()): ?int
    {
        if (!empty($signup)) {
            $url = ESCROW_BASE_URL . ESCROW_CUSTOMER;
            $headers = array('Content-Type: application/json');
            $user_pwd = ESCROW_EMAIL . ':' . ESCROW_API_KEY;

            if ($signup['signup_escrow_id']) {
                $url .= '/' . $signup['signup_escrow_id'];
                $response = $this->curlRequest($url, $headers, [], FALSE, FALSE, '', FALSE, $user_pwd);
                $decoded_json = json_decode($response);
                if ($decoded_json->error) {
                    //
                    log_message('ERROR', $decoded_json->error);
                } else if ($decoded_json->id) {
                    return $decoded_json->id;
                }
            } else {
                $post_fields = array(
                    "email" => $signup['signup_email'],
                );

                $response = $this->curlRequest($url, $headers, $post_fields, TRUE, FALSE, '', FALSE, $user_pwd);
                $decoded_json = json_decode($response);

                if ($decoded_json->error) {
                    //
                    log_message('ERROR', $decoded_json->error);
                } else if ($decoded_json->id) {
                    $this->model_signup->update_by_pk(
                        $this->userid,
                        array(
                            'signup_escrow_id' => $decoded_json->id
                        )
                    );
                    return $decoded_json->id;
                }
            }
        }
        return NULL;
    }

    /* ======================== ESCROW ======================== */

    /* ======================== STRIPE LOG ======================== */

    /**
     * Method saveStripeLog - for webhook differentiation ease
     *
     * @param int $signup_id
     * @param string $reference
     * @param int $reference_id
     * @param string $resource_type
     * @param string $resource_id
     * @param string $resource_response
     *
     * @return int
     */
    function saveStripeLog(int $signup_id = 0, string $reference = STRIPE_LOG_REFERENCE_SIGNUP, int $reference_id = 0, string $resource_type = '', string $resource_id = '', string $resource_response = ''): int
    {
        $reference_detail = array();
        $signup_id = $signup_id ? $signup_id : $this->userid;

        if ($reference_id) {
            switch ($reference) {
                case STRIPE_LOG_REFERENCE_JOB:
                    $reference_detail = $this->model_job->find_by_pk($reference_id);
                    break;
                case STRIPE_LOG_REFERENCE_SIGNUP:
                    $reference_detail = $this->model_signup->find_by_pk($reference_id);
                    break;
                case STRIPE_LOG_REFERENCE_TECHNOLOGY:
                    $reference_detail = $this->model_product->find_by_pk($reference_id);
                    break;
                default:
                    return 0;
            }

            if (empty($reference_detail)) {
                return 0;
            }
        } else {
            return 0;
        }
        return $this->model_stripe_log->insert_record(
            array(
                'stripe_log_signup_id' => $signup_id,
                'stripe_log_reference' => $reference,
                'stripe_log_reference_id' => $reference_id,
                'stripe_log_resource_type' => $resource_type,
                'stripe_log_resource_id' => $resource_id,
                'stripe_log_resource_response' => $resource_response,
            )
        );
    }

    /**
     * Method getStripeLog
     *
     * @param array $where_param
     *
     * @return array
     */
    function getStripeLog(array $where_param = array()): ?array
    {
        return $this->model_stripe_log->find_one_active(
            array(
                'where' => $where_param
            )
        );
    }

    /* ======================== STRIPE LOG ======================== */

    /* ======================== Box ======================== */

    /**
     * Method refreshBoxAccessToken
     *
     * @param array $headers
     *
     * @return ?string
     */
    public function refreshBoxAccessToken(string $refresh_token): ?string
    {
        $headers = [
            'content-type: application/x-www-form-urlencoded',
        ];

        $post_fields = array();
        if ($refresh_token) {

            $post_fields = array(
                'client_id' => BOX_CLIENT_ID,
                'client_secret' => BOX_CLIENT_SECRET,
                'refresh_token' => $refresh_token,
                'grant_type' => 'refresh_token',
            );

            // refresh access token
            return $this->curlRequest(BOX_OAUTH_TOKEN_URL, $headers, $post_fields, TRUE, FALSE, '', TRUE);
        }
        return NULL;
    }

    /**
     * Method setBoxSession
     *
     * @param object $decoded_response
     *
     * @return bool
     */
    public function setBoxSession(object $decoded_response, bool $is_cron = FALSE): bool
    {
        $updated = 0;
        try {
            $this->session->set_userdata(
                'box',
                array(
                    'access_token' => $decoded_response->access_token,
                    'expires_in' => $decoded_response->expires_in,
                    'refresh_token' => $decoded_response->refresh_token,
                    'token_type' => $decoded_response->token_type,
                    'expiry_time' => date('Y-m-d H:i:s', strtotime('+' . $decoded_response->expires_in . ' seconds'))
                )
            );

            if ($is_cron || $this->model_signup->inRole([ROLE_1, ROLE_0])) {
                $configArray = array(
                    BOX_CONFIG_ACCESS_TOKEN => $decoded_response->access_token,
                    BOX_CONFIG_REFRESH_TOKEN => $decoded_response->refresh_token,
                    BOX_CONFIG_TOKEN_EXPIRY => ''
                );

                if (property_exists($decoded_response, 'expires_in') && $decoded_response->expires_in) {
                    $configArray[BOX_CONFIG_TOKEN_EXPIRY] = date('Y-m-d H:i:s', strtotime('+' . $decoded_response->expires_in . ' seconds'));
                }
                // update config value
                $updated = $this->model_config->update_config($configArray);
                log_message('ERROR', 'Updating box config');
            } else {
                $updated = 1;
            }
        } catch (\Exception $e) {
            //
            $this->_log_message(
                LOG_TYPE_API,
                LOG_SOURCE_BOX_CRON,
                LOG_LEVEL_ERROR,
                $e->getMessage(),
                ''
            );
            log_message('ERROR', $e->getMessage());
            return false;
        }

        if ($updated) {
            log_message('ERROR', 'Box config has been updated on: ' . date('Y-m-d H:i:s'));
            //
            $this->_log_message(
                LOG_TYPE_API,
                LOG_SOURCE_BOX_CRON,
                LOG_LEVEL_INFO,
                'Box config has been updated',
                ''
            );
            return true;
        } else {
            return false;
        }
    }

    /* ======================== Box ======================== */

    /* ======================== PAYPAL ======================== */

    /**
     * Method generateAccessToken - PayPal
     *
     * @return void
     */
    function generateAccessToken()
    {
        $status = FALSE;
        $message = '';
        $response = '';

        try {
            $url = PAYPAL_URL . PAYPAL_AUTH_URL;
            $user_pwd = PAYPAL_CLIENTID . ":" . PAYPAL_SECRETKEY;

            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';

            $body = ['grant_type' => 'client_credentials'];

            $response = $this->curlRequest($url, $headers, $body, TRUE, FALSE, '', TRUE, $user_pwd);

            if ($response) {
                $status = TRUE;
            }
            log_message('ERROR', 'generating paypal token');
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return ['status' => $status, 'message' => $message, 'response' => $response];
    }

    function paypalResource(string $url, array $headers, array $body, $postRequest = TRUE)
    {
        $response = $this->curlRequest($url, $headers, $body, $postRequest);
        return json_decode($response);
    }

    function savePaypalLog()
    {
    }

    /* ======================== PAYPAL ======================== */

    /* ======================== PLAID ======================== */

    /**
     * Method getPlaidAccount
     *
     * @return ?object
     */
    function getPlaidAccount()
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "access_token" => $this->plaid_token->access_token
        );
        $url = PLAID_API_URL . PLAID_GET_ACCOUNTS;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));
    }

    /**
     * createTransferAuthorization function
     *
     * @param string $account_id
     * @param integer $amount
     * @return ?object
     */
    function createTransferAuthorization($access_token = '', $account_id = '', $amount = 0)
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "access_token" => $access_token,
            "account_id" => $account_id,
            "type" => "credit",
            "network" => "ach",
            "amount" => $amount,
            "ach_class" => "web",
            "user" => array(
                "legal_name" => $this->model_signup->profileName($this->user_data, FALSE),
                "email_address" => $this->user_data['signup_email'],
                "phone_number" => $this->user_data['signup_phone'],
                "address" => array(
                    "street" => $this->user_data['signup_address'],
                    "city" => $this->user_data['signup_city'],
                    "region" => $this->user_data['signup_state'],
                    "postal_code" => $this->user_data['signup_zip'],
                    "country" => $this->user_data['signup_country']
                )
            ),
            "device" => array(
                "ip_address" => $_SERVER['REMOTE_ADDR'],
                "user_agent" => $_SERVER['HTTP_USER_AGENT']
            )
        );
        $url = PLAID_API_URL . PLAID_TRANSFER_AUTHORIZATION;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));
    }

    /**
     * createPlaidTransfer function
     *
     * @param string $account_id
     * @param string $authorization_id
     * @param string $description
     * @return ?object
     */
    function createPlaidTransfer($access_token = '', $account_id = '', $authorization_id = '', $description = '')
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "access_token" => $access_token,
            "account_id" => $account_id,
            "authorization_id" => $authorization_id,
            "description" => $description
        );
        $url = PLAID_API_URL . PLAID_CREATE_TRANSFER;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));
    }

    /**
     * Method getPlaidTransferList
     *
     * @return ?object
     */
    function getPlaidTransferList() {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
        );
        $url = PLAID_API_URL . PLAID_GET_TRANSFER_EVENT_LIST;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));
    }

    /* ======================== PLAID ======================== */
}