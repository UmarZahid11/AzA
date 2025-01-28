<?php

declare(strict_types=1);

use Stripe\Subscription;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Method
 */
class Method extends MY_Controller
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
    function index() : void {
        $data = array();
        $data['payment_method'] = NULL;

        if($this->user_data['signup_subscription_id']) {
            $subscription = $this->resource('subscriptions', $this->user_data['signup_subscription_id']);
            if($subscription) {
                $data['payment_method'] = $this->resource('paymentMethods', $subscription->default_payment_method);
            }
    
            if(!$data['payment_method']) {
                // error_404();
            }
        }

        //
        $this->layout_data['title'] = 'Saved Payment Methods | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * update function
     *
     * @return void
     */
    function update() : void {
        $data = array();

        $data['error'] = false;
        $data['errorMessage'] = __(ERROR_MESSAGE);

        $data['merchant_session'] = $this->createSession();

        //
        $this->layout_data['title'] = 'Methods | ' . $this->layout_data['title'];
        //
        $this->load_view("update", $data);
    }

    function createSession($create_new = FALSE) : ?object {
        $session = NULL;
        if($this->user_data['signup_customer_id'] && !$create_new) {
            $checkoutSessionPayload = [
                'payment_method_types' => ['card'],
                'mode' => 'setup',
                'customer' => $this->user_data['signup_customer_id'],
                'success_url' => base_url() . 'dashboard/payment/method/result/' . ORDER_SUCCESS . '/{CHECKOUT_SESSION_ID}',
                // 'cancel_url' => base_url() . 'dashboard/payment/method/result/' . ORDER_FAILED . '/{CHECKOUT_SESSION_ID}',
                'cancel_url' => base_url() . 'dashboard/payment/method',
            ];
            try {
                $session = $this->stripe->checkout->sessions->create($checkoutSessionPayload);
            } catch(\Exception $e) {
                log_message('ERROR', $e->getMessage());
                $session = $this->createSession(TRUE);
            }
        } else {
            $customer = $this->model_stripe_log->createStripeResource('customers', [
                'email' => $this->user_data['signup_email']
            ]);
            if($customer && $customer->id) {
                $this->model_signup->update_by_pk(
                    $this->userid,
                    array(
                        'signup_customer_id' => $customer->id
                    )
                );
                $checkoutSessionPayload = [
                    'payment_method_types' => ['card'],
                    'mode' => 'setup',
                    'customer' => $customer->id,
                    'success_url' => base_url() . 'dashboard/payment/method/result/' . ORDER_SUCCESS . '/{CHECKOUT_SESSION_ID}',
                    // 'cancel_url' => base_url() . 'dashboard/payment/method/result/' . ORDER_FAILED . '/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => base_url() . 'dashboard/payment/method',
                ];
                try {
                    $session = $this->stripe->checkout->sessions->create($checkoutSessionPayload);
                } catch(\Exception $e) {
                    log_message('ERROR', $e->getMessage());
                }
            }
        }
        return (($session));
    }

    /**
     * result
     *
     * @param [type] $status
     * @param string $checkoutSessionId
     * @return void
     */
    function result($status = ORDER_FAILED, $checkoutSessionId = '') : void {

        $data = array();
        $subscriptionArray = [];

        if ($this->userid == 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l('login'));
        }

        if (!$checkoutSessionId)
            error_404();
        
        if (!in_array($status, [ORDER_SUCCESS, ORDER_FAILED])) {
            $status = 'unknown';
        }

        $data['status'] = $status;
        try {
            $session = $this->stripe->checkout->sessions->retrieve(
                $checkoutSessionId,
                []
            );

            // get setup intent from new session
            $setup_intent = $this->resource('setupIntents', $session->setup_intent);

            if($setup_intent->payment_method) {
                // get payment method from setup
                $payment_method = $this->resource('paymentMethods', $setup_intent->payment_method);

                if($payment_method->id) {
                    // fetch current subscription details
                    $subscription = $this->resource('subscriptions', $this->user_data['signup_subscription_id']);

                    // update payment method of the subscription
                    $this->stripe->subscriptions->update($subscription->id, [
                        'default_payment_method' => $payment_method->id
                    ]);

                    // fetch updated subscription
                    $subscription = $this->resource('subscriptions', $subscription->id);

                    // update signup subscription
                    if ($subscription) {
                        $subscriptionArray['signup_subscription_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscription);
                    }
                    $updated = $this->model_signup->update_by_pk($this->userid, $subscriptionArray);

                    if($updated) {
                        $data['message'] = 'The payment method of your subscription has been updated successfully.';
                    }
                }
            }
        } catch (\Exception $e) {
            $this->session->set_flashdata('stripe_error', $e->getMessage());
        }

        //
        $this->layout_data['title'] = ucfirst($status) . ' | ' . $this->layout_data['title'];
        //
        $this->load_view('result', $data);
    }
}