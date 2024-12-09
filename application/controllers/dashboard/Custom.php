<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/vendor/autoload.php');

use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;
use Twilio\Exceptions\TwilioException;

/**
 * Class Custom
 */
class Custom extends MY_Controller
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
     * @return void
     */
    public function index()
    {
        error_404();
    }

    /**
     * Method validate_phone - for phone confirmation
     *
     * @return void
     */
    public function validate_phone(): void
    {
        global $config;

        $json_param['status'] = STATUS_FALSE;
        $json_param['refresh'] = STATUS_FALSE;

        if ($_POST && $_POST['id'] && $this->userid > 0) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                $userid = $_POST['id'];

                if ($this->signup_info['signup_info_phone_verification_attempt'] < $this->getConfigValueByVariable('verification_attempt_limit')) {

                    // 4 digit code
                    $code = random_digits();

                    // invalidate all previous tokens
                    $where_params['where']['token_user_id'] = $userid;
                    $data = array(
                        'token_status' => STATUS_INACTIVE
                    );
                    $this->model_token->update_model($where_params, $data);

                    // create new token
                    $token = md5($code);
                    $data = array(
                        'token_user' => $token,
                        'token_user_id' => $userid,
                        'token_status' => 1,
                        'token_createdon' => date("Y-m-d"),
                    );

                    // Save token
                    $this->model_token->set_attributes($data);
                    $this->model_token->save();

                    $to = TWILIO_ENVIRONMENT == 'development' ? $this->user_data['signup_phone'] : $this->user_data['signup_phone'];
                    $sid   = TWILIO_ACCOUNT_SID; // g('db.admin.account_sid');
                    $token = TWILIO_AUTH_TOKEN; // g('db.admin.auth_token');

                    $twilioError = false;
                    $twilioErrorMessage = "";

                    try {
                        $twilio = new Client($sid, $token);

                        $message = $twilio->messages
                            ->create(
                                $to,
                                array(
                                    "messagingServiceSid" => TWILIO_SERVICE_SID,
                                    "body" => '<#> ' . $code . ' is your ' . $config['site_name'] . ' account verification code.'
                                )
                            );
                    } catch (RestException $e) {
                        $twilioErrorMessage = $e->getMessage();
                        $twilioError = true;
                    } catch (TwilioException $e) {
                        $twilioErrorMessage = $e->getMessage();
                        $twilioError = true;
                    } catch (\Exception $e) {
                        $twilioErrorMessage = $e->getMessage();
                        $twilioError = true;
                    }

                    if (!$twilioError) {

                        //
                        $this->model_signup_info->update_model(
                            array(
                                'where' => array(
                                    'signup_info_signup_id' => $this->userid
                                ),
                            ),
                            array(
                                'signup_info_phone_verification_attempt' => $this->signup_info['signup_info_phone_verification_attempt'] + 1
                            )
                        );
                        //
                        $this->model_signup->update_by_pk($userid, array('signup_twilio_response' => serialize($message)));

                        //
                        $json_param['txt'] = __("Otp has been sent successfully.");
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['refresh'] = STATUS_TRUE;
                    } else {
                        log_message('ERROR', $twilioErrorMessage);
                        //
                        $this->_log_message(
                            LOG_TYPE_API,
                            LOG_SOURCE_TWILIO,
                            LOG_LEVEL_ERROR,
                            $twilioErrorMessage,
                            ''
                        );
                        $json_param['txt'] = $twilioErrorMessage ?? __("Error in sending otp, Please try again later.");
                        $json_param['refresh'] = STATUS_FALSE;
                    }
                } else {
                    $json_param['txt'] = __("Message verification error. Reason: " . $this->getConfigValueByVariable('verification_attempt_limit') . " attempt limit reached.");
                    $json_param['refresh'] = STATUS_TRUE;
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }

        echo json_encode($json_param);
    }
    
    /**
     * Method validatePhoneOtp - for phone confirmation
     *
     * @return void
     */
    public function validatePhoneOtp(): void
    {
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST) && $this->userid > 0) {
            // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

                $signup_details = $this->model_signup->find_by_pk($this->userid);
                $updated = 0;

                if (!$signup_details['signup_is_phone_confirmed']) {

                    $code = $_POST['otp-1'] . $_POST['otp-2'] . $_POST['otp-3'] . $_POST['otp-4'];
                    $param = array();
                    $param['where']['token_user'] = md5($code);
                    $param['where']['token_user_id'] = $this->userid;

                    $token_details = $this->model_token->find_one_active($param);

                    if (!empty($token_details)) {
                        $param = array();
                        $param['signup_is_phone_confirmed'] = STATUS_ACTIVE;
                        $updated = $this->model_signup->update_by_pk($this->userid, $param);

                        if ($updated) {
                            // notification
                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PHONE_VERIFIED, 0, NOTIFICATION_PHONE_VERIFIED_COMMENT);

                            $json_param['txt'] = __("The requested phone number has been verified.");
                            $json_param['status'] = STATUS_TRUE;
                        } else {
                            $json_param['txt'] = __("An error while trying to verify the provided otp!");
                        }
                    } else {
                        $json_param['txt'] = __("The otp you have entered is invalid.");
                    }
                } else {
                    $json_param['txt'] = __("The requested phone number has already been verified!");
                }
            // } else {
            //     $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            // }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method sendOtp - for raw otp only
     *
     * @return void
     */
    public function sendOtp(): void
    {
        global $config;

        $json_param['status'] = STATUS_FALSE;

        if ($_POST && $_POST['id'] && $this->userid > 0) {
            // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

                $userid = $_POST['id'];

                // 4 digit code
                $code = random_digits();

                // invalidate all previous tokens
                $where_params['where']['token_user_id'] = $userid;
                $data = array(
                    'token_status' => STATUS_INACTIVE
                );
                $this->model_token->update_model($where_params, $data);

                // create new token
                $token = md5($code);
                $data = array(
                    'token_user' => $token,
                    'token_user_id' => $userid,
                    'token_status' => 1,
                    'token_createdon' => date("Y-m-d"),
                );

                // Save token
                $this->model_token->set_attributes($data);
                $this->model_token->save();

                $to = TWILIO_ENVIRONMENT == 'development' ? $this->user_data['signup_phone'] : $this->user_data['signup_phone'];
                $sid   = TWILIO_ACCOUNT_SID;
                $token = TWILIO_AUTH_TOKEN;

                $twilioError = false;
                $twilioErrorMessage = "";

                try {
                    $twilio = new Client($sid, $token);

                    $message = $twilio->messages
                        ->create(
                            $to,
                            array(
                                "messagingServiceSid" => TWILIO_SERVICE_SID,
                                "body" => '<#> ' . $code . ' is your ' . $config['site_name'] . ' account verification code.'
                            )
                        );
                } catch (RestException $e) {
                    $twilioErrorMessage = $e->getMessage();
                    $twilioError = true;
                } catch (TwilioException $e) {
                    $twilioErrorMessage = $e->getMessage();
                    $twilioError = true;
                } catch (\Exception $e) {
                    $twilioErrorMessage = $e->getMessage();
                    $twilioError = true;
                }

                if (!$twilioError) {

                    //
                    $json_param['txt'] = __("Otp has been sent successfully.");
                    $json_param['status'] = STATUS_TRUE;
                } else {
                    log_message('ERROR', $twilioErrorMessage);
                    //
                    $this->_log_message(
                        LOG_TYPE_API,
                        LOG_SOURCE_TWILIO,
                        LOG_LEVEL_ERROR,
                        $twilioErrorMessage,
                        ''
                    );
                    $json_param['txt'] = $twilioErrorMessage ?? __("Error in sending otp, Please try again later.");
                }
            // } else {
            //     $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            // }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }

        echo json_encode($json_param);
    }

    /**
     * Method verifyOtp
     *
     * @return void
     */
    public function verifyOtp(): void
    {
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST) && $this->userid > 0) {
            // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

                $signup_details = $this->model_signup->find_by_pk($this->userid);
                $updated = 0;

                $code = $_POST['otp-1'] . $_POST['otp-2'] . $_POST['otp-3'] . $_POST['otp-4'];
                $param = array();
                $param['where']['token_user'] = md5($code);
                $param['where']['token_user_id'] = $this->userid;

                $token_details = $this->model_token->find_one_active($param);

                if (!empty($token_details)) {
                    $json_param['txt'] = __("The requested otp has been verified.");
                    $json_param['status'] = STATUS_TRUE;
                } else {
                    $json_param['txt'] = __("The otp you have entered is invalid.");
                }
            // } else {
            //     $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            // }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method unique_phone
     *
     * @param string $str
     *
     * @return bool
     */
    public function unique_phone(string $str): bool
    {
        $param = array();
        $param['where']['signup_phone'] = $str;
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        $param['where']['signup_id !='] = $this->userid;
        if (empty($this->model_signup->find_one_active($param))) {
            return true;
        } else {
            return false;
        }
    }

    // /**
    //  * Method unique_representative_phone
    //  *
    //  * @param string $str
    //  *
    //  * @return bool
    //  */
    // public function unique_representative_phone($str): bool
    // {
    //     $param = array();
    //     $param['where']['signup_company_representative_phone'] = $str;
    //     $param['where']['signup_company_signup_id !='] = $this->userid;
    //     $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
    //     $param['joins'][] = array(
    //         'table' => 'signup',
    //         'joint' => 'signup.signup_id = signup_company.signup_company_signup_id',
    //         'type' => 'both'
    //     );
    //     if (empty($this->model_signup_company->find_one_active($param))) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // /**
    //  * Method unique_representative_email
    //  *
    //  * @param string $str
    //  *
    //  * @return bool
    //  */
    // public function unique_representative_email($str): bool
    // {
    //     $param = array();
    //     $param['where']['signup_company_representative_email'] = $str;
    //     $param['where']['signup_company_signup_id !='] = $this->userid;
    //     $param['joins'][] = array(
    //         'table' => 'signup',
    //         'joint' => 'signup.signup_id = signup_company.signup_company_signup_id',
    //         'type' => 'both'
    //     );
    //     $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
    //     if (empty($this->model_signup_company->find_one_active($param))) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // /**
    //  * Method unique_company_slug
    //  *
    //  * @param string $str
    //  *
    //  * @return bool
    //  */
    // public function unique_company_slug(string $str): bool
    // {
    //     $param = array();
    //     $param['where']['signup_company_slug'] = $str;
    //     $param['where']['signup_company_signup_id !='] = $this->userid;
    //     $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
    //     $param['joins'][] = array(
    //         'table' => 'signup',
    //         'joint' => 'signup.signup_id = signup_company.signup_company_signup_id',
    //         'type' => 'both'
    //     );
    //     if (empty($this->model_signup_company->find_one_active($param))) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * Method delete_notification
     *
     * @return void
     */
    public function delete_notification(): void
    {
        $json_param = array();
        if ($this->userid > 0) {
            if (isset($_POST['id'])) {
                $notificationDetails = $this->model_notification->find_one_active(
                    array(
                        'where' => array(
                            'notification_signup_id' => $this->userid,
                            'notification_id' => $_POST['id']
                        )
                    )
                );

                if (!empty($notificationDetails)) {
                    $updated = $this->model_notification->update_model(
                        array(
                            'where' => array(
                                'notification_signup_id' => $this->userid,
                                'notification_id' => $_POST['id']
                            ),
                        ),
                        array(
                            'notification_status' => STATUS_DELETE
                        )
                    );

                    if ($updated) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __("Notification deleted!");
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
        }
        echo json_encode($json_param);
    }

    /**
     * Method profile_search
     *
     * @return void
     */
    public function profile_search(): void
    {
        $returnData = array();
        if ($this->userid > 0) {
            $profiles = array();

            $searchTerm = $this->input->get('term');

            $param = array();
            $param['where_like'][] = array(
                'column' => 'signup_firstname',
                'value' => $searchTerm,
                'type' => 'both',
            );
            $param['or_like'][] = array(
                'column' => 'signup_lastname',
                'value' => $searchTerm,
                'type' => 'both',
            );
            $profiles = $this->model_signup->find_all_active($param);

            foreach ($profiles as $key => $val) {
                $data['id'] = ucfirst($val['signup_firstname']) . ' ' . ucfirst($val['signup_lastname']);
                if ($this->model_signup->hasPremiumPermission()) {
                    $data['value'] = $this->model_signup->signupName($val, false);
                } else {
                    $data['value'] = $this->model_signup->signupName($val);
                }
                array_push($returnData, $data);
            }
        }

        echo json_encode($returnData);
    }

    /**
     * Method search_redirect
     *
     * @param string $search
     *
     * @return void
     */
    public function search_redirect(string $search = ""): void
    {
        if (isset($search) && $search && $this->userid > 0) {
            $search = explode('-', $search);
            $signup_firstname = $search[0];
            $signup_lastname = $search[1];

            $signup = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_isdeleted' => STATUS_INACTIVE
                    ),
                    'where_like' => array(
                        0 => array(
                            'column' => 'signup_firstname',
                            'value' => $signup_firstname,
                            'type' => 'both',
                        ),
                        1 => array(
                            'column' => 'signup_lastname',
                            'value' => $signup_lastname,
                            'type' => 'both',
                        )
                    )
                )
            );

            if (empty($signup)) {
                $this->session->set_flashdata('error', __('Unable to find requested user profile!'));
                redirect(l('dashboard'));
            } else {
                if ($signup['signup_type'] == ROLE_3) {
                    redirect(l('dashboard/profile/detail/' . JWT::encode($signup['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $signup['signup_type']));
                } elseif ($signup['signup_type'] == ROLE_1) {
                    $this->session->set_flashdata('error', __('Cannot preview a general user\'s profile!'));
                    redirect(l('dashboard'));
                } else {
                    $this->session->set_flashdata('error', __('Unable to find requested user profile!'));
                    redirect(l('dashboard'));
                }
            }
        } else {
            $this->session->set_flashdata('error', __('Encountered an invalid search term!'));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method emailDrop - email search dropdown
     *
     * @return void
     */
    public function emailDrop(): void
    {
        $searchTerm = urlencode($this->input->get('term'));
        $searchTerm = urldecode($searchTerm);

        $emailsArray = array();

        if (strpos($searchTerm, ';') !== false) {
            $emailsArray = explode(';', $searchTerm);
            $searchTerm = $emailsArray[count($emailsArray) - 1];
        }

        $suggestedEmail = $this->model_signup->find_all_active(
            array(
                'where' => array(
                    'signup_id != ' => $this->userid
                ),
                'where_like' => array(
                    0 => array(
                        'column' => 'signup_email',
                        'value' => $searchTerm,
                        'type' => 'both',
                    )
                )
            )
        );

        $returnData = array();

        foreach ($suggestedEmail as $key => $value) {
            $data['id'] = $value['signup_email'];
            $data['value'] = $value['signup_email'];
            array_push($returnData, $data);
        }
        echo json_encode($returnData);
    }

    /**
     * Method sendMessage
     *
     * @return void
     */
    public function sendMessage(): void
    {
        $json_param = array();
        if (isset($_POST) && $this->userid > 0) {
            if (!($this->model_signup->hasPremiumPermission())) {
                $json_param['status'] = 0;
                $json_param['notify'] = 0;
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            } else {
                $chat_signup1 = isset($this->model_signup->find_by_email($_POST['chat_signupid1'])['signup_id']) ? $this->model_signup->find_by_email($_POST['chat_signupid1'])['signup_id'] : 0;
                $chat_signup2 = isset($this->model_signup->find_by_email($_POST['chat_signupid2'])['signup_id']) ? $this->model_signup->find_by_email($_POST['chat_signupid2'])['signup_id'] : 0;

                if ($chat_signup1 && $chat_signup2) {
                    if ($chat_signup1 != $chat_signup2) {
                        $insert_param = $_POST['chat'];
                        $insert_param['chat_signup1'] = $chat_signup1;
                        $insert_param['chat_signup2'] = $chat_signup2;

                        $inserted_chat = $this->model_chat->insert_record($insert_param);

                        if ($inserted_chat) {

                            $insert_chat_message = array();
                            $insert_chat_message['chat_message_chat_id'] = $inserted_chat;
                            $insert_chat_message['chat_message_sender'] = $chat_signup1;
                            $insert_chat_message['chat_message_receiver'] = $chat_signup2;
                            $insert_chat_message['chat_message_text'] = $_POST['message'];
                            $inserted_chat_message = $this->model_chat_message->insert_record($insert_chat_message);

                            if ($inserted_chat_message) {
                                $this->model_notification->sendNotification($chat_signup2, $chat_signup1, NOTIFICATION_EMAIL, $inserted_chat, NOTIFICATION_EMAIL_COMMENT);

                                $json_param['status'] = STATUS_TRUE;
                                $json_param['notify'] = STATUS_TRUE;
                                $json_param['txt'] = __("Message sent!");
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['notify'] = STATUS_FALSE;
                                $json_param['txt'] =  __(ERROR_MESSAGE);
                            }
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['notify'] = STATUS_FALSE;
                            $json_param['txt'] =  __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['notify'] = STATUS_FALSE;
                        $json_param['txt'] = __("Cannot send message to yourself.");
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['notify'] = STATUS_FALSE;
                    $json_param['txt'] = __("Requested recepient doesn\'t exists.");
                }
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['notify'] = STATUS_FALSE;
            $json_param['txt'] =  __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete_chat
     *
     * @return void
     */
    public function delete_chat(): void
    {
        $json_param = array();

        if (isset($_POST['id']) && $this->userid > 0) {
            if (!($this->model_signup->hasPremiumPermission())) {
                $json_param['status'] = 0;
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            } else {
                $param = array();
                $param['where']['chat_id'] = $_POST['id'];
                $param['where']['chat_isdeleted'] = STATUS_INACTIVE;
                $param['where']['chat_reference_type'] = CHAT_REFERENCE_EMAIL;

                if (isset($_POST['type'])) {
                    switch ($_POST['type']) {
                        case 'sent':
                            $param['where']['chat_signup1'] = $this->userid;
                            break;
                        case 'inbox':
                            $param['where']['chat_signup2'] = $this->userid;
                            break;
                    }
                    $chatDetails = $this->model_chat->find_one_active($param);

                    if (!empty($chatDetails)) {
                        $update_param = array();
                        $update_param['chat_isdeleted'] = STATUS_ACTIVE;
                        $update_param['chat_status'] = STATUS_DELETE;
                        $update_param['chat_deletedon'] = date("Y-m-d");
                        $updated = $this->model_chat->update_by_pk($chatDetails['chat_id'], $update_param);
                        if ($updated) {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __("Chat deleted successfully.");
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __(ERROR_MESSAGE_REFRESH_REQUIRED);
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __("Requested chat doesn\'t exists.");
                    }
                } else {
                    $chatDetails = $this->model_chat->find_one_active(
                        array(
                            'where' => array(
                                'chat_id' => $_POST['id'],
                                'chat_reference_type' => CHAT_REFERENCE_EMAIL
                            )
                        )
                    );
                    if (!empty($chatDetails)) {
                        if ($chatDetails['chat_signup1'] == $this->userid || $chatDetails['chat_signup2'] == $this->userid) {
                            $update_param = array();
                            $update_param['chat_isdeleted'] = STATUS_ACTIVE;
                            $update_param['chat_status'] = STATUS_DELETE;
                            $update_param['chat_deletedon'] = date("Y-m-d");
                            $updated = $this->model_chat->update_by_pk($chatDetails['chat_id'], $update_param);
                            if ($updated) {
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = __("Chat deleted successfully.");
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['txt'] = __(ERROR_MESSAGE_REFRESH_REQUIRED);
                            }
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __("Requested chat doesn\'t exists.");
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __("Requested chat doesn\'t exists.");
                    }
                }
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method message_reply
     *
     * @return void
     */
    public function message_reply(): void
    {
        if (isset($_POST['chat_message']) && $this->userid > 0) {
            if (!($this->model_signup->hasPremiumPermission())) {
                $json_param['status'] = STATUS_FALSE;
                $json_param['notify'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            } else {
                $insert_param = $_POST['chat_message'];

                $inserted = $this->model_chat_message->insert_record($insert_param);
                if ($inserted) {
                    // add notify
                    $this->model_notification->sendNotification($insert_param['chat_message_receiver'], $insert_param['chat_message_sender'], NOTIFICATION_EMAIL, $insert_param['chat_message_chat_id'], NOTIFICATION_EMAIL_COMMENT);

                    $json_param['status'] = STATUS_TRUE;
                    $json_param['notify'] = STATUS_TRUE;
                    $json_param['txt'] = __('Message sent.');
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['notify'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['notify'] = STATUS_INACTIVE;
            $json_param['txt'] = __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method chatCountCheck
     *
     * @return void
     */
    public function chatCountCheck(): void
    {
        $chat['count'] = 0;

        if (isset($_POST['chat_id']) && $this->userid > 0) {

            $id = $_POST['chat_id'];

            $chat_message = $this->model_chat_message->find_all_active(
                array(
                    'order' => 'chat_message_parent DESC, chat_message_id DESC',
                    'where' => array(
                        'chat_message_chat_id' => $id
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'chat',
                            'joint' => 'chat.chat_id = chat_message.chat_message_chat_id',
                            'type'  => 'both'
                        )
                    )
                )
            );

            if (!empty($chat_message)) {
                $chat['count'] = count($chat_message);
            }
        }
        echo json_encode($chat);
    }

    /**
     * Method seen_notification
     *
     * @return void
     */
    public function seen_notification(): void
    {
        $json_param = array();

        if ($this->userid > 0) {
            $update_notifications = $this->model_notification->update_model(
                array(
                    'where' => array(
                        'notification_signup_id' => $this->userid
                    )
                ),
                array(
                    'notification_seen' => STATUS_ACTIVE,
                    'notification_alert_seen' => STATUS_ACTIVE
                )
            );
            if ($update_notifications) {
                $json_param['status'] = STATUS_TRUE;
            } else {
                $json_param['status'] = STATUS_FALSE;
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
        }
        echo json_encode($json_param);
    }

    /**
     * Method seen_message
     *
     * @return void
     */
    public function seen_message(): void
    {
        $json_param = array();

        if ($this->userid > 0) {
            $update_chat = $this->model_chat->update_model(
                array(
                    'where' => array(
                        'chat_signup2' => $this->userid
                    )
                ),
                array(
                    'chat_seen' => STATUS_ACTIVE
                )
            );
            if ($update_chat) {
                $json_param['status'] = STATUS_TRUE;
            } else {
                $json_param['status'] = STATUS_FALSE;
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
        }
        echo json_encode($json_param);
    }

    /**
     * Method save_availability_slot
     *
     * @return void
     */
    public function save_availability_slot(): void
    {
        $json_param = array();
        $affect_param = array();
        $affected = 0;
        $calendar_signup_id = $this->userid;
        $requester_detail = array();

        if (isset($_POST) && $this->userid > 0) {
            if (isset($_POST['signup_availability_id']) && $_POST['signup_availability_id']) {

                $signup_availability_id = $_POST['signup_availability_id'];
                $signup_availability = $this->model_signup_availability->find_by_pk($signup_availability_id);

                if (!empty($signup_availability)) {

                    $affect_param = isset($_POST['signup_availability']) ? $_POST['signup_availability'] : array();
                    //
                    if(isset($_POST['slot_title']) && !isset($affect_param['signup_availability_title'])) {
                        $affect_param['signup_availability_title'] = $_POST['slot_title'];
                    }

                    //
                    $calendar_signup_id = $signup_availability['signup_availability_signup_id'];
                    if(isset($affect_param['signup_availability_requester_id'])) {
                        $requester_detail = $this->model_signup->find_by_pk($affect_param['signup_availability_requester_id']);
                    }
                    //
                    if ($requester_detail) {
                        $post_fields = array(
                            'agenda' => $affect_param['signup_availability_purpose'],
                            'settings' => array(
                                'auto_recording' => 'cloud',
                                'contact_email' => $requester_detail['signup_email'],
                                'contact_name' => $this->model_signup->profileName($requester_detail),
                                'join_before_host' => 'true',
                                'meeting_authentication' => 'false',
                                'mute_upon_entry' => 'true',
                            ),
                            'start_time' => date('Y-m-d\TH:i:s\Z', strtotime($signup_availability['signup_availability_start'])),
                            'type' => 2,
                        );

                        //
                        $headers = $this->getZoomBearerHeader();
                        $url = str_replace('{userId}', 'me', ZOOM_CREATE_MEETING_URL);
                        $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                        $decoded_response = json_decode($response);

                        $affect_param['signup_availability_meeting_response'] = $response;
                        if (($decoded_response && isset($decoded_response->start_url) && NULL !== $decoded_response->start_url) || (in_array($this->session->userdata['last_http_status'], [200, 204], TRUE))) {
                            $affect_param['signup_availability_meeting_start_url'] = $decoded_response->start_url;
                            $affect_param['signup_availability_meeting_join_url'] = $decoded_response->join_url;
                            $affect_param['signup_availability_meeting_uuid'] = $decoded_response->uuid;
                            $affect_param['signup_availability_meeting_fetchid'] = $decoded_response->id;
                        }
                    }

                    //
                    $affected = $this->model_signup_availability->update_by_pk($signup_availability_id, $affect_param);
                }
            } else {
                $affect_param['signup_availability_signup_id'] = $this->userid;
                $affect_param['signup_availability_title'] = isset($_POST['slot_title']) ? $_POST['slot_title'] : "My availability slot.";
                $affect_param['signup_availability_start'] = $_POST['start_time'];
                $affect_param['signup_availability_end'] = $_POST['end_time'];
                //
                $affected = $this->model_signup_availability->insert_record($affect_param);
            }

            //
            if ($affected) {

                //
                $slots = array();
                // 'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
                $slots = $this->model_signup_availability->find_all_active(
                    array(
                        'where' => array(
                            'signup_availability_signup_id' => $calendar_signup_id,
                        ),
                        'joins' => array(
                            0 => array(
                                'table' => 'signup',
                                'joint' => 'signup_availability.signup_availability_signup_id = signup.signup_id',
                                'type'  => 'left'
                            )
                        )
                    )
                );

                //
                $json_param['slots'] = array();
                //
                if (!empty($slots)) {
                    foreach ($slots as $key => $value) {
                        $meeting_recording = NULL;
                        try {
                            if ($value['signup_availability_meeting_uuid']) {
                                $meeting_recording = $this->getZoomMeetingRecording($value['signup_availability_meeting_uuid']);
                            }
                        } catch (\Exception $e) {
                            log_message('ERROR', $e->getMessage());
                        }

                        $json_param['slots'][] = [
                            'id' => $value['signup_availability_id'],
                            'email' => $value['signup_email'],
                            'title' => ($value['signup_availability_purpose'] ?? $value['signup_availability_title']) . ($value['signup_email'] ? ' - Requester: ' . $value['signup_email'] : ''),
                            'purpose' => $value['signup_availability_purpose'],
                            'color' => ($value['signup_availability_type'] == SLOT_LOCKED) ? SLOT_LOCKED_COLOR : SLOT_AVAILABLE_COLOR,
                            'type' => CALENDAR_TYPE_SLOT, //$value['signup_availability_type'],
                            'start' => $value['signup_availability_start'],
                            'end' => $value['signup_availability_end'],
                            'start_url' => $value['signup_availability_meeting_start_url'],
                            'join_url' => $value['signup_availability_meeting_join_url'],
                            'current_status' => $value['signup_availability_meeting_current_status'],
                            'meeting_recording' => $meeting_recording ? json_decode($meeting_recording) : ''
                        ];
                    }
                }

                $json_param['slot'] = $this->model_signup_availability->userAvailabilitySlot($affected, $this->userid);
                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("Availability slot saved successfully!");
            } else {
                $json_param['slot'] = '{}';
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['slot'] = '{}';
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete_availability_slot
     *
     * @return void
     */
    public function delete_availability_slot(): void
    {
        $json_param = array();

        if ($this->userid > 0) {
            if (isset($_POST['id'])) {

                $param = array();
                $delete_param = array();
                $delete_param['signup_availability_signup_id'] = $param['where']['signup_availability_signup_id'] = $this->userid;
                $delete_param['signup_availability_id'] = $param['where']['signup_availability_id'] = $_POST['id'];

                $availability_slot = $this->model_signup_availability->find_one_active($param);

                if (!empty($availability_slot)) {
                    $json_param['slot'] = $this->model_signup_availability->userAvailabilitySlot($availability_slot['signup_availability_id'], $this->userid);
                    $this->model_signup_availability->delete_record($delete_param);
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = __("Slot deleted!");
                } else {
                    $json_param['slot'] = '{}';
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['slot'] = '{}';
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['slot'] = '{}';
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method signupFollow
     *
     * @return void
     */
    public function signupFollow(): void
    {
        $error = FALSE;
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $reference_details = array();

        if ($this->userid > 0) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST)) {
                    $reference = isset($_POST['reference']) && $_POST['reference'] ? $_POST['reference'] : FOLLOW_REFERENCE_SIGNUP;

                    if ($reference == FOLLOW_REFERENCE_SIGNUP && $_POST['reference_id'] == $this->userid) {
                        $error = TRUE;
                    }

                    $notification_follow = '';
                    $notification_comment = '';

                    if (!$error) {
                        switch ($reference) {
                            case FOLLOW_REFERENCE_SIGNUP:
                                $reference_details = $this->model_signup->find_by_pk($_POST['reference_id']);
                                $notification_follow = NOTIFICATION_FOLLOW;
                                $notification_comment = NOTIFICATION_FOLLOW_COMMENT;
                                break;
                            case FOLLOW_REFERENCE_PRODUCT:
                                $reference_details = $this->model_product->find_one_active(
                                    array(
                                        'where' => array(
                                            'product_id' => $_POST['reference_id']
                                        ),
                                        'joins' => array(
                                            0 => array(
                                                'table' => 'signup',
                                                'joint' => 'signup.signup_id = product.product_signup_id',
                                                'type' => 'both'
                                            )
                                        )
                                    )
                                );
                                $notification_follow = NOTIFICATION_FOLLOW_PRODUCT;
                                $notification_comment = NOTIFICATION_FOLLOW_PRODUCT_COMMENT;
                                break;
                            case FOLLOW_REFERENCE_SERVICE:
                                $reference_details = $this->model_product->find_one_active(
                                    array(
                                        'where' => array(
                                            'product_id' => $_POST['reference_id']
                                        ),
                                        'joins' => array(
                                            0 => array(
                                                'table' => 'signup',
                                                'joint' => 'signup.signup_id = product.product_signup_id',
                                                'type' => 'both'
                                            )
                                        )
                                    )
                                );
                                $notification_follow = NOTIFICATION_FOLLOW_SERVICE;
                                $notification_comment = NOTIFICATION_FOLLOW_SERVICE_COMMENT;
                                break;
                            case FOLLOW_REFERENCE_TECHNOLOGY:
                                $reference_details = $this->model_product->find_one_active(
                                    array(
                                        'where' => array(
                                            'product_id' => $_POST['reference_id']
                                        ),
                                        'joins' => array(
                                            0 => array(
                                                'table' => 'signup',
                                                'joint' => 'signup.signup_id = product.product_signup_id',
                                                'type' => 'both'
                                            )
                                        )
                                    )
                                );
                                $notification_follow = NOTIFICATION_FOLLOW_TECHNOLOGY;
                                $notification_comment = NOTIFICATION_FOLLOW_TECHNOLOGY_COMMENT;
                                break;
                        }

                        if (!empty($reference_details)) {
                            $follow_details = $this->model_signup_follow->find_one(
                                array(
                                    'where' => array(
                                        'signup_follow_reference_id' => $_POST['reference_id'],
                                        'signup_follow_follower_id' => $this->userid,
                                        'signup_follow_reference_type' => $reference
                                    )
                                )
                            );

                            if (empty($follow_details)) {
                                $inserted = $this->model_signup_follow->insert_record(
                                    array(
                                        'signup_follow_reference_id' => $_POST['reference_id'],
                                        'signup_follow_follower_id' => $this->userid,
                                        'signup_follow_reference_type' => $reference
                                    )
                                );
                                if ($inserted) {
                                    //
                                    $this->model_notification->sendNotification($reference_details['signup_id'], $this->userid, $notification_follow, $_POST['reference_id'], $notification_comment);

                                    $json_param['status'] = STATUS_TRUE;
                                    $json_param['txt'] = __("Success!");
                                } else {
                                    $json_param['status'] = STATUS_FALSE;
                                    $json_param['txt'] = __(ERROR_MESSAGE_INSERT);
                                }
                            } else {
                                if ($follow_details['signup_follow_status']) {
                                    $follow_status = STATUS_INACTIVE;
                                } else {
                                    $follow_status = STATUS_ACTIVE;
                                }
                                $updated = $this->model_signup_follow->update_model(
                                    array(
                                        'where' => array(
                                            'signup_follow_reference_id' => $_POST['reference_id'],
                                            'signup_follow_follower_id' => $this->userid
                                        ),
                                    ),
                                    array(
                                        'signup_follow_status' => $follow_status
                                    )
                                );
                                if ($updated) {
                                    //
                                    if ($follow_status) {
                                        $this->model_notification->sendNotification($reference_details['signup_id'], $this->userid, $notification_follow, $_POST['reference_id'], $notification_comment);
                                    }

                                    $json_param['status'] = STATUS_TRUE;
                                    $json_param['txt'] = __(SUCCESS_MESSAGE);
                                } else {
                                    $json_param['status'] = STATUS_FALSE;
                                    $json_param['txt'] = __(ERROR_MESSAGE_UPDATE);
                                }
                            }
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method get_profile_viewers
     *
     * @return void
     */
    public function get_profile_viewers(): void
    {
        $json_param = array();

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
                if ($this->model_signup->hasPremiumPermission()) {
                    if (isset($_POST['user_id']) && isset($_POST['date'])) {
                        $prefileViewers = $this->model_signup_analytics->find_all_active(
                            array(
                                'where' => array(
                                    'signup_analytics_signup_id' => $_POST['user_id'],
                                    'signup_analytics_date' => date('Y-m-d', strtotime($_POST['date'])),
                                    'signup_analytics_type' => ANALYTICS_TYPE_VIEW
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'signup',
                                        'joint' => 'signup.signup_id = signup_analytics.signup_analytics_referer_id',
                                        'type' => 'both'
                                    ),
                                    1 => array(
                                        'table' => 'signup_info',
                                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                        'type' => 'both'
                                    )
                                )
                            )
                        );

                        $html = '';

                        foreach ($prefileViewers as $key => $value) {
                            $html .= '<div id="reacted_users_box" class="wo_react_ursrs_list">' .
                                '<div class="who_react_to_this_user">' .
                                '<div class="who_react_to_this_user_info">' .
                                '<div class="avatar pull-left" id="inline_emo_react">' .
                                '<a href="' . l('dashboard/profile/detail/' . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type']) . '">' .
                                '<img src="' . get_image($value['signup_logo_image_path'], $value['signup_logo_image']) . '" onerror=this.onerror=null;this.src="' . g("images_root") . 'user.png' . '";>' .
                                '</a>' .
                                '</div>' .
                                '<div>' .
                                '<span class="user-popover views_info_count">' .
                                '<a href="' . l('dashboard/profile/detail/' . JWT::encode($value['signup_id']), CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] . '">' .
                                '<p>' . $this->model_signup->profileName($value, FALSE) . '</p>' .
                                '</a>' .
                                '</span>' .
                                '</div>' .
                                '</div>' .
                                '</div>' .
                                '</div>';
                        }

                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = '';
                        $json_param['data'] = $prefileViewers;
                        $json_param['html'] = $html;
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                        $json_param['data'] = [];
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
                    $json_param['data'] = [];
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
                $json_param['data'] = [];
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            $json_param['data'] = [];
        }
        echo json_encode($json_param);
    }

    /**
     * Method fetchSubAccounts
     *
     * @return void
     */
    public function fetchSubAccounts()
    {
        $json_param = array();
        $json_param['result'] = '{}';
        if (isset($_POST['AccountType'])) {
            $AccountType = $this->model_quickbook_account_repo->find_one_active(
                array(
                    'where' => array(
                        'quickbook_account_repo_type' => $_POST['AccountType']
                    )
                )
            );
            $AccountSubType = $this->model_quickbook_account_repo->find_all_active(
                array(
                    'where' => array(
                        'quickbook_account_repo_parent' => $AccountType['quickbook_account_repo_id'],
                        'quickbook_account_repo_is_subtype' => 1
                    )
                )
            );

            $options = '';
            foreach ($AccountSubType as $key => $value) {
                $options .= '<option value="' . $value['quickbook_account_repo_type'] . '"  ' . (isset($_POST["selected"]) && $_POST["selected"] == $value["quickbook_account_repo_type"] ? "selected" : "") . '>' . $value['quickbook_account_repo_type'] . '</option>';
            }
            $json_param['status'] = 1;
            $json_param['txt'] = __(SUCCESS_MESSAGE);
            $json_param['result'] = $options;
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method send_comment
     *
     * @return void
     */
    public function send_comment()
    {
        $json_param = array();
        if ($this->userid > 0) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['comment']) && count($_POST['comment']) > 0) {
                    if (!$this->model_signup->hasPremiumPermission()) {
                        $json_param['status'] = 0;
                        $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
                    } else {
                        $insert_param = $_POST['comment'];

                        $inserted = $this->model_comment->insert_record($insert_param);
                        if ($inserted) {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __(SUCCESS_MESSAGE);
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __(ERROR_MESSAGE);
                        }
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
        }

        echo json_encode($json_param);
    }

    /**
     * Method comment_reaction
     *
     * @return void
     */
    public function comment_reaction()
    {
        $json_param = array();
        $showError = STATUS_TRUE;
        $referenceDetails = array();

        if ($this->userid > 0) {
            // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['comment_id']) && isset($_POST['reaction']) && isset($_POST['reference_id']) && isset($_POST['type'])) {

                $referenceDetails = $this->{'model_' . $_POST['type']}->find_by_pk($_POST['reference_id']);

                if (!empty($referenceDetails)) {

                    $myReaction = $this->model_comment_reaction->find_one_active(
                        array(
                            'where' => array(
                                'comment_reaction_reference_id' => $_POST['reference_id'],
                                'comment_reaction_comment_id' => $_POST['comment_id'],
                                'comment_reaction_userid' => $this->userid
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = comment_reaction.comment_reaction_userid',
                                    'type' => 'both'
                                ),
                                1 => array(
                                    'table' => 'signup_info',
                                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                    'type' => 'both'
                                )
                            )
                        )
                    );

                    $insert_param = array();
                    $update_param = array();
                    $where_param = array();

                    $where_param['where']['comment_reaction_comment_id'] = $insert_param['comment_reaction_comment_id'] = $_POST['comment_id'];
                    $where_param['where']['comment_reaction_reference_id'] = $insert_param['comment_reaction_reference_id'] = $_POST['reference_id'];
                    $where_param['where']['comment_reaction_userid'] = $insert_param['comment_reaction_userid'] = $this->userid;

                    if ($_POST['reaction']) {
                        $update_param['comment_reaction_text'] = $insert_param['comment_reaction_text'] = $_POST['reaction'];

                        if (empty($myReaction)) {
                            $inserted = $this->model_comment_reaction->insert_record($insert_param);
                        } else {
                            $inserted = $this->model_comment_reaction->update_model($where_param, $update_param);
                            $showError = STATUS_FALSE;
                        }
                    } else {
                        $inserted = $this->model_comment_reaction->delete_record($where_param['where']);
                        $showError = STATUS_FALSE;
                    }

                    if ($inserted || !$showError) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = "";
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
            // } else {
            //     $json_param['status'] = STATUS_FALSE;
            //     $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            // }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
        }
        echo json_encode($json_param);
    }

    /**
     * Method get_comment_reaction_users
     *
     * @return void
     */
    public function get_comment_reaction_users()
    {
        $json_param = array();

        if ($this->userid > 0) {
            if (isset($_POST['comment_id']) && isset($_POST['reference_id'])) {
                $referenceDetails = $this->{'model_' . $_POST['type']}->find_by_pk($_POST['reference_id']);

                if (!empty($referenceDetails)) {
                    $where_param = array();
                    $where_param['comment_reaction_reference_id'] = $_POST['reference_id'];
                    $where_param['comment_reaction_comment_id'] = $_POST['comment_id'];
                    if (isset($_POST['reaction'])) {
                        $where_param['comment_reaction_text'] = $_POST['reaction'];
                    }

                    $commentReactions = $this->model_comment_reaction->find_all_active(
                        array(
                            'where' => $where_param,
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = comment_reaction.comment_reaction_userid',
                                    'type' => 'both'
                                ),
                                1 => array(
                                    'table' => 'signup_info',
                                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                    'type' => 'both'
                                )
                            ),
                            'fields' => 'signup_id, signup_type, signup_firstname, signup_lastname, signup_email, signup_fullname, signup_logo_image, signup_logo_image_path, signup_info_isonline'
                        )
                    );

                    $html = "";
                    foreach ($commentReactions as $value) {
                        $html .= '<div id="reacted_users_box" class="wo_react_ursrs_list">' .
                            '<div class="who_react_to_this_user">' .
                            '<div class="who_react_to_this_user_info">' .
                            '<div class="avatar pull-left" id="inline_emo_react">' .
                            '<a href="' . l('dashboard/profile/detail/' . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type']) . '">' .
                            '<img src="' . get_image($value['signup_logo_image_path'], $value['signup_logo_image']) . '" onerror=this.onerror=null;this.src="' . g("images_root") . 'user.png' . '";>' .
                            '</a>' .
                            '</div>' .
                            '<div>' .
                            '<span class="user-popover views_info_count" data-id="9" data-row-id="23" data-type="user">' .
                            '<a href="' . l('dashboard/profile/detail/') . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] . '">' .
                            '<p>' . $this->model_signup->profileName($value, FALSE) . '</p>' .
                            '</a>' .
                            '</span>' .
                            '</div>' .
                            '</div>' .
                            '</div>' .
                            '</div>';
                    }

                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = '';
                    $json_param['data'] = $commentReactions;
                    $json_param['html'] = $html;
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                    $json_param['data'] = [];
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
                $json_param['data'] = [];
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            $json_param['data'] = [];
        }
        echo json_encode($json_param);
    }

    /**
     * Method save_review
     *
     * @return void
     */
    public function save_review()
    {
        $json_param = array();

        if ($this->userid > 0) {
            if (isset($_POST['review']) && isset($_POST['review']['review_reference_id'])) {
                $model = 'model_' . $_POST['review']['review_type'];
                $reference = $this->$model->find_by_pk($_POST['review']['review_reference_id']);
                if (!empty($reference)) {
                    //
                    if (isset($_POST['review_id'])) {
                        $review_affected = $this->model_review->update_by_pk($_POST['review_id'], $_POST['review']);
                    } else {
                        $review_affected = $this->model_review->insert_record($_POST['review']);
                    }

                    if ($review_affected) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method refreshCount
     *
     * @return void
     */
    public function refreshCount(): void
    {
        $json_param = array();
        $json_param['notification_status'] = STATUS_FALSE;
        $json_param['chat_status'] = STATUS_FALSE;

        // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
                if (!empty($_POST)) {
                    if (isset($_POST['seen_notifications'])) {
                        $seen_notifications = $this->model_notification->find_count_active(
                            array(
                                'where' => array(
                                    'notification_signup_id' => $this->userid,
                                    'notification_seen' => STATUS_FALSE
                                )
                            )
                        );
                        if ($seen_notifications != $_POST['seen_notifications']) {
                            $json_param['notification_status'] = STATUS_TRUE;
                            $json_param['notification_data'] = $this->pushNotification();
                        } else {
                            $json_param['notification_txt'] = 'No new notification';
                        }
                    }
                    if (isset($_POST['seen_chat'])) {
                        $seen_chat = $this->model_chat->find_count_active(
                            array(
                                'where' => array(
                                    'chat_signup2' => $this->userid,
                                    'chat_seen' => STATUS_FALSE,
                                    'chat_reference_type' => CHAT_REFERENCE_EMAIL,
                                )
                            )
                        );
                        if ($seen_chat != $_POST['seen_chat']) {
                            $json_param['chat_status'] = STATUS_TRUE;
                        } else {
                            $json_param['chat_txt'] = 'No new message';
                        }
                    }
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
            }
        // } else {
        //     $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        // }
        echo json_encode($json_param);
    }

    /**
     * Method pushNotification
     *
     * @return void
     */
    function pushNotification(): array
    {
        $data = array();
        $notifications = $this->model_notification->find_all_active(
            array(
                'order' => 'notification_id desc',
                'where' => array(
                    'notification_signup_id' => $this->userid,
                    'notification_alert_seen' => STATUS_FALSE,
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = notification.notification_from',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    ),
                )
            )
        );

        if (!empty($notifications)) {
            foreach ($notifications as $key => $value) {
                $data[$key] = array(
                    'title' => g('site_name'),
                    'icon' => ($this->layout_data['logo']['logo_image_path'] . $this->layout_data['logo']['logo_image']),
                    'body' =>  $this->model_signup->profileName($value) . ' ' . $value['notification_comment'],
                    'url' => $this->model_notification->notificationRedirection($value)
                );
            }
        }
        return $data;
    }

    /**
     * Method getSearchData
     *
     * @return void
     */
    function getSearchData(): void
    {
        $search_result = "";

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['search']) && $_POST['search']) {
                $search = $_POST['search'];

                $signup_search = $this->model_signup->find_all_active(
                    array(
                        'limit' => PER_PAGE,
                        'where_like' => array(
                            0 => array(
                                'column' => 'signup_firstname',
                                'value' => $search,
                                'type' => 'both',
                            )
                        ),
                        'or_like' => array(
                            0 => array(
                                'column' => 'signup_lastname',
                                'value' => $search,
                                'type' => 'both',
                            )
                        ),
                        'or_like' => array(
                            0 => array(
                                'column' => 'signup_email',
                                'value' => $search,
                                'type' => 'both',
                            )
                        ),
                        0 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_company',
                            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                            'type' => 'left'
                        ),
                    )
                );

                $product_search = $this->model_product->find_all_active(
                    array(
                        'limit' => PER_PAGE,
                        'where' => array(
                            'product_reference_type' => PRODUCT_REFERENCE_PRODUCT
                        ),
                        'where_like' => array(
                            0 => array(
                                'column' => 'product_name',
                                'value' => $search,
                                'type' => 'both',
                            )
                        ),
                        'or_like' => array(
                            0 => array(
                                'column' => 'product_number',
                                'value' => $search,
                                'type' => 'both',
                            )
                        )
                    )
                );

                $service_search = $this->model_product->find_all_active(
                    array(
                        'limit' => PER_PAGE,
                        'where' => array(
                            'product_reference_type' => PRODUCT_REFERENCE_SERVICE
                        ),
                        'where_like' => array(
                            0 => array(
                                'column' => 'product_name',
                                'value' => $search,
                                'type' => 'both',
                            )
                        ),
                        'or_like' => array(
                            0 => array(
                                'column' => 'product_number',
                                'value' => $search,
                                'type' => 'both',
                            )
                        )
                    )
                );

                $technology_search = $this->model_product->find_all_active(
                    array(
                        'limit' => PER_PAGE,
                        'where' => array(
                            'product_reference_type' => PRODUCT_REFERENCE_TECHNOLOGY
                        ),
                        'where_like' => array(
                            0 => array(
                                'column' => 'product_name',
                                'value' => $search,
                                'type' => 'both',
                            )
                        ),
                        'or_like' => array(
                            0 => array(
                                'column' => 'product_number',
                                'value' => $search,
                                'type' => 'both',
                            )
                        )
                    )
                );

                $search_result = '<table class="searchTable">';
                if (empty($signup_search) && empty($product_search) && empty($technology_search) && empty($service_search)) {
                    $search_result .= '<tr>';
                    $search_result .= '<td>No data macthed your searched keyword!</td>';
                    $search_result .= '</tr>';
                } else {
                    if (!empty($signup_search)) {
                        $search_result .= '<tr class="header">';
                        $search_result .= '<th>Users</th>';
                        $search_result .= '</tr>';
                        foreach ($signup_search as $value) {
                            $search_result .= '<tr>';
                            $search_result .= '<td><a href="' . l('dashboard/profile/detail/' . JWT::encode($value['signup_id']) . '/' . $value['signup_type']) . '">' . $this->model_signup->profileName($value) . '</a></td>';
                            $search_result .= '</tr>';
                        }
                    }
                    if (!empty($product_search)) {
                        $search_result .= '<tr class="header">';
                        $search_result .= '<th>Products</th>';
                        $search_result .= '</tr>';
                        foreach ($product_search as $value) {
                            $search_result .= '<tr>';
                            $search_result .= '<td><a href="' . l('dashboard/product/detail/' . $value['product_slug']) . '">' . $value['product_name'] . '</a></td>';
                            $search_result .= '</tr>';
                        }
                    }
                    if (!empty($technology_search)) {
                        $search_result .= '<tr class="header">';
                        $search_result .= '<th>Technology</th>';
                        $search_result .= '</tr>';
                        foreach ($technology_search as $value) {
                            $search_result .= '<tr>';
                            $search_result .= '<td><a href="' . l('dashboard/product/detail/' . $value['product_slug']) . '">' . $value['product_name'] . '</a></td>';
                            $search_result .= '</tr>';
                        }
                    }
                    if (!empty($service_search)) {
                        $search_result .= '<tr class="header">';
                        $search_result .= '<th>Services</th>';
                        $search_result .= '</tr>';
                        foreach ($service_search as $value) {
                            $search_result .= '<tr>';
                            $search_result .= '<td><a href="' . l('dashboard/product/detail/' . $value['product_slug']) . '">' . $value['product_name'] . '</a></td>';
                            $search_result .= '</tr>';
                        }
                    }
                }
                $search_result .= '</table>';
            }
        }
        echo $search_result;
    }
}
