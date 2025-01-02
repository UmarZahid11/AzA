<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Job
 */
class Job extends MY_Controller
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
     * Method post
     *
     * @param string $jobSlug
     * @param string $edit
     *
     * @return void
     */
    public function post(string $jobSlug = '', string $edit = ""): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data = array();

            $data['job_question'] = array();

            $this->register_plugins("select2");

            $data['job_category'] = $this->model_job_category->find_all_active();

            $data['language'] = $this->model_language->find_all();

            if ($jobSlug && $edit == 'edit') {
                $param = array();
                $param['where']['job_slug'] = $jobSlug;
                // $param['where']['job_userid'] = $this->userid;
                $param['where']['job_isdeleted'] = 0;
                $data['job'] = $this->model_job->find_one($param);
                if (empty($data['job'])) {
                    $this->session->set_flashdata('error', __('The requested job doesn\'t exist!'));
                    redirect(l('dashboard/job/listing'));
                }

                $data['job_question'] = $this->model_job_question->find_all_active(
                    array(
                        'where' => array(
                            'job_question_job_id' => $data['job']['job_id']
                        )
                    )
                );
            }

            $data['edit'] = $edit;

            $data['job_type'] = $this->model_job_type->find_all_active();

            //
            $this->layout_data['title'] = 'Post Job | ' . $this->layout_data['title'];
            //
            $this->load_view("post", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method listing
     *
     * @param int $page
     * @param int $limit
     * @param int $organizationId
     * @param bool $hasApplied
     * @param string $search
     *
     * @return void
     */
    public function listing(int $page = 1, int $limit = PER_PAGE, int $organizationId = 0, bool $hasApplied = FALSE, string $search = ''): void
    {
        $data = array();

        $appliedJobsIds = array();

        if ($hasApplied) {
            $appliedJobsIds = $this->model_job_application->appliedJobsIds($this->userid);
        }

        $data['organizationId'] = $organizationId;
        $data['hasApplied'] = $hasApplied;
        $data['search'] = $search;

        $data['organization'] = array();

        if ($organizationId) {
            $data['organization'] = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_id' => $organizationId,
                        'signup_type' => ROLE_3,
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
            if (empty($data['organization'])) {
                error_404();
            }
        }

        //
        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;
        //

        $param = array();
        $count_param = array();

        if ($organizationId) {
            $param['where']['job_userid'] = $organizationId;
            $count_param['where']['job_userid'] = $organizationId;
        }

        if ($hasApplied) {
            if (count($appliedJobsIds) > 0) {
                $param['where_in']['job_id'] = $appliedJobsIds;
                $count_param['where_in']['job_id'] = $appliedJobsIds;
            } else {
                $param['where_in']['job_id'] = [0];
                $count_param['where_in']['job_id'] = [0];
            }
        }
        if ($search) {
            $count_param['where_like'][] = $param['where_like'][] = array(
                'column' => 'job_title',
                'value' => $search,
                'type' => 'both',
            );
        }

        $count_param['where']['job_isdeleted'] = $param['where']['job_isdeleted'] = STATUS_INACTIVE;
        $count_param['where']['job_status'] = $param['where']['job_status'] = STATUS_ACTIVE;

        $param['order'] = 'job_id DESC';
        $data['offset'] = $param['offset'] = $paginationStart;
        $param['limit'] = $limit;

        //
        // $count_param['where']['job_subscription_expiry >'] = $param['where']['job_subscription_expiry >'] = date('Y-m-d H:i:s');

        $data['job'] = $this->model_job->find_all($param);
        $data['job_count'] = $allRecrods = count($this->model_job->find_all($count_param));

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        $data['job_category'] = $this->model_job_category->find_all_active();

        //
        $this->layout_data['title'] = 'Job Listing | ' . $this->layout_data['title'];
        //
        $this->load_view("listing", $data);
    }

    /**
     * Method posted
     *
     * @param int $page
     * @param int $limit
     * @param int $organizationId
     * @param string $search
     *
     * @return void
     */
    public function posted(int $page = 1, int $limit = PER_PAGE, int $organizationId = 0, string $search = ''): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data = array();

            $data['search'] = $search;
            $data['organizationId'] = $organizationId;

            $data['page'] = $page;
            $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            $param = array();
            $count_param = array();

            if ($organizationId) {
            } else {
                $count_param['where']['job_userid'] = $param['where']['job_userid'] = $this->userid;
            }
            if ($search) {
                $count_param['where_like'][] = $param['where_like'][] = array(
                    'column' => 'job_title',
                    'value' => $search,
                    'type' => 'both',
                );
            }
            $count_param['where']['job_isdeleted'] = $param['where']['job_isdeleted'] = STATUS_INACTIVE;

            // $param['where']['job_expiry >'] = date("Y-m-d");
            // $count_param['where']['job_expiry >'] = date("Y-m-d");

            $param['order'] = 'job_id DESC';
            $data['offset'] = $param['offset'] = $paginationStart;
            $param['limit'] = $limit;

            $job = $this->model_job->find_all($param);

            $data['job'] = $job;

            $data['job_count'] = $allRecrods = ($this->model_job->find_count($count_param));

            // Calculate total pages
            $data['totalPages'] = ceil($allRecrods / $limit);

            $data['job_category'] = $this->model_job_category->find_all_active();

            //
            $this->layout_data['title'] = 'Posted Jobs | ' . $this->layout_data['title'];
            //
            $this->load_view("posted", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method expired
     *
     * @param int $page
     * @param int $limit
     * @param int $organizationId
     *
     * @return void
     */
    public function expired(int $page = 1, int $limit = PER_PAGE, int $organizationId = 0): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            global $config;

            $data = array();
            $data['organizationId'] = $organizationId;

            $data['page'] = $page;
            $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            $param = array();
            if ($organizationId) {
            } else {
                $param['where']['job_userid'] = $this->userid;
            }
            $param['where']['job_isdeleted'] = 0;
            $param['where']['job_expiry <'] = date("Y-m-d");
            $param['order'] = 'job_id DESC';
            $data['offset'] = $param['offset'] = $paginationStart;
            $param['limit'] = $limit;

            $job = $this->model_job->find_all($param);

            $data['job'] = $job;

            $param = array();
            if ($organizationId) {
            } else {
                $param['where']['job_userid'] = $this->userid;
            }
            $param['where']['job_expiry <'] = date("Y-m-d");
            $param['where']['job_isdeleted'] = STATUS_INACTIVE;
            $data['job_count'] = $allRecrods = count($this->model_job->find_all($param));

            // Calculate total pages
            $data['totalPages'] = ceil($allRecrods / $limit);

            $data['job_category'] = $this->model_job_category->find_all_active();

            //
            $this->layout_data['title'] = 'Expired Jobs | ' . $this->layout_data['title'];
            //
            $this->load_view("expired", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method pending
     *
     * @return void
     */
    public function pending(): void
    {
        $data = array();

        //
        $this->layout_data['title'] = 'Pending Jobs | ' . $this->layout_data['title'];
        //
        $this->load_view("pending", $data);
    }

    /**
     * Method detail
     *
     * @param string $jobSlug
     *
     * @return void
     */
    public function detail(string $jobSlug = ""): void
    {
        if (!$jobSlug) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_RESOURCE_NOT_FOUND));
            redirect(l('dashboard/job/listing'));
        }

        $data = array();
        $data['job'] = array();

        $param = array();
        $param['where']['job_slug'] = $jobSlug;
        // $param['where']['job_userid'] = $this->userid;
        $param['where']['job_isdeleted'] = STATUS_INACTIVE;
        $param['joins'][] = array(
            'table' => 'signup',
            'joint' => 'signup.signup_id = job.job_userid',
            'type' => 'both'
        );
        $param['joins'][] = array(
            'table' => 'signup_company',
            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
            'type' => 'left'
        );

        $data['job'] = $this->model_job->find_one($param);

        if (empty($data['job'])) {
            $this->session->set_flashdata('error', __('The requested job doesn\'t exists or moved to a new page!'));
            redirect(l('dashboard/job/listing'));
        }

        $data['job_application'] = $this->model_job_application->find_one_active(
            array(
                'where' => array(
                    'job_application_signup_id' => $this->userid,
                    'job_application_job_id' => $data['job']['job_id'],
                )
            )
        );
        $data['job_question'] = $this->model_job_question->find_all_active(
            array(
                'where' => array(
                    'job_question_job_id' => $data['job']['job_id'],
                )
            )
        );

        //
        $data['comment'] = $this->model_comment->find_all_active(
            array(
                'order' => 'comment_id DESC',
                'where' => array(
                    'comment_parent_id' => 0,
                    'comment_reference_id' => $data['job']['job_id'],
                    'comment_reference_type' => 'job'
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = comment.comment_userid',
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

        // commenting purpose
        $data['type'] = REFERENCE_TYPE_JOB;
        $data['reference_id'] = $data['job']['job_id'];

        //
        $query = 'SELECT * FROM `fb_signup_follow`';
        $query .= ' INNER JOIN fb_signup ON (fb_signup_follow.signup_follow_follower_id = fb_signup.signup_id AND signup_follow_follower_id != ' . $this->userid . ')';
        $query .= ' where signup_type = ' . ROLE_3;
        $query .= ' AND signup_follow_reference_id = ' . $this->userid;
        $query .= ' AND signup_follow_reference_type = "' . FOLLOW_REFERENCE_SIGNUP . '"';
        $query .= ' AND (signup_worktype = "' . $data['job']['job_type'] . '"';
        if ($data['job']['signup_company_type']) {
            $query .= ' OR signup_preferred_organization = "' . $data['job']['signup_company_type'] . '"';
        }

        $job_category = $data['job']['job_category'] ? implode('', unserialize($data['job']['job_category'])) : [];
        if(!empty($job_category)) {
            $query .= ' OR signup_sciencework IN (' . $job_category . '))';
        } else {
            $query .= ')';    
        }
        $query .= ' ORDER BY signup_follow_id DESC limit 10';

        //
        $query_array = $this->db->query($query);
        $data['ideal_candidate'] = [];
        if($query_array) {
            $data['ideal_candidate'] = ($query_array->result_array());
        }

        //
        $this->layout_data['title'] = $data['job']['job_title'] . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $subscription_error = FALSE;
        $subscribed = FALSE;
        $subscription = '';

        // if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if ((isset($_POST['stripeToken']) && g('db.admin.enable_job_listing_subscription')) || (!g('db.admin.enable_job_listing_subscription')) || (isset($_POST['job_id']) && intVal($_POST['job_id']) > 0)) {

                    if (isset($_POST['job'])) {
                        $type = 'insert';
                        $error = false;
                        $errorMessage = __(ERROR_MESSAGE);
                        $successMessage = "Success";
                        $jobId = 0;
                        $affectJob = $_POST['job'];

                        // check if job exists with the requested id
                        if (isset($_POST['job_id']) && intVal($_POST['job_id']) > 0) {
                            $param = array();
                            $param['where']['job_id'] = $_POST['job_id'];
                            $param['where']['job_userid'] = $this->userid;
                            $jobDetail = $this->model_job->find_one($param);
                            if (empty($jobDetail)) {
                                $error = true;
                                $errorMessage = "The requested job doesn't exists";
                            } else {
                                $jobId = $jobDetail['job_id'];
                            }
                        }

                        if (!$error) {
                            $somError = FALSE;
                            $param = array();
                            // exclude currently updating job for updation - for title repetition check
                            if ($jobId) {
                                $param['where']['job_id !='] = $_POST['job_id'];
                            }
                            $param['where']['job_slug'] = $affectJob['job_slug'];
                            $jobDetail = $this->model_job->find_one($param);

                            if (empty($jobDetail)) {
                                // subscribe
                                if (isset($_POST['stripeToken']) && g('db.admin.enable_job_listing_subscription') && g('db.admin.job_listing_subscription_fee')) {
                                    $customer = $this->createStripeResource('customers', [
                                        'source' => $_POST['stripeToken'],
                                    ]);
                                    $product = $this->createStripeResource('products', [
                                        'name' => $affectJob['job_title'],
                                    ]);
                                    //
                                    $interval = (isset($affectJob['job_subscription_interval']) && $affectJob['job_subscription_interval'] ? $affectJob['job_subscription_interval'] : 1);
                                    $cancel_at = strtotime(date('Y-m-d H:i:s', strtotime('+' . (int) $interval . $affectJob['job_subscription_interval_type'])));
                                    
                                    switch($affectJob['job_subscription_interval_type']) {
                                        case 'day':
                                            $job_listing_subscription_fee = g('db.admin.job_listing_subscription_fee') * $interval;
                                            break;
                                        case 'week':
                                            $job_listing_subscription_fee = g('db.admin.job_listing_subscription_fee') * 7 * $interval;
                                            break;
                                        case 'month':
                                            $job_listing_subscription_fee = g('db.admin.job_listing_subscription_fee') * 28 * $interval;
                                            break;
                                        default:
                                            $job_listing_subscription_fee = g('db.admin.job_listing_subscription_fee') * $interval;
                                    }
                                    
                                    //
                                    $price = $this->createStripeResource('prices', [
                                        'unit_amount' => $job_listing_subscription_fee * 100,
                                        'currency' => DEFAULT_CURRENCY_CODE,
                                        'product' => $product->id,
                                        'recurring' => array(
                                            'interval' => $affectJob['job_subscription_interval_type'],
                                            // 'interval' => SUBSCRIPTION_JOB_INTERVAL_TYPE,
                                            'interval_count' => 1,
                                        ),
                                    ]);

                                    $subscription = $this->createStripeResource('subscriptions', [
                                        'customer' => $customer->id,
                                        'cancel_at' => $cancel_at,
                                        'items' => [
                                            ['price' => $price->id],
                                        ],
                                    ]);

                                    if ($subscription) {
                                        $affectJob['job_subscription_id'] = $subscription->id;
                                        $affectJob['job_subscription_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscription);
                                        $affectJob['job_subscription_status'] = $this->model_membership->subscriptionStatus($subscription->status);
                                        $affectJob['job_subscription_current_period_start'] = date('Y-m-d H:i:s', $subscription->current_period_start);
                                        $affectJob['job_subscription_current_period_end'] = date('Y-m-d H:i:s', $subscription->current_period_end);
                                        $affectJob['job_subscription_expiry'] = date('Y-m-d H:i:s', $cancel_at);
                                        //
                                        $subscribed = TRUE;
                                    } else {
                                        $subscription_error = TRUE;
                                        $errorMessage = 'An error occurred while trying to process subscription.';
                                    }
                                }

                                if (!$subscription_error) {
                                    $affectJob['job_category'] = isset($affectJob['job_category']) ? serialize($affectJob['job_category']) : '';
                                    $affectJob['job_language'] = isset($affectJob['job_language']) ? serialize($affectJob['job_language']) : '';

                                    if (isset($_FILES['job_attachment']) && count($_FILES['job_attachment']['name']) > 0) {
                                        for ($i = 0; $i < count($_FILES['job_attachment']['name']); $i++) {
                                            if (isset($_FILES['job_attachment']['error'][$i]) && $_FILES['job_attachment']['error'][$i] == 0 && $_FILES['job_attachment']['size'][$i] < MAX_FILE_SIZE) {

                                                $tmp = $_FILES['job_attachment']['tmp_name'][$i];
                                                $ext = pathinfo($_FILES['job_attachment']['name'][$i], PATHINFO_EXTENSION);
                                                $name = mt_rand() . '.' . $ext;
                                                $upload_path = 'assets/uploads/job/';

                                                if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                                    $somError = TRUE;
                                                } else {
                                                    // juug
                                                    switch($i) {
                                                        case '0':
                                                            $affectJob['job_attachment'] = $name;
                                                            break;
                                                        case 1:
                                                            $affectJob['job_attachment1'] = $name;
                                                            break;
                                                        case 2:
                                                            $affectJob['job_attachment2'] = $name;
                                                            break;
                                                    }

                                                    $affectJob['job_attachment_path'] = $upload_path;
                                                }

                                            }
                                        }
                                    }

                                    // if action => update else, action => insert
                                    if ($jobId) {
                                        $affected = $this->model_job->update_by_pk($jobId, $affectJob);

                                        $type = UPDATE;
                                        $successMessage = __("The requested changes has been saved");

                                        // if error
                                        if (!$affected) {
                                            $error = true;
                                            $errorMessage = __(ERROR_MESSAGE_UPTODATE);
                                        }
                                    } else {
                                        $affected = $this->model_job->insert_record($affectJob);

                                        $type = INSERT;
                                        $successMessage = __("The requested job has been saved");

                                        // if error
                                        if (!$affected) {
                                            $error = true;
                                            $errorMessage = __(ERROR_MESSAGE);
                                        } else {
                                            $jobId = $affected;
                                        }
                                    }

                                    // if job is only inserted and fee is paid
                                    if ($subscribed) {
                                        // save order
                                        $affect_param = array();
                                        $affect_param['order_user_id'] = $this->userid;
                                        $affect_param['order_email'] = $this->user_data['signup_email'];
                                        $affect_param['order_firstname'] = $this->user_data['signup_firstname'];
                                        $affect_param['order_lastname'] = $this->user_data['signup_lastname'];
                                        $affect_param['order_phone'] = $this->user_data['signup_phone'];
                                        $affect_param['order_address1'] = $this->user_data['signup_address'];
                                        $affect_param['order_city'] = $this->user_data['signup_city'];
                                        $affect_param['order_zip'] = $this->user_data['signup_zip'];
                                        $affect_param['order_reference_id'] = $jobId;
                                        $affect_param['order_reference_type'] = ORDER_REFERENCE_JOB;
                                        $affect_param['order_quantity'] = $interval;
                                        $affect_param['order_quantity_interval'] = $affectJob['job_subscription_interval_type'];
                                        $affect_param['order_total'] = $job_listing_subscription_fee; //g('db.admin.job_listing_subscription_fee');
                                        $affect_param['order_shipping'] = 0;
                                        $affect_param['order_amount'] = $affect_param['order_total'] + $affect_param['order_shipping'];
                                        $affect_param['order_status'] = STATUS_ACTIVE;
                                        $affect_param['order_payment_status'] = STATUS_ACTIVE;
                                        $affect_param['order_status_message'] = PAYMENT_STATUS[PAYMENT_STATUS_COMPLETED];
                                        $affect_param['order_shipment_price'] = price($affect_param['order_shipping']);
                                        $affect_param['order_merchant'] = STRIPE;
                                        $affect_param['order_currency'] = DEFAULT_CURRENCY_CODE;
                                        $affect_param['order_transaction_id'] = $subscription ? $subscription->id : '';
                                        $affect_param['order_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscription);
                                        //
                                        $affected_order = $this->model_order->insert_record($affect_param);

                                        if ($affected_order) {
                                            $affect_param = array();
                                            $affect_param['order_item_status'] = STATUS_ACTIVE;
                                            $affect_param['order_item_order_id'] = $affected_order;
                                            $affect_param['order_item_product_id'] = $jobId;
                                            $affect_param['order_item_user_id'] = $this->userid;
                                            $affect_param['order_item_price'] = g('db.admin.job_listing_subscription_fee');
                                            $affect_param['order_item_subtotal'] = g('db.admin.job_listing_subscription_fee');
                                            $affect_param['order_item_qty'] = $interval;
                                            $affect_param['order_item_qty_interval'] = $affectJob['job_subscription_interval_type'];
                                            //
                                            $this->model_order_item->insert_record($affect_param);
                                        }
                                        // saving to log for webhook differentiaition
                                        if (!$this->saveStripeLog($this->userid, STRIPE_LOG_REFERENCE_JOB, (int) $jobId, STRIPE_LOG_RESOURCE_TYPE['subscriptions'], $subscription->id, str_replace('Stripe\Subscription JSON:', '', (string) $subscription))) {
                                            log_message('ERROR', 'Unable to generate log');
                                        }
                                    }

                                    //
                                    $affected_question = 0;
                                    if (isset($_POST['job_question']) && is_array($_POST['job_question']) && count($_POST['job_question']) > 0) {
                                        $job_question = $_POST['job_question'];
                                        foreach ($job_question as $value) {
                                            if ($value['job_question_title']) {
                                                $affected = false;

                                                if (isset($value['job_question_id']) && $value['job_question_id']) {
                                                    $job_question_id = $value['job_question_id'];
                                                    if (!empty($this->model_job_question->find_by_pk($job_question_id))) {
                                                        $affected = $this->model_job_question->update_by_pk(
                                                            $job_question_id,
                                                            array(
                                                                'job_question_title' => $value['job_question_title']
                                                            )
                                                        );
                                                    }
                                                } else {
                                                    $question_count = $this->model_job_question->find_count_active(
                                                        array(
                                                            'where' => array(
                                                                'job_question_job_id' => $jobId
                                                            )
                                                        )
                                                    );
                                                    if ($question_count < 5) {
                                                        $insert_question['job_question_job_id'] = $jobId;
                                                        $insert_question['job_question_title'] = $value['job_question_title'];
                                                        $affected = $this->model_job_question->insert_record($insert_question);
                                                    }
                                                }
                                                if ($affected)
                                                    $affected_question++;
                                            }
                                        }
                                    }

                                    // notification
                                    switch ($type) {
                                        case INSERT:
                                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_JOB_POST_INSERT, $jobId, NOTIFICATION_JOB_POST_INSERT_COMMENT);
                                            break;
                                        case UPDATE:
                                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_JOB_POST_UPDATE, $jobId, NOTIFICATION_JOB_POST_UPDATE_COMMENT);
                                            break;
                                    }

                                    if (!$error) {
                                        // notification
                                        $this->notifyPremiumUsers($affected);

                                        $json_param['status'] = STATUS_TRUE;
                                        $json_param['type'] = $type;
                                        $json_param['slug'] = $affectJob['job_slug'];
                                        $json_param['txt'] = $successMessage . ($somError ? ' with an error.' : ' successfully.');
                                    } elseif ($affected_question > 0) {
                                        $json_param['status'] = STATUS_TRUE;
                                        $json_param['type'] = $type;
                                        $json_param['slug'] = $affectJob['job_slug'];
                                        $json_param['txt'] = $successMessage . ($somError ? ' with an error.' : ' successfully.');
                                    } else {
                                        $json_param['txt'] = $errorMessage;
                                    }
                                } else {
                                    $json_param['txt'] = $errorMessage;
                                }
                            } else {
                                $json_param['txt'] = __("A unique job title is required.");
                            }
                        } else {
                            $json_param['txt'] = $errorMessage;
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_STRIPE_TOKEN);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        // } else {
        //     $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        // }
        echo json_encode($json_param);
    }

    /**
     * Method notifyPremiumUsers
     *
     * @param int $jobId
     *
     * @return void
     */
    public function notifyPremiumUsers(int $jobId): void
    {
        $signup = $this->model_signup->withRole(ROLE_3);
        $jobData = $this->model_job->find_by_pk($jobId);
        foreach ($signup as $key => $value) {
            if (ENVIRONMENT != 'development' && !empty($jobData)) {
                $this->model_email->_new_job_email($value['signup_email'], l('dashboard/job/detail/') . $jobData['job_slug'], $this->model_signup->profileName($this->user_data, FALSE));
            }
            $this->model_notification->sendNotification($value['signup_id'], $this->userid, NOTIFICATION_JOB_POSTED, $jobId, NOTIFICATION_JOB_POSTED_COMMENT);
        }
    }

    /**
     * Method update
     *
     * @return void
     */
    public function update(): void
    {
        $json_param = array();
        if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $job_data = isset($_POST['job']) ? $_POST['job'] : array();
                $affected_job = 0;
                if (isset($_POST['job_id'])) {
                    if (!empty($this->model_job->find_by_pk($_POST['job_id']))) {
                        $job_data['job_updatedon'] = date('Y-m-d H:i:s');
                        $affected_job = $this->model_job->update_by_pk($_POST['job_id'], $job_data);
                    }
                }
                if ($affected_job) {
                    // notify here
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = __(SUCCESS_MESSAGE);
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
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete
     *
     * @return void
     */
    public function delete(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        $error = FALSE;

        if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {

            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST)) {
                    $jobId = $_POST['id'];

                    $param = array();
                    $param['where']['job_id'] = $jobId;
                    $param['where']['job_userid'] = $this->userid;
                    $jobDetail = $this->model_job->find_one($param);

                    if (!empty($jobDetail)) {

                        $updateParam = array();
                        $whereParam = array();
                        $updateParam['job_isdeleted'] = STATUS_ACTIVE;
                        $updateParam['job_deletedat'] = date("Y-m-d H:i:s");
                        $whereParam['where']['job_id'] = $jobDetail['job_id'];
                        $updatedJob = $this->model_job->update_model($whereParam, $updateParam);

                        if ($updatedJob) {
                            //
                            if ($jobDetail['job_subscription_id']) {
                                $subscriptionId = $jobDetail['job_subscription_id'];
                                $subscriptionDetails = '';

                                try {
                                    $subscriptionDetails = $this->resource('subscriptions', $subscriptionId);
                                } catch (\Exception $e) {
                                    $error = true;
                                    $errorMessage = $e->getMessage();
                                    //
                                    $this->_log_message(
                                        LOG_TYPE_PAYMENT,
                                        LOG_SOURCE_STRIPE,
                                        LOG_LEVEL_ERROR,
                                        $errorMessage,
                                        (string) $subscriptionDetails
                                    );
                                }

                                if (!$error) {
                                    if ($subscriptionDetails && $subscriptionDetails->canceled_at == NULL) {
                                        try {
                                            $subscriptionDetails = $this->stripe->subscriptions->cancel(
                                                $subscriptionId,
                                                []
                                            );

                                            if ($subscriptionDetails->canceled_at != NULL) {
                                                $this->downgradeSubscription($subscriptionDetails, (int) $jobId);
                                            }
                                        } catch (\Exception $e) {
                                            $this->session->set_flashdata('error', $e->getMessage());
                                        }
                                    }
                                } else {
                                    $this->downgradeSubscription($subscriptionDetails, (int) $jobId);
                                }
                            }

                            // notification
                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_JOB_POST_DELETE, 0, NOTIFICATION_JOB_POST_DELETE_COMMENT);

                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __('Job deleted successfully!');
                        } else {
                            $json_param['txt'] = __('Error in deleting requested job.');
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
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
     * Method downgradeSubscription
     *
     * @param object $subscriptionDetails
     * @param int $jobId
     *
     * @return int
     */
    function downgradeSubscription(?object $subscriptionDetails, int $jobId): int
    {
        $updated = 0;
        $reference = $this->model_job->find_by_pk($jobId);

        if ($reference) {
            $updated = $this->model_job->update_by_pk(
                $jobId,
                array(
                    'job_subscription_status' => SUBSCRIPTION_CANCELLED,
                    'job_subscription_response' => str_replace('Stripe\Subscription JSON:', '', (string) $subscriptionDetails),
                    'job_status' => STATUS_DELETE,
                )
            );
            $this->model_order->update_model(
                array(
                    'where' => array(
                        'order_transaction_id' => $reference['job_subscription_id']
                    )
                ),
                array(
                    'order_payment_status' => PAYMENT_STATUS_CANCELLED
                )
            );
        }
        return $updated;
    }

    /**
     * Method deleteAttachment
     *
     * @return void
     */
    function deleteAttachment() : void {

        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $jobId = $_POST['id'];

                $param = array();
                $param['where']['job_id'] = $jobId;
                $param['where']['job_userid'] = $this->userid;
                $jobDetail = $this->model_job->find_one($param);

                if (!empty($jobDetail)) {
                    $affect_param = array();
                    $param_name = isset($_POST['param']) && $_POST['param'] ? $_POST['param'] : '';
                    if($param_name) {
                        $affect_param[$param_name] = '';
                    } else {
                        $affect_param['job_attachment'] = '';
                    }

                    $affected = $this->model_job->update_by_pk($jobId, $affect_param);
                    if ($affected) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }
    /**
     * Method delete_application
     *
     * @return void
     */
    public function delete_application(): void
    {
        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $job_application_id = isset($_POST['id']) ? $_POST['id'] : '';

                $param = array();
                $param['where']['job_application_id'] = $job_application_id;
                $jobApplication = $this->model_job_application->find_one($param);

                if (!empty($jobApplication)) {
                    $updateParam = array();
                    $whereParam = array();
                    $updateParam['job_application_status'] = STATUS_DELETE;
                    $whereParam['where']['job_application_id'] = $jobApplication['job_application_id'];
                    $updatedJobApplication = $this->model_job_application->update_model($whereParam, $updateParam);

                    if ($updatedJobApplication) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __("Job application deleted successfully!");
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __("Error in deleting requested job application.");
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __("Requested job application doesn\'t exists!");
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method apply
     *
     * @param string $job_id
     *
     * @return void
     */
    public function apply(string $job_id = ''): void
    {
        global $config;

        $data = array();

        if (!$job_id) {
            error_404();
        }

        try {
            $job_id = JWT::decode($job_id, CI_ENCRYPTION_SECRET);
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
            $job_application = $this->model_job->find_one_active(
                array(
                    'where' => array(
                        'job_id' => $job_id,
                        'job_application_signup_id' => $this->userid,
                        'job_application_status' => 1
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'fb_job_application',
                            'joint' => 'job_application.job_application_job_id = job.job_id',
                            'type' => 'both'
                        ),
                    )
                )
            );

            if (empty($job_application)) {
                $data['job'] = $this->model_job->find_one_active(
                    array(
                        'where' => array(
                            'job_id' => $job_id,
                        ),
                    )
                );
                if (empty($data['job'])) {
                    error_404();
                }
            } else {
                // application already sent
                error_404();
            }

            $data['job_question'] = $this->model_job_question->find_all_active(
                array(
                    'where' => array(
                        'job_question_job_id' => $job_id,
                    )
                )
            );

            $data['title'] = $config['title'];

            //
            $this->layout_data['title'] = 'Apply now | ' . $this->layout_data['title'];
            //
            $this->load_view("apply", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method milestone_payment
     *
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    function milestone_payment(int $page = 1, int $limit = PER_PAGE): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data['page'] = $page;
            $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;
            $data['offset'] = $paginationStart;

            $data['job_milestone_payment'] = $this->model_job_milestone_payment->find_all_active(
                array(
                    'offset' => $paginationStart,
                    'limit' => $limit,
                    'where' => array(
                        'job_userid' => $this->userid
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'job_milestone',
                            'joint' => 'job_milestone_payment.job_milestone_payment_milestone_id = job_milestone.job_milestone_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'job',
                            'joint' => 'job_milestone.job_milestone_job_id = job.job_id',
                            'type'  => 'both'
                        ),
                    )
                )
            );

            // $allRecrods
            $data['job_milestone_payment_count'] = $allRecrods = $this->model_job_milestone_payment->find_count_active(
                array(
                    'where' => array(
                        'job_userid' => $this->userid
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'job_milestone',
                            'joint' => 'job_milestone_payment.job_milestone_payment_milestone_id = job_milestone.job_milestone_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'job',
                            'joint' => 'job_milestone.job_milestone_job_id = job.job_id',
                            'type'  => 'both'
                        ),
                    )
                )
            );

            // Calculate total pages
            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            $this->layout_data['title'] = 'Jobs Milestone Payments | ' . $this->layout_data['title'];
            //
            $this->load_view("milestone_payment", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }
}
