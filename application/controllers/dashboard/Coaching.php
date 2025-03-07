<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Coaching
 */
class Coaching extends MY_Controller
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
     * index function
     *
     * @return void
     */
    public function index(): void
    {
        $data['calendar_events'] = [];

        $coachings = $this->model_coaching->find_all_active();

        foreach ($coachings as $coaching) {
            $data['calendar_events'][] = array(
                'id' => $coaching['coaching_id'],
                'email' => $this->user_data['signup_email'], //g('db.admin.email'),
                'title' => ($coaching['coaching_title'] ?? $coaching['coaching_title']) . ' - Organizer: ' . COACHING_ORGANIZER,
                'start' => $coaching['coaching_start_time'],
                'end' => date('Y-m-d H:i:s', strtotime('+' . (int) $coaching['coaching_duration'] . 'minutes', strtotime($coaching['coaching_start_time']))),
                'type' => CALENDAR_TYPE_COACHING,
                'start_url' => $coaching['coaching_start_url'],
                'join_url' => $coaching['coaching_join_url'],
                'current_status' => $coaching['coaching_current_status'],
                'coaching_url' => l('dashboard/coaching/detail/' . JWT::encode($coaching['coaching_id']))
            );
        }

        //
        $this->layout_data['title'] = 'Coaching | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * listing function
     *
     * @return void
     */
    public function listing(int $page = 1, int $limit = PER_PAGE, $search = ''): void
    {
        $data = array();

        $data['search'] = $search;

        $data['page'] = $page;
        $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $coaching_params = array(
            'order' => 'coaching_updatedon desc',
            'offset' => $paginationStart,
            'limit' => $limit,
        );

        $coaching_count_params = array(
            'order' => 'coaching_updatedon desc'
        );

        if ($search) {
            $coaching_params['where_like'][] = $coaching_count_params['where_like'][] = array(
                'column' => 'coaching_title',
                'value' => $search,
                'type' => 'both',
            );
        }

        $data['coachings'] = $this->model_coaching->find_all_active($coaching_params);
        $data['coachings_count'] = $allRecrods = $this->model_coaching->find_count_active($coaching_count_params);
        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = 'Coaching | ' . $this->layout_data['title'];
        //
        $this->load_view("listing", $data);
    }

    /**
     * Method detail
     *
     * @param string $coaching_id
     *
     * @return void
     */
    public function detail(string $coaching_id = ''): void
    {
        if (!$coaching_id) {
            error_404();
        }

        try {
            $coaching_id = JWT::decode($coaching_id, CI_ENCRYPTION_SECRET);
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
            error_404();
        }

        if ($this->model_signup->hasPremiumPermission()) {
            $data = array();

            $data['coaching'] = $this->model_coaching->find_by_pk($coaching_id);

            if (empty($data['coaching'])) {
                error_404();
            }

            $data['user_role'] = $this->model_signup->getUserRole($this->userid);
            $data['membership_coaching_limit'] = $this->model_membership->coachingLimit($this->userid);

            //
            $data['user_application'] = $this->model_coaching_application->getUserApplication($this->userid, (int) $data['coaching']['coaching_id']); 
            $data['user_total_applications'] = $this->model_coaching_application->getTotalApplicationsByUser($this->userid); 

            $data['coaching_cost'] = $this->getCoachingCost($data['coaching']);

            //
            $data['coaching_recording'] = NULL;
            if ($data['coaching']['coaching_uuid']) {
                $coaching_response = $this->getZoomMeetingRecording($data['coaching']['coaching_uuid']);
                if ($coaching_response) {
                    $data['coaching_response'] = json_decode($coaching_response);
                }
            }
            
            $data['paypalAccessToken'] = $this->paypalAccessToken;

            //
            $this->layout_data['title'] = 'Coaching details | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);
        } else {
            error_404();
        }
    }

    /**
     * getCoachingCost function
     *
     * @param array $coaching
     * @return int
     */
    function getCoachingCost(array $coaching = []) {

        $coaching_cost = $coaching['coaching_cost'];

        $membership_coaching_limit = $this->model_membership->coachingLimit($this->userid);
        $user_total_applications = $this->model_coaching_application->getTotalApplicationsByUser($this->userid); 

        if((int) $user_total_applications < (int) $membership_coaching_limit) {
            $coaching_cost = 0;
        } else {
            $current_role_coaching_cost = $this->model_coaching_cost->find_one_active(
                array(
                    'where' => array(
                        'coaching_cost_coaching_id' => $coaching['coaching_id'],
                        'coaching_cost_membership_id' => $this->user_data['signup_type'],
                    )
                )
            );

            if ($current_role_coaching_cost) {
                $coaching_cost = $current_role_coaching_cost['coaching_cost_value'];
            }
        }

        return (int) $coaching_cost;
    }

    /**
     * saveApplication function
     *
     * @return void
     */
    function saveApplication(): void
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $json_param['session_url'] = '';

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

            if ($this->userid > 0) {
                if (isset($_POST['coaching_application'])) {

                    $affected_application = 0;
                    $inserted = FALSE;
                    $coaching_application = $_POST['coaching_application'];

                    $coaching = $this->model_coaching->find_by_pk(
                        (int) $coaching_application['coaching_application_coaching_id']
                    );

                    if ($coaching) {

                        //
                        $coaching_cost = $this->getCoachingCost($coaching);

                        $session = '';
                        $json_param['merchant'] = $merchant = isset($coaching_application['coaching_application_merchant']) && $coaching_application['coaching_application_merchant'] ? $coaching_application['coaching_application_merchant'] : 'STRIPE';

                        //
                        $userApplication = $this->model_coaching_application->getUserApplication((int) $coaching_application['coaching_application_signup_id'], (int) $coaching_application['coaching_application_coaching_id']);

                        if ($userApplication) {
                            if(!$userApplication['coaching_application_payment_status']) {

                                if ($coaching_cost != 0) {
                                    //
                                    $session = $this->checkoutSessionSetup($coaching, (int) $coaching_cost, $merchant);
                                                                    
                                    if($session) {
                                        switch($merchant) {
                                            case STRIPE:
                                                $coaching_application['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $session ? $session->id : '';
                                                $coaching_application['coaching_application_checkout_session_response'] = $orderParam['order_response'] = str_replace('Stripe\Checkout\Session JSON:', '', (string) $session);
                                                if($session->url) {
                                                    $json_param['session_url'] = $session->url;
                                                }
                                                break;
                                            case PAYPAL:
                                                $coaching_application['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $session ? $session->id : '';
                                                $coaching_application['coaching_application_checkout_session_response'] = $orderParam['order_response'] = serialize($session);
                                                if($session->status == 'PAYER_ACTION_REQUIRED') {
                                                    foreach($session->links as $link) {
                                                        if($link->rel == 'payer-action') {
                                                            $json_param['session_url'] = $link->href;
                                                        }
                                                    }
                                                }
                                                break;
                                            case PLAID:
                                                $json_param['link_token'] = $coaching_application['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $session ? $session->link_token : '';
                                                break;
                                            default:
                                        }
                                    }
                                } else {
                                    $coaching_application['coaching_application_status'] = STATUS_ACTIVE;
                                    $coaching_application['coaching_application_payment_status'] = STATUS_ACTIVE;
                                    //
                                    $orderParam['order_status'] = STATUS_ACTIVE;
                                    $orderParam['order_payment_status'] = STATUS_ACTIVE;
                                    $orderParam['order_status_message'] = "Completed";
                                    $orderParam['order_payment_comments'] = "Paid";    
                                }

                                $affected_application = $this->model_coaching_application->update_by_pk(
                                    $userApplication['coaching_application_id'],
                                    $coaching_application
                                );

                                $this->model_order->update_model(
                                    array(
                                        'where' => array(
                                            'order_reference_type' => ORDER_REFERENCE_COACHING,
                                            'order_reference_id' => $userApplication['coaching_application_id'],
                                        ),
                                    ),
                                    $orderParam
                                );
                            } else {
                                $json_param['txt'] = 'The application payment has already been completed!';
                            }
                        } else {

                            //
                            $orderParam['order_user_id'] = $this->userid;
                            $orderParam['order_email'] = $this->user_data['signup_email'];
                            $orderParam['order_firstname'] = $this->user_data['signup_firstname'];
                            $orderParam['order_lastname'] = $this->user_data['signup_lastname'];
                            $orderParam['order_phone'] = $this->user_data['signup_phone'];
                            $orderParam['order_address1'] = $this->user_data['signup_address'];
                            $orderParam['order_city'] = $this->user_data['signup_city'];
                            $orderParam['order_zip'] = $this->user_data['signup_zip'];

                            // order type = 7 => coaching
                            $orderParam['order_reference_type'] = ORDER_REFERENCE_COACHING;

                            //
                            $orderParam['order_quantity'] = 1;
                            $orderParam['order_total'] = $coaching_cost;
                            $orderParam['order_shipping'] = 0;
                            $orderParam['order_amount'] = $orderParam['order_total'] + $orderParam['order_shipping'];
                            $orderParam['order_shipment_price'] = price($orderParam['order_shipping']);
                            $orderParam['order_merchant'] = $merchant;
                            $orderParam['order_currency'] = DEFAULT_CURRENCY_CODE;

                            if ($coaching_cost == 0) {
                                $coaching_application['coaching_application_status'] = STATUS_ACTIVE;
                                $coaching_application['coaching_application_payment_status'] = STATUS_ACTIVE;
                                //
                                $orderParam['order_status'] = STATUS_ACTIVE;
                                $orderParam['order_payment_status'] = STATUS_ACTIVE;
                                $orderParam['order_status_message'] = "Completed";
                                $orderParam['order_payment_comments'] = "Paid";
                            } else {
                                //
                                $orderParam['order_status'] = STATUS_INACTIVE;
                                $orderParam['order_payment_status'] = STATUS_INACTIVE;
                                $orderParam['order_status_message'] = "Pending";
                                $orderParam['order_payment_comments'] = "Unpaid";

                                //
                                $session = $this->checkoutSessionSetup($coaching, (int) $coaching_cost, $merchant);
                                
                                if($session) {
                                    switch($merchant) {
                                        case STRIPE:
                                            $coaching_application['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $session ? $session->id : '';
                                            $coaching_application['coaching_application_checkout_session_response'] = $orderParam['order_response'] = str_replace('Stripe\Checkout\Session JSON:', '', (string) $session);
                                            if($session->url) {
                                                $json_param['session_url'] = $session->url;
                                            }
                                            break;
                                        case PAYPAL:
                                            $coaching_application['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $session ? $session->id : '';
                                            $coaching_application['coaching_application_checkout_session_response'] = $orderParam['order_response'] = serialize($session);
                                            if($session->status == 'PAYER_ACTION_REQUIRED') {
                                                foreach($session->links as $link) {
                                                    if($link->rel == 'payer-action') {
                                                        $json_param['session_url'] = $link->href;
                                                    }
                                                }
                                            }
                                            break;
                                        case PLAID:
                                            $json_param['link_token'] = $coaching_application['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $session ? $session->link_token : '';
                                            break;
                                        default:
                                    }
                                }
                            }

                            try {
                                $affected_application = $this->model_coaching_application->insert_record($coaching_application);
        
                                // $id -> coaching_id
                                $orderParam['order_reference_id'] = $affected_application;
                                $affectedOrder = $this->model_order->insert_record($orderParam);

                                if ($affectedOrder) {
                                    $itemParam = array();
                                    $itemParam['order_item_status'] = STATUS_ACTIVE;
                                    $itemParam['order_item_order_id'] = $affectedOrder;
                                    // $id -> membership_id
                                    $itemParam['order_item_product_id'] = $affected_application;
                                    $itemParam['order_item_user_id'] = $this->userid;
                                    $itemParam['order_item_price'] = $coaching_cost;
                                    $itemParam['order_item_subtotal'] = $coaching_cost;
                                    $itemParam['order_item_qty'] = 1;
                                    $this->model_order_item->insert_record($itemParam);
                                } 
                               
                                //
                                $inserted = TRUE;
                            } catch (Exception $e) {
                                $json_param['txt'] = $e->getMessage();
                            }
                        }

                        if ($affected_application) {
                            if ($inserted) {
                                // notify
                                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_COACHING_REQUEST_SENT, $coaching_application['coaching_application_coaching_id'], NOTIFICATION_COACHING_REQUEST_SENT_COMMENT);
                            }
                            $json_param['status'] = TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;
                        }
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                    }
                } else {
                    $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                }
            } else {
                $json_param['txt'] = ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE;
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }

        echo json_encode($json_param);
    }

    /**
     * checkoutSessionSetup function
     *
     * @param array $coaching
     * @param int $coaching_cost
     * @param string $merchant
     * @return object?
     */
    private function checkoutSessionSetup(array $coaching, int $coaching_cost, string $merchant = STRIPE)
    {
        global $config;

        $session = NULL;

        try {
            switch($merchant) {
                case STRIPE:
                    $customer = $this->model_stripe_log->createStripeResource('customers', [
                        'email' => $this->user_data['signup_email']
                    ]);
                    //
                    $product = $this->model_stripe_log->createStripeResource('products', [
                        'name' => $coaching['coaching_title']
                    ]);
                    $price = $this->model_stripe_log->createStripeResource('prices', [
                        'unit_amount' => $coaching_cost * 100,
                        'currency' => DEFAULT_CURRENCY_CODE,
                        'product' => $product->id,
                    ]);
                    $checkoutSessionPayload = [
                        'payment_method_types' => ['card'],
                        'customer' => $customer->id,
                        'success_url' => base_url() . 'dashboard/coaching/result/' . JWT::encode($coaching['coaching_id']) . '/' . ORDER_SUCCESS . '/{CHECKOUT_SESSION_ID}',
                        'cancel_url' => base_url() . 'dashboard/coaching/detail/' . JWT::encode($coaching['coaching_id']),
                        'mode' => 'payment',
                        'line_items' => [
                            [
                                'price' => $price->id,
                                'quantity' => 1,
                            ],
                        ],
                    ];
                    $session = $this->stripe->checkout->sessions->create($checkoutSessionPayload);
                    break;
                case PAYPAL:
                    $purchase_units[] = array(
                        "amount" => array(
                            "currency_code" => DEFAULT_CURRENCY_CODE,
                            "value" => $coaching_cost
                        ),
                        "payee" => array(
                            'email_address' => $this->user_data['signup_email']
                        ),
                    );
                    $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL;
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                    $body = array(
                        "intent" => "AUTHORIZE",
                        "payment_source" => array(
                            "paypal" => array(
                                "experience_context" => array(
                                    "payment_method_preference" => "IMMEDIATE_PAYMENT_REQUIRED",
                                    "landing_page" => "LOGIN",
                                    "shipping_preference" => "NO_SHIPPING",
                                    "user_action" => "PAY_NOW",
                                    "return_url" => base_url() . 'dashboard/coaching/result/' . JWT::encode($coaching['coaching_id']) . '/' . ORDER_SUCCESS . '/0/' . PAYPAL,
                                    "cancel_url" => base_url() . 'dashboard/coaching/detail/' . JWT::encode($coaching['coaching_id'])
                                )
                            )
                        ),
                        "purchase_units" => $purchase_units
                    );

                    //
                    $response = $this->curlRequest($url, $headers, $body, TRUE);
                    $session = json_decode($response);
                    break;
                case PLAID:
                    $postArray = array(
                        "client_id" => PLAID_CLIENT_ID,
                        "secret" => PLAID_CLIENT_SECRET,
                        "user" => array(
                            "client_user_id" => PLAID_CLIENT_PREFIX . time() . '-' . $this->userid,
                        ),
                        "client_name" => $config['title'],
                        "products" => [PLAID_TYPE_AUTH],
                        "country_codes" => ['US'],
                        "language" => "en",
                        // "link_customization_name" => 'transfer_ui'
                    );
                    $url = PLAID_API_URL . PLAID_CREATE_LINK_TOKEN;
                        
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';

                    //
                    $response = $this->curlRequest($url, $headers, $postArray, TRUE);
                    $session = json_decode($response);
                    break;
            }
            return $session;
        } catch (\Exception $e) {
            $this->session->set_flashdata('stripe_error', $e->getMessage());
            log_message('ERROR', $e->getMessage());
        }
        return NULL;
    }

    /**
     * processPlaidTransfer function
     *
     * @return void
     */
    function processPlaidTransfer() {

        $json_param['status'] = FALSE;
        $json_param['redirect'] = '';

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
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

                    $access_token = $decoded_response->access_token;
                    $account_id = isset($_POST['account_id']) && $_POST['account_id'] ? $_POST['account_id'] : '';
                    
                    $coaching = $this->model_coaching->find_by_pk($_POST['coaching_id']);
                    $coaching_cost = $coaching['coaching_cost'];

                    $current_role_coaching_cost = $this->model_coaching_cost->find_one_active(
                        array(
                            'where' => array(
                                'coaching_cost_coaching_id' => $coaching['coaching_id'],
                                'coaching_cost_membership_id' => $this->user_data['signup_type'],
                            )
                        )
                    );

                    if ($current_role_coaching_cost) {
                        $coaching_cost = $current_role_coaching_cost['coaching_cost_value'];
                    }

                    $transferAuth = $this->createTransferAuthorization($access_token, $account_id, number_format((float) $coaching_cost, 2));

                    if(isset($transferAuth->authorization) && $transferAuth->authorization->decision == 'declined') {
                        $json_param['message'] = $transferAuth->authorization->decision_rationale->description;
                    } else {
                        $authorization_id = $transferAuth->authorization->id;
                        $transfer = $this->createPlaidTransfer($access_token, $account_id, $authorization_id, strip_string($coaching['coaching_title'], 10));

                        if ($transfer && property_exists($transfer, 'transfer')) {
                            if ($transfer->transfer && property_exists($transfer->transfer, 'id')) {
                                
                                $updateParam['coaching_application_checkout_session_id'] = $orderParam['order_session_checkout_id'] = $transfer->transfer->id;
                                $updateParam['coaching_application_checkout_session_response'] = $orderParam['order_response'] = serialize($transfer);

                                $this->model_coaching_application->update_model(
                                    array(
                                        'where' => array(
                                            'coaching_application_checkout_session_id' => $_POST['link_token'],
                                            'coaching_application_signup_id' => $this->userid,
                                            'coaching_application_coaching_id' => $coaching['coaching_id'],
                                        )
                                    ),
                                    $updateParam
                                );
                                $this->model_order->update_model(
                                    array(
                                        'where' => array(
                                            'order_user_id' => $this->userid,
                                            'order_session_checkout_id' => $_POST['link_token']
                                        )
                                    ),
                                    $orderParam
                                );
                                
                                $json_param['status'] = TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            }
                        } else if(property_exists($transfer, 'error_message')) {
                            $json_param['txt'] = $transfer->error_message;
                        }
                    }
                }
            }
        } else {
            $json_param['message'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);
    }

    /**
     * Method result
     *
     * @param int $coachingId
     * @param string $status
     * @param string $checkoutSessionId
     *
     * @return void
     */
    public function result($coachingId = '', $status = ORDER_FAILED, $checkoutSessionId = '', $merchant = STRIPE)
    {
        global $config;

        $data = array();
        $updatedOrder = 0;
        $order = [];

        if ($this->userid == 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l('login'));
        }

        if (!$checkoutSessionId && $merchant == STRIPE)
            error_404();

        if (!isset($_GET['token']) && $merchant == PAYPAL)
            error_404();

        if($coachingId) {
            try {
                $coachingId = JWT::decode($coachingId, CI_ENCRYPTION_SECRET);
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
                error_404();
            }    
        }

        $param = array();
        $param['where']['inner_banner_name'] = 'Membership Payment Result';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        //
        $data['coaching'] = $this->model_coaching->find_by_pk($coachingId);
        if (empty($data['coaching'])) {
            $this->session->set_flashdata('error', __('Error in finding requested coaching!'));
            redirect(l('coaching'));
        }

        if (!in_array($status, [ORDER_SUCCESS, ORDER_FAILED])) {
            $status = 'unknown';
        }

        $session = "";
        $data['status'] = $status;

        if ($status == ORDER_SUCCESS) {
            
            switch($merchant) {
                case STRIPE:
                    try {
                        $session = $this->stripe->checkout->sessions->retrieve(
                            $checkoutSessionId,
                            []
                        );           
                    } catch (\Exception $e) {
                        $this->session->set_flashdata('stripe_error', $e->getMessage());
                    }

                    if ($session) {
                        $payment_intent = $this->stripe->paymentIntents->retrieve($session->payment_intent);

                        if ($payment_intent) {

                            $this->model_coaching_application->update_model(
                                array(
                                    'where' => array(
                                        'coaching_application_checkout_session_id' => $checkoutSessionId,
                                        'coaching_application_coaching_id' => $data['coaching']['coaching_id'],
                                        'coaching_application_signup_id' => $this->userid
                                    )
                                ),
                                array(
                                    'coaching_application_transaction_id' => $session->payment_intent,
                                    'coaching_application_checkout_session_response' => str_replace('Stripe\Checkout\Session', '', str_replace('Stripe\Checkout\Session JSON:', '', ($session))),
                                    'coaching_application_status' => STATUS_ACTIVE,
                                    'coaching_application_payment_status' => STATUS_ACTIVE,
                                )
                            );

                            $order = $this->model_order->find_one(
                                array(
                                    'where' => array(
                                        'order_user_id' => $this->userid,
                                        'order_session_checkout_id' => $checkoutSessionId
                                    )
                                )
                            );
    
                            if($order) {
                                $updatedOrder = $this->model_order->update_model(
                                    array('where' => array(
                                        'order_user_id' => $this->userid,
                                        'order_session_checkout_id' => $checkoutSessionId
                                    )),
                                    array(
                                        'order_status' => STATUS_ACTIVE,
                                        'order_payment_status' => ($session->status == 'complete') ? 1 : 0,
                                        'order_status_message' => $session->status,
                                        'order_transaction_id' => $session->payment_intent,
                                        'order_response' => str_replace('Stripe\Checkout\Session', '', str_replace('Stripe\Checkout\Session JSON:', '', ($session))),
                                        'order_payment_comments' => 'Paid',
                                    )
                                );
        
                                if ($updatedOrder) {
                                    // saving to log for webhook differentiaition
                                    if(!$this->saveStripeLog($this->userid, STRIPE_LOG_REFERENCE_COACHING, $this->userid, STRIPE_LOG_RESOURCE_TYPE['checkout_sessions'], $session->id, str_replace('Stripe\Checkout\Session', '', str_replace('Stripe\Checkout\Session JSON:', '', ($session))))) {
                                        log_message('ERROR', 'Unable to generate log');
                                    }
                                }
                            }
                        }
                    }
                    break;
                case PAYPAL:
                    $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL . '/' . $_GET['token'];
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                    $response = $this->curlRequest($url, $headers);
                    $session = json_decode($response);

                    if($session) {
                        $this->model_coaching_application->update_model(
                            array(
                                'where' => array(
                                    'coaching_application_checkout_session_id' => $_GET['token'],
                                    'coaching_application_coaching_id' => $data['coaching']['coaching_id'],
                                    'coaching_application_signup_id' => $this->userid
                                )
                            ),
                            array(
                                'coaching_application_transaction_id' => $session->id,
                                'coaching_application_checkout_session_response' => serialize($session),
                                'coaching_application_status' => STATUS_ACTIVE,
                                'coaching_application_payment_status' => STATUS_ACTIVE,
                            )
                        );

                        $order = $this->model_order->find_one(
                            array(
                                'where' => array(
                                    'order_user_id' => $this->userid,
                                    'order_session_checkout_id' => $_GET['token']
                                )
                            )
                        );

                        if($order) {
                            $updatedOrder = $this->model_order->update_model(
                                array('where' => array(
                                    'order_user_id' => $this->userid,
                                    'order_session_checkout_id' => $_GET['token']
                                )),
                                array(
                                    'order_status' => STATUS_ACTIVE,
                                    'order_payment_status' => ($session->status == 'APPROVED') ? 1 : 0,
                                    'order_status_message' => $session->status,
                                    'order_transaction_id' => $session->id,
                                    'order_response' => serialize($session),
                                    'order_payment_comments' => 'Paid',
                                )
                            );
                        }
                    }
                    break;
                default:
                    error_404();
            }
        }
        
        if($order && $updatedOrder) {
            // Notification
            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_COACHING_REQUEST_COMPLETED, $data['coaching']['coaching_id'], NOTIFICATION_COACHING_REQUEST_COMPLETED_COMMENT);

            if(ENVIRONMENT != 'development') {
                //
                switch($merchant) {
                    case STRIPE:
                        $receipt_url = $payment_intent->charges ? $payment_intent->charges->data[0]->receipt_url : '';
                        if ($receipt_url && $this->user_data['signup_email'] && ENVIRONMENT != 'development') {
                            try {
                                // Generate Body of Email
                                $stripeReceipt_url = file_get_contents($receipt_url);
                                $matches = array();
                                if($stripeReceipt_url) {
                                    preg_match("/<body[^>]*>(.*?)<\/body>/is", $stripeReceipt_url, $matches);
                                }
                                $to = $this->user_data['signup_email'];
                                if(!empty($matches)) {
                                    $this->model_email->notification_order_charge_receipt($to, $matches[1], $config['site_name'] . ' Order Payment Receipt');
                                }
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
                            }
                        } else {
                            $this->model_email->generalOrderInvoice((int) $order['order_id']);
                        }
                        break;
                    case PAYPAL:
                        $this->model_email->generalOrderInvoice((int) $order['order_id']);
                        break;
                }
            }
        } else {
            // error_404();
        }

        //
        $this->layout_data['title'] = ucfirst($status) . ' | ' . $this->layout_data['title'];
        //
        $this->load_view('result', $data);
    }
}