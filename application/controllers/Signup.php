<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Signup
 */
class Signup extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method index
     *
     * @param string $type
     *
     * @return void
     */
    public function index(string $type = '')
    {
        global $config;

        $data['config'] = $config;

        if ($this->userid > 0) {
            $this->session->set_flashdata('error', __('A login session is already active!'));
            redirect(l(''));
        }

        if ($type) {
            try {
                $type = JWT::decode($type, CI_ENCRYPTION_SECRET);
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
                //
                $this->_log_message(
                    LOG_TYPE_GENERAL,
                    LOG_SOURCE_SERVER,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
                redirect(l('signup') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''));
            }
        } else {
            $type = ROLE_1;
        }

        if (!in_array($type, [ROLE_1, ROLE_3, ROLE_4, ROLE_5])) {
            redirect(l('signup') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''));
        }

        $data['type'] = $type;

        $param = array();
        $param['where']['inner_banner_name'] = 'Signup';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Signup';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        $data['job_category'] = $this->model_job_category->find_all_active();

        $data['job_type'] = $this->model_job_type->find_all_active();

        //
        $this->layout_data['title'] = 'Signup | ' . $this->layout_data['title'];
        //
        $this->load_view('index', $data);
    }

    /**
     * Method validateSignup
     *
     * @return void
     */
    function validateSignup() {
        $json_param = array();

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

            if ($this->custom_validate("model_signup")) {
                $json_param['status'] = STATUS_TRUE;
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = validation_errors();
                $json_param['error'] = form_error_array();
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);
    }

    /**
     * Method save_signup
     *
     * @return void
     */
    public function save_signup()
    {
        $inserted_param = array();
        $mailVariable = NULL;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

            if ($this->validate("model_signup")) {

                /* CAPTCHA VALIDATION */

                // $createGoogleUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . GOOGLE_CAPTCHA_SECRET_KEY . '&response=' . $_POST['g-recaptcha-response'];
                // $verifyRecaptcha = $this->curlRequest($createGoogleUrl);
                // $decodeGoogleResponse = json_decode($verifyRecaptcha, true);

                // if ($decodeGoogleResponse['success'] == 1) {

                /* CAPTCHA VALIDATION */

                $inserted_param = $_POST['signup'];

                if (isset($_POST['stripeToken']) && $_POST['stripeToken']) {

                    $setupIntents = '';
                    try {
                        $customer = $this->createStripeResource(
                            'customers',
                            [
                                "email" => $inserted_param['signup_email'],
                            ]
                        );
    
                        if($customer && $customer->id) {
                            $setupIntents = $this->createStripeResource(
                                'setupIntents',
                                [
                                    'payment_method_types' => ['card'],
                                    'customer' =>  $customer->id
                                ]
                            );
                            $inserted_param['signup_setupintent_id'] = $setupIntents ? $setupIntents->id : '';
                        }
                    } catch(\Exception $e) { 
                        log_message('ERROR', $e->getMessage());
                    }
                }

                if (array_filled($_FILES) and $_FILES['signup_image']['error'] == 0 && $_FILES['signup_image']['size'] < MAX_FILE_SIZE) {
                    // Get temp file
                    $tmp = $_FILES['signup_image']['tmp_name'];
                    // Generate file name
                    $name = mt_rand() . $_FILES['signup_image']['name'];

                    // Get upload path
                    $upload_path = "assets/uploads/user/";

                    // Set data
                    $inserted_param['signup_logo_image'] = $name;
                    $inserted_param['signup_logo_image_path'] = $upload_path;

                    // Upload new file
                    move_uploaded_file($tmp, $upload_path . $name);
                }

                //
                $inserted_param['signup_password'] = password_hash($inserted_param['signup_password'], PASSWORD_BCRYPT);

                // set default membership to free
                // $inserted_param['signup_type'] = ROLE_1;
                if($inserted_param['signup_type'] == ROLE_1) {
                    // set default membership status to active
                    $inserted_param['signup_membership_status'] = STATUS_ACTIVE;
                    $inserted_param['signup_trial_expiry'] = date('Y-m-d H:i:s', strtotime('+' . STRIPE_TRIAL_PERIOD_DAYS . ' days'));
                } else if($inserted_param['signup_type'] == ROLE_3) {
                    // set to active when subscription is bought
                    $inserted_param['signup_membership_status'] = STATUS_INACTIVE;
                }

                // set default to general membership
                $inserted_param['signup_membership_id'] = constant(strtoupper(MEMBERSHIP_PREFIX) . ROLE_1);
                $inserted_param['signup_status'] = STATUS_ACTIVE;
                $inserted_param['signup_is_confirmed'] = STATUS_FALSE;
                // if auto approve is enabled from admin config.
                if ($this->getConfigValueByVariable('auto_approve_new_signup')) {
                    $inserted_param['signup_is_approved'] = STATUS_TRUE;
                }

                // set location value
                try {
                    $inserted_param['signup_location'] = get_location();
                } catch (\Exception $e) {
                    log_message('error', $e->getMessage());
                    $inserted_param['signup_location'] = "";
                }

                $inserted_id = $this->model_signup->insert_record($inserted_param);

                if ($inserted_id > 0) {
                    //
                    $this->model_signup->auto_login($inserted_id);

                    //
                    // set last online time
                    if ($this->model_signup_info->find_one_active(
                        array(
                            'where' => array(
                                'signup_info_signup_id' => $this->userid
                            )
                        )
                    )) {
                        // set online
                        $this->model_signup_info->setSignupOnline($this->userid, true);
                    } else {
                        //
                        $this->model_signup_info->insertSignupInfo($inserted_id);
                    }

                    //
                    if (isset($_POST['redirect_url']) && $_POST['redirect_url'] != "") {
                        $json_param['redirect_url'] = urldecode($_POST['redirect_url']);
                    } else {
                        if($inserted_param['signup_type'] == ROLE_3) {
                            $json_param['redirect_url'] = l('membership/payment/') . JWT::decode(ROLE_3);
                        } else {
                            $json_param['redirect_url'] = l('dashboard/profile/create');
                        }
                    }
                    $json_param['status'] = STATUS_TRUE;

                    //
                    if (ENVIRONMENT != 'development') {
                        // sent email to user for info and email verification
                        $mailVariable = $this->model_email->notification_register($inserted_id, 'user');

                        // sent email to admin for one user added
                        $this->model_email->notification_register($inserted_id, 'admin');
                    } else {
                        $mailVariable = true;
                    }

                    if ($mailVariable) {
                        $this->model_notification->sendNotification($inserted_id, $inserted_id, NOTIFICATION_WELCOME, 0, NOTIFICATION_WELCOME_COMMENT);
                        $json_param['txt'] = __('Account registeration successfull!');
                    } else {
                        $json_param['txt'] = __('Account created with an Error!');
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
                // } else {
                //     $json_param['status'] = 0;
                //     $json_param['txt'] = "Invalid Captcha!";
                // }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = validation_errors();
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);
    }

    /**
     * Method authentication
     *
     * @param int $id
     *
     * @return void
     */
    public function authentication($id): void
    {
        $token_number = md5("REG-" . $id . "GEF");

        if ($token_number == $_GET['token']) {
            $user_id = $id;

            $update = $this->model_signup->update_by_pk($user_id, array('signup_is_confirmed' => STATUS_ACTIVE));

            if ($update > 0) {
                $this->model_signup->auto_login($user_id, 'front');

                $this->session->set_flashdata('success', __('Your email account has been verified, and now you are good to go!'));
                redirect(l(''));
            } else {
                error_404();
            }
        } else {
            error_404();
        }
    }
}
