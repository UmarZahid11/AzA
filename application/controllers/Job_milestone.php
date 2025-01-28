<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Job_milestone
 */
class Job_milestone extends MY_Controller
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
     * Method save_milestone
     *
     * @return void
     */
    public function save_milestone(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['job_milestone']['job_milestone_job_id']) && isset($_POST['job_milestone']['job_milestone_application_id'])) {

                    $job_application = $this->model_job_application->find_by_pk($_POST['job_milestone']['job_milestone_application_id']);
                    $job = $this->model_job->find_by_pk($_POST['job_milestone']['job_milestone_job_id']);

                    $affected_milestone = 0;

                    if (!empty($job) && !empty($job_application)) {

                        $milestone_data = isset($_POST['job_milestone']) ? $_POST['job_milestone'] : array();
                        if (isset($_POST['job_milestone_id'])) {

                            $job_milestone = $this->model_job_milestone->find_one_active(
                                array(
                                    'where' => array(
                                        'job_milestone_id' => $_POST['job_milestone_id']
                                    ),
                                    'joins' => array(
                                        0 => array(
                                            'table' => 'job',
                                            'joint' => 'job.job_id = job_milestone.job_milestone_job_id',
                                            'type'  => 'both'
                                        ),
                                        1 => array(
                                            'table' => 'job_application',
                                            'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                                            'type'  => 'both'
                                        )
                                    )
                                )
                            );

                            if (!empty($job_milestone)) {
                                $milestone_data['job_milestone_last_update_by'] = $this->userid;
                                $milestone_data['job_milestone_updatedon'] = date('Y-m-d H:i:s');
                                if ($job_milestone['job_milestone_lock_status'] == 0) {
                                    $affected_milestone = $this->model_job_milestone->update_by_pk($_POST['job_milestone_id'], $milestone_data);

                                    // email here
                                    if ($this->userid == $job_application['job_application_signup_id']) {
                                        switch ($milestone_data['job_milestone_request_status']) {
                                            case 1:
                                                $this->model_notification->sendNotification($job['job_userid'], $job_application['job_application_signup_id'], NOTIFICATION_MILESTONE_APPROVED, $job_application['job_application_id'], NOTIFICATION_MILESTONE_APPROVED_COMMENT, '', $job['job_id']);
                                                break;
                                            case 2:
                                                $this->model_notification->sendNotification($job['job_userid'], $job_application['job_application_signup_id'], NOTIFICATION_MILESTONE_DECLINED, $job_application['job_application_id'], NOTIFICATION_MILESTONE_DECLINED_COMMENT, '', $job['job_id']);
                                                break;
                                        }
                                    }
                                    if ($this->userid == $job['job_userid']) {
                                        //
                                        $this->model_notification->sendNotification($job_application['job_application_signup_id'], $job['job_userid'], NOTIFICATION_MILESTONE_UPDATE, $job_application['job_application_id'], NOTIFICATION_MILESTONE_UPDATE_COMMENT, '', $job['job_id']);
                                    }
                                }
                            }
                        } else {
                            $affected_milestone = $this->model_job_milestone->insert_record($milestone_data);
                            $milestone_data['job'] = $this->model_job->find_by_pk($milestone_data['job_milestone_job_id']);
                            $milestone_data['job_application'] = $this->model_job_application->find_by_pk($milestone_data['job_milestone_application_id']);
                            if (isset($milestone_data['job_application']['job_application_signup_id']) && isset($milestone_data['job']['job_userid']) && isset($milestone_data['job_milestone_application_id']) && isset($milestone_data['job_milestone_job_id'])) {
                                $this->model_notification->sendNotification($milestone_data['job_application']['job_application_signup_id'], $milestone_data['job']['job_userid'], NOTIFICATION_MILESTONE, $milestone_data['job_milestone_application_id'], NOTIFICATION_MILESTONE_COMMENT, '', $milestone_data['job_milestone_job_id']);

                                $job_applicatant = $this->model_signup->find_by_pk($job_application['job_application_signup_id']);
                                $job_organizer = $this->model_signup->find_one_active(
                                    array(
                                        'where' => array(
                                            'signup_id' => $job['job_userid'],
                                            'signup_isdeleted' => STATUS_INACTIVE
                                        ),
                                        'joins' => array(
                                            0 => array(
                                                'table' => 'signup_company',
                                                'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                                                'type'  => 'left'
                                            )
                                        )
                                    )
                                );

                                if (ENVIRONMENT != 'development' && !empty($job_organizer) && !empty($job_applicatant)) {
                                    //
                                    $url = l('dashboard/application/detail/' . JWT::encode($job_application['job_application_id']) . '/' . $job_application['job_application_job_id']);
                                    $this->model_email->notification_job_milestone(
                                        $job_applicatant['signup_email'],
                                        $job['job_title'],
                                        $this->model_signup->profileName($job_organizer, FALSE),
                                        $url
                                    );
                                }
                            }
                        }
                    } else {
                        $errorMessage = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                    }

                    if ($affected_milestone) {
                        // notify here
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    } else {
                        $json_param['txt'] = isset($errorMessage) && $errorMessage ? $errorMessage : __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method save_milestone_attachment
     *
     * @return void
     */
    public function save_milestone_attachment()
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['job_milestone_id'])) {

                    $job_milestone = $this->model_job_milestone->find_one_active(
                        array(
                            'where' => array(
                                'job_milestone_id' => $_POST['job_milestone_id']
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'job',
                                    'joint' => 'job.job_id = job_milestone.job_milestone_job_id',
                                    'type'  => 'both'
                                ),
                                1 => array(
                                    'table' => 'job_application',
                                    'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                                    'type'  => 'both'
                                )
                            )
                        )
                    );
                    if (!empty($job_milestone)) {

                        $attachment_param = array();
                        if (isset($_FILES['job_milestone_attachment_name']) && ($_FILES['job_milestone_attachment_name']['error']) == 0 && $_FILES['job_milestone_attachment_name']['size'] < MAX_FILE_SIZE) {
                            $tmp = $_FILES['job_milestone_attachment_name']['tmp_name'];
                            $name = mt_rand() . $_FILES['job_milestone_attachment_name']['name'];
                            $upload_path = 'assets/uploads/job_milestone_attachment/';

                            if (move_uploaded_file($tmp, $upload_path . $name)) {
                                $attachment_param['job_milestone_attachment_name'] = $name;
                                $attachment_param['job_milestone_attachment_path'] = $upload_path;
                            }
                        }

                        $attachment_param['job_milestone_attachment_milestone_id'] = isset($_POST['job_milestone_id']) ? $_POST['job_milestone_id'] : 0;
                        $attachment_param['job_milestone_attachment_text'] = isset($_POST['job_milestone_attachment_text']) ? $_POST['job_milestone_attachment_text'] : '';
                        $affected_milestone_attachment = $this->model_job_milestone_attachment->insert_record($attachment_param);
                        if ($affected_milestone_attachment) {
                            // last submission
                            $milestone_data['job_milestone_last_submission_id'] = $affected_milestone_attachment;
                            $milestone_data['job_milestone_last_submission_by'] = $this->userid;
                            $milestone_data['job_milestone_completion_status'] = MILESTONE_PROCESSING;
                            $updated = $this->model_job_milestone->update_by_pk($_POST['job_milestone_id'], $milestone_data);

                            if ($updated) {
                                // notify here
                                $this->model_notification->sendNotification($job_milestone['job_userid'], $job_milestone['job_application_signup_id'], NOTIFICATION_MILESTONE_SUBMITTED, $job_milestone['job_milestone_application_id'], NOTIFICATION_MILESTONE_SUBMITTED_COMMENT, '', $job_milestone['job_milestone_job_id']);
                            }

                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __(SUCCESS_MESSAGE);
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE_INSERT);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method update_milestone
     *
     * @return void
     */
    public function update_milestone()
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['job_milestone_id'])) {
                    $job_milestone = $this->model_job_milestone->find_one_active(
                        array(
                            'where' => array(
                                'job_milestone_id' => $_POST['job_milestone_id']
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'job',
                                    'joint' => 'job.job_id = job_milestone.job_milestone_job_id',
                                    'type'  => 'both'
                                ),
                                1 => array(
                                    'table' => 'job_application',
                                    'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                                    'type'  => 'both'
                                ),
                                2 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                                    'type'  => 'both'
                                )
                            )
                        )
                    );

                    //
                    $error = FALSE;
                    $errorMessage = __(ERROR_MESSAGE);

                    //
                    $charge_id = '';
                    $receipt_url = '';
                    $balance_transaction = '';
                    $milestone_payment_status = FALSE;
                    $affect_param = array();

                    $job_milestone_payment = $this->model_job_milestone_payment->find_one_active(
                        array(
                            'where' => array(
                                'job_milestone_payment_milestone_id' => $_POST['job_milestone_id']
                            )
                        )
                    );

                    //
                    if (!empty($job_milestone)) {
                        $amount = (int) $job_milestone['job_milestone_amount'];

                        //
                        if ($_POST['type'] == 'status_action') {
                            if ($_POST['job_milestone']['job_milestone_completion_status'] == MILESTONE_COMPLETE) {

                                $affect_param['job_milestone_payment_milestone_id'] = $job_milestone['job_milestone_id'];
                                $affect_param['job_milestone_payment_amount'] = $amount;
                                // add pending amount to be paid by admin
                                // add stripe fee later
                                $affect_param['job_milestone_payment_due'] = milestone_due_payment($amount);
                                //
                                $affect_param['job_milestone_payment_last_updated_by'] = $this->userid;
                                $affect_param['job_milestone_payment_updatedon'] = date('Y-m-d H:i:s');

                                if (isset($_POST['stripeToken'])) {

                                    $token = $_POST['stripeToken'];
                                    try {
                                        $charge = $this->model_stripe_log->createStripeResource('charges', [
                                            'amount' => ((($amount + percent_amount($amount, STRIPE_FEE_PERECENTAGE)) * 100) + STRIPE_FEE_EXTRA_CENTS),
                                            'currency' => DEFAULT_CURRENCY_CODE,
                                            'description' => 'Milestone payment from: ' . $this->user_data['signup_email'] . ' to: ' . $job_milestone['signup_email'] . ' for job: ' . $job_milestone['job_title'],
                                            'source' => $token,
                                        ]);
                                    } catch (\Exception $e) {
                                        $error = TRUE;
                                        $errorMessage = $e->getMessage();
                                    }

                                    if (!$error) {
                                        $charge_id = $charge->id;
                                        $receipt_url = $charge->receipt_url;
                                        $balance_transaction = $charge->balance_transaction;
                                        $charge = str_replace('Stripe\Charge JSON:', '', $charge);
                                        $response = json_decode($charge, true);
                                        if ($response['status'] == 'succeeded') {
                                            $milestone_payment_status = TRUE;
                                        }
                                    }

                                    if ($milestone_payment_status) {
                                        //
                                        $affect_param['job_milestone_payment_charge_id'] = $charge_id;
                                        $affect_param['job_milestone_payment_receipt_url'] = $receipt_url;
                                        $affect_param['job_milestone_payment_transaction_id'] = $balance_transaction;
                                        $affect_param['job_milestone_payment_response'] = $charge;
                                        $affect_param['job_milestone_payment_money_position_status'] = MILESTONE_PAYMENT_ESCROW;
                                    }
                                } else {
                                    // handle for plaid transfer
                                    $affect_param['job_milestone_payment_method'] = MILESTONE_PLAID_PAYMENT;
                                    $affect_param['job_milestone_payment_charge_id'] = isset($_POST['transfer_intent_id']) ? $_POST['transfer_intent_id'] : '';
                                    // add response
                                    $transfer_intent_data = $this->getinfo('transfer', $affect_param['job_milestone_payment_charge_id']);

                                    if ($transfer_intent_data && property_exists($transfer_intent_data, 'transfer_intent')) {
                                        $affect_param['job_milestone_payment_response'] = json_encode($transfer_intent_data);
                                        if ($transfer_intent_data->transfer_intent && property_exists($transfer_intent_data->transfer_intent, 'authorization_decision') && property_exists($transfer_intent_data->transfer_intent, 'status')) {
                                            if ($transfer_intent_data->transfer_intent->authorization_decision === 'APPROVED' && $transfer_intent_data->transfer_intent->status === 'SUCCEEDED') {
                                                $affect_param['job_milestone_payment_money_position_status'] = MILESTONE_PAYMENT_ESCROW;
                                            }
                                        }
                                    }
                                }

                                //
                                if (!empty($job_milestone_payment)) {
                                    $this->model_job_milestone_payment->update_by_pk($job_milestone_payment['job_milestone_payment_id'], $affect_param);
                                } else {
                                    $this->model_job_milestone_payment->insert_record($affect_param);
                                }
                            }
                            // if milestone completed else code will continue and no charge will be acquired
                        }

                        //
                        if (!$error) {

                            $email = $job_milestone['signup_email'];

                            if ($receipt_url && $email && ENVIRONMENT != 'development') {
                                // Generate Body of Email
                                $stripeReceipt_url = file_get_contents($receipt_url);
                                $matches = array();
                                preg_match("/<body[^>]*>(.*?)<\/body>/is", $stripeReceipt_url, $matches);

                                $to = $email;
                                $this->model_email->notification_charge_receipt($to, $matches[1]);
                            }

                            if (isset($_POST['job_milestone']['job_milestone_comment']) && $_POST['job_milestone']['job_milestone_comment']) {
                                // logging old comments by role_3 user
                                $this->model_job_milestone_comment->insert_record(
                                    array(
                                        'job_milestone_comment_milestone_id' => $job_milestone['job_milestone_id'],
                                        'job_milestone_comment_text' => $_POST['job_milestone']['job_milestone_comment'],
                                    )
                                );
                            }

                            $update_param = isset($_POST['job_milestone']) ? $_POST['job_milestone'] : array();
                            $update_param['job_milestone_last_update_by'] = $this->userid;
                            $update_param['job_milestone_updatedon'] = date('Y-m-d H:i:s');
                            $updated = $this->model_job_milestone->update_by_pk($job_milestone['job_milestone_id'], $update_param);

                            if ($updated) {
                                // notify here
                                if (isset($_POST['type'])) {
                                    switch ($_POST['type']) {
                                        case 'start':
                                            $this->model_notification->sendNotification($job_milestone['job_userid'], $job_milestone['job_application_signup_id'], NOTIFICATION_MILESTONE_STARTED, $job_milestone['job_milestone_application_id'], NOTIFICATION_MILESTONE_STARTED_COMMENT, '', $job_milestone['job_milestone_job_id']);
                                            break;
                                        case 'delete':
                                            $this->model_notification->sendNotification($job_milestone['job_application_signup_id'], $job_milestone['job_userid'], NOTIFICATION_MILESTONE_DELETED, $job_milestone['job_milestone_application_id'], NOTIFICATION_MILESTONE_DELETED_COMMENT, '', $job_milestone['job_milestone_job_id']);
                                            break;
                                        case 'status_action':
                                            $this->model_notification->sendNotification($job_milestone['job_application_signup_id'], $job_milestone['job_userid'], NOTIFICATION_MILESTONE_ACTION, $job_milestone['job_milestone_application_id'], NOTIFICATION_MILESTONE_ACTION_COMMENT, '', $job_milestone['job_milestone_job_id']);
                                            break;
                                    }
                                }

                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = __(SUCCESS_MESSAGE);
                            } else {
                                $json_param['txt'] = __(ERROR_MESSAGE_UPDATE);
                            }
                        } else {
                            //
                            $this->_log_message(
                                LOG_TYPE_PAYMENT,
                                LOG_SOURCE_SERVER,
                                LOG_LEVEL_ERROR,
                                $errorMessage,
                                ''
                            );
                            $json_param['txt'] = $errorMessage ?? __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method milestone_payment_transfer - cron or can be called manually by admin - for stripe
     *
     * @return void
     */
    public function milestone_payment_transfer(): void
    {
        //
        $status = TRUE;
        $return_message = '';
        $error_data = array();
        $refresh = FALSE;

        //
        $error = FALSE;
        $errorMessage = ERROR_MESSAGE;
        $transfer_data = "";

        if (isset($this->session_data['email']) && $this->session_data['email'] == $this->model_user->find_by_pk(1)['user_email']) {

            //
            $where_param['job_milestone_payment_money_position_status'] = MILESTONE_PAYMENT_ESCROW;
            $where_param['job_milestone_payment_method'] = MILESTONE_STRIPE_PAYMENT;
            //
            if (isset($_POST['id']) && $_POST['id']) {
                $where_param['job_milestone_payment_id'] = $_POST['id'];
            }

            $joins_param = array(
                0 => array(
                    'table' => 'job_milestone',
                    'joint' => 'job_milestone.job_milestone_id = job_milestone_payment.job_milestone_payment_milestone_id',
                    'type'  => 'both'
                ),
                1 => array(
                    'table' => 'job',
                    'joint' => 'job.job_id = job_milestone.job_milestone_job_id',
                    'type'  => 'both'
                ),
                2 => array(
                    'table' => 'job_application',
                    'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                    'type'  => 'both'
                ),
                3 => array(
                    'table' => 'signup',
                    'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                    'type'  => 'both'
                )
            );

            $job_milestone_payment = $this->model_job_milestone_payment->find_all_active(
                array(
                    'where' => $where_param,
                    'joins' => $joins_param
                )
            );

            if (!empty($job_milestone_payment)) {

                foreach ($job_milestone_payment as $key => $value) {
                    $force_manual = FALSE;
                    if (isset($_POST['force_manual']) && $_POST['force_manual']) {
                        $force_manual = TRUE;
                    }

                    $calculated_date = strtotime(date('Y-m-d H:i:s', strtotime('+' . STRIPE_TRANSFER_DELAY . ' ' . STRIPE_TRANSFER_INTERVAL, strtotime($value['job_milestone_payment_createdon']))));

                    if ($force_manual || ($calculated_date >= strtotime(date('Y-m-d H:i:s')))) {
                        try {
                            $transfer_data = $this->model_stripe_log->createStripeResource('transfers', [
                                "amount" => $value['job_milestone_payment_due'] * 100,
                                "currency" => DEFAULT_CURRENCY_CODE,
                                "destination" => $value['signup_account_id'],
                                "source_transaction" => $value['job_milestone_payment_charge_id'],
                            ]);
                        } catch (\Exception $e) {
                            $error = TRUE;
                            $errorMessage = $e->getMessage();
                        }

                        if ($error) {
                            array_push(
                                $error_data,
                                array(
                                    $key => array(
                                        'response' => $transfer_data,
                                        'value' => $value
                                    )
                                )
                            );

                            //
                            $this->_log_message(
                                LOG_TYPE_PAYMENT,
                                ($force_manual ? LOG_SOURCE_STRIPE : LOG_SOURCE_CRON),
                                LOG_LEVEL_ERROR,
                                $errorMessage,
                                $transfer_data
                            );
                        } else {
                            $this->model_job_milestone_payment->update_by_pk(
                                $value['job_milestone_payment_id'],
                                array(
                                    'job_milestone_payment_transfer_id' => $transfer_data->id,
                                    'job_milestone_payment_transfer_response' =>  str_replace('Stripe\Transfer JSON:', '', $transfer_data),
                                    'job_milestone_payment_money_position_status' => MILESTONE_PAYMENT_PAID
                                )
                            );
                        }
                    }
                }
                //
                if (!empty($error_data) && count($job_milestone_payment) > 0) {
                    if (count($job_milestone_payment) == count($error_data)) {
                        $return_message = 'All transfers have been failed.';
                        $status = FALSE;
                    } else {
                        $return_message = 'One or more transfer failed.';
                        $status = FALSE;
                    }
                } else {
                    $refresh = TRUE;
                    $return_message = 'All transfers have been completed.';
                }
            } else {
                $return_message = 'No payment in escrow to transfer.';
            }
        } else {
            $status = FALSE;
            $return_message = ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL;
        }

        echo json_encode(
            array(
                'status' => $status,
                'message' => $return_message,
                'reason' => $errorMessage,
                'data' => $error_data,
                'refresh' => $refresh
            )
        );
    }

    /**
     * Method milestone_payment_transfer_plaid
     *
     * @return void
     */
    function milestone_payment_transfer_plaid(): void
    {
        //
        $status = FALSE;
        $message = ERROR_MESSAGE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

                $transfer_intent_id = isset($_POST['transfer_intent_id']) ? $_POST['transfer_intent_id'] : '';

                if ($transfer_intent_id) {
                    //
                    $where_param['job_milestone_payment_money_position_status'] = MILESTONE_PAYMENT_ESCROW;
                    $where_param['job_milestone_payment_method'] = MILESTONE_PLAID_PAYMENT;
                    //
                    if (isset($_POST['milestone_id']) && $_POST['milestone_id']) {
                        $where_param['job_milestone_payment_milestone_id'] = $_POST['milestone_id'];
                    }

                    $joins_param = array(
                        0 => array(
                            'table' => 'job_milestone',
                            'joint' => 'job_milestone.job_milestone_id = job_milestone_payment.job_milestone_payment_milestone_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'job',
                            'joint' => 'job.job_id = job_milestone.job_milestone_job_id',
                            'type'  => 'both'
                        ),
                        2 => array(
                            'table' => 'job_application',
                            'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                            'type'  => 'both'
                        ),
                        3 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                            'type'  => 'both'
                        )
                    );

                    $job_milestone_payment = $this->model_job_milestone_payment->find_all_active(
                        array(
                            'where' => $where_param,
                            'joins' => $joins_param
                        )
                    );

                    if (!empty($job_milestone_payment)) {
                        foreach ($job_milestone_payment as $key => $value) {
                            $affect_param = array();
                            // add response
                            $transfer_intent_data = $this->getinfo('transfer', $transfer_intent_id);

                            if ($transfer_intent_data && property_exists($transfer_intent_data, 'transfer_intent')) {
                                $job_milestone_payment['job_milestone_payment_response'] = json_encode($transfer_intent_data);
                                if ($transfer_intent_data->transfer_intent && property_exists($transfer_intent_data->transfer_intent, 'authorization_decision') && property_exists($transfer_intent_data->transfer_intent, 'status')) {
                                    if ($transfer_intent_data->transfer_intent->authorization_decision === 'APPROVED' && $transfer_intent_data->transfer_intent->status === 'SUCCEEDED') {
                                        $affect_param['job_milestone_payment_transfer_id'] = $transfer_intent_id;
                                        $affect_param['job_milestone_payment_transfer_response'] = json_encode($transfer_intent_data);
                                        $affect_param['job_milestone_payment_money_position_status'] = MILESTONE_PAYMENT_PAID;
                                    }
                                }
                            }
                            $updated = $this->model_job_milestone_payment->update_by_pk($value['job_milestone_payment_id'], $affect_param);
                            if ($updated) {
                                $status = TRUE;
                                $message = SUCCESS_MESSAGE;
                            }
                        }
                    } else {
                        $message = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                    }
                } else {
                    $message = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                }
            } else {
                $message = ERROR_MESSAGE_LINK_EXPIRED;
            }
        } else {
            $message = ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE;
        }

        echo json_encode(
            array(
                'status' => $status,
                'message' => $message,
            )
        );
    }

    function escrowPayment(): void
    {
        $url = ESCROW_BASE_URL . ESCROW_TRANSACTION;
        $headers = array('Content-Type: application/json');
        $user_pwd = ESCROW_EMAIL . ':' . ESCROW_API_KEY;

        $post_fields = array(
            "parties" => array(
                0 => array(
                    "initiator" => true,
                    "role" => "buyer",
                    "customer" => "aza.entrepreneur1@gmail.com"
                ),
                1 => array(
                    "initiator" => false,
                    "role" => "seller",
                    "customer" => "aza.organization1@gmail.com"
                )
            ),
            "currency" => strtolower(DEFAULT_CURRENCY_CODE),
            "description" => "The milestone payment of johnwick.com",
            "items" => [
                0 => array(
                    "title" => "johnwick.com",
                    "description" => "johnwick.com",
                    "type" => "domain_name",
                    "inspection_period" => ESCROW_INSPECTION_PERIOD,
                    "quantity" => 1,
                    "schedule" => [
                        0 => array(
                            "amount" => 1000.0,
                            "payer_customer" => "aza.entrepreneur1@gmail.com",
                            "beneficiary_customer" => "aza.organization1@gmail.com",
                        )
                    ],
                )
            ]
        );
        $response = $this->curlRequest($url, $headers, $post_fields, TRUE, FALSE, '', FALSE, $user_pwd);
        $decoded_json = json_decode($response);
        debug($response);
    }
}
