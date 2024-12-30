<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Order
 */
class Order extends MY_Controller
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
     * Method listing
     *
     * @param string $type
     * @param int $page
     * @param int $limit
     * @param string $order_reference
     *
     * @return void
     */
    function listing(string $order_reference = PRODUCT_REFERENCE_PRODUCT, $type = ORDER_PAID, int $page = 1, int $limit = PER_PAGE): void
    {
        $data = array();

        if ($order_reference) {
            switch ($order_reference) {
                case PRODUCT_REFERENCE_PRODUCT:
                    $data['order_reference_type'] = ORDER_REFERENCE_PRODUCT;
                    break;
                case PRODUCT_REFERENCE_TECHNOLOGY:
                    $data['order_reference_type'] = ORDER_REFERENCE_TECHNOLOGY;
                    break;
                case PRODUCT_REFERENCE_TECHNOLOGY_LISTING:
                    $data['order_reference_type'] = ORDER_REFERENCE_TECHNOLOGY_LISTING;
                    break;
                case PRODUCT_REFERENCE_MEMBERSHIP:
                    $data['order_reference_type'] = ORDER_REFERENCE_MEMBERSHIP;
                    break;
                case PRODUCT_REFERENCE_JOB:
                    $data['order_reference_type'] = ORDER_REFERENCE_JOB;
                    break;
                default:
                    error_404();
            }
        }

        $data['order_reference'] = $order_reference;

        $data['type'] = $type;

        $data['page'] = $page;
        $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $param = array();
        $count_param['where']['order_reference_type'] = $param['where']['order_reference_type'] = $data['order_reference_type'];
        $count_param['where']['order_user_id'] = $param['where']['order_user_id'] = $this->userid;

        $param['order'] = 'order_id DESC';
        $param['offset'] = $paginationStart;
        $param['limit'] = $limit;

        switch ($type) {
            case ORDER_PAID:
                $count_param['where']['order_status'] = $param['where']['order_status'] = STATUS_TRUE;
				$count_param['where_in']['order_payment_status'] = $param['where_in']['order_payment_status'] = [ORDER_PAYMENT_PAID, ORDER_PAYMENT_TRIALING, ORDER_PAYMENT_CANCELLED];
                break;
            case ORDER_UNPAID:
                $count_param['where']['order_status'] = $param['where']['order_status'] = STATUS_FALSE;
                $count_param['where']['order_payment_status'] = $param['where']['order_payment_status'] = ORDER_PAYMENT_PENDING;
                break;
        }

        $param['joins'] = array(
            0 => array(
                'table' => 'signup',
                'joint' => 'signup.signup_id = order.order_user_id',
                'type' => 'both'
            )
        );

        $data['orders'] = $this->model_order->find_all(
            $param
        );
        $data['orders_count'] = $allRecrods = $this->model_order->find_count(
            $count_param
        );

        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = 'Orders' . ' ' . $type . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("listing", $data);
    }

    /**
     * Method detail
     *
     * @param string $order_id
     *
     * @return void
     */
    function detail(string $order_id = ''): void
    {
        $data = array();

        try {
            $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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

        $data['order'] = $this->model_order->find_one(
            array(
                'where' => array(
                    'order_id' => $order_id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = order.order_user_id',
                        'type' => 'both'
                    )
                )
            )
        );

        if (empty($data['order'])) {
            error_404();
        }

        $data['payment_method'] = '';

        if (isset($data['order']['signup_subscription_response']) && $data['order']['signup_subscription_response']) {
            $decoded_response = json_decode($data['order']['signup_subscription_response']);
            $default_payment_method = $decoded_response->default_payment_method;

            $data['payment_method'] = $this->resource('paymentMethods', $default_payment_method);
        }

        $data['order_items'] = $this->model_order_item->find_all_active(
            array(
                'where' => array(
                    'order_item_order_id' => $data['order']['order_id']
                )
            )
        );

        //
        $this->layout_data['title'] = 'Order detail | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * cart
     *
     * @return void
     */
    public function cart(): void
    {
        $data = array();
        //
        $this->layout_data['title'] = 'Cart | ' . $this->layout_data['title'];
        //
        $this->load_view("checkout/cart", $data);
    }

    /**
     * Method checkout
     *
     * @return void
     */
    public function checkout(string $order_id = '', string $reference_type = ORDER_REFERENCE_PRODUCT): void
    {
        $data = array();

        $data['countries'] = $this->model_countries->find_all();
        $data['reference_type'] = $reference_type;
        //
        $this->layout_data['title'] = 'Checkout | ' . $this->layout_data['title'];

        if ($order_id) {
            try {
                $data['order_id'] = $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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

            if($order_id) {
                $data['order'] = $this->model_order->find_one(
                    array(
                        'where' => array(
                            'order_id' => $order_id,
                            'order_reference_type' => $reference_type,
                            'order_user_id' => $this->userid,
                            'order_payment_status' => ORDER_PAYMENT_PENDING
                        ),
                        'joins' => array(
                            0 => array(
                                'table' => 'signup',
                                'joint' => 'signup.signup_id = order.order_user_id',
                                'type' => 'both'
                            )
                        )
                    )
                );

                if (empty($data['order'])) {
                    error_404();
                }

                $data['order_item'] = $this->model_order_item->find_all(
                    array(
                        'where' => array(
                            'order_item_order_id' => $data['order']['order_id']
                        )
                    )
                );
                $data['type'] = UPDATE;
            } else {
                $data['type'] = INSERT;
            }

            //
            $this->load_view("checkout/checkout", $data);
        } elseif ($this->cart->contents()) {
            $data['type'] = INSERT;
            //
            $this->load_view("checkout/checkout", $data);
        } else {
            $this->session->set_flashdata('error', ERROR_MESSAGE_CART_EMPTY);
            redirect(l('dashboard/order/cart'));
        }
    }

    /**
     * Method payment
     *
     * @param string $order_id
     *
     * @return void
     */
    function payment(string $order_id = ''): void
    {
        try {
            $data['order_id'] = $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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

        $data['order'] = array();

        if ($order_id) {
            $data['order'] = $this->model_order->find_one(
                array(
                    'where' => array(
                        'order_id' => $order_id,
                        // 'order_reference_type' => ORDER_REFERENCE_PRODUCT,
                        'order_user_id' => $this->userid,
                        'order_payment_status' => ORDER_PAYMENT_PENDING
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = order.order_user_id',
                            'type' => 'both'
                        )
                    )
                )
            );
        }

        if (empty($data['order'])) {
            error_404();
        }

        $data['order_item'] = $this->model_order_item->find_all(
            array(
                'where' => array(
                    'order_item_order_id' => $data['order']['order_id']
                )
            )
        );

        //
        $this->layout_data['title'] = 'Payment | ' . $this->layout_data['title'];
        //
        $this->load_view("checkout/payment", $data);
    }

    /**
     * Method result
     *
     * @param string $order_id
     * @param string $type
     *
     * @return void
     */
    public function result(string $order_id = '')
    {
        if (!$order_id) {
            error_404();
        }

        // if (!in_array($type, [ORDER_SUCCESS, ORDER_FAILED])) {
        //     error_404();
        // }

        $data = array();

        try {
            $data['order_id'] = $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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

        if ($order_id) {
            $data['order'] = $this->model_order->find_one(
                array(
                    'where' => array(
                        'order_id' => $order_id,
                        'order_reference_type' => ORDER_REFERENCE_PRODUCT,
                        'order_user_id' => $this->userid,
                        // 'order_payment_status' => ORDER_PAYMENT_PAID
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = order.order_user_id',
                            'type' => 'both'
                        )
                    )
                )
            );
        }

        if (empty($data['order'])) {
            error_404();
        }

        switch ($data['order']['order_payment_status']) {
            case 1:
                $data['type'] = ORDER_SUCCESS;
                $data['message'] = ORDER_SUCCESS_MESSAGE;
                break;
            case 2:
                $data['type'] = ORDER_DECLINED;
                $data['message'] = ORDER_DECLINED_MESSAGE;
                break;
            default:
                $data['type'] = ORDER_FAILED;
                $data['message'] = ORDER_FAILED_MESSAGE;
                break;
        }

        //
        $this->layout_data['title'] = 'Order result | ' . $this->layout_data['title'];
        //
        $this->load_view("checkout/result", $data);
    }

    /**
     * Method invoices
     *
     * @return void
     */
    function invoices($type = INVOICE_SUBSCRIPTION, $page = ''): void
    {
        if ($this->model_signup->hasPremiumPermission()) {
            $data = array();
            $data['page'] = $page;

            $data['type'] = $type;
            $data['invoices'] = NULL;

            switch($type) {
                case INVOICE_SUBSCRIPTION:
                    $search_array = [
                        'query' => 'subscription:"' . $this->user_data['signup_subscription_id'] . '"',
                        'limit' => PER_PAGE,
                    ];
                    if ($page) {
                        $search_array['page'] = urldecode($data['page']);
                    }
                    try {
                        $data['invoices'] = $this->stripe_v2020->invoices->search($search_array);
                    } catch (\Exception $e) {
                        log_message('ERROR', $e->getMessage());
                        //
                        $this->_log_message(
                            LOG_TYPE_API,
                            LOG_SOURCE_STRIPE,
                            LOG_LEVEL_ERROR,
                            $e->getMessage(),
                            ''
                        );
                    }
                    break;
                case INVOICE_PRODUCT:
                    $data['page'] = $page = $page ? $page : 1;
                    $data['limit'] = $limit = PER_PAGE;
                    $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    $data['invoices'] = $this->model_order->find_all_active(
                        array(
                            'order' => 'order_id desc',
                            'offset' => $data['offset'],
                            'limit' => $limit,
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_PRODUCT
                            ),
                        )
                    );
                    $data['invoices_count'] = $allRecrods = $this->model_order->find_count_active(
                        array(
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_PRODUCT
                            ),
                        )
                    );
                    $data['totalPages'] = ceil($allRecrods / $limit);
                    break;
                case INVOICE_SERVICE:
                    $data['page'] = $page = $page ? $page : 1;
                    $data['limit'] = $limit = PER_PAGE;
                    $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    $data['invoices'] = $this->model_order->find_all_active(
                        array(
                            'order' => 'order_id desc',
                            'offset' => $data['offset'],
                            'limit' => $limit,
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_SERVICE
                            ),
                        )
                    );
                    $data['invoices_count'] = $allRecrods = $this->model_order->find_count_active(
                        array(
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_SERVICE
                            ),
                        )
                    );
                    $data['totalPages'] = ceil($allRecrods / $limit);
                    break;
                case INVOICE_SERVICE_PROVIDED:
                        $data['page'] = $page = $page ? $page : 1;
                        $data['limit'] = $limit = PER_PAGE;
                        $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;
                        $data['prev'] = $page - 1;
                        $data['next'] = $page + 1;
    
                        $data['invoices'] = $this->model_order->find_all_active(
                            array(
                                'order' => 'order_id desc',
                                'offset' => $data['offset'],
                                'limit' => $limit,
                                'where' => array(
                                    'product_signup_id' => $this->userid,
                                    'order_reference_type' => ORDER_REFERENCE_SERVICE,
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'product',
                                        'joint' => 'product.product_id = order.order_reference_id',
                                        'type' => 'both'
                                    )
                                )
                            )
                        );
                        $data['invoices_count'] = $allRecrods = $this->model_order->find_count_active(
                            array(
                                'where' => array(
                                    'product_signup_id' => $this->userid,
                                    'order_reference_type' => ORDER_REFERENCE_SERVICE
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'product',
                                        'joint' => 'product.product_id = order.order_reference_id',
                                        'type' => 'both'
                                    )
                                )
                            )
                        );
                        $data['totalPages'] = ceil($allRecrods / $limit);
                        break;
                case INVOICE_JOB:
                    $data['page'] = $page = $page ? $page : 1;
                    $data['limit'] = $limit = PER_PAGE;
                    $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    $data['invoices'] = $this->model_order->find_all_active(
                        array(
                            'order' => 'order_id desc',
                            'offset' => $data['offset'],
                            'limit' => $limit,
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_JOB
                            ),
                        )
                    );
                    $data['invoices_count'] = $allRecrods = $this->model_order->find_count_active(
                        array(
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_JOB
                            ),
                        )
                    );
                    $data['totalPages'] = ceil($allRecrods / $limit);
                    break;
                case INVOICE_TECHNOLOGY:
                    $data['page'] = $page = $page ? $page : 1;
                    $data['limit'] = $limit = PER_PAGE;
                    $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    $data['invoices'] = $this->model_order->find_all_active(
                        array(
                            'order' => 'order_id desc',
                            'offset' => $data['offset'],
                            'limit' => $limit,
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_TECHNOLOGY_LISTING
                            ),
                        )
                    );
                    $data['invoices_count'] = $allRecrods = $this->model_order->find_count_active(
                        array(
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_TECHNOLOGY_LISTING
                            ),
                        )
                    );
                    $data['totalPages'] = ceil($allRecrods / $limit);
                    break;
                case INVOICE_COACHING:
                    $data['page'] = $page = $page ? $page : 1;
                    $data['limit'] = $limit = PER_PAGE;
                    $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    $data['invoices'] = $this->model_order->find_all_active(
                        array(
                            'order' => 'order_id desc',
                            'offset' => $data['offset'],
                            'limit' => $limit,
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_COACHING
                            ),
                        )
                    );
                    $data['invoices_count'] = $allRecrods = $this->model_order->find_count_active(
                        array(
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_COACHING
                            ),
                        )
                    );
                    $data['totalPages'] = ceil($allRecrods / $limit);
                    break;
            }

            //
            $this->layout_data['title'] = 'Invoices' . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("invoices", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method checkoutAction
     *
     * @return void
     */
    function checkoutAction(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $bypass = FALSE;
        $account_error = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if ($this->cart->contents() || (isset($_POST['order_id']) && $_POST['order_id'])) {
                    if (isset($_POST['order'])) {

                        $stripe_connect_active = 0;

                        if ($this->cart->contents()) {
                            $order_items = $this->cart->contents();
                            foreach ($order_items as $value) {
                                $product_account_detail = $this->model_product->find_one_active(
                                    array(
                                        'where' => array(
                                            'product_id' => $value['id'],
                                            'signup_is_stripe_connected' => STATUS_ACTIVE
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

                                if (!empty($product_account_detail)) {
                                    $stripe_connect_active++;
                                }
                            }
                            if ($stripe_connect_active != count($order_items)) {
                                // stripe connect inactive for one or more product owner
                                $account_error = TRUE;
                            }
                        }

                        if (!$account_error) {

                            $error = FALSE;
                            $affected_order = 0;
                            $order_id = NULL;

                            $affect_param = $_POST['order'];
                            if (!isset($_POST['shipping_check'])) {
                                $affect_param['order_shipping_firstname'] = $affect_param['order_firstname'];
                                $affect_param['order_shipping_lastname'] = $affect_param['order_lastname'];
                                $affect_param['order_shipping_email'] = $affect_param['order_email'];
                                $affect_param['order_shipping_phone'] = $affect_param['order_phone'];
                                $affect_param['order_shipping_address1'] = $affect_param['order_address1'];
                                $affect_param['order_shipping_country'] = $affect_param['order_country'];
                                $affect_param['order_shipping_state'] = $affect_param['order_state'];
                                $affect_param['order_shipping_city'] = $affect_param['order_city'];
                                $affect_param['order_shipping_zip'] = $affect_param['order_zip'];
                            } else {
                                $affect_param['order_is_shipment_address'] = STATUS_ACTIVE;
                            }

                            $affect_param['order_quantity'] = 1;
                            $affect_param['order_status'] = STATUS_INACTIVE;
                            $affect_param['order_payment_status'] = STATUS_INACTIVE;
                            $affect_param['order_status_message'] = "Pending";
                            $affect_param['order_payment_comments'] = "Unpaid";
                            $affect_param['order_shipment_price'] = 0;
                            $affect_param['order_currency'] = DEFAULT_CURRENCY_CODE;

                            if (isset($_POST['order_id']) && $_POST['order_id']) {
                                try {
                                    $order_id = JWT::decode($_POST['order_id'], CI_ENCRYPTION_SECRET);
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
                                    $error = TRUE;
                                }

                                if (!$error) {
                                    // return number of affected rows
                                    $affected_order = $this->model_order->update_by_pk($order_id, $affect_param);
                                    // in case no changes has been encountered
                                    $bypass = TRUE;
                                }
                            } else {
                                // return last inserted id
                                $order_id = $affected_order = $this->model_order->insert_record($affect_param);
                            }

                            if ($affected_order || $bypass) {
                                if ($this->cart->contents()) {
                                    $order_items = $this->cart->contents();
                                    foreach ($order_items as $value) {
                                        $insert_item = array();
                                        $insert_item['order_item_status'] = STATUS_ACTIVE;
                                        $insert_item['order_item_order_id'] = $order_id;
                                        $insert_item['order_item_product_id'] = $value['id'];
                                        $insert_item['order_item_product_request_id'] = $value['options']['request_id'];
                                        $insert_item['order_item_user_id'] = $this->userid;
                                        $insert_item['order_item_price'] = $value['price'];
                                        $insert_item['order_item_subtotal'] = $value['subtotal'];
                                        $insert_item['order_item_qty'] = $value['qty'];
                                        $insert_item['order_item_option'] = serialize($value['options']);
                                        $insert_item['order_item_payment_due'] = $insert_item['order_item_subtotal'];
                                        // $insert_item['order_item_subtotal'] - ($insert_item['order_item_subtotal'] * ((int) g('db.admin.service_fee') / 100))

                                        //
                                        $this->model_order_item->insert_record($insert_item);
                                    }
                                    //
                                    $this->cart->destroy();
                                }
                                // notification
                                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_ORDER_SAVED, 0, NOTIFICATION_ORDER_SAVED_COMMENT);

                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                                $json_param['redirect_url'] = l('dashboard/order/payment/' . JWT::encode($order_id));
                            } else {
                                $json_param['txt'] = __(ERROR_MESSAGE);
                            }
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE_STRIPE_CONNECT_OWNER_ERROR);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_CART_EMPTY);
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
     * Method paymentAction
     *
     * @return void
     */
    public function paymentAction(): void
    {
        global $config;

        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $error = FALSE;
        $charge = NULL;
        $response = NULL;
        $order = array();
        $order_payment_status = FALSE;
        $charge_id = NULL;
        $receipt_url = '';
        $account_error = FALSE;
        $transfer_data = "";

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['order_id']) && $_POST['order_id']) {
                $order_id = $_POST['order_id'];

                try {
                    $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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
                    $error = TRUE;
                    $json_param['txt'] = $e->getMessage();
                }

                if (!$error) {
                    $order = $this->model_order->find_one(
                        array(
                            'where' => array(
                                'order_id' => (int) $order_id,
                                'order_user_id' => $this->userid,
                            )
                        )
                    );
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }

                if (isset($_POST['stripeToken']) && $_POST['stripeToken']) {

                    if (!empty($order)) {

                        $order_items = $this->model_order_item->find_all_active(
                            array(
                                'where' => array(
                                    'order_item_order_id' => $order['order_id']
                                )
                            )
                        );

                        if (!empty($order_items)) {
                            $stripe_connect_active = 0;
                            foreach ($order_items as $value) {
                                $product_account_detail = $this->model_product->find_one_active(
                                    array(
                                        'where' => array(
                                            'product_id' => $value['order_item_product_id'],
                                            'signup_is_stripe_connected' => STATUS_ACTIVE
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
                                if (!empty($product_account_detail)) {
                                    $stripe_connect_active++;
                                }
                            }
                            if ($stripe_connect_active != count($order_items)) {
                                // stripe connect inactive for one or more product owner
                                $account_error = TRUE;
                            }

                            //
                            if (!$account_error) {
                                $token = $_POST['stripeToken'];

                                $total = intval($order['order_total']) * 100;

                                try {
                                    $charge = $this->createStripeResource(
                                        'charges',
                                        [
                                            "amount" => $total,
                                            "currency" => DEFAULT_CURRENCY_CODE,
                                            "card" => $token,
                                            "description" => "Stripe charge for order id: " . $order['order_id']
                                        ]
                                    );
                                } catch (\Exception $e) {
                                    $json_param['txt'] = $e->getMessage();
                                    $error = TRUE;
                                }

                                if (!$error) {
                                    $charge_id = $charge->id;
                                    $receipt_url = $charge->receipt_url;
                                    $charge = str_replace('Stripe\Charge JSON:', '', (string) $charge);
                                    $response = json_decode($charge, true);
                                    if ($response['status'] == 'succeeded') {
                                        $order_payment_status = TRUE;
                                    }
                                }

                                if ($order_payment_status) {

                                    $affect_param = array();
                                    $affect_param['order_payment_status'] = STATUS_TRUE;
                                    $affect_param['order_status'] = STATUS_TRUE;
                                    $affect_param['order_payment_comments'] = 'Completed';
                                    $affect_param['order_merchant'] = 'STRIPE';
                                    $affect_param['order_stripe_response'] = $charge;
                                    $affect_param['order_stripe_charge_id'] = $charge_id;
                                    $affected = $this->model_order->update_by_pk($order_id, $affect_param);

                                    // mailing
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
                                    }

                                    if ($affected) {
                                        // refetching all again
                                        $order_items = $this->model_order_item->find_all_active(
                                            array(
                                                'where' => array(
                                                    'order_item_order_id' => $order_id,
                                                    // transfer status false
                                                    'order_item_stripe_transfer_status' => TRANSFER_UNPAID
                                                ),
                                                'joins' => array(
                                                    0 => array(
                                                        'table' => 'product',
                                                        'joint' => 'product.product_id = order_item.order_item_product_id',
                                                        'type' => 'both'
                                                    ),
                                                    1 => array(
                                                        'table' => 'signup',
                                                        'joint' => 'signup.signup_id = product.product_signup_id',
                                                        'type' => 'both'
                                                    ),
                                                    2 => array(
                                                        'table' => 'order',
                                                        'joint' => 'order.order_id = order_item.order_item_order_id',
                                                        'type' => 'both'
                                                    )
                                                )
                                            )
                                        );

                                        foreach ($order_items as $value) {
                                            try {
                                                $transfer_data = $this->createStripeResource('transfers', [
                                                    "amount" => $value['order_item_payment_due'] * 100,
                                                    "currency" => DEFAULT_CURRENCY_CODE,
                                                    "destination" => $value['signup_account_id'],
                                                    "source_transaction" => $value['order_stripe_charge_id'],
                                                ]);

                                                $updated = $this->model_order_item->update_by_pk(
                                                    $value['order_item_id'],
                                                    array(
                                                        'order_item_stripe_transfer_status' => STATUS_ACTIVE,
                                                        'order_item_stripe_transfer_response' => str_replace('Stripe\Transfer JSON:', '', (string) $transfer_data)
                                                    )
                                                );

                                                if ($updated) {
                                                    $this->model_product_request->update_model(
                                                        array(
                                                            'where' => array(
                                                                'product_request_product_id' => $value['order_item_product_id'],
                                                                'product_request_signup_id' => $this->userid
                                                            )
                                                        ),
                                                        array(
                                                            'product_request_current_status' => REQUEST_COMPLETE
                                                        )
                                                    );
                                                }
                                            } catch (\Exception $e) {
                                                $error = TRUE;
                                                $json_param['txt'] = $e->getMessage();
                                            }
                                        }

                                        if (!$error) {
                                            // notification here
                                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PAYMENT_COMPLETED, 0, NOTIFICATION_PAYMENT_COMPLETED_COMMENT);
                                            //
                                            $json_param['status'] = STATUS_TRUE;
                                            $json_param['txt'] = SUCCESS_MESSAGE;
                                            $json_param['redirect_url'] = l('dashboard/order/result/' . JWT::encode($order_id));
                                        }
                                    } else {
                                        $json_param['txt'] = __(ERROR_MESSAGE_UPDATE);
                                    }
                                }
                            } else {
                                $json_param['txt'] = __(ERROR_MESSAGE_STRIPE_CONNECT_OWNER_ERROR);
                            }
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else if ('erscrow') {
                    // escrow transfer here
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_STRIPE_TOKEN);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method createOrder - PayPal
     *
     * @return void
     */
    function createOrder() : void
    {
        $json_param['status'] = FALSE;
        $json_param['response'] = '';
        $json_param['message'] = 'An error occurred while trying to process your request.';
        $response = NULL;
        $error = FALSE;
        $order = array();
        $account_error = FALSE;
        $purchase_units = array();

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['order_id']) && $_POST['order_id']) {
                $order_id = $_POST['order_id'];

                try {
                    $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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
                    $error = TRUE;
                    $json_param['message'] = $e->getMessage();
                }

                if (!$error) {
                    $order = $this->model_order->find_one(
                        array(
                            'where' => array(
                                'order_id' => (int) $order_id,
                                'order_user_id' => $this->userid,
                            )
                        )
                    );
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }

                if (!empty($order)) {

                    $order_items = $this->model_order_item->find_all_active(
                        array(
                            'where' => array(
                                'order_item_order_id' => $order['order_id']
                            )
                        )
                    );

                    if (!empty($order_items)) {
                        $paypal_connect_active = 0;
                        foreach ($order_items as $value) {
                            $product_account_detail = $this->model_product->find_one_active(
                                array(
                                    'where' => array(
                                        'product_id' => $value['order_item_product_id'],
                                        'signup_paypal_email !=' => NULL
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
                            if (!empty($product_account_detail)) {
                                $paypal_connect_active++;
                            }
                        }
                        if ($paypal_connect_active != count($order_items)) {
                            // paypal connect inactive for one or more product owner
                            $account_error = TRUE;
                        }

                        //
                        if (!$account_error) {

                            $total = intval($order['order_total']);

                            $order_items = $this->model_order_item->find_all_active(
                                array(
                                    'where' => array(
                                        'order_item_order_id' => $order_id
                                    ),
                                    'joins' => array(
                                        0 => array(
                                            'table' => 'product',
                                            'joint' => 'product.product_id = order_item.order_item_product_id',
                                            'type' => 'both'
                                        ),
                                        1 => array(
                                            'table' => 'signup',
                                            'joint' => 'signup.signup_id = product.product_signup_id',
                                            'type' => 'both'
                                        ),
                                        2 => array(
                                            'table' => 'order',
                                            'joint' => 'order.order_id = order_item.order_item_order_id',
                                            'type' => 'both'
                                        )
                                    )
                                )
                            );

                            $purchase_units[] = array(
                                "reference_id" => (time() . rand(100, 10000)),
                                "amount" => array(
                                    "currency_code" => DEFAULT_CURRENCY_CODE,
                                    "value" => ($order['order_fee'] + $order['order_shipping'] + $order['order_tax'])
                                ),
                            );

                            foreach ($order_items as $order_item) {
                                $purchase_units[] = array(
                                    "reference_id" => (time() . rand(100, 10000)),
                                    "amount" => array(
                                        "currency_code" => DEFAULT_CURRENCY_CODE,
                                        "value" => $total
                                    ),
                                    "payee" => array(
                                        'email_address' => $order_item['signup_paypal_email']
                                    ),
                                );
                            }

                            try {
                                // authorize only

                                $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL;
                                $headers = array();
                                $headers[] = 'Content-Type: application/json';
                                $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                                $body = array(
                                    "intent" => "AUTHORIZE",
                                    "purchase_units" => $purchase_units
                                );

                                //
                                $response = $this->curlRequest($url, $headers, $body, TRUE);

                                $decoded_response = json_decode($response);
                                if(property_exists($decoded_response, 'message')) {
                                    $json_param['message'] = $decoded_response->message;
                                } else {
                                    $json_param['status'] = TRUE;
                                    $json_param['message'] = SUCCESS_MESSAGE;
                                    $json_param['response'] = $decoded_response;
                                }

                                log_message('ERROR', serialize($response));
                            } catch(\Exception $e) {
                                $json_param['message'] = $e->getMessage();
                                log_message('ERROR', $e->getMessage());
                            }
                        } else {
                            $json_param['message'] = ERROR_MESSAGE_PAYPAL_CONNECT_OWNER_ERROR;
                        }
                    } else {
                        $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['message'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method authorizeOrder - PayPal
     *
     * @return void
     */
    function authorizeOrder() : void
    {
        $json_param['status'] = FALSE;
        $json_param['message'] = 'An error occurred while trying to process your request.';

        $error = FALSE;
        $order = array();
        $order_payment_status = FALSE;
        $status = 'PENDING';

        // paypal order id
        $orderId = '';
        $purchase_units = [];

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['order_id']) && $_POST['order_id']) {
                $order_id = $_POST['order_id'];

                try {
                    $order_id = JWT::decode($order_id, CI_ENCRYPTION_SECRET);
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
                    $error = TRUE;
                    $json_param['message'] = $e->getMessage();
                }

                if (!$error) {
                    $order = $this->model_order->find_one(
                        array(
                            'where' => array(
                                'order_id' => (int) $order_id,
                                'order_user_id' => $this->userid,
                            )
                        )
                    );
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }

                if (!empty($order)) {
                    try {

                        // https://developer.paypal.com/docs/checkout/standard/customize/authorization/

                        // fetch
                        // curl -v -X GET https://api-m.sandbox.paypal.com/v2/checkout/orders/48S239579N169645 \
                        //   -H "Content-Type: application/json" \
                        //   -H "Authorization: Bearer ACCESS-TOKEN" \

                        $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL . '/' . $_POST['orderID'];
                        $headers = array();
                        $headers[] = 'Content-Type: application/json';
                        $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                        $response = $this->curlRequest($url, $headers);
                        $decoded_response = json_decode($response);
                        if(property_exists($decoded_response, 'message')) {
                            $json_param['message'] = $decoded_response->message;
                        } else {
                            $orderId = $decoded_response->id;
                        }

                        if($orderId) {

                            $order_items = $this->model_order_item->find_all_active(
                                array(
                                    'where' => array(
                                        'order_item_order_id' => $order_id,
                                        // transfer status false
                                        'order_item_stripe_transfer_status' => TRANSFER_UNPAID
                                    ),
                                    'joins' => array(
                                        0 => array(
                                            'table' => 'product',
                                            'joint' => 'product.product_id = order_item.order_item_product_id',
                                            'type' => 'both'
                                        ),
                                        1 => array(
                                            'table' => 'signup',
                                            'joint' => 'signup.signup_id = product.product_signup_id',
                                            'type' => 'both'
                                        ),
                                        2 => array(
                                            'table' => 'order',
                                            'joint' => 'order.order_id = order_item.order_item_order_id',
                                            'type' => 'both'
                                        )
                                    )
                                )
                            );

                            // capture
                            // curl -v -X POST https://api-m.sandbox.paypal.com/v2/payments/authorizations/66P728836U784324A/capture \
                            //   -H "Content-Type: application/json" \
                            //   -H "Authorization: Bearer ACCESS-TOKEN" \
                            //   -H "PayPal-Request-Id: PAYPAL-REQUEST-ID" \

                            $captureUrl = str_replace('{orderId}', $orderId, PAYPAL_PAYMENT_CAPTURE_URL);
                            $url = PAYPAL_URL . $captureUrl;

                            $headers = array();
                            $headers[] = 'Content-Type: application/json';
                            $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                            foreach ($order_items as $order_item) {

                                $purchase_units[] = array(
                                    "payment_instruction" => array(
                                        "platform_fees" => array(
                                            0 => array(
                                                "amount" => array(
                                                    "currency_code" => DEFAULT_CURRENCY_CODE,
                                                    "value" => (intval($order_item['order_item_subtotal']) * ((int) g('db.admin.service_fee') / 100))
                                                )
                                            )
                                        )
                                    )
                                );
                            }

                            $body = array(
                                "purchase_units" => $purchase_units
                            );

                            $response = $this->curlRequest($url, $headers, $body, TRUE);
                            $decoded_response = json_decode($response);
                            if(property_exists($decoded_response, 'message')) {
                                $json_param['message'] = $decoded_response->message;
                            } else {
                                foreach ($order_items as $order_item) {
                                    $updated = $this->model_order_item->update_by_pk(
                                        $order_item['order_item_id'],
                                        array(
                                            'order_item_stripe_transfer_status' => STATUS_ACTIVE,
                                        )
                                    );
                                }

                                $orderId = $decoded_response->id;
                                $status = $decoded_response->status;
                            }
                        } else {
                            $error = TRUE;
                        }
                    } catch (\Exception $e) {
                        $json_param['message'] = $e->getMessage();
                        $error = TRUE;
                    }

                    if (!$error) {
                        // check for id
                        if ($status == 'COMPLETED') {
                            $order_payment_status = TRUE;
                        }
                    }

                    if ($order_payment_status) {
                        // '_token': $('meta[name=csrf-token]').attr("content"),
                        // 'order_id': $('input[name=order_id]').val(),
                        // 'orderID': data.orderID,
                        // 'payerID': data.payerID,
                        // 'paymentID': data.paymentID,
                        // 'facilitatorAccessToken': data.facilitatorAccessToken,

                        $affect_param = array();
                        $affect_param['order_payment_status'] = STATUS_TRUE;
                        $affect_param['order_status'] = STATUS_TRUE;
                        $affect_param['order_payment_comments'] = 'Completed';
                        $affect_param['order_merchant'] = PAYPAL;
                        $affect_param['order_paypal_response'] = serialize($_POST);
                        $affect_param['order_paypal_order_id'] = $orderId;
                        $affected = $this->model_order->update_by_pk($order_id, $affect_param);

                        if($affected) {
                            // fire invoice email

                            // notification here
                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PAYMENT_COMPLETED, 0, NOTIFICATION_PAYMENT_COMPLETED_COMMENT);
                            //
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;

                            // captured on response save order and redirect to success page
                            $json_param['redirect_url'] = l('dashboard/order/result/' . JWT::encode($order_id));
                        }
                    }
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['message'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method taxCalculator - checkout tax calculator
     *
     * @return void
     */
    function taxCalculator(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = 'Failed to retrieve tax details of the requested zipcode.';
        $json_param['response'] = '';

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['zip_code']) && $_POST['zip_code']) {
                $zipcode = $_POST['zip_code'];
                $url = TAX_API_URL . '?zip_code=' . $zipcode;
                $headers = [
                    'Content-Type: application/json',
                    'X-Api-Key: ' . X_API_KEY,
                ];
                $response = $this->curlRequest($url, $headers);
                $decoded_json = json_decode($response, TRUE);

                if (isset($decoded_json['error'])) {
                    $json_param['txt'] = $decoded_json['error'];
                } else {
                    $json_param['response'] = $decoded_json;
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = SUCCESS_MESSAGE;
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }
}
