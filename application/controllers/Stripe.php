<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Stripe
 */
class Stripe extends MY_Controller
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
     * Method webhook
     *
     * @return void
     */
    function webhook(): void
    {
        // The library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = STRIPE_ENDPOINT_SECRET;

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        log_message('ERROR', 'Stripe webhook: payload: ' . serialize($payload));

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            log_message('ERROR', 'Stripe webhook: error: ' . serialize($e->getMessage()));
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            log_message('ERROR', 'Stripe webhook: error: ' . serialize($e->getMessage()));
            // Invalid signature
            http_response_code(400);
            exit();
        }

        $this->eventSwitch($event);

        log_message('ERROR', 'Stripe webhook: ' . 'Event details: ' . serialize($event));

        http_response_code(200);
    }

    /**
     * Method webhookManual
     *
     * @return void
     */
    function webhookManual(): void
    {
        $event = NULL;

        $this->eventSwitch($event);

        log_message('ERROR', 'Stripe webhook: ' . 'Event details: ' . serialize($event));

        http_response_code(200);
    }

    /**
     * Method eventSwitch
     *
     * @param object $event
     *
     * @return void
     */
    function eventSwitch($event): void
    {
        $eventType = $event->type;

        // Handle the event
        switch ($eventType) {
                // invoice -> status = draft or paid, billing_reason = subscription_cycyle
            case 'invoice.created':
                $object = $event->data->object;
                break;
                // invoice has been paid
            case 'invoice.paid':
                $object = $event->data->object;
                if (property_exists($object, 'billing_reason')) {
                    if ((in_array($object->billing_reason, ['subscription_cycle', 'subscription_update'])) && $object->status == 'paid' && $object->paid == 'true') {
                        $stripe_log = $this->getStripeLog(
                            [
                                'stripe_log_resource_id' => $object->subscription,
                            ]
                        );
                        if ($stripe_log) {
                            switch ($stripe_log['stripe_log_reference']) {
                                case STRIPE_LOG_REFERENCE_JOB:
                                    $reference = $this->model_job->find_one_active(
                                        array(
                                            'where' => array(
                                                'job_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_job->update_by_pk(
                                            $reference['job_id'],
                                            array(
                                                'job_subscription_status' => SUBSCRIPTION_ACTIVE,
                                                'job_status' => STATUS_ACTIVE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'Job with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_SIGNUP:
                                    $reference = $this->model_signup->find_one_active(
                                        array(
                                            'where' => array(
                                                'signup_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_signup->update_by_pk(
                                            $reference['signup_id'],
                                            array(
                                                // 'signup_membership_id' => ROLE_3,
                                                // 'signup_type' => ROLE_3,
                                                'signup_membership_status' => SUBSCRIPTION_ACTIVE,
                                                'signup_subscription_status' => SUBSCRIPTION_ACTIVE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'User with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_TECHNOLOGY:
                                    $reference = $this->model_product->find_one_active(
                                        array(
                                            'where' => array(
                                                'product_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_product->update_by_pk(
                                            $reference['product_id'],
                                            array(
                                                'product_subscription_status' => SUBSCRIPTION_ACTIVE,
                                                'product_status' => STATUS_ACTIVE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'Product with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;   
                            }
                        } else {
                            log_message('ERROR', '`' . $object->subscription . '` not found.');
                        }
                    } else {
                        log_message('ERROR', 'Property `billing_reason` type `subscription_cycle`, `subscription_update` not found.');
                    }
                } else {
                    log_message('ERROR', 'Property `billing_reason` not found.');
                }
                break;
                // invoice payment has been succeeded
            case 'invoice.payment_succeeded':
                $object = $event->data->object;
                if (property_exists($object, 'billing_reason')) {
                    if ($object->billing_reason === 'subscription_cycle' && $object->status == 'paid' && $object->paid == 'true') {
                        $stripe_log = $this->getStripeLog(
                            [
                                'stripe_log_resource_id' => $object->subscription
                            ]
                        );
                        if ($stripe_log) {
                            switch ($stripe_log['stripe_log_reference']) {
                                case STRIPE_LOG_REFERENCE_JOB:
                                    $reference = $this->model_job->find_one_active(
                                        array(
                                            'where' => array(
                                                'job_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_job->update_by_pk(
                                            $reference['job_id'],
                                            array(
                                                'job_subscription_status' => SUBSCRIPTION_ACTIVE,
                                                'job_status' => STATUS_ACTIVE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'Job with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_SIGNUP:
                                    $reference = $this->model_signup->find_one_active(
                                        array(
                                            'where' => array(
                                                'signup_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_signup->update_by_pk(
                                            $reference['signup_id'],
                                            array(
                                                // 'signup_membership_id' => ROLE_3,
                                                // 'signup_type' => ROLE_3,
                                                'signup_membership_status' => SUBSCRIPTION_ACTIVE,
                                                'signup_subscription_status' => SUBSCRIPTION_ACTIVE,
                                            )
                                        );
                                        $this->model_order->update_model(
                                            array(
                                                'where' => array(
                                                    'order_stripe_transaction_id' => $reference['signup_subscription_id']
                                                )
                                            ),
                                            array(
                                                'order_payment_status' => PAYMENT_STATUS_COMPLETED
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'User with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_TECHNOLOGY:
                                    $reference = $this->model_product->find_one_active(
                                        array(
                                            'where' => array(
                                                'product_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_product->update_by_pk(
                                            $reference['product_id'],
                                            array(
                                                'product_subscription_status' => SUBSCRIPTION_ACTIVE,
                                                'product_status' => STATUS_ACTIVE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'Product with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;   
                            }
                        } else {
                            log_message('ERROR', '`' . $object->subscription . '` not found.');
                        }
                    } else {
                        log_message('ERROR', 'Property `billing_reason` type `subscription_cycle` not found or status is unpaid.');
                    }
                } else {
                    log_message('ERROR', 'Property `billing_reason` not found.');
                }
                break;
                // invoice payment has been failed
            case 'invoice.payment_failed':
                $object = $event->data->object;
                //
                if (property_exists($object, 'subscription') && $object->subscription) {
                    $subscription_response = str_replace('Stripe\Subscription JSON:', '', (string) $this->resource('subscriptions', $object->subscription, FALSE));

                    if ($subscription_response) {
                        $stripe_log = $this->getStripeLog(
                            [
                                'stripe_log_resource_id' => $object->subscription
                            ]
                        );

                        $decoded_subscription = json_decode($subscription_response);
                        $date1 = new DateTime("now");
                        $date2 = new DateTime(date('Y-m-d H:i:s', $decoded_subscription->start));
                        $interval = $date1->diff($date2);
                        $trial_days = g('db.admin.trial_days') ?? STRIPE_TRIAL_PERIOD_DAYS;

                        if ($stripe_log) {
                            switch ($stripe_log['stripe_log_reference']) {
                                case STRIPE_LOG_REFERENCE_JOB:
                                    $reference = $this->model_job->find_one_active(
                                        array(
                                            'where' => array(
                                                'job_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        if ($interval->days == $trial_days) {
                                            $this->model_job->update_by_pk(
                                                $reference['job_id'],
                                                array(
                                                    'job_subscription_status' => SUBSCRIPTION_CANCELLED,
                                                    'job_status' => STATUS_DELETE,

                                                )
                                            );
                                        }
                                    } else {
                                        log_message('ERROR', 'Job with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_SIGNUP:
                                    $reference = $this->model_signup->find_one_active(
                                        array(
                                            'where' => array(
                                                'signup_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );

                                    if ($reference) {
                                        if ($interval->days == $trial_days) {
                                            $this->model_signup->update_by_pk(
                                                $reference['signup_id'],
                                                array(
                                                    'signup_membership_id' => ROLE_1,
                                                    'signup_type' => ROLE_1,
                                                    'signup_membership_status' => SUBSCRIPTION_CANCELLED,
                                                    'signup_subscription_status' => SUBSCRIPTION_CANCELLED,
                                                )
                                            );
                                        }
                                    } else {
                                        log_message('ERROR', 'User with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_TECHNOLOGY:
                                    $reference = $this->model_product->find_one_active(
                                        array(
                                            'where' => array(
                                                'product_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        if ($interval->days == $trial_days) {
                                            $this->model_product->update_by_pk(
                                                $reference['product_id'],
                                                array(
                                                    'product_subscription_status' => SUBSCRIPTION_CANCELLED,
                                                    'product_status' => STATUS_DELETE,

                                                )
                                            );
                                        }
                                    } else {
                                        log_message('ERROR', 'Product with subscription: `' . $object->subscription . '` not found.');
                                    }
                                    break;
                            }
                            $this->model_order->update_model(
                                array(
                                    'where' => array(
                                        'order_stripe_transaction_id' => $stripe_log['stripe_log_resource_id']
                                    )
                                ),
                                array(
                                    'order_payment_status' => PAYMENT_STATUS_CANCELLED
                                )
                            );
                        } else {
                            log_message('ERROR', '`' . $object->subscription . '` not found.');
                        }
                    } else {
                        log_message('ERROR', 'Subscription `' . $object->subscription . '` not found.');
                    }
                } else {
                    log_message('ERROR', 'Subscription property not found.');
                }
                break;
                // new subscription cycle schedule is created
            case 'subscription_schedule.updated':
                $object = $event->data->object;
                break;
                // subscription first created, invoice isn't paid yet
            case 'customer.subscription.created':
                $object = $event->data->object;
                break;
                // subscription updated, invoice paid/
            case 'customer.subscription.updated':
                $object = $event->data->object;
                $stripe_log = $this->getStripeLog(
                    [
                        'stripe_log_resource_id' => $object->id
                    ]
                );

                if ($stripe_log) {
                    $subscription_response = str_replace('Stripe\Subscription JSON:', '', (string) $this->resource('subscriptions', $object->id, FALSE));

                    switch ($stripe_log['stripe_log_reference']) {
                        case STRIPE_LOG_REFERENCE_JOB:
                            $reference = $this->model_job->find_one_active(
                                array(
                                    'where' => array(
                                        'job_subscription_id' => $stripe_log['stripe_log_resource_id']
                                    ),
                                )
                            );
                            if ($reference) {
                                $this->model_job->update_by_pk(
                                    $reference['job_id'],
                                    array(
                                        'job_subscription_response' => $subscription_response,
                                        'job_subscription_current_period_start' => date('Y-m-d H:i:s', $object->current_period_start),
                                        'job_subscription_current_period_end' => date('Y-m-d H:i:s', $object->current_period_end),
                                    )
                                );
                            } else {
                                log_message('ERROR', 'Job with subscription: `' . $object->id . '` not found.');
                            }
                            break;
                        case STRIPE_LOG_REFERENCE_SIGNUP:
                            $reference = $this->model_signup->find_one_active(
                                array(
                                    'where' => array(
                                        'signup_subscription_id' => $stripe_log['stripe_log_resource_id']
                                    ),
                                )
                            );
                            if ($reference) {
                                $this->model_signup->update_by_pk(
                                    $reference['signup_id'],
                                    array(
                                        'signup_subscription_response' => $subscription_response,
                                        'signup_subscription_current_period_start' => date('Y-m-d H:i:s', $object->current_period_start),
                                        'signup_subscription_current_period_end' => date('Y-m-d H:i:s', $object->current_period_end),
                                        'signup_trial_expiry' => ($object->trial_end ? date('Y-m-d H:i:s', $object->trial_end) : $reference['signup_trial_expiry']),
                                        'signup_subscription_status' => $this->model_membership->subscriptionStatus($object->status)
                                    )
                                );
                            } else {
                                log_message('ERROR', 'User with subscription: `' . $object->id . '` not found.');
                            }
                            break;
                        case STRIPE_LOG_REFERENCE_TECHNOLOGY:
                            $reference = $this->model_product->find_one_active(
                                array(
                                    'where' => array(
                                        'product_subscription_id' => $stripe_log['stripe_log_resource_id']
                                    ),
                                )
                            );
                            if ($reference) {
                                $this->model_product->update_by_pk(
                                    $reference['product_id'],
                                    array(
                                        'product_subscription_response' => $subscription_response,
                                        'product_subscription_current_period_start' => date('Y-m-d H:i:s', $object->current_period_start),
                                        'product_subscription_current_period_end' => date('Y-m-d H:i:s', $object->current_period_end),
                                    )
                                );
                            } else {
                                log_message('ERROR', 'Product with subscription: `' . $object->id . '` not found.');
                            }
                            break;
                    }
                } else {
                    log_message('ERROR', '`' . $object->id . '` not found.');
                }
                break;
                // cancelled (from setting) = deleted, status = cancelled
            case 'customer.subscription.deleted':
                $object = $event->data->object;
                if (property_exists($object, 'ended_at')) {
                    $stripe_log = $this->getStripeLog(
                        [
                            'stripe_log_resource_id' => $object->id
                        ]
                    );

                    if ($stripe_log) {
                        if ($object->ended_at < strtotime(date('Y-m-d H:i:s')) && $object->status == 'canceled') {
                            switch ($stripe_log['stripe_log_reference']) {
                                case STRIPE_LOG_REFERENCE_JOB:
                                    $reference = $this->model_job->find_one_active(
                                        array(
                                            'where' => array(
                                                'job_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_job->update_by_pk(
                                            $reference['job_id'],
                                            array(
                                                'job_subscription_status' => SUBSCRIPTION_CANCELLED,
                                                'job_subscription_current_period_start' => date('Y-m-d H:i:s', $object->current_period_start),
                                                'job_subscription_current_period_end' => date('Y-m-d H:i:s', $object->current_period_end),
                                                'job_status' => STATUS_DELETE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'Job with subscription: `' . $object->id . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_SIGNUP:
                                    $reference = $this->model_signup->find_one_active(
                                        array(
                                            'where' => array(
                                                'job_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_signup->update_by_pk(
                                            $reference['signup_id'],
                                            array(
                                                'signup_membership_id' => ROLE_1,
                                                'signup_type' => ROLE_1,
                                                'signup_membership_status' => SUBSCRIPTION_CANCELLED,
                                                'signup_subscription_status' => SUBSCRIPTION_CANCELLED,
                                                'signup_subscription_current_period_start' => date('Y-m-d H:i:s', $object->current_period_start),
                                                'signup_subscription_current_period_end' => date('Y-m-d H:i:s', $object->current_period_end),
                                            )
                                        );
                                        $this->model_order->update_model(
                                            array(
                                                'where' => array(
                                                    'order_stripe_transaction_id' => $stripe_log['stripe_log_resource_id']
                                                )
                                            ),
                                            array(
                                                'order_payment_status' => PAYMENT_STATUS_CANCELLED
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'User with subscription: `' . $object->id . '` not found.');
                                    }
                                    break;
                                case STRIPE_LOG_REFERENCE_TECHNOLOGY:
                                    $reference = $this->model_product->find_one_active(
                                        array(
                                            'where' => array(
                                                'product_subscription_id' => $stripe_log['stripe_log_resource_id']
                                            ),
                                        )
                                    );
                                    if ($reference) {
                                        $this->model_product->update_by_pk(
                                            $reference['product_id'],
                                            array(
                                                'product_subscription_status' => SUBSCRIPTION_CANCELLED,
                                                'product_subscription_current_period_start' => date('Y-m-d H:i:s', $object->current_period_start),
                                                'product_subscription_current_period_end' => date('Y-m-d H:i:s', $object->current_period_end),
                                                'product_status' => STATUS_DELETE,
                                            )
                                        );
                                    } else {
                                        log_message('ERROR', 'Product with subscription: `' . $object->id . '` not found.');
                                    }
                                    break;
                            }
                        }
                    } else {
                        log_message('ERROR', '`' . $object->id . '` not found.');
                    }
                }
                break;
                // not used
            case 'customer.subscription.paused':
                $object = $event->data->object;
                break;
                // not used
            case 'customer.subscription.pending_update_applied':
                $object = $event->data->object;
                break;
                // not used
            case 'customer.subscription.pending_update_expired':
                $object = $event->data->object;
                break;
                // not used
            case 'customer.subscription.resumed':
                $object = $event->data->object;
                break;
            default:
                log_message('ERROR', 'Stripe webhook: ' .  'Received unknown event type: ' . $event->type);
        }
    }

    /**
     * Method resource
     * usage example:
     * http://localhost/azaverze/stripe/resource/customers/cus_OGHPxGAXkojNkx
     * http://localhost/azaverze/stripe/resource/invoices/in_1NTkr8ASwfulAoL3rW5CfmDJ
     *
     * @param string $resourceType
     * @param string $resourceId
     * @param bool $debug
     *
     * @return object
     */
    function resource(string $resourceType, string $resourceId, bool $debug = TRUE): ?object
    {
        $resourceDetail = NULL;
        try {
            $resourceDetail = $this->stripe->{$resourceType}->retrieve(
                $resourceId,
                []
            );
        } catch (\Exception $e) {
            log_message('ERROR', $e->getMessage());
        }

        if ($debug) {
            echo '<pre>';
            print_r($resourceDetail);
            echo '</pre>';
        }
        return $resourceDetail;
    }

    // function endTrial($subscriptionId = 'sub_1O0lg1ASwfulAoL3KyXEI1lX') : void {
    //     try {
    //         $this->stripe->subscriptions->update($subscriptionId, ['trial_end' => 'now']);
    //     } catch(\Exception $e) {
    //         log_message('ERROR', $e->getMessage());
    //     }
    // }
}
