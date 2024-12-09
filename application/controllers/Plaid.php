<?php

declare(strict_types=1);

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Plaid - action class (views in dashboard/home)
 */
class Plaid extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method link
     *
     * @param string $type
     * @param string $income_type
     *
     * @return void
     */
    public function link(string $type = 'auth', string $income_type = ''): void
    {
        if ($this->userid > 0) {
            if (in_array($type, PLAID_LINK_TYPE)) {
                $param = array();
                $param['where']['inner_banner_name'] = 'Plaid';
                $data['banner'] = $this->model_inner_banner->find_one_active($param);

                $data['type'] = $type;
                $data['income_type'] = $income_type;

                switch ($type) {
                    case PLAID_TYPE_AUTH:
                        if ($this->plaid_token) {
                            $this->session->set_flashdata('plaid_reuathenticate', 'The user has already been authenticated.');
                            redirect(l(''));
                        }
                        break;
                    case PLAID_TYPE_INCOME:
                        if ($this->plaid_token) {
                            if (!in_array($income_type, PLAID_INCOME_TYPE)) {
                                $this->session->set_flashdata('error', ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                                redirect(l(''));
                            }
                        }
                        break;
                }

                //
                $this->layout_data['title'] = 'Plaid Authorization | ' . $this->layout_data['title'];
                //
                $this->load_view('index', $data);
            } else {
                $this->session->set_flashdata('error', __(ERROR_MESSAGE_RESOURCE_NOT_FOUND));
                redirect(l(''));
            }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l('login?redirect_url=' . urlencode(l('plaid/link/') . $type . ($income_type ? ('/' . $income_type) : ''))));
        }
    }

    /**
     * Method generate_token
     *
     * @return void
     */
    public function generate_token(): void
    {
        global $config;

        $json_param = array();
        $access_token = '';
        $income_error = FALSE;
        $transfer_error = FALSE;
        $milestone = array();

        if ($this->userid > 0) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

                if (isset($_POST['type'])) {

                    $postArray = array();

                    if (isset($_POST['income_type']) && $_POST['income_type'] && !in_array($_POST['income_type'], PLAID_INCOME_TYPE)) {
                        $income_error = TRUE;
                    }

                    if ($_POST['type'] == PLAID_TYPE_TRANSFER) {
                        $milestone = $this->model_job_milestone->find_one_active(
                            array(
                                'where' => array(
                                    'job_milestone_id' => isset($_POST['milestone_id']) ? $_POST['milestone_id'] : 0
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'job_application',
                                        'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                                        'type' => 'both'
                                    ),
                                    1 => array(
                                        'table' => 'signup',
                                        'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                                        'type' => 'both'
                                    )
                                )
                            )
                        );

                        if ($this->user_data['signup_plaid_account_id']) {
                            if (!isset($_POST['transfer_intent_id']) || (isset($_POST['transfer_intent_id']) && !$_POST['transfer_intent_id'])) {
                                $transfer_error = TRUE;
                            }
                        }
                        if ($this->user_data['signup_plaid_transfer_response']) {
                            $account_transfer_response = $this->jwt_decode($this->user_data['signup_plaid_transfer_response'], CI_ENCRYPTION_SECRET);

                            if ($account_transfer_response && property_exists($account_transfer_response, 'access_token')) {
                                $access_token = $account_transfer_response->access_token;
                            }
                        }
                    }
                    
                    switch ($_POST['type']) {
                        case PLAID_TYPE_AUTH:
                            $postArray = array(
                                "client_id" => PLAID_CLIENT_ID,
                                "secret" => PLAID_CLIENT_SECRET,
                                "user" => array(
                                    "client_user_id" => PLAID_CLIENT_PREFIX . time() . '-' . $this->userid
                                ),
                                "client_name" => $config['title'],
                                "products" => array(PLAID_TYPE_AUTH),
                                "country_codes" => array("US"),
                                "language" => "en",
                                "account_filters" => array(
                                    "depository" => array(
                                        "account_subtypes" => array("checking")
                                    )
                                )
                            );
                            break;
                        case PLAID_TYPE_INCOME:
                            $userToken = $this->createPlaidUser();
                            switch ($_POST['income_type']) {
                                case PLAID_BANK_INCOME:
                                    $postArray = array(
                                        "client_id" => PLAID_CLIENT_ID,
                                        "secret" => PLAID_CLIENT_SECRET,
                                        "user_token" => $userToken,
                                        "user" => array(
                                            "client_user_id" => PLAID_CLIENT_PREFIX . time() . '-' . $this->userid
                                        ),
                                        "client_name" => $config['title'],
                                        "products" => array("income_verification", "transactions", "assets"),
                                        "income_verification" => array(
                                            "income_source_types" => array(PLAID_BANK_INCOME),
                                            "bank_income" => array(
                                                "days_requested" => 360,
                                                "enable_multiple_items" => TRUE,
                                            ),
                                        ),
                                        "webhook" => l('plaid/webhook'),
                                        "country_codes" => array("US"),
                                        "language" => "en",
                                    );
                                    break;
                                case PLAID_PAYROLL_INCOME:
                                    $postArray = array(
                                        "client_id" => PLAID_CLIENT_ID,
                                        "secret" => PLAID_CLIENT_SECRET,
                                        "user_token" => $userToken,
                                        "user" => array(
                                            "client_user_id" => PLAID_CLIENT_PREFIX . time() . '-' . $this->userid
                                        ),
                                        "client_name" => $config['title'],
                                        "products" => array("income_verification"),
                                        "income_verification" => array(
                                            "income_source_types" => array(PLAID_PAYROLL_INCOME),
                                        ),
                                        "webhook" => l('plaid/webhook'),
                                        "country_codes" => array("US"),
                                        "language" => "en",
                                    );
                                    break;
                            }
                            break;
                        case PLAID_TYPE_TRANSFER:
                            $postArray = array(
                                "client_id" => PLAID_CLIENT_ID,
                                "secret" => PLAID_CLIENT_SECRET,
                                "user" => array(
                                    "client_user_id" => PLAID_CLIENT_PREFIX . time() . '-' . $this->userid,
                                ),
                                "client_name" => $config['title'],
                                "products" => [PLAID_TYPE_TRANSFER],
                                "country_codes" => ['US'],
                                "language" => "en",
                                "link_customization_name" => 'transfer_ui'
                            );
                            if (isset($_POST['transfer_intent_id'])) {
                                $postArray["transfer"] = array(
                                    "intent_id" => isset($_POST['transfer_intent_id']) ? $_POST['transfer_intent_id'] : ''
                                );
                            }
                            if ($access_token) {
                                $postArray["access_token"] = $access_token;
                            }
                            $json_param['account_id'] = $this->user_data['signup_plaid_account_id'];
                            break;
                    }
                    
                    if (!$income_error && !$transfer_error) {
                        
                        $url = PLAID_API_URL . PLAID_CREATE_LINK_TOKEN;
                        
                        $headers = array();
                        $headers[] = 'Content-Type: application/json';

                        //
                        $response = $this->curlRequest($url, $headers, $postArray, TRUE);
                        $decoded_response = json_decode($response);

                        $json_param['link_token'] = '';
                        $json_param['expiration'] = '';

                        if (!$decoded_response) {
                            $json_param['status'] = FALSE;
                            $json_param['message'] = __(ERROR_MESSAGE);
                        } else {
                            if (isset($decoded_response->error_type) && null !== $decoded_response->error_type) {
                                $json_param['status'] = FALSE;
                                $json_param['message'] = isset($decoded_response->error_message) ? $decoded_response->error_message : ERROR_MESSAGE;
                            } else {
                                $json_param['status'] = TRUE;
                                $json_param['link_token'] = $decoded_response->link_token;
                                $json_param['expiration'] = $decoded_response->expiration;
                                $json_param['message'] = __(SUCCESS_MESSAGE);
                            }
                        }
                    } else {
                        $json_param['status'] = FALSE;
                        $json_param['message'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                    }
                } else {
                    $json_param['status'] = FALSE;
                    $json_param['message'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                }
            } else {
                $json_param['status'] = FALSE;
                $json_param['message'] = ERROR_MESSAGE_LINK_EXPIRED;
            }
        } else {
            $json_param['status'] = FALSE;
            $json_param['message'] = ERROR_MESSAGE_LOGIN;
        }
        echo json_encode($json_param);
    }

    /**
     * Method exchange_token
     *
     * @return void
     */
    public function exchange_token(): void
    {
        $json_param = array();
        $json_param['status'] = FALSE;
        $json_param['redirect'] = '';

        // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "public_token" => $_POST['public_token']
        );

        $url = PLAID_API_URL . PLAID_PUBLIC_TOKEN_EXCHANGE;

        $headers = array();
        $headers[] = 'Content-Type: application/json';

        //
        $response = $this->curlRequest($url, $headers, $postArray, TRUE);
        $decoded_response = json_decode($response);

        if (!$decoded_response) {
            $json_param['message'] = __(ERROR_MESSAGE);
            $json_param['status'] = FALSE;
        } else {
            if (isset($decoded_response->error_type) && null !== $decoded_response->error_type) {
                $json_param['message'] = isset($decoded_response->error_message) ? $decoded_response->error_message : 'Error';
            } else {
                $income_error = FALSE;
                $approved = FALSE;

                //
                if (isset($_POST['income_type']) && $_POST['income_type']) {

                    $income_result = '';
                    switch ($_POST['income_type']) {
                        case PLAID_BANK_INCOME:
                            //
                            $this->set_signup_income($_POST['income_type'], $decoded_response);
                            $sessionInfo = $this->getInfo('session');

                            $income_result = 'bank_income_results';

                            if (property_exists($sessionInfo, 'sessions') && $income_result) {
                                foreach ($sessionInfo->sessions as $value) {
                                    if (property_exists($value->results, $income_result)) {
                                        if (property_exists($value, 'link_session_id') && $value->link_session_id == $_POST['link_session_id']) {
                                            foreach ($value->results->{$income_result} as $resultValue) {
                                                if ($resultValue->status == 'APPROVED') {
                                                    $approved = TRUE;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (!$approved) {
                                $income_error = TRUE;
                            } else {
                                $this->model_signup->update_by_pk(
                                    $this->userid,
                                    array(
                                        'signup_is_employment_verified' => STATUS_ACTIVE
                                    )
                                );
                                $json_param['message'] = 'Income verification process has been completed.';
                                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_EMPLOYMENT_CONFIRMED, 0, NOTIFICATION_EMPLOYMENT_CONFIRMED_COMMENT);
                            }
                            break;
                        case PLAID_PAYROLL_INCOME:
                            $json_param['message'] = 'Income verification process through payroll is in progress, your payroll will be verified shortly.';
                            break;
                    }
                } else {
                    switch ($_POST['type']) {
                        case PLAID_TYPE_AUTH:
                            //
                            $this->set_plaid_session($decoded_response);
                            $json_param['message'] = 'Plaid authentication successful.';
                            break;
                        case PLAID_TYPE_TRANSFER:
                            if (!$this->user_data['signup_plaid_account_id']) {
                                $account_id = isset($_POST['account_id']) && $_POST['account_id'] ? $_POST['account_id'] : '';
                                //
                                $this->set_plaid_transfer_token($decoded_response, $account_id, FALSE);
                                $json_param['message'] = 'Plaid transfer connection successful.';
                            }
                            break;
                    }
                }

                if (!$income_error) {
                    $json_param['status'] = TRUE;
                    $json_param['redirect'] = $this->session->has_userdata('plaid_intended') ? $this->session->userdata('plaid_intended') : l('dashboard/plaid');
                } else {
                    $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED, 0, NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED_COMMENT);
                    // error only set to true inside income_type condition
                    $json_param['message'] = 'Income verification process has been failed.';
                }
            }
        }
        // } else {
        //     $json_param['message'] = ERROR_MESSAGE_LINK_EXPIRED;
        // }
        echo json_encode($json_param);
    }

    /**
     * Method set_plaid_session
     *
     * @param object $decoded_response
     *
     * @return int
     */
    public function set_plaid_session(object $decoded_response): int
    {
        $plaidArray = array(
            'access_token' => $decoded_response->access_token,
            'item_id' => $decoded_response->item_id,
            'request_id' => $decoded_response->request_id,
        );

        $this->session->set_userdata(
            'plaid',
            $plaidArray
        );

        return $this->model_signup->update_by_pk(
            $this->userid,
            array(
                'signup_plaid_token' => $this->jwt_encode($plaidArray, CI_ENCRYPTION_SECRET),
            )
        );
    }

    /**
     * Method set_plaid_transfer_token
     *
     * @param object $decoded_response
     * @param string $account_id
     *
     * @return int
     */
    function set_plaid_transfer_token(object $decoded_response, string $account_id): int
    {
        return $this->model_signup->update_by_pk(
            $this->userid,
            array(
                'signup_plaid_transfer_response' => $this->jwt_encode($decoded_response, CI_ENCRYPTION_SECRET),
                'signup_plaid_account_id' => $account_id
            )
        );
    }

    /**
     * Method set_signup_income
     *
     * @param string $income_type
     * @param object $decoded_response
     *
     * @return int
     */
    public function set_signup_income(string $income_type, object $decoded_response): int
    {
        switch ($income_type) {
            case PLAID_BANK_INCOME:
                $affect_array = array(
                    'signup_plaid_bank_income_response' => $this->jwt_encode($decoded_response)
                );
                break;
            case PLAID_PAYROLL_INCOME:
                $affect_array = array(
                    'signup_plaid_payroll_income_response' => $this->jwt_encode($decoded_response)
                );
                break;
        }
        return $this->model_signup->update_by_pk($this->userid, $affect_array);
    }

    /**
     * Method get_balance
     *
     * @return void
     */
    public function get_balance()
    {
        $json_param = array();
        $balance = 0;
        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['account_id']) && $_POST['account_id']) {
                $balance = $this->plaid->accounts->getBalance($this->plaid_token->access_token, ['account_ids' => [$_POST['account_id']]]);

                $json_param['status'] = TRUE;
                $json_param['txt'] = __(SUCCESS_MESSAGE);
                $json_param['obj'] = $balance;
                $json_param['balance'] = $balance->accounts[0]->balances->available;
            } else {
                $json_param['status'] = FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['status'] = FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method createPlaidUser
     *
     * @return void
     */
    public function createPlaidUser()
    {
        $user_token = 0;
        if ($this->userid > 0) {
            $headers = array();
            $headers[] = 'Content-Type: application/json';

            if (!$this->user_data['signup_plaid_user_response'] || !$this->user_data['signup_plaid_user_id']) {

                $postArray = array(
                    "client_id" => PLAID_CLIENT_ID,
                    "secret" => PLAID_CLIENT_SECRET,
                    "client_user_id" => $this->jwt_encode(PLAID_CLIENT_PREFIX . time() . '-' . $this->userid)
                );
                $url = PLAID_API_URL . PLAID_CREATE_USER;
            
                $response = $this->curlRequest($url, $headers, $postArray, TRUE);
                $decoded_response = json_decode($response);

                if ($decoded_response) {
                    if (property_exists($decoded_response, 'user_token')) {
                        $updated = $this->model_signup->update_by_pk(
                            $this->userid,
                            array(
                                'signup_plaid_user_response' => $this->jwt_encode($decoded_response),
                                'signup_plaid_user_id' => $decoded_response->user_id
                            )
                        );
                        if ($updated) {
                            $user_token = $decoded_response->user_token;
                        }
                    }
                }
            } else {
                $decoded_response = $this->jwt_decode($this->user_data['signup_plaid_user_response'], CI_ENCRYPTION_SECRET);
                log_message('ERROR', serialize($decoded_response));
                if ($decoded_response) {
                    if (property_exists($decoded_response, 'user_token')) {
                        $user_token = $decoded_response->user_token;
                    } // else we can empty signup_plaid_user_response column and call this function recursive
                } // else we can empty signup_plaid_user_response column and call this function recursive
            }
        }
        return $user_token;
    }

    /**
     * Method getInfo - http://localhost/azaverze/plaid/getInfo/transfer/7dbc72b6-a28d-aed5-3f09-d3fdf3415729/1
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
        if ($type && in_array($type, ['bank', 'payroll', 'employment', 'session', 'transfer'])) {
            if ($this->userid > 0) {
                $headers = array();
                $headers[] = 'Content-Type: application/json';

                $postArray = array(
                    "client_id" => PLAID_CLIENT_ID,
                    "secret" => PLAID_CLIENT_SECRET,
                );

                if ($type == 'session') {
                    $postArray["user_token"] = $this->createPlaidUser();
                }

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

    /**
     * Method webhook
     *
     * @return void
     */
    public function webhook()
    {
        //
        $this->_log_message(LOG_TYPE_GENERAL, LOG_SOURCE_PLAID, LOG_LEVEL_INFO, serialize(file_get_contents('php://input')));
        $this->_log_message(LOG_TYPE_GENERAL, LOG_SOURCE_PLAID, LOG_LEVEL_INFO, serialize($_SERVER));
        //
        $payload = file_get_contents('php://input');
        $payload = json_decode($payload, true);
        $header = $_SERVER;

        // $payload = array(
        //     "environment" => "sandbox",
        //     "item_id"=> "1q19XQywZ7CQN8b8PNBpUKxlmQl4yrS53KGbj",
        //     "user_id"=> "a27d1db2b9d562de87a8c6108ba33a5282ef449fcfaa84ae8510ec9ff3e13574",
        //     "verification_status"=> "VERIFICATION_STATUS_PROCESSING_COMPLETE",
        //     "webhook_code"=> "INCOME_VERIFICATION",
        //     "webhook_type"=> "INCOME"
        // );

        $signed_jwt = isset($header['HTTP_PLAID_VERIFICATION']) ? $header['HTTP_PLAID_VERIFICATION'] : '';
        // $signed_jwt = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjZjNTUxNmUxLTkyZGMtNDc5ZS1hOGZmLTVhNTE5OTJlMDAwMSIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE2OTQ0NTE5MjcsInJlcXVlc3RfYm9keV9zaGEyNTYiOiI4MDljMGE3YmZhMzQ1YjkzNzA2YmVlMzBiNjgxMjk5ZmEyYmYxNDk1NzgwMzdmNjgxOTZmMTlkNTI1ZjQzYjFkIn0.ckP_yPtSwjVrMYqILisifBGhBbamBB6hIdPo0hU8oNikReRjZI3pA7Wzx98Fqi4Ek2KTG28bxdAy99YSlu4HJg';
        // $signed_jwt = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjZjNTUxNmUxLTkyZGMtNDc5ZS1hOGZmLTVhNTE5OTJlMDAwMSIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE2OTQ1MjkyMTUsInJlcXVlc3RfYm9keV9zaGEyNTYiOiIwZmZhZThiMDJiMGI3MjkxNzJmM2UwYWM1NWRmYmZhZmE5YzY4NWJiODkyN2M4N2MyYjM0OWQzOTA1YmQ4NGU1In0.tGubuTn5YwfngX_9vyZ5Q9Qby6DOVCo_j579QEI179zQAnbebKnh2djlHEG84OhG0E0aVeFiuguEwUBYWhBggg';

        if ($signed_jwt && $payload && isset($payload['webhook_code']) && in_array($payload['webhook_code'], PLIAD_WEBHOOK_CODE)) {

            //
            $decoded_jwt_header = $this->jwt_decode($signed_jwt, '', FALSE, TRUE);
            $decoded_jwt_body = $this->jwt_decode($signed_jwt, '', FALSE, FALSE);

            //
            if (property_exists($decoded_jwt_header, 'alg') && $decoded_jwt_header->alg == 'ES256') {

                //
                $headers = array();
                $headers[] = 'Content-Type: application/json';

                $url = PLAID_API_URL . PLAID_GET_WEBHOOK_VERIFICATION_KEY;
                $postArray = array(
                    "client_id" => PLAID_CLIENT_ID,
                    "secret" => PLAID_CLIENT_SECRET,
                    "key_id" => $decoded_jwt_header->kid,
                );
                $response = $this->curlRequest($url, $headers, $postArray, TRUE);
                $decoded_response = json_decode($response, TRUE);

                if (isset($decoded_response['error_type']) && $decoded_response['error_type'] !== NULL) {
                    $message = isset($decoded_response['error_message']) ? $decoded_response['error_message'] : ERROR_MESSAGE;
                    log_message('ERROR', 'Plaid webhook: ' . $message);
                } else {
                    if ($decoded_response['key']['expired_at'] != null) {
                        log_message('ERROR', 'Plaid webhook: ' . 'The key has been expired.');
                        die;
                    }

                    $jwks['keys'] = [$decoded_response['key']];
                    JWT::$leeway = 5;
                    $decoded_payload = (JWT::decode($signed_jwt, JWK::parseKeySet($jwks)));

                    if ($decoded_payload->request_body_sha256 && $decoded_jwt_body->request_body_sha256) {
                        if (JWT::constantTimeEquals($decoded_payload->request_body_sha256, $decoded_jwt_body->request_body_sha256)) {
                            switch ($payload['webhook_code']) {
                                case "INCOME_VERIFICATION":
                                    if($payload['verification_status'] == 'VERIFICATION_STATUS_PROCESSING_COMPLETE') {
                                        $signup = $this->model_signup->find_one_active(
                                            array(
                                                'where' => array(
                                                    'signup_plaid_user_id' => $payload['user_id']
                                                )
                                            )
                                        );
                                        if(!empty($signup)) {
                                            $this->model_signup->update_by_pk(
                                                $signup['signup_id'],
                                                array(
                                                    'signup_is_employment_verified' => STATUS_ACTIVE
                                                )
                                            );
                                            $this->model_notification->sendNotification($signup['signup_id'], $signup['signup_id'], NOTIFICATION_EMPLOYMENT_CONFIRMED, 0, NOTIFICATION_EMPLOYMENT_CONFIRMED_COMMENT);
                                        } else {
                                            log_message('ERROR', 'Plaid webhook: ' . ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                                        }
                                    } else {
                                        $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED, 0, NOTIFICATION_EMPLOYMENT_CONFIRMED_FAILED_COMMENT);
                                        log_message('ERROR', 'Plaid webhook: ' . 'Employment confirmation failed.');
                                    }
                                break;
                            }
                        } else {
                            log_message('ERROR', 'Plaid webhook: ' . 'Mismatched body hash.');
                        }
                    } else {
                        log_message('ERROR', 'Plaid webhook: ' . ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                }
            } else {
                log_message('ERROR', 'Plaid webhook: ' . 'Algorithm not supported.');
            }
        } else {
            log_message('ERROR', 'Plaid webhook: ' . ERROR_MESSAGE_INVALID_PAYLOAD);
        }
    }

    /**
     * Method remove_authentication
     *
     * @return void
     */
    public function remove_authentication()
    {
        $json_param = array();
        $json_param['status'] = FALSE;
        $json_param['redirect_url'] = '';
        if ($this->userid > 0 && $this->user_data['signup_plaid_token']) {
            $updated = $this->model_signup->update_by_pk(
                $this->userid,
                array(
                    'signup_plaid_token' => ''
                )
            );
            if ($updated) {
                $json_param['status'] = TRUE;
                $json_param['redirect_url'] = l('plaid/link/' . PLAID_TYPE_AUTH);
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        }
        echo json_encode($json_param);
    }

    /**
     * Method oauth2
     *
     * @return void
     */
    public function oauth2(): void
    {
        log_message('info', $_REQUEST['code']);
    }
}
