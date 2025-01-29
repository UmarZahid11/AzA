<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Membership
 */
class Membership extends MY_Controller
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
        $param = array();
        $param['where']['inner_banner_name'] = 'Membership';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $membership_sections = $this->model_membership_section->find_all_active();
        //
        $sectionData = [];
        foreach($membership_sections as $section) {
            $sectionData[$section['membership_section_name']] = $this->model_membership_attribute_identifier->find_all_active(
                array(
                    'where' => array(
                        'membership_attribute_section_id' => $section['membership_section_id']
                    ),
                    'joins' => array(
                        0 => array(
                            "table" => "membership_attribute" ,
                            "joint" => "membership_attribute.membership_attribute_id = membership_attribute_identifier.membership_attribute_identifier_id",
                            "type" => "both"   
                        )
                    ),
                    'fields' => 'membership_attribute_name'
                )
            );
        }
        $data['sectionData'] = $sectionData;

        $memberships = $this->model_membership->find_all_active();
        //
        $membershipData = [];
        foreach($memberships as $membership) {
            $membershipData[$membership['membership_id']]['membership'] = $membership;
            $membershipData[$membership['membership_id']]['membership']['membership_interval'] = $this->model_membership_interval->find_by_pk($membership['membership_interval_id']);
            $membershipData[$membership['membership_id']]['data'] = $this->model_membership_pivot->find_all_active(
                array(
                    'where' => array(
                        'membership_pivot_membership_id' => $membership['membership_id']
                    ),
                    'joins' => array(
                        0 => array(
                            "table" => "membership_attribute_identifier" ,
                            "joint" => "membership_attribute_identifier.membership_attribute_identifier_id = membership_pivot.membership_pivot_attribute_id",
                            "type" => "both"   
                        )
                    ),
                    'fields' => 'membership_pivot_value'
                )
            );
        }
        $data['membershipData'] = $membershipData;

        //
        $this->layout_data['title'] = 'Membership | ' . $this->layout_data['title'];
        //
        $this->load_view('index', $data);
    }

    /**
     * Method view
     *
     * @return void
     */
    public function view()
    {
        $param = array();
        $param['where']['inner_banner_name'] = 'Membership';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['membership'] = $this->model_membership->find_all_active(
            array(
                'order' => 'membership_id desc',
                'limit' => 3,
                'where_in' => array(
                    'membership_id' => [ROLE_3, ROLE_1]
                )
            )
        );

        $data['membership_attribute'] = $this->model_membership_attribute->find_all_active();

        //
        $this->layout_data['title'] = 'Membership | ' . $this->layout_data['title'];
        //
        $this->load_view('view', $data);
    }

    /**
     * Method view
     *
     * @return void
     */
    public function pricing()
    {
        $param = array();
        $param['where']['inner_banner_name'] = 'Membership';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['membership'] = $this->model_membership->find_all_active(
            array(
                'order' => 'membership_id desc',
                'limit' => 3,
                'where_in' => array(
                    'membership_id' => [ROLE_3, ROLE_1]
                )
            )
        );

        $data['membership_attribute'] = $this->model_membership_attribute->find_all_active();

        //
        $this->layout_data['title'] = 'Membership | ' . $this->layout_data['title'];
        //
        $this->load_view('pricing', $data);
    }
    
    /**
     * Method payment
     *
     * @param string $id
     * @param string $interval
     *
     * @return void
     */
    public function payment(string $id = '', string $interval = SUBSCRIPTION_INTERVAL_1, $merchant = STRIPE): void
    {
        if (!$id) {
            $this->session->set_flashdata('error', __('Error in finding requested membership!'));
            redirect(l('membership'));
        } else {
            try {
                $id = JWT::decode($id, CI_ENCRYPTION_SECRET);
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

        if (!in_array($interval, [SUBSCRIPTION_INTERVAL_1]))
            error_404();
       
        $data['merchant'] = $merchant;

        //
        $this->layout_data['title'] = 'Payment | ' . $this->layout_data['title'];

        $data['membership'] = $this->model_membership->find_by_pk($id);

        switch (true) {
            case ($this->userid == 0):
                $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
                redirect(l('login'));
                break;
                // requested membership doesn't exists?
            case (empty($data['membership'])):
                $this->session->set_flashdata('error', __('An error occurred while trying to find the requested membership.'));
                redirect(l('membership'));
                break;
                // already has a membership other than general?
            case (
                    (
                        isset($this->user_data['signup_membership_status']) && 
                        $this->user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE &&
                        $data['membership']['membership_id'] == $this->user_data['signup_membership_id']
                    )
                ):
                $this->session->set_flashdata('error', __('A membership package is already active.'));
                redirect(l('membership'));
                break;
            // case ($data['membership']['membership_cost'] == 0) :
            //     $this->session->set_flashdata('error', __('Cannot subscribe to the package of 0 cost.'));
            //     redirect(l('membership'));
            //     break;
                // 	is approved by admin?
            // case ((isset($this->user_data['signup_is_approved']) && !$this->user_data['signup_is_approved']) && (!$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_APPROVAL, TRUE))):
            //     $this->session->set_flashdata('error', __('Error: The account approval is pending. Try contacting the website administrator for approval.'));
            //     redirect(l('membership'));
            //     break;
                // is email confirmed?
            // case ((isset($this->user_data['signup_is_confirmed']) && !$this->user_data['signup_is_confirmed'] && $this->getConfigValueByVariable('email_confirmation')) && (!$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_EMAIL, TRUE))):
            //     $this->session->set_flashdata('error', __('Error: Email address confirmation is required to subscribe this membership.'));
            //     redirect(l('membership'));
            //     break;
                // uncomment when phone verification is functional (when premium twilio keys are available)
            // case ((isset($this->user_data['signup_is_phone_confirmed']) && !$this->user_data['signup_is_phone_confirmed'] && $this->getConfigValueByVariable('phone_verification')) && (!$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_PHONE, TRUE))):
            //     $this->session->set_flashdata('error', __('Error: Phone number confirmation is required to subscribe this membership.'));
            //     redirect(l('membership'));
            //     break;
        }

        //
        $param = array();
        $param['where']['inner_banner_name'] = 'Membership Payment';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $shippment_price = 0;
        $data['new_membership'] = FALSE;
        $data['free_membership'] = FALSE;
        $data['membership_updated'] = FALSE;
        $data['membership_cancelled'] = FALSE;

        $data['merchant_session'] = $merchant_session = '';

        $membershipCost = $data['membership']['membership_cost'];

        if ($membershipCost == '0' || $membershipCost == NULL) {
            $data['free_membership'] = TRUE;
        } else {
            //
            switch ($interval) {
                case SUBSCRIPTION_INTERVAL_1:
                    $membershipCost = $membershipCost;
                    break;
            }
        }

        // cancel or update previous subscription if active
        if($this->user_data['signup_subscription_id']) {
            if($this->user_data['signup_merchant'] == STRIPE) {

                //
                $previous_subscription = $this->user_data['signup_subscription_id'];
                $previous_subscription_detail = $this->model_stripe_log->resource('subscriptions', $previous_subscription);

                //
                if($previous_subscription_detail && isset($previous_subscription_detail->plan) && $previous_subscription_detail->plan->active == 1) {
                    if($data['free_membership']) {
                        // cancel previous subscription if active
                        $this->stripe->subscriptions->cancel($this->user_data['signup_subscription_id'], []);
                        $data['membership_cancelled'] = TRUE;
                    } else {
                        if(isset($previous_subscription_detail->items) && $previous_subscription_detail->items->data[0]->id) {
                            $subscription_item_id = $previous_subscription_detail->items->data[0]->id;

                            // update membership from previous membership
                            switch($data['membership']['membership_id']) {
                                case ROLE_3:
                                    $product_title = MEMBERSHIP_PRODUCT_ENTREPRENEUR_TITLE; 
                                    break;
                                case ROLE_4:
                                    $product_title = MEMBERSHIP_PRODUCT_INNOVATOR_TITLE; 
                                    break;
                                case ROLE_5:
                                    $product_title = MEMBERSHIP_PRODUCT_LEADER_TITLE; 
                                    break;
                            }
            
                            $product = $this->model_stripe_log->createStripeResource('products', [
                                'name' => $product_title
                            ]);
                            $price = $this->model_stripe_log->createStripeResource('prices', [
                                'unit_amount' => $membershipCost * 100,
                                'currency' => DEFAULT_CURRENCY_CODE,
                                'product' => $product->id,
                                'recurring' => array(
                                    'interval' => SUBSCRIPTION_INTERVAL_TYPE,
                                    'interval_count' => $interval,
                                ),
                            ]);
                            $this->stripe->subscriptions->update(
                                $this->user_data['signup_subscription_id'],
                                [
                                    'items' => [
                                        [
                                            'id' => $subscription_item_id,
                                            'price' => $price->id,
                                        ],
                                    ],
                                ]
                            );
                            $data['membership_updated'] = TRUE;
                        }
                    }
                } else {
                    $data['new_membership'] = TRUE;
                }
            }
        } else {
            $data['new_membership'] = TRUE;
        }

        if($data['new_membership'] || ($data['free_membership'] && !$data['membership_cancelled']) && !$data['membership_updated']) {

            $insertParam = array();
            $insertedOrder = 0;
            $paypal_error = FALSE;

            //
            if(!$data['free_membership']) {
                switch($merchant) {
                    case STRIPE:
                        $data['merchant_session'] = $merchant_session = $this->setupStripeCustomerPayment($data['membership'], (float) $membershipCost, (int) $interval);
                        break;
                    case PAYPAL:
                        // plan is fetched
                        $data['merchant_session'] = $merchant_session = $this->setupPaypalCustomerPayment($data['membership'], (float) $membershipCost, (int) $interval);
                        break;
                }
            }

            //
            $insertParam['order_user_id'] = $this->userid;
            $insertParam['order_email'] = $this->user_data['signup_email'];
            $insertParam['order_firstname'] = $this->user_data['signup_firstname'];
            $insertParam['order_lastname'] = $this->user_data['signup_lastname'];
            $insertParam['order_phone'] = $this->user_data['signup_phone'];
            $insertParam['order_address1'] = $this->user_data['signup_address'];
            $insertParam['order_city'] = $this->user_data['signup_city'];
            $insertParam['order_zip'] = $this->user_data['signup_zip'];

            // $id -> membership_id
            $insertParam['order_reference_id'] = $id;
            // order type = 1 => membership
            $insertParam['order_reference_type'] = ORDER_REFERENCE_MEMBERSHIP;

            //
            $insertParam['order_quantity'] = $interval;
            $insertParam['order_total'] = $membershipCost;
            $insertParam['order_shipping'] = $shippment_price;
            $insertParam['order_amount'] = $insertParam['order_total'] + $shippment_price;
            $insertParam['order_shipment_price'] = price($shippment_price);
            $insertParam['order_merchant'] = $merchant;
            $insertParam['order_currency'] = DEFAULT_CURRENCY_CODE;

            //
            $insertParam['order_status'] = STATUS_INACTIVE;
            $insertParam['order_payment_status'] = STATUS_INACTIVE;
            $insertParam['order_status_message'] = "Pending";
            $insertParam['order_payment_comments'] = "Unpaid";

            switch($merchant) {
                case STRIPE:
                    $insertParam['order_session_checkout_id'] = $merchant_session ? $merchant_session->id : '';
                    // response in raw json format
                    $insertParam['order_response'] = str_replace('Stripe\Checkout\Session JSON:', '', (string) $merchant_session);
                    break;
                case PAYPAL:
                    if($merchant_session && property_exists($merchant_session, 'id')) {
                        // plan is fetched in merchant_session
                        $insertParam['order_session_checkout_id'] = $merchant_session ? $merchant_session->id : '';
                        // response in raw json format
                        $insertParam['order_response'] = serialize($merchant_session);
                    } else {
                        $paypal_error = TRUE;
                    }
                    break;
            }

            if(!$paypal_error) {
                $insertedOrder = $this->model_order->insert_record($insertParam);
                $data['order'] = $this->model_order->find_by_pk($insertedOrder);
            }

            if (!$insertedOrder) {
                error_404();
            } else {
                $insertParam = array();
                $insertParam['order_item_status'] = STATUS_ACTIVE;
                $insertParam['order_item_order_id'] = $insertedOrder;
                // $id -> membership_id
                $insertParam['order_item_product_id'] = $id;
                $insertParam['order_item_user_id'] = $this->userid;
                $insertParam['order_item_price'] = $membershipCost;
                $insertParam['order_item_subtotal'] = $membershipCost;
                $insertParam['order_item_qty'] = $interval;
                $this->model_order_item->insert_record($insertParam);
            }
        } else {

            // for stripe only
            $data['order'] = $this->model_order->find_one_active(
                array(
                    'where' => array(
                        'order_session_checkout_id' => $this->user_data['signup_session_id']
                    ),
                )
            );
            
            if($data['order']) {

                if($data['membership_cancelled']) {
                    $affectParam['order_session_checkout_id'] = '';
                    $affectParam['order_transaction_id'] = '';
                    $affectParam['order_response'] = '';
                    // $affectParam['order_payment_status'] = SUBSCRIPTION_CANCELLED;
                } else {
                    $affectParam['order_payment_status'] = SUBSCRIPTION_ACTIVE;
                }

                if($data['free_membership']) {
                    $affectParam['order_status'] = STATUS_ACTIVE;
                    $affectParam['order_status_message'] = "Completed";
                    $affectParam['order_payment_comments'] = "paid";    
                }
    
                $affectParam['order_reference_id'] = $id;
                $affectParam['order_total'] = $membershipCost;
                $affectParam['order_amount'] = $membershipCost + $shippment_price;

                //
                $this->model_order->update_by_pk(
                    $data['order']['order_id'],
                    $affectParam
                );

                //
                $this->model_order_item->update_model(
                    array(
                        'where' => array(
                            'order_item_order_id' => $data['order']['order_id']
                        )
                    ),
                    array(
                        'order_item_price' => $membershipCost,
                        'order_item_subtotal' => $membershipCost
                    )
                );
            }

            $affectParam = [];

            // remove subscription ids from signup
            if($data['membership_cancelled']) {

                $affectParam = [
                    'signup_type' => $data['membership']['membership_id'],
                    'signup_subscription_id' => '',
                    'signup_subscription_response' => '',
                    'signup_session_id' => '',
                    'signup_session_response' => '',
                    'signup_customer_id' => '',
                    'signup_customer_response' => '',
                ];

                $this->model_signup->update_by_pk(
                    $this->userid, 
                    $affectParam
                );
            }
        }

        //
        $this->load_view('payment', $data);
    }

    /**
     * Method result
     *
     * @param int $membershipId
     * @param string $status
     * @param string $checkoutSessionId
     *
     * @return void
     */
    public function result($membershipId = '', $status = ORDER_FAILED, $checkoutSessionId = '', $merchant = STRIPE)
    {
        $error = FALSE;
        $data = array();
        if ($this->userid == 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l('login'));
        }

        if (!$checkoutSessionId)
            error_404();

        if($membershipId) {
            try {
                $membershipId = JWT::decode($membershipId, CI_ENCRYPTION_SECRET);
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
        } else {
            error_404();            
        }

        $param = array();
        $param['where']['inner_banner_name'] = 'Membership Payment Result';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        //
        $data['membership'] = $this->model_membership->find_by_pk($membershipId);
        if (empty($data['membership'])) {
            $this->session->set_flashdata('error', __('Error in finding requested membership!'));
            redirect(l('membership'));
        }

        if (!in_array($status, [ORDER_SUCCESS, ORDER_FAILED])) {
            $status = 'unknown';
        }

        $data['status'] = $status;

        if ($status == 'success') {
            switch($merchant) {
                case STRIPE:
                    $session = "";
                    $data['customer'] = "";
                    try {
                        $session = $this->stripe->checkout->sessions->retrieve(
                            $checkoutSessionId,
                            []
                        );
            
                        $data['customer'] = $this->model_stripe_log->resource('customers', $session->customer);
                    } catch (\Exception $e) {
                        $error = TRUE;
                        $this->session->set_flashdata('stripe_error', $e->getMessage());
                    }
                    if ($data['customer'] && !$error) {
                        $customer = str_replace('Stripe\Customer JSON:', '', $data['customer']);
                        $subscription = $this->model_stripe_log->resource('subscriptions', $session->subscription);
        
                        $subscriptionArray = array(
                            'signup_type' => $membershipId,
                            'signup_membership_id' => $membershipId,
                            'signup_membership_status' => STATUS_ACTIVE,
                            'signup_subscription_id' => $session->subscription,
                            // response in raw json format
                            'signup_session_id' => $session->id,
                            'signup_session_response' => str_replace('Stripe\Checkout\Session JSON:', '', (string) $session),
                            // response in raw json format
                            'signup_customer_id' => isset($data['customer']->id) ? $data['customer']->id : '',
                            'signup_customer_response' => $customer,
                        );

                        if ($subscription) {
                            $subscriptionArray['signup_subscription_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscription);
                            $subscriptionArray['signup_subscription_status'] = $this->model_membership->subscriptionStatus($subscription->status);
                            $subscriptionArray['signup_subscription_current_period_start'] = date('Y-m-d H:i:s', $subscription->current_period_start);
                            $subscriptionArray['signup_subscription_current_period_end'] = date('Y-m-d H:i:s', $subscription->current_period_end);
                            $subscriptionArray['signup_trial_expiry'] = $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : '';
                        } else {
                            $error = TRUE;
                        }
        
                        if (!$error) {
                            $updatedSignup = $this->model_signup->update_by_pk($this->userid, $subscriptionArray);
        
                            $updatedOrder = $this->model_order->update_model(
                                array('where' => array(
                                    'order_user_id' => $this->userid,
                                    'order_session_checkout_id' => $checkoutSessionId
                                )),
                                array(
                                    'order_payment_status' => $this->model_membership->subscriptionStatus($subscription->status),
                                    'order_status' => STATUS_ACTIVE,
                                    'order_transaction_id' => $subscription->id,
                                    // response in raw json format
                                    // updated response of stripe session
                                    'order_response' => str_replace('Stripe\Checkout\Session', '', str_replace('Stripe\Checkout\Session JSON:', '', ($session))),
                                    'order_status_message' => $this->model_membership->subscriptionStatusString($this->model_membership->subscriptionStatus($subscription->status)),
                                )
                            );
        
                            if (($updatedOrder && $updatedSignup)) {
                                // saving to log for webhook differentiaition
                                if(!$this->saveStripeLog($this->userid, STRIPE_LOG_REFERENCE_SIGNUP, $this->userid, STRIPE_LOG_RESOURCE_TYPE['subscriptions'], $subscription->id, str_replace('Stripe\Subscription JSON:', '', (string) $subscription))) {
                                    log_message('ERROR', 'Unable to generate log');
                                }
                            }
                        } else {
                            error_404();
                        }
                    } else {
                        error_404();
                    }
                    break;
                case PAYPAL:
                    $url = PAYPAL_URL . PAYPAL_SUBSCRIPTION_URL . '/' . $checkoutSessionId;
                    //
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;
                    
                    //
                    $subscription = $this->paypalResource($url, $headers, [], FALSE);
                    if(property_exists($subscription, 'message')) {
                        log_message('ERROR', $subscription->message);
                        $error = TRUE;
                    } else {
                        $subscription_id = $subscription->id;
                    }
                    
                    $subscription_status = '';
                    // if(isset($subscription->billing_info->cycle_executions[1]) && $subscription->billing_info->cycle_executions[1]->cycles_completed == 0) {
                        $subscription_status = $subscription->billing_info->cycle_executions[0]->tenure_type;
                    // }
                    
                    if(!$error) {
                        $subscriptionArray = array(
                            'signup_type' => $membershipId,
                            'signup_membership_id' => $membershipId,
                            'signup_membership_status' => STATUS_ACTIVE,
                            'signup_subscription_id' => $subscription_id,
                        );
        
                        if ($subscription) {
                            $subscriptionArray['signup_subscription_response'] = serialize($subscription);
                            $subscriptionArray['signup_subscription_status'] = $this->model_membership->subscriptionStatus($subscription_status);
                            $subscriptionArray['signup_subscription_current_period_start'] = date('Y-m-d H:i:s', strtotime($subscription->start_time));
                            $subscriptionArray['signup_subscription_current_period_end'] = date('Y-m-d H:i:s', strtotime($subscription->billing_info->next_billing_time));
                        } else {
                            $error = TRUE;
                        }
        
                        if (!$error) {
                            $updatedSignup = $this->model_signup->update_by_pk($this->userid, $subscriptionArray);
        
                            $updatedOrder = $this->model_order->update_model(
                                array('where' => array(
                                    'order_user_id' => $this->userid,
                                    'order_session_checkout_id' => $subscription->plan_id
                                )),
                                array(
                                    'order_payment_status' => $this->model_membership->subscriptionStatus($subscription_status),
                                    'order_status' => STATUS_ACTIVE,
                                    'order_transaction_id' => $subscription->id,
                                    // response in raw json format
                                    // updated response of from plan to session response
                                    'order_response' => serialize($subscription),
                                    'order_status_message' => $this->model_membership->subscriptionStatusString($this->model_membership->subscriptionStatus($subscription_status)),
                                )
                            );
                            
                            if (($updatedOrder && $updatedSignup)) {
                                // saving to log for webhook differentiaition
                                // if(!$this->savePaypalLog($this->userid, STRIPE_LOG_REFERENCE_SIGNUP, $this->userid, STRIPE_LOG_RESOURCE_TYPE['subscriptions'], $subscription->id, serialize($subscription))) {
                                //     log_message('ERROR', 'Unable to generate log');
                                // }
                            }
                        } else {
                            error_404();
                        }
                    } else {
                        error_404();
                    }
                    break;
                case FREE:
                    if($checkoutSessionId) {
                        try {
                            $checkoutSessionId = JWT::decode($checkoutSessionId, CI_ENCRYPTION_SECRET);
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
                    } else {
                        error_404();            
                    }
                    $subscriptionArray = array(
                        'signup_type' => $membershipId,
                        'signup_membership_id' => $membershipId,
                        'signup_membership_status' => STATUS_ACTIVE,
                    );
                    $updatedSignup = $this->model_signup->update_by_pk($this->userid, $subscriptionArray);
        
                    $updatedOrder = $this->model_order->update_model(
                        array('where' => array(
                            'order_user_id' => $this->userid,
                            'order_id' => $checkoutSessionId
                        )),
                        array(
                            'order_payment_status' => SUBSCRIPTION_ACTIVE,
                            'order_status' => STATUS_ACTIVE,
                        )
                    );
                    break;
                default:
                    error_404();
            }
        } else {
            $error = TRUE;
        }
        
        if(!$error) {
            try {
                // notification
                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_MEMBERSHIP_SUCCESS, 0, NOTIFICATION_MEMBERSHIP_SUCCESS_COMMENT);
                //
                if($checkoutSessionId) {
                    $this->send_invoice($checkoutSessionId, $merchant);
                }
            } catch (\Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
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
            $data['status'] = $status = 'unknown';
        }

        //
        $this->layout_data['title'] = ucfirst($status) . ' | ' . $this->layout_data['title'];
        //
        $this->load_view('result', $data);
    }

    /**
     * Method send_invoice
     *
     * @param string $checkoutSessionId
     *
     * @return void
     */
    public function send_invoice($checkoutSessionId = '', $merchant = STRIPE)
    {
        if($checkoutSessionId) {

            $where_array = [];
            $where_array['order_user_id'] = $this->userid;
            if($merchant == FREE) {
                $where_array['order_id'] = $checkoutSessionId;
            } else {
                $where_array['order_session_checkout_id'] = $checkoutSessionId;
            }

            $order_details = $this->model_order->find_one_active(
                array(
                    'where' => $where_array,
                    'joins' => array(
                        0 => array(
                            'table' => 'membership',
                            'joint' => 'membership.membership_id = order.order_reference_id',
                            'type' => 'both',
                        )
                    )
                )
            );

            if (ENVIRONMENT != 'development' && $order_details) {
                try {
                    $this->model_email->notification_order_invoice($order_details['order_id'], 'USER');
                    $this->model_email->notification_order_invoice($order_details['order_id'], 'Admin');
                } catch (\Exception $e) {
                    $this->session->set_flashdata('error', $e->getMessage());
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
        }
    }

    /**
     * Method invoiceEmail
     *
     * @param int $order_id
     *
     * @return void
     */
    function invoiceEmail(int $order_id): void
    {
        $this->model_email->notification_order_invoice($order_id, 'USER');
    }

    /**
     * Method setupStripeCustomerPayment
     *
     * @param int $membershipId
     *
     * @return object
     */
    function setupStripeCustomerPayment(array $membership, float $membershipCost, int $interval): ?object
    {
        $trial_days = g('db.admin.trial_days') ?? STRIPE_TRIAL_PERIOD_DAYS;

        if(!empty($membership)) {
            try {
                $customer = $this->model_stripe_log->createStripeResource('customers', [
                    'email' => $this->user_data['signup_email']
                ]);
                switch($membership['membership_id']) {
                    case ROLE_3:
                        $product_title = MEMBERSHIP_PRODUCT_ENTREPRENEUR_TITLE; 
                        break;
                    case ROLE_4:
                        $product_title = MEMBERSHIP_PRODUCT_INNOVATOR_TITLE; 
                        break;
                    case ROLE_5:
                        $product_title = MEMBERSHIP_PRODUCT_LEADER_TITLE; 
                        break;
                }

                $product = $this->model_stripe_log->createStripeResource('products', [
                    'name' => $product_title
                ]);
                $price = $this->model_stripe_log->createStripeResource('prices', [
                    'unit_amount' => $membershipCost * 100,
                    'currency' => DEFAULT_CURRENCY_CODE,
                    'product' => $product->id,
                    'recurring' => array(
                        'interval' => SUBSCRIPTION_INTERVAL_TYPE,
                        'interval_count' => $interval,
                    ),
                ]);

                $checkoutSessionPayload = [
                    'payment_method_types' => ['card'],
                    'customer' => $customer->id,
                    'success_url' => base_url() . 'membership/result/' . JWT::encode($membership['membership_id']) . '/' . ORDER_SUCCESS . '/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => base_url() . 'membership/result/' . JWT::encode($membership['membership_id']) . '/' . ORDER_FAILED . '/{CHECKOUT_SESSION_ID}',
                    'mode' => 'subscription',
                    'line_items' => [
                        [
                            'price' => $price->id,
                            'quantity' => 1,
                        ],
                    ],
                    'payment_method_collection' => 'always',
                ];
                if (g('db.admin.enable_subscription_trial')) {
                    $checkoutSessionPayload['subscription_data'] = [
                        'trial_settings' => ['end_behavior' => ['missing_payment_method' => 'cancel']],
                        'trial_period_days' => $trial_days,
                    ];
                }
                $session = $this->stripe->checkout->sessions->create($checkoutSessionPayload);
                return $session;
            } catch (\Exception $e) {
                $this->session->set_flashdata('stripe_error', $e->getMessage());
                log_message('ERROR', $e->getMessage());
            }
        }
        return NULL;
    }
    
    /**
     * Method setupPaypalCustomerPayment
     *
     * @param int $membershipId
     *
     * @return object
     */
    function setupPaypalCustomerPayment(array $membership, float $membershipCost, int $interval): ?object
    {
        $trial_days = g('db.admin.trial_days') ?? STRIPE_TRIAL_PERIOD_DAYS;
        $product_id = '';
        $plan_id = '';
        $error = FALSE;
        $session = NULL;

        try {
            $url = PAYPAL_URL . PAYPAL_PRODUCT_URL;
            //
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;
            
            //
            $body = array(
                "name"=> $membership['membership_title'],
                "description"=> g('db.admin.header_message'),
                "type"=> "SERVICE",
                "category"=> "SOFTWARE",
            );
            
            //
            $response = $this->paypalResource($url, $headers, $body, TRUE);
            if(property_exists($response, 'message')) {
                log_message('ERROR', $response->message);
                $error = TRUE;
            } else {
                $product_id = $response->id;
            }
            
            if(!$error) {
                $url = PAYPAL_URL . PAYPAL_PLAN_URL;
                //
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;
                
                //
                if (g('db.admin.enable_subscription_trial')) {
                    $billing_cycles = [
                        0 => array(
                            "frequency" => array(
                                "interval_unit" => strtoupper(SUBSCRIPTION_INTERVAL_TYPE),
                                "interval_count" => 1
                            ),
                            "tenure_type" => "TRIAL",
                            "sequence" => 1,
                            "total_cycles" => 1
                        ),
                        1 => array(
                            "frequency" => array(
                                "interval_unit" => strtoupper(SUBSCRIPTION_INTERVAL_TYPE),
                                "interval_count" => $interval
                            ),
                            "tenure_type" => "REGULAR",
                            "sequence" => 2,
                            "total_cycles" => 999,
                            "pricing_scheme"=> array(
                                "fixed_price" => array(
                                    "value" => number_format($membershipCost, 2),
                                    "currency_code" => DEFAULT_CURRENCY_CODE
                                )
                            )
                        )
                    ];
                } else {
                    $billing_cycles = [
                        0 => array(
                            "frequency" => array(
                                "interval_unit" => strtoupper(SUBSCRIPTION_INTERVAL_TYPE),
                                "interval_count" => $interval
                            ),
                            "tenure_type" => "REGULAR",
                            "sequence" => 1,
                            "total_cycles" => 999,
                            "pricing_scheme"=> array(
                                "fixed_price" => array(
                                    "value" => number_format($membershipCost, 2),
                                    "currency_code" => DEFAULT_CURRENCY_CODE
                                )
                            )
                        )
                    ];
                }

                //
                $body = array(
                    "product_id"=> $product_id,
                    "name"=> $membership['membership_title'],
                    "description"=> g('db.admin.header_message'),
                    "billing_cycles"=> $billing_cycles,
                    "payment_preferences" => array(
                        "auto_bill_outstanding" => true
                    )
                );

                //
                $response = $this->paypalResource($url, $headers, $body, TRUE);
                
                if(property_exists($response, 'message')) {
                    log_message('ERROR', $response->message);
                    $error = TRUE;
                } else {
                    $plan_id = $response->id;
                }
            }
            return $response;
        } catch (\Exception $e) {
            $this->session->set_flashdata('stripe_error', $e->getMessage());
            log_message('ERROR', $e->getMessage());
        }
        return NULL;
    }

    /**
     * fireTrialExpiryMail function - cron function
     *
     * @return void
     */
    function fireTrialExpiryMail() : void
    {
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        $param['where']['signup_trial_expiry !='] = '';

        $signups = $this->model_signup->find_all_active(
            $param
        );

        foreach($signups as $signup) {
            if(strtotime($signup['signup_trial_expiry']) > strtotime(date('Y-m-d H:i:s'))) {

                $period_end = $signup['signup_trial_expiry'];
                $date1 = new DateTime("now");
                $date2 = new DateTime(date('Y-m-d H:i:s', strtotime($period_end)));
                $trial_days = $date1->diff($date2)->days;

                // check for trial days and check if expiry email has alreay been sent!
                if($trial_days == TRIAL_EMAIL_DAYS && count($this->model_signup_trial_expiry_log->getBySignupId($signup['signup_id'])) <= 3) {
                    // fire email
                    $to = $signup['signup_email'];
                    $subject = 'Trial expiry alert!';
                    $message = 'Dear ' . ucfirst($signup['signup_firstname'] . ' ' . $signup['signup_lastname']) . ",<br />";
                    $message .= 'Your trial is going to expire on :' . date('d M, Y h:i a', strtotime($signup['signup_trial_expiry']));
                    $this->model_email->fireEmail($to, '', $subject, $message, '', []);
                }
            }
        }
    }
}
