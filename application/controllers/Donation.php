<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Donation
 */
class Donation extends MY_Controller
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
     * index
     *
     * @return void
     */
    public function index($page = 1, $limit = 9)
    {
        $data = array();

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $data['fundraisings'] = $this->model_fundraising->find_all_active(
            array(
                'limit' => $limit,
                'offset' => $paginationStart,
                'order' => 'fundraising_updatedon desc'
            )
        );

        $allRecrods = $data['fundraisings_count'] = $this->model_fundraising->find_count_active();

        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = 'Donation | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * detail
     *
     * @return void
     */
    public function detail($slug = NULL)
    {
        $data = array();

        if ($slug) {
            $data['fundraising'] = $fundraising = $this->model_fundraising->find_one_active(
                array(
                    'where' => array(
                        'fundraising_slug' => $slug
                    )
                )
            );
            if (!$fundraising) {
                error_404();
            }

            $data['donation'] = $this->model_donation->donationByActivity($fundraising['fundraising_id']);
            $data['donation_percentage'] = (($fundraising['fundraising_amount'] / $data['donation']) * 100);
            $title = $data['fundraising']['fundraising_title'];
            //
            $this->session->set_userdata('plaid_intended', l('donation/detail/' . $data['fundraising']['fundraising_slug']));
        } else {
            $data['donation'] = $this->model_donation->donationByActivity();
            $data['donation_percentage'] = ($data['donation'] / g('db.admin.target') * 100);
            $title = 'Donation';
            //
            $this->session->set_userdata('plaid_intended', l('donation/detail'));
        }

        //
        $this->layout_data['title'] = $title . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * save function
     *
     * @return void
     */
    function save()
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $json_param['url'] = '';
        $updateParam = array();

        if (isset($_POST['donationEmail']) && isset($_POST['donationAmount'])) {
            
            $merchant = STRIPE;
            if (isset($_POST['merchant']) && in_array($_POST['merchant'], [STRIPE, PAYPAL, PLAID])) {
                $merchant = $_POST['merchant'];
            }

            $donationParam = array();

            if (isset($_POST['fundraising'])) {
                $fundraising = $this->model_fundraising->find_by_pk($_POST['fundraising']);
                if ($fundraising) {
                    $productName = $fundraising['fundraising_title'];
                    $donationParam['donation_type'] = DONATION_FUNDRAISING;
                    $donationParam['donation_reference'] = $fundraising['fundraising_id'];
                } else {
                    $productName = g('db.admin.donation_text');
                }
            } else {
                $productName = g('db.admin.donation_text');
            }

            $donationParam['donation_email'] = $_POST['donationEmail'];
            $donationParam['donation_amount'] = $_POST['donationAmount'];
            $donationParam['donation_status'] = 0;
            $donationParam['donation_method'] = $merchant;
            $donationId = $this->model_donation->insert_record($donationParam);

            $status = 0;
            if (isset($_POST['status'])) {
                if ($_POST['status'] == 'Completed') {
                    $status = 1;
                }
            }

            if ($donationId) {
                switch ($merchant) {
                    case STRIPE:
                        $donationSession = $this->setupStripeDonation($donationId, $donationParam['donation_amount'], $donationParam['donation_email'], $productName);

                        if ($donationSession && isset($donationSession->url) && $donationSession->url != NULL) {
                            $updateParam['donation_checkout_id'] = $donationSession->id;
                            $this->model_donation->update_by_pk(
                                $donationId,
                                $updateParam
                            );

                            $json_param['status'] = TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;
                            $json_param['url'] = $donationSession->url;
                        }
                        break;
                    case PAYPAL:
                        $updateParam['donation_checkout_id'] = (isset($_POST['txId']) ? $_POST['txId'] : '');
                        $updateParam['donation_checkout_response'] = (isset($_POST['params']) ? serialize($_POST['params']) : '');
                        $updateParam['donation_status'] = $status;
                        $this->model_donation->update_by_pk(
                            $donationId,
                            $updateParam
                        );
                        $json_param['status'] = TRUE;
                        $json_param['txt'] = SUCCESS_MESSAGE;
                        $json_param['url'] = '';
                        break;
                    case PLAID:
                        $error = FALSE;
                        $account_id = '';
                        $authorization_id = '';
                        $transfer_id = '';
                        $transfer_status = '';

                        try {
                            //
                            $plaid_account = $this->getPlaidAccount();
                            if (property_exists($plaid_account, 'accounts')) {
                                if (isset($plaid_account->accounts[0])) {
                                    $account = $plaid_account->accounts[0];
                                    if (property_exists($account, 'account_id')) {
                                        $account_id = $account->account_id;
                                    }
                                }
                            } else if(property_exists($plaid_account, 'error_message')) {
                                $json_param['txt'] = $plaid_account->error_message;
                            }

                            //
                            if ($account_id) {
                                
                                //
                                $transfer_authorization = $this->createTransferAuthorization($account_id, number_format($donationParam['donation_amount'], 2));
                                if ($transfer_authorization && property_exists($transfer_authorization, 'authorization')) {
                                    if ($transfer_authorization->authorization && property_exists($transfer_authorization->authorization, 'id')) {
                                        $authorization_id = $transfer_authorization->authorization->id;
                                    }
                                } else if(property_exists($transfer_authorization, 'error_message')) {
                                    $json_param['txt'] = $transfer_authorization->error_message;
                                }

                                //
                                if ($authorization_id) {
                                    $transfer = $this->createPlaidTransfer($account_id, $authorization_id, strip_string($productName, 10));
                                    if ($transfer && property_exists($transfer, 'transfer')) {
                                        if ($transfer->transfer && property_exists($transfer->transfer, 'id')) {
                                            $transfer_id = $transfer->transfer->id;
                                            $transfer_status = $transfer->transfer->status;                                            
                                        }
                                    } else if(property_exists($transfer, 'error_message')) {
                                        $json_param['txt'] = $transfer->error_message;
                                    }
                                } else {
                                    $error = TRUE;
                                }
                                // $transfer_id
                                if($transfer_id) {
                                    $json_param['transfer_status'] = $transfer_status;
                                    $json_param['id'] = $updateParam['donation_checkout_id'] = $transfer_id;
                                    $updateParam['donation_checkout_response'] = serialize($transfer);
                                } else {
                                    $error = TRUE;
                                }
                            } else {
                                $error = TRUE;
                            }

                            if(!$error) {
                                $this->model_donation->update_by_pk(
                                    $donationId,
                                    $updateParam
                                );
                                $json_param['status'] = TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                                $json_param['url'] = base_url() . 'donation/result/' . $donationId . '/' . ORDER_SUCCESS . '/' . $transfer_id . '/' . PLAID;
                            }
                        } catch (\Exception $e) {
                            $json_param['txt'] = $e->getMessage();
                        }
                        break;
                }
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
        }

        echo json_encode($json_param);
    }

    /**
     * Method setupStripeCustomerPayment
     *
     * @param int $membershipId
     *
     * @return object
     */
    function setupStripeDonation(int $donationId = 0, float $donationAmount, string $donationEmail = '', $productName): ?object
    {
        try {
            $customer = $this->createStripeResource('customers', [
                'email' => $donationEmail
            ]);
            $product = $this->createStripeResource('products', [
                'name' => $productName,
            ]);
            $price = $this->createStripeResource('prices', [
                'unit_amount' => $donationAmount * 100,
                'currency' => DEFAULT_CURRENCY_CODE,
                'product' => $product->id
            ]);
            $checkoutSessionPayload = [
                'payment_method_types' => ['card'],
                'customer' => $customer->id,
                'success_url' => base_url() . 'donation/result/' . $donationId . '/' . ORDER_SUCCESS . '/{CHECKOUT_SESSION_ID}',
                'cancel_url' => base_url() . 'donation',
                'mode' => 'payment',
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
            ];
            $session = $this->stripe->checkout->sessions->create($checkoutSessionPayload);
            return ($session);
        } catch (\Exception $e) {
            $this->session->set_flashdata('stripe_error', $e->getMessage());
            log_message('ERROR', $e->getMessage());
        }
        return NULL;
    }

    /**
     * result
     *
     * @param integer $donationId
     * @param bool $status
     * @param integer $checkoutSessionId
     * @param string $merchant
     * @return void
     */
    public function result($donationId = 0, $status = ORDER_FAILED, $checkoutSessionId = 0, $merchant = STRIPE)
    {
        global $config;

        $error = FALSE;
        $fire_email = FALSE;
        $data = array();

        if ($merchant == STRIPE && !$checkoutSessionId)
            error_404();

        $param = array();
        $param['where']['inner_banner_name'] = 'Membership Payment Result';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        //
        $data['donation'] = $this->model_donation->find_by_pk($donationId);
        if (empty($data['donation'])) {
            $this->session->set_flashdata('error', __('Error in finding requested donation!'));
            redirect(l('donation'));
        }

        if (!in_array($status, [ORDER_SUCCESS, ORDER_FAILED])) {
            $status = 'unknown';
        }

        $data['status'] = $status;

        if ($status == 'success') {
            switch ($merchant) {
                case STRIPE:
                    $session = "";
                    $data['customer'] = "";

                    try {
                        $session = $this->stripe->checkout->sessions->retrieve(
                            $checkoutSessionId,
                            []
                        );
                    } catch (\Exception $e) {
                        $error = TRUE;
                        $this->session->set_flashdata('stripe_error', $e->getMessage());
                    }

                    if($session && $session->status == 'complete') {
                        if (!$error && $data['donation']['donation_status'] != 1) {
                            $this->model_donation->update_model(
                                array('where' => array(
                                    'donation_checkout_id' => $checkoutSessionId
                                )),
                                array(
                                    'donation_checkout_response' => str_replace('Stripe\Checkout\Session JSON:', '', ($session)),
                                    'donation_status' => 1
                                )
                            );

                            $fire_email = TRUE;
                        } else {
                            error_404();
                        }
                    } else {
                        $status = ORDER_FAILED;
                    }
                    break;
                case PLAID:
                    try {
                        // get transfer details
                        $session = $this->getPlaidTransfer($checkoutSessionId);
                        // $status
                    } catch (\Exception $e) {
                        $error = TRUE;
                        $this->session->set_flashdata('plaid_error', $e->getMessage());
                    }

                    if($session && property_exists($session, 'transfer') && $session->transfer) {
                        if(property_exists($session->transfer, 'status') && $session->transfer->status == 'posted') {
                            if (!$error && $data['donation']['donation_status'] != 1) {
                                $this->model_donation->update_model(
                                    array('where' => array(
                                        'donation_checkout_id' => $checkoutSessionId
                                    )),
                                    array(
                                        'donation_status' => 1
                                    )
                                );
    
                                $fire_email = TRUE;
                            } else {
                                error_404();
                            }
                        } else {
                            $status = 'In-process';
                        }
                    }
                    break;
            }

            //
            if($fire_email) {
                try {
                    //
                    $to = $data['donation']['donation_email'];
                    $subject = $config['title'] . ' - Donation Alert!';
                    $message = 'Dear user, <br/> Your donation of amount ' . price($data['donation']['donation_amount']) . ' has been received on :' . date('d M, Y h:i a');
                    $title = 'Donation Sent';
                    $form_input = [
                        'E-Mail' => $data['donation']['donation_email'],
                        'Amount' => price($data['donation']['donation_amount']),
                        'Donation Time' => date('d M, Y h:i a', strtotime($data['donation']['donation_createdon']))
                    ];
                    $this->model_email->fireEmail($to, '', $subject, $message, $title, $form_input);

                    //
                    $to = g('db.admin.support_email');
                    $subject = $config['title'] . ' - Donation Alert!';
                    $message = 'Dear site administrator, <br/> A donation of amount ' . price($data['donation']['donation_amount']) . ' has been received on :' . date('d M, Y h:i a');
                    $title = 'Donation Received';
                    $form_input = [
                        'E-Mail' => $data['donation']['donation_email'],
                        'Amount' => price($data['donation']['donation_amount']),
                        'Donation Time' => date('d M, Y h:i a', strtotime($data['donation']['donation_createdon']))
                    ];
                    $this->model_email->fireEmail($to, '', $subject, $message, $title, $form_input);
                } catch (Exception $e) {
                    log_message('ERROR', $e->getMessage());
                }                
            }
        }

        //
        $this->layout_data['title'] = ucfirst($status) . ' | ' . $this->layout_data['title'];
        //
        $this->load_view('result', $data);
    }

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
     * Method getPlaidTransfer
     *
     * @return ?object
     */
    function getPlaidTransfer($transfer_id) {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "transfer_id" => $transfer_id
        );
        $url = PLAID_API_URL . PLAID_GET_TRANSFER;
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


    /**
     * simulatePlaidTransfer function
     *
     * @param string $transfer_id
     * @return void
     */
    function simulatePlaidTransfer($transfer_id) {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "transfer_id" => $transfer_id,
            "event_type" => "posted"
        );
        $url = PLAID_API_URL . PLAID_SIMULATE_TRANSFER;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));
    }

    /**
     * fireTransferWebhook function
     *
     * @return void
     */
    function fireTransferWebhook($transfer_id)
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "webhook" => base_url() . 'donation/plaidWebhook'
        );
        $url = PLAID_API_URL . PLAID_FIRE_TRANSFER_WEBHOOK;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));        
    }

    /**
     * plaidWebhook function
     *
     * @return void
     */
    function plaidWebhook() {
        global $config;

        $payload = (object) @file_get_contents('php://input');
        //
        $this->_log_message(
            LOG_TYPE_GENERAL,
            LOG_SOURCE_PLAID,
            LOG_LEVEL_ERROR,
            serialize($payload),
            ''
        );
        
        if($payload && $payload->webhook_code == 'TRANSFER_EVENTS_UPDATE' && $payload->webhook_type == 'TRANSFER') {
            $transfer_events = $this->getPlaidTransferList();
            if($transfer_events && property_exists($transfer_events, 'transfer_events')) {
                foreach($transfer_events->transfer_events as $transfer_event) {
                    if($transfer_event->event_type == 'posted') {
                        $data['donation'] = $this->model_donation->find_one(
                            array(
                                'where' => array(
                                    'donation_checkout_id' => $transfer_event->transfer_id,
                                    'donation_status' => 0
                                )
                            )
                        );
                        if($data['donation']) {
                            $this->model_donation->update_by_pk(
                                $data['donation']['donation_id'],
                                array(
                                    'donation_status' => 1
                                )
                            );

                            try {
                                //
                                $to = $data['donation']['donation_email'];
                                $subject = $config['title'] . ' - Donation Alert!';
                                $message = 'Dear user, <br/> Your donation of amount ' . price($data['donation']['donation_amount']) . ' has been received on :' . date('d M, Y h:i a');
                                $title = 'Donation Sent';
                                $form_input = [
                                    'E-Mail' => $data['donation']['donation_email'],
                                    'Amount' => price($data['donation']['donation_amount']),
                                    'Donation Time' => date('d M, Y h:i a', strtotime($data['donation']['donation_createdon']))
                                ];
                                $this->model_email->fireEmail($to, '', $subject, $message, $title, $form_input);
    
                                //
                                $to = g('db.admin.support_email');
                                $subject = $config['title'] . ' - Donation Alert!';
                                $message = 'Dear site administrator, <br/> A donation of amount ' . price($data['donation']['donation_amount']) . ' has been received on :' . date('d M, Y h:i a');
                                $title = 'Donation Received';
                                $form_input = [
                                    'E-Mail' => $data['donation']['donation_email'],
                                    'Amount' => price($data['donation']['donation_amount']),
                                    'Donation Time' => date('d M, Y h:i a', strtotime($data['donation']['donation_createdon']))
                                ];
                                $this->model_email->fireEmail($to, '', $subject, $message, $title, $form_input);
                            } catch (Exception $e) {
                                log_message('ERROR', $e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * createTransferAuthorization function
     *
     * @param string $account_id
     * @param integer $amount
     * @return ?object
     */
    function createTransferAuthorization($account_id = '', $amount = 0)
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "access_token" => $this->plaid_token->access_token,
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
    function createPlaidTransfer($account_id = '', $authorization_id = '', $description = '')
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $postArray = array(
            "client_id" => PLAID_CLIENT_ID,
            "secret" => PLAID_CLIENT_SECRET,
            "access_token" => $this->plaid_token->access_token,
            "account_id" => $account_id,
            "authorization_id" => $authorization_id,
            "description" => $description
        );
        $url = PLAID_API_URL . PLAID_CREATE_TRANSFER;
        return json_decode($this->curlRequest($url, $headers, $postArray, TRUE));
    }
}
