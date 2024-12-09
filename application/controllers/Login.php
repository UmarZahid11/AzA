<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login
 */
class Login extends MY_Controller
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
                redirect(l('signin') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''));
            }
        } else {
            $type = ROLE_1;
        }

        if (!in_array($type, [ROLE_0. ROLE_1, ROLE_3, ROLE_4, ROLE_5])) {
            redirect(l('signin') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''));
        }

        $data['type'] = $type;

        $data['banner'] = $this->model_inner_banner->find_one_active(
            array(
                'where' => array(
                    'inner_banner_name' => 'Login'
                )
            )
        );

        $data['login_cms'] = $this->model_cms_page->find_all_active(
            array(
                'where' => array(
                    'cms_page_name' => 'Login'
                )
            )
        );

        //
        $this->layout_data['title'] = 'Login | ' . $this->layout_data['title'];
        //
        $this->load_view('index', $data);
    }

    /**
     * Method setSignupSession
     *
     * @param array $signup
     *
     * @return void
     */
    private function setSignupSession(array $signup): void
    {
        $this->model_signup->set_user_session($signup);

        // set last online time
        // signup_location = get_location() -> if required
        if ($this->model_signup_info->find_one_active(
            array(
                'where' => array(
                    'signup_info_signup_id' => $signup['signup_id']
                )
            )
        )) {
            //
            if (!$this->model_signup_info->setSignupOnline((int) $signup['signup_id'], true)) {
                log_message('error', 'unable to set online status');
            }
        } else {
            //
            $this->model_signup_info->insertSignupInfo($signup['signup_id']);
        }
    }

    /**
     * Method do_login
     *
     * @return void
     */
    public function do_login()
    {
        // logging
        $location_ip = get_location('ip');
        $location_json = get_location('json');
        $location_array = get_location('array');

        // logging
        $log_param['signup_log_login_status'] = FAIL;
        $log_param['signup_log_ip'] = $location_ip;
        $log_param['signup_log_country'] = isset($location_array['country']) ? $location_array['country'] : '';
        $log_param['signup_log_region'] = isset($location_array['region']) ? $location_array['region'] : '';
        $log_param['signup_log_city'] = isset($location_array['city']) ? $location_array['city'] : '';
        $log_param['signup_log_json'] = serialize($location_json);

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

            $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
			$secretKey = defined('CAPTCHA_SECRET_KEY') ? CAPTCHA_SECRET_KEY : '';

			if($secretKey) {
				// post request to server
				$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
				$response = file_get_contents($url);
				$responseKeys = json_decode($response, true);
			} else {
				$responseKeys["success"] = TRUE;
			}

            // should return JSON with success as true
            if ($responseKeys["success"]) {

                if (isset($_POST) && count($_POST) > 0) {
                    if (!empty($_POST['signup']['signup_email']) && !empty($_POST['signup']['signup_password'])) {

                        $login_data = $_POST['signup'];
                        $signup_param['where']['signup_email'] = $login_data['signup_email'] = $login_data['signup_email'];
                        $signup_param['where']['signup_isdeleted'] = STATUS_INACTIVE;
                        $signup = $this->model_signup->find_one_active($signup_param);

                        if (!empty($signup)) {
                            // logging
                            $log_param['signup_log_signup_id'] = $signup['signup_id'];

                            $hash = $signup['signup_password'];

                            $signup_info = $this->model_signup_info->find_one_active(
                                array(
                                    'where' => array(
                                        'signup_info_signup_id' => $signup['signup_id']
                                    ),
                                )
                            );
                            if ($signup_info) {
                                $interval = $signup_info['signup_info_last_signup_attempt'] ? date_diff(date_create(date('Y-m-d H:i:s', strtotime('+' . LOGIN_ATTEMPT_TIME_LIMIT . ' minutes', strtotime($signup_info['signup_info_last_signup_attempt'])))), date_create(date('Y-m-d H:i:s'))) : 0;
                                if (
                                    ($signup_info['signup_info_signup_attempt'] < LOGIN_ATTEMPT_LIMIT) ||
                                    (validateDate($signup_info['signup_info_last_signup_attempt'], 'Y-m-d H:i:s') &&
                                        (date('Y-m-d H:i:s', strtotime('+' . LOGIN_ATTEMPT_TIME_LIMIT . ' minutes', strtotime($signup_info['signup_info_last_signup_attempt']))) < date('Y-m-d H:i:s'))
                                    )
                                ) {
                                    // verify password
                                    if (password_verify($login_data['signup_password'], $hash)) {
                                        $reverificationRequired = FALSE;
                                        // if user is already verified then reverification will be performed
                                        if (
                                            (isset($signup['signup_is_verified']) && $signup['signup_is_verified']) &&
                                            (isset($signup['signup_vouched_token']) && $signup['signup_vouched_token']) &&
                                            (isset($_POST['signup_reverified']) && !$_POST['signup_reverified'])
                                        ) {
                                            // reverification is enabled from admin configuration and current user donot have special privilige from admin
                                            if ($this->getConfigValueByVariable('login_reverification') && !$this->model_signup_bypass_privilege->get((int) $signup['signup_id'], PRIVILEGE_TYPE_IDENTITY)) {
                                                $reverificationRequired = TRUE;
                                            }
                                        }
                                        $param['jobId'] = '';
                                        $param['reverify'] = $reverificationRequired;

                                        if ($reverificationRequired) {
                                            $param['status'] = STATUS_TRUE;
                                            $param['txt'] = __("Identity verification is required before proceeding.");
                                            $param['jobId'] = $signup['signup_vouched_token'];
                                        } else {
                                            $this->setSignupSession($signup);
                                            $param['status'] = STATUS_TRUE;
                                            $param['txt'] = __("Login successful!");
                                        }

                                        // if (isset($signup['signup_is_phone_confirmed']) && !$signup['signup_is_phone_confirmed']) {
                                        //     $param['redirect_url'] = base_url() . 'dashboard';
                                        // }
                                        // check if redirection URL exists and has a value
                                        if (isset($_POST['redirect_url']) && $_POST['redirect_url'] != "") {
                                            $param['redirect_url'] = urldecode($_POST['redirect_url']);
                                        } else {
                                            $param['redirect_url'] = base_url() . 'home/redirecting?action=social';
                                            // $param['redirect_url'] = base_url() . 'announcement/listing';
                                        }

                                        // logging
                                        $log_param['signup_log_login_status'] = SUCCESS;

                                        // auto log in the administrator start
                                        try {
                                            if ($this->model_signup->hasRole(ROLE_0, $signup)) {
                                                $user_id = $this->model_user->find_one_active(
                                                    array(
                                                        'where' => array(
                                                            'user_email' => $login_data['signup_email']
                                                        )
                                                    )
                                                )['user_id'];
                                                $this->model_user->auto_login($user_id);
                                            }
                                        } catch (\Exception $e) {
                                            error_log($e->getMessage());
                                            //
                                            $this->_log_message(
                                                LOG_TYPE_GENERAL,
                                                LOG_SOURCE_SERVER,
                                                LOG_LEVEL_ERROR,
                                                $e->getMessage(),
                                                ''
                                            );
                                        }
                                        // auto log in the administrator end
                                    } else {
                                        //
                                        $this->model_signup_info->updateAttemptLimit($signup_info, (int) $signup['signup_id']);

                                        $param['status'] = STATUS_FALSE;
                                        $param['txt'] = __("Invalid email or password.");
                                    }
                                } else {
                                    $param['status'] = STATUS_FALSE;
                                    $param['txt'] = __("Login error, Reason: " . LOGIN_ATTEMPT_LIMIT . " attempt limit reached. Try again after " . $interval->format('%i minute(s) %s second(s)'));
                                }
                            } else {
                                $param['status'] = STATUS_FALSE;
                                $param['txt'] = __("The requested email doesn't exists.");
                            }
                        } else {
                            $param['status'] = STATUS_FALSE;
                            $param['txt'] = __("The requested email doesn't exists.");
                        }
                    } else {
                        $param['status'] = STATUS_FALSE;
                        $param['txt'] = __("A valid email and password is required.");
                    }
                } else {
                    $param['status'] = STATUS_FALSE;
                    $param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $param['status'] = STATUS_FALSE;
                $param['txt'] = __(ERROR_MESSAGE_CAPTCHA_FAILED);
                $param['data'] = [];
            }
        } else {
            $param['status'] = STATUS_FALSE;
            $param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            $param['data'] = [];
        }

        // create log
        $this->model_signup_log->insert_record($log_param);

        echo json_encode($param);
    }

    /**
     * Method login_after_verification
     *
     * @return void
     */
    public function login_after_verification(): void
    {
        if (isset($_POST['signup_id'])) {
            $signup = $this->model_signup->find_by_pk($_POST['signup_id']);
            if (!empty($signup)) {
                $this->setSignupSession($signup);
                $json_param['status'] = TRUE;
                $json_param['txt'] = __('Login successful');
            } else {
                $json_param['status'] = FALSE;
                $json_param['txt'] = __('Login failed');
            }
        } else {
            $json_param['status'] = FALSE;
            $json_param['txt'] = __('Login failed');
        }
        echo json_encode($json_param);
    }

    /**
     * Method confirmation
     *
     * @param $token $token
     *
     * @return void
     */
    public function confirmation($token)
    {
        // confirm account here
        if ($token) {
            $param = array();
            $param['where']['token_user'] = $token;
            $token_details = $this->model_token->find_one_active($param);

            if ($token_details) {
                $param = array();
                $param['signup_is_confirmed'] = STATUS_ACTIVE;
                $user_id = $token_details['token_user_id'];
                $this->model_signup->update_by_pk($user_id, $param);

                $update_token['token_status'] = STATUS_FALSE;
                $this->model_token->update_by_pk($token_details['token_id'], $update_token);

                $this->session->set_flashdata('activation_message', __('The email confirmation process was successful!'));

                redirect(l('dashboard'));
            } else {
                error_404();;
            }
        } else {
            error_404();;
        }
    }

    /**
     * resend_confirmation
     *
     * @return void
     */
    public function resend_confirmation()
    {
        if ($this->userid > 0) {
            $signup = $this->model_signup->find_by_pk($this->userid);
            $mailVariable = "";
            if (!$signup['signup_is_confirmed']) {

                try {
                    $mailVariable = $this->model_email->notification_register($this->userid, 'user');
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    //
                    $this->_log_message(
                        LOG_TYPE_GENERAL,
                        LOG_SOURCE_SERVER,
                        LOG_LEVEL_ERROR,
                        $e->getMessage(),
                        ''
                    );
                }

                if ($mailVariable) {
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = __("A confirmation message has been sent to your email!");
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __("Error in sending confirmation e-mail!");
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __("The requested account is already activated!");
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * forgot
     *
     * @return void
     */
    public function forgot()
    {
        $email = $this->input->post('signup');
        $this->form_validation->set_rules('signup[signup_email]', 'Email', 'required|valid_email');
        $this->form_validation->set_error_delimiters("<span style=\"color:white;\" for=\"%s\" class=\"has-error help-block\">", '</span>');

        if ($this->form_validation->run() == FALSE) {
            $json_param['status'] = false;
            $json_param['txt'] = validation_errors();
        } else {
            $params = array();
            $params['where']['signup_email'] = $email['signup_email'];
            $params['where']['signup_isdeleted'] = STATUS_INACTIVE;
            $query = $this->model_signup->find_one_active($params);

            if ($query && count($query) > 0) {
                // Remove old token if exist
                $where_params['where']['token_user_id'] = $query['signup_id'];
                $data = array(
                    'token_status' => STATUS_INACTIVE
                );
                $this->model_token->update_model($where_params, $data);

                // Generate token
                $token = md5((string) time());
                $data = array(
                    'token_user' => $token,
                    'token_user_id' => $query['signup_id'],
                    'token_status' => STATUS_ACTIVE
                );

                // Save token
                $this->model_token->set_attributes($data);
                $this->model_token->save();

                $this->model_email->reset_password($query, $token);

                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("A password recovery link was sent to your email!");
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __("Unable to find the requested email!");
            }
        }
        echo json_encode($json_param);
    }

    /**
     * validate_username
     *
     * @return void
     */
    public function validate_username()
    {
        if (isset($_POST['username']) && $_POST['username'] != "") {
            $param = array();
            $param['where']['signup_username'] = $_POST['username'];
            $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
            $user_details = $this->model_signup->find_one_active($param);
            if ($user_details) {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __("The requested username is already in use!");
            } else {
                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("The requested username is available!");
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __("The Username field is required!");
        }
        echo json_encode($json_param);
    }

    /**
     * validate_email
     *
     * @return void
     */
    public function validate_email()
    {
        if (isset($_POST['email']) && $_POST['email'] != "") {
            $param = array();
            $param['where']['signup_email'] = $_POST['email'];
            $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
            $user_details = $this->model_signup->find_one_active($param);
            if ($user_details) {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __("The requested email is already in use!");
            } else {
                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("The requested email is available!");
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __("The email field is required!");
        }
        echo json_encode($json_param);
    }
}
