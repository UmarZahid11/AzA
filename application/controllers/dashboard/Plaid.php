<?php

declare(strict_types=1);

use TomorrowIdeas\Plaid\PlaidRequestException;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Plaid
 */
class Plaid extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->userid <= 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l(''));
        }
    }

    /**
     * Method plaid
     *
     * @return void
     */
    public function index(): void
    {
        if ($this->model_signup->hasPremiumPermission()) {
            if (!empty($this->plaid_token)) {

                $data = array();

                //
                $this->layout_data['title'] = 'Plaid | ' . $this->layout_data['title'];
                //
                $this->load_view("index", $data);
            } else {
                $this->session->set_userdata('plaid_intended', l('dashboard/plaid'));
                redirect(l('plaid/link/' . PLAID_TYPE_AUTH));
            }
        } else {
            error_404();
        }
    }

    /**
     * Method listing
     *
     * @param string $entity
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function listing($entity = '', int $page = 0, $limit = PER_PAGE): void
    {
        if (!$entity) {
            error_404();
        }
        if ($this->model_signup->hasPremiumPermission()) {
            if ($entity && in_array($entity, PLAID_ENTITY_TYPE)) {

                if (!empty($this->plaid_token)) {
                    $data = array();

                    $data['entity'] = $entity;
                    $data['entityArray'] = array();

                    $data['access_token'] = $access_token = $this->plaid_token->access_token;

                    $data['page'] = $page;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    try {
                        switch ($entity) {
                            case 'accounts':
                                $data['entityArray'] = $this->plaid->accounts->list($access_token);
                                break;
                            case 'bank_transfers':
                                $data['entityArray'] = $this->plaid->bank_transfers->list();
                                break;
                            case 'categories':
                                $data['entityArray'] = $this->plaid->categories->list();
                                break;
                            case 'institutions':
                                $data['entityArray'] = $this->plaid->institutions->list($limit, $page, ['US']);
                                break;
                            case 'liabilities':
                                $data['entityArray'] = $this->plaid->liabilities->list($access_token);
                                break;
                            case 'transactions':
                                $data['entityArray'] = $this->plaid->transactions->list($access_token, new DateTime(date('Y-m-d H:i:s')), new DateTime(date('Y-m-d H:i:s')));
                                break;
                            case 'items':
                                $data['entityArray'] = $this->plaid->items->get($access_token);
                                break;
                            case 'investments':
                                $data['entityArray'] = $this->plaid->investments->listHoldings($access_token);
                                break;
                            case 'assets':
                                $data['entityArray'] = $this->plaid->reports->createAssetReport([$access_token], 10, array(
                                    "client_report_id" => '123',
                                    "user" => array(
                                        "client_user_id" => 'userid123',
                                        "first_name" => 'Jane',
                                        "middle_name" => 'Leah',
                                        "last_name" => 'Doe',
                                        "ssn" => '123-45-6789',
                                        "phone_number" => '(555) 123-4567',
                                        "email" => 'jane.doe@example.com'
                                    )
                                ));
                                break;
                            case 'identity':
                                $headers = array(
                                    'Content-Type: application/json'
                                );
                                $postArray = array(
                                    "client_id" => PLAID_CLIENT_ID,
                                    "secret" => PLAID_CLIENT_SECRET,
                                    "access_token" => $access_token
                                );
                                $json = $this->curlRequest(PLAID_API_URL . PLAID_GET_IDENTITY, $headers, $postArray, TRUE);
                                $data['entityArray'] = json_decode($json);
                                break;
                        }
                    } catch (PlaidRequestException $e) {
                        log_message('ERROR', $e->getMessage());
                        //
                        $this->_log_message(
                            LOG_TYPE_GENERAL,
                            LOG_SOURCE_SERVER,
                            LOG_LEVEL_ERROR,
                            $e->getMessage(),
                            ''
                        );
                        $this->session->set_flashdata('error', __($e->getMessage()));
                        redirect(l('dashboard/plaid'));
                    }

                    if ((isset($data['entityArray']->error_code) && $data['entityArray']->error_code == 'INVALID_ACCESS_TOKEN')) {
                        $this->session->set_flashdata('error', (isset($data['entityArray']->error_message) ? $data['entityArray']->error_message : ERROR_MESSAGE));
                        redirect(l('dashboard/plaid'));
                    }

                    //
                    $this->layout_data['title'] = 'Plaid ' . ucfirst($entity) . ' | ' . $this->layout_data['title'];
                    //
                    $this->load_view($entity . "/listing", $data);
                } else {
                    $this->session->set_userdata('plaid_intended', l('dashboard/plaid/listing/' . $entity . ($page ? ('/' . $page) : '') . ($limit ? ('/' . $limit) : '')));
                    redirect(l('plaid/link/' . PLAID_TYPE_AUTH));
                }
            } else {
                error_404();
            }
        } else {
            error_404();
        }
    }

    /**
     * Method authorizeTransaction
     *
     * @return void
     */
    public function authorizeTransaction()
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "access_token" => $this->plaid_token->access_token
        );
        $url = PLAID_API_URL . PLAID_GET_ACCOUNTS;

        $response = $this->curlRequest($url, $headers, $postArray, TRUE);
        $decoded_response = json_decode($response);

        if ($decoded_response->accounts[0]->account_id) {
            $postArray = array(
                "client_id" => PLAID_CLIENT_ID,
                "secret" => PLAID_CLIENT_SECRET,
                "access_token" => $this->plaid_token->access_token,
                "account_id" => $decoded_response->accounts[0]->account_id,
                "type" => "credit",
                "network" => "ach",
                "amount" => 2,
                "ach_class" => "ppd",
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
            $url = PLAID_API_URL . PLAID_CREATE_AUTHORIZE;
            $response = $this->curlRequest($url, $headers, $postArray, TRUE);
            $decoded_response = json_decode($response);
            debug($decoded_response, 1);
        }
    }

    /**
     * Method createTransferIntent
     *
     * @return void
     */
    function createTransferIntent(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $decoded_response = '';
        $errorMessage = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
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

                if (!empty($milestone)) {

                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $post_array = array(
                        "client_id" => PLAID_CLIENT_ID,
                        "secret" => PLAID_CLIENT_SECRET,
                        "amount" => number_format((float) $_POST['amount'], 2),
                        "description" => "Aza",
                        "ach_class" => "ppd",
                        "user" => array(
                            "legal_name" => $this->model_signup->profileName($this->user_data)
                        ),
                    );

                    switch ($_POST['mode']) {
                        case PLAID_TRANSFER_MODE_PAYMENT:
                            $post_array["mode"] = PLAID_TRANSFER_MODE_PAYMENT;
                            break;
                        case PLAID_TRANSFER_MODE_DISBURSEMENT:
                            $post_array["mode"] = PLAID_TRANSFER_MODE_DISBURSEMENT;
                            break;
                    }

                    if ($this->user_data['signup_plaid_account_id']) {
                        $post_array["account_id"] = $this->user_data['signup_plaid_account_id'];
                    }
                    $url = PLAID_API_URL . PLAID_TRANSFER_INTENT;

                    try {
                        $response = $this->curlRequest($url, $headers, $post_array, TRUE);
                        $decoded_response = json_decode($response);
                    } catch (\Exception $e) {
                        $errorMessage = $e->getMessage();
                    }

                    $json_param['response'] = $decoded_response;

                    if (!$decoded_response) {
                        $json_param['status'] = FALSE;
                        $json_param['message'] = $errorMessage;
                    } else {
                        if (isset($decoded_response->error_type) && null !== $decoded_response->error_type) {
                            $json_param['status'] = FALSE;
                            $json_param['txt'] = isset($decoded_response->error_message) ? $decoded_response->error_message : ERROR_MESSAGE;
                        } else {
                            $json_param['status'] = TRUE;
                            $json_param['txt'] = __(SUCCESS_MESSAGE);
                        }
                    }
                } else {
                    $json_param['txt'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method relogin
     *
     * @return void
     */
    public function relogin()
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
                // removing previous plaid token will trigger the redirection towards plaid login page
                $updated = $this->model_signup->update_by_pk(
                    $this->userid,
                    array(
                        'signup_plaid_token' => ''
                    )
                );

                if ($updated) {
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = __(SUCCESS_MESSAGE);
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method remove_plaid_account
     *
     * @return void
     */
    function remove_plaid_account(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
                if($this->user_data['signup_plaid_account_id']) {
                    $updated = $this->model_signup->update_by_pk(
                        $this->userid,
                        array(
                            'signup_plaid_account_id' => '',
                            'signup_plaid_transfer_response' => ''
                        )
                    );

                    if ($updated) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = 'The account\'s saved plaid account has already been removed.';
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }
}
