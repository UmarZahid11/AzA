<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile
 */
class Profile extends MY_Controller
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
     *
     * @return void
     */
    public function listing(string $type = '', int $page = 1, int $limit = PER_PAGE): void
    {
        if (!$type || $this->userid == 0) {
            error_404();
        }

        try {
            $type = JWT::decode($type, CI_ENCRYPTION_SECRET);
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

        if (!is_int($type) || !in_array($type, [ROLE_1, ROLE_3, ROLE_4, ROLE_5])) {
            error_404();
        }

        $data = array();

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $data['type'] = $this->model_signup->getRawRole($type);

        if (!$data['type']) {
            $this->session->set_flashdata(ERROR_MESSAGE);
            redirect(l('dashboard'));
        }
        
        //
        $data['signup_type'] = $type;

        $search = '';
    
        //
        $query = 'Select * from `fb_signup`' . ' ';
        $query .= 'LEFT JOIN `fb_signup_company` ON `fb_signup_company`.`signup_company_signup_id` = `fb_signup`.`signup_id`' . ' ';
        $query .= 'JOIN `fb_signup_info` ON `fb_signup_info`.`signup_info_signup_id` = `fb_signup`.`signup_id`' . ' ';
        $query .= 'WHERE `signup_type` = ' . $type . ' ';
        if ($type == ROLE_3) {
            $query .= 'AND `fb_signup_company`.`signup_company_name` != ""' . ' ';
        }
        $query .= 'AND `fb_signup`.`signup_status` = 1' . ' ';

        if(isset($_GET['search']) && $_GET['search']) {
            //
            $data['search'] = $_GET['search'];

            $query .= 'AND ( `signup_firstname` LIKE "%' . $data['search'] . '%"' . ' ';
            $query .= 'OR  `signup_lastname` LIKE "%' . $data['search'] . '%"' . ' ';
            $query .= 'OR  `signup_fullname` LIKE "%' . $data['search'] . '%"' . ' ';
            $query .= 'OR  `signup_email` LIKE "%' . $data['search'] . '%"' . ' ';
            $query .= 'OR  `signup_company_name` LIKE "%' . $data['search'] . '%"' . ') ';
        }

        //
        $count_query = $query;
        $organization = $this->db->query($query)->result_array();;

        // sort wrt to highest rating
        for ($i = 0; $i < count($organization); $i++) {

            $organization[$i]['reviewAvg'] = $this->model_review->reviewAvg($organization[$i]['signup_id'], REVIEW_TYPE_SIGNUP);

            $key = $organization[$i];
            $j = $i - 1;

            while ($j >= 0 && $organization[$j]['reviewAvg'] < $key['reviewAvg']) {
                $organization[$j + 1] = $organization[$j];
                $j = $j - 1;
            }
            $organization[$j + 1] = $key;
        }

        $data['organization'] = array_slice($organization, $paginationStart, $limit);

        $data['organization_count'] = $allRecrods = count($this->db->query($count_query)->result_array());

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = $data['type'] . ' Listing | ' . $this->layout_data['title'];
        //
        $this->load_view("listing", $data);
    }

    /**
     * Method detail
     *
     * @param string $id
     * @param int $type
     *
     * @return void
     */
    public function detail(string $id = '', int $type = 0): void
    {
        if (!$id || $this->userid == 0 || $type == '') {
            error_404();
        }

        //
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

        $data = array();
        $data['type'] = $this->model_signup->getRole($type);

        if (!$data['type']) {
            $this->session->set_flashdata(ERROR_MESSAGE);
            redirect(l('dashboard'));
        }

        // if ($data['type'] == RAW_ROLE_1) {
        //     $data['type'] .= ' User';
        // }

        $data['user'] = $this->model_signup->find_one_active(
            array(
                'where' => array(
                    'signup_id' => ($id ? $id : $this->userid),
                    'signup_type' => $type,
                    'signup_isdeleted' => STATUS_INACTIVE
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type'  => 'left'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type'  => 'left'
                    ),
                )
            )
        );

        if (empty($data['user'])) {
            $this->session->set_flashdata('error', 'Requested ' . $data['type'] . ' doesn\'t exist!');
            redirect(l('dashboard/profile/listing/' . JWT::encode($type, CI_ENCRYPTION_SECRET)));
        }

        // Add analytics
        $this->model_signup_analytics->addAnalytic((int) $id, $this->userid, ANALYTICS_TYPE_VIEW);

        $data['analytics'] = $this->model_signup_analytics->getUserAnalytics((int) $data['user']['signup_id']);

        $data['follower_count'] = $this->model_signup_follow->getFollowerCount((int) $data['user']['signup_id']);
        $data['followee_count'] = $this->model_signup_follow->getFolloweeCount((int) $data['user']['signup_id']);

        // 'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
        $slots = $this->model_signup_availability->find_all_active(
            array(
                'where' => array(
                    'signup_availability_signup_id' => $id,
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup_availability.signup_availability_requester_id = signup.signup_id',
                        'type'  => 'left'
                    )
                )
            )
        );

        //
        $data['availability_slots'] = array();
        //
        if (!empty($slots)) {
            foreach ($slots as $value) {
                $meeting_recording = array();
                if ($value['signup_availability_meeting_uuid']) {
                    $meeting_recording = $this->getZoomMeetingRecording($value['signup_availability_meeting_uuid']);
                }

                $data['availability_slots'][] = [
                    'id' => $value['signup_availability_id'],
                    'email' => $value['signup_email'],
                    'title' => ($value['signup_availability_purpose'] ?? $value['signup_availability_title']) . ($value['signup_email'] ? ' - Requester: ' . $value['signup_email'] : ''),
                    'purpose' => $value['signup_availability_purpose'],
                    'color' => ($value['signup_availability_type'] == SLOT_LOCKED) ? SLOT_LOCKED_COLOR : SLOT_AVAILABLE_COLOR,
                    'type' => $value['signup_availability_type'],
                    'start' => $value['signup_availability_start'],
                    'end' => $value['signup_availability_end'],
                    'start_url' => $value['signup_availability_meeting_start_url'],
                    'join_url' => $value['signup_availability_meeting_join_url'],
                    'current_status' => $value['signup_availability_meeting_current_status'],
                    'meeting_recording' => $meeting_recording ? json_decode($meeting_recording) : ''
                ];
            }
        }

        // $data['availability_slots'] = $this->model_signup_availability->userAvailabilitySlots($id);

        $signup_credentials = $this->model_signup_credential->find_all_active(
            array(
                'where' => array(
                    'signup_credential_signup_id' =>  $id
                ),
            )
        );
        $data['signup_credentials'] = array();
        foreach ($signup_credentials as $value) {
            $data['signup_credentials'][$value['signup_credential_type']][] = $value;
        }

        //
        $data['review'] = $this->model_review->find_all_active(
            array(
                'limit' => '10',
                'order' => 'review_id DESC',
                'where' => array(
                    'review_type' => REVIEW_TYPE_SIGNUP,
                    'review_reference_id' => $id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = review.review_reference_id',
                        'type' => 'both'
                    )
                )
            )
        );

        //
        $data['review_type'] = REVIEW_TYPE_SIGNUP;

        //
        $data['review_exists'] = $this->model_review->find_one_active(
            array(
                'where' => array(
                    'review_type' => REVIEW_TYPE_SIGNUP,
                    'review_reference_id' => $id,
                    'review_reviewer_id' => $this->userid
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = review.review_reference_id',
                        'type' => 'both'
                    )
                )
            )
        );

        //
        $this->layout_data['title'] = $data['type'] . ' Profile | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * Method users - follow count
     *
     * @param string $reference_id
     * @param int $type
     * @param string $reference_type
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function users(string $reference_id = '', int $type = 0, string $reference_type = FOLLOW_REFERENCE_SIGNUP, int $page = 1, int $limit = PER_PAGE): void
    {
        if ($this->model_signup->hasPremiumPermission()) {
            if ($reference_id) {
                $data = array();

                try {
                    $reference_id = JWT::decode($reference_id, CI_ENCRYPTION_SECRET);
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

                $data['reference_detail'] = array();
                $data['reference_title'] = '';

                switch ($reference_type) {
                    case FOLLOW_REFERENCE_SIGNUP:
                        $data['reference_detail'] = $this->model_signup->find_by_pk((int) $reference_id);
                        $data['reference_title'] = $this->model_signup->profileName($data['reference_detail'], FALSE);
                        break;
                    case FOLLOW_REFERENCE_PRODUCT:
                    case FOLLOW_REFERENCE_SERVICE:
                    case FOLLOW_REFERENCE_TECHNOLOGY:
                        $data['reference_detail'] = $this->model_product->find_one_active(
                            array(
                                'where' => array(
                                    'product_id' => (int) $reference_id
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
                        if (!empty($data['reference_detail']))
                            $data['reference_title'] = $data['reference_detail']['product_name'];
                        break;
                }

                if (empty($data['reference_detail']))
                    error_404();

                $data['page'] = $page;
                $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

                $data['limit'] = $limit;

                // Prev + Next
                $data['prev'] = $page - 1;
                $data['next'] = $page + 1;

                $data['users'] = array();

                switch ($type) {
                    case TYPE_FOLLOWER:
                        $data['users'] = $this->model_signup_follow->getFollower((int) $reference_id, $paginationStart, $limit, $reference_type);
                        $allRecrods = $data['users_count'] = $this->model_signup_follow->getFollowerCount((int) $reference_id, $reference_type);
                        $data['type'] = __(FOLLOWER);
                        $data['type_num'] = __(TYPE_FOLLOWER);
                        //
                        $this->layout_data['title'] = __(FOLLOWER) . ' | ' . $this->layout_data['title'];
                        break;
                    case TYPE_FOLLOWEE:
                        $data['users'] = $this->model_signup_follow->getFollowee((int) $reference_id, $paginationStart, $limit, $reference_type);
                        $allRecrods = $data['users_count'] = $this->model_signup_follow->getFolloweeCount((int) $reference_id, $reference_type);
                        $data['type'] = __(FOLLOWEE);
                        $data['type_num'] = __(TYPE_FOLLOWEE);
                        //
                        $this->layout_data['title'] = __(FOLLOWEE) . ' | ' . $this->layout_data['title'];
                        break;
                    default:
                        error_404();
                }

                // Calculate total pages
                $data['totalPages'] = ceil($allRecrods / $limit);

                $data['reference_id'] = $reference_id;
                $data['reference_type'] = $reference_type;

                //
                $this->load_view("users", $data);
            } else {
                error_404();
            }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * create
     *
     * @return void
     */
    public function create(): void
    {
        $data = array();

        $data['signup_experience'] = $this->model_signup_credential->find_all_active(
            array(
                'where' => array(
                    'signup_credential_signup_id' => $this->userid,
                    'signup_credential_type' => 'experience'
                )
            )
        );
        $data['signup_education'] = $this->model_signup_credential->find_all_active(
            array(
                'where' => array(
                    'signup_credential_signup_id' => $this->userid,
                    'signup_credential_type' => 'education'
                )
            )
        );
        $data['signup_license'] = $this->model_signup_credential->find_all_active(
            array(
                'where' => array(
                    'signup_credential_signup_id' => $this->userid,
                    'signup_credential_type' => 'license'
                )
            )
        );
        $data['signup_certificate'] = $this->model_signup_credential->find_all_active(
            array(
                'where' => array(
                    'signup_credential_signup_id' => $this->userid,
                    'signup_credential_type' => 'certificate'
                )
            )
        );
        $data['signup_publication'] = $this->model_signup_credential->find_all_active(
            array(
                'where' => array(
                    'signup_credential_signup_id' => $this->userid,
                    'signup_credential_type' => 'publication'
                )
            )
        );

        $data['job_type'] = $this->model_job_type->find_all_active();

        $data['job_category'] = $this->model_job_category->find_all_active();

        $data['job_category_array'] = $this->model_job_category->find_all_list_active(array(), 'job_category_name');

        $data['organization_type'] = $this->model_organization_type->find_all_active();

        $data['language'] = $this->model_language->find_all_active();

        $this->register_plugins("select2");

        //
        $this->layout_data['title'] = 'Create Profile | ' . $this->layout_data['title'];
        //
        $this->load_view("create", $data);
    }

    /**
     * Method update
     *
     * @return void
     */
    public function update(): void
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $file_error = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
                if (!empty($_POST)) {
                    $error = false;
                    $errorMessage = __(ERROR_MESSAGE);

                    $updated_param = $_POST['signup'];
                    if (isset($updated_param['signup_phone'])) {
                        if (
                            empty($this->model_signup->find_one_active(
                                array(
                                    'where' => array(
                                        'signup_id !=' => $this->userid,
                                        'signup_phone' => $updated_param['signup_phone'],
                                        'signup_isdeleted' => STATUS_INACTIVE,
                                    )
                                )
                            ))
                        ) {
                            $error = false;
                        } else {
                            $error = true;
                            $errorMessage = __("The Phone number is already associated with another account.");
                        }
                    }
                    if (isset($updated_param['signup_language'])) {
                        $updated_param['signup_language'] = serialize($_POST['signup']['signup_language']);
                    }

                    if (!$error) {
                        $upload = STATUS_FALSE;
                        $name = '';
                        if (isset($_FILES['signup_video']) && $_FILES['signup_video']['error'] == 0) {
                            $upload = STATUS_TRUE;

                            // Get temp file
                            $tmp = $_FILES['signup_video']['tmp_name'];
                            // Generate file name
                            $name = mt_rand() . $_FILES['signup_video']['name'];
                        }

                        // Remove old file
                        if (!empty($this->userdata['signup_video']) && $upload) {
                            unlink($this->config->item('site_upload_signup') . basename($this->userdata['signup_video']));
                        }

                        if ($upload) {
                            $upload_path = $this->config->item('site_upload_signup');

                            // Set data
                            $updated_param['signup_video'] = $name;
                            $updated_param['signup_logo_image_path'] = $upload_path;

                            // Upload new file
                            if (move_uploaded_file($tmp, $upload_path . $name)) {
                                $file_error = STATUS_FALSE;
                            } else {
                                $file_error = STATUS_TRUE;
                            }
                        }

                        $updated = $this->model_signup->update_by_pk($this->userid, $updated_param);

                        if ($updated > 0) {
                            // notification
                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PROFILE_UPDATE, 0, NOTIFICATION_PROFILE_UPDATE_COMMENT);

                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __("The record has been updated successfully" . ($file_error ? ' with an attachment error.' : '.'));
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE_UPTODATE);
                        }
                    } else {
                        $json_param['txt'] = $errorMessage;
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * delete profile
     *
     * @return void
     */
    public function delete(): void
    {
        $error = false;

        if ($_POST && $_POST['id'] && $this->userid > 0) {
            $userid = $_POST['id'];
            $signup = $this->model_signup->find_by_pk($userid);

            if ($signup) {

                // cancel subscription before profile delete
                if (
                    isset($signup['signup_subscription_id'])
                    && $signup['signup_subscription_id'] != NULL
                    && isset($signup['signup_membership_status'])
                    && $signup['signup_membership_status'] == SUBSCRIPTION_ACTIVE
                ) {
    
                    $checkoutSessionId = $signup['signup_subscription_id'];
                    //
                    $query = 'SELECT * FROM `fb_order`';
                    $query .= ' where order_user_id = ' . $signup['signup_id'];
                    $query .= ' AND (order_transaction_id = "' . $checkoutSessionId . '")';
                    //
                    $order = ($this->db->query($query)->row_array());
    
                    if ($order) {
    
                        $subscriptionId = $this->user_data['signup_subscription_id'];
                        $subscriptionDetails = '';
    
                        switch ($order['order_merchant']) {
                            case STRIPE:
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
                                    if ($subscriptionDetails->canceled_at == NULL) {
                                        try {
                                            $subscriptionDetails = $this->stripe->subscriptions->cancel(
                                                $subscriptionId,
                                                []
                                            );
                                        } catch(\Exception $e) {
                                            $errorMessage = $e->getMessage();
                                        }
                                    }
                                }
                        }
    
                        //
                        $this->model_order->update_by_pk(
                            $order['order_id'],
                            array(
                                'order_payment_status' => PAYMENT_STATUS_CANCELLED
                            )
                        );
    
                        //
                        $update_array = array(
                            'signup_membership_id' => ROLE_1,
                            'signup_type' => ROLE_1,
                            'signup_membership_status' => SUBSCRIPTION_ACTIVE,
                            'signup_subscription_status' => SUBSCRIPTION_ACTIVE,
                            'signup_subscription_response' => str_replace('Stripe\Subscription JSON:', '', (string) $subscriptionDetails),
                            'signup_subscription_current_period_start' => NULL,
                            'signup_subscription_current_period_end' => NULL,
                        );
    
                        $updated = $this->model_signup->update_by_pk(
                            $this->userid,
                            $update_array
                        );
                    }
                }
                // cancel subscription before profile delete
    
                $param = array();
                $param['signup_status'] = STATUS_FALSE;
                $param['signup_deletedon'] = date("Y-m-d H:i:s");
                $param['signup_isdeleted'] = STATUS_ACTIVE;

                $updated = $this->model_signup->update_by_pk($userid, $param);
                if ($updated) {
                    $this->session->unset_userdata('userdata');
                    $this->session->set_flashdata('success', __('Account Deleted'));
                    $json_param['txt'] = __("Account deleted successfully.");
                    $json_param['status'] = STATUS_TRUE;
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                    $json_param['status'] = STATUS_FALSE;
                }
            } else {
                $json_param['txt'] = __("Requested user doesn't exist.");
                $json_param['status'] = STATUS_FALSE;
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE);
            $json_param['status'] = STATUS_FALSE;
        }
        echo json_encode($json_param);
    }

    /**
     * Method update_image profile
     *
     * @return void
     */
    public function update_image(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        $user_id = $this->userid;
        $upload = STATUS_FALSE;
        $error = STATUS_FALSE;
        $errorMessage = __(ERROR_MESSAGE);

        $json_param['status'] = STATUS_FALSE;

        $upload_path = $this->config->item('site_upload_signup');

        $data = array(
            'signup_logo_image' => '',
            'signup_logo_image_path' => $upload_path,
        );

        if ($user_id != null) {

            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0 && $_FILES['file']['size'] < MAX_FILE_SIZE) {
                $upload = STATUS_TRUE;

                // Get temp file
                $tmp = $_FILES['file']['tmp_name'];
                // Generate file name
                $name = mt_rand() . $_FILES['file']['name'];

                // Set data
                $data = array(
                    'signup_logo_image' => $name,
                    'signup_logo_image_path' => $upload_path,
                );
            }

            // Remove old file
            if (!empty($this->userdata['signup_logo_image']) && $upload) {
                unlink($this->config->item('site_upload_signup') . basename($this->userdata['signup_logo_image']));
            }

            if ($upload) {
                // Upload new file
                if (move_uploaded_file($tmp, $upload_path . $name)) {
                    $error = STATUS_FALSE;
                    $errorMessage = "";
                } else {
                    $error = STATUS_TRUE;
                    $errorMessage = __(ERROR_MESSAGE_FILE_UPLOAD);
                }
            }

            if ($error) {
                $inserted_id = 0;
            } else {
                $inserted_id = $this->model_signup->update_by_pk($user_id, $data);
            }

            if ($inserted_id > 0) {
                // notification
                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PROFILE_IMAGE_UPDATE, 0, NOTIFICATION_PROFILE_IMAGE_UPDATE_COMMENT);

                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("Changes have been saved.");
            } else {
                $json_param['txt'] = $errorMessage;
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE);
        }

        echo json_encode($json_param);
    }

    /**
     * Method saveVideo profile
     *
     * @return void
     */
    public function saveVideo(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        $user_id = $this->userid;
        $upload = STATUS_FALSE;
        $error = STATUS_TRUE;
        $errorMessage = __(ERROR_MESSAGE);
        $no_attachment = FALSE;

        $json_param['status'] = STATUS_FALSE;

        $upload_path = $this->config->item('site_upload_signup');

        $data = array(
            'signup_video' => '',
        );

        if ($user_id != null) {

            if (isset($_FILES['signup_video']) && $_FILES['signup_video']['error'] == 0) {
                $upload = STATUS_TRUE;

                // Get temp file
                $tmp = $_FILES['signup_video']['tmp_name'];
                // Generate file name
                $name = mt_rand() . $_FILES['signup_video']['name'];

                // Set data
                $data = array(
                    'signup_video' => $name,
                );
            } else {
                $no_attachment = TRUE;
            }

            // Remove old file
            if (!empty($this->userdata['signup_video']) && $upload) {
                unlink($this->config->item('site_upload_signup') . basename($this->userdata['signup_video']));
            }

            if ($upload) {
                // Upload new file
                if (move_uploaded_file($tmp, $upload_path . $name)) {
                    $error = STATUS_FALSE;
                    $errorMessage = "";
                } else {
                    $error = STATUS_TRUE;
                    $errorMessage = __(ERROR_MESSAGE_FILE_UPLOAD);
                }
            }

            if ($error) {
                $inserted_id = 0;
            } else {
                $inserted_id = $this->model_signup->update_by_pk($user_id, $data);
            }

            if ($inserted_id > 0) {
                // notification
                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PROFILE_VIDEO_UPDATE, 0, NOTIFICATION_PROFILE_VIDEO_UPDATE_COMMENT);

                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("Changes have been saved.");
            } else {
                if ($no_attachment) {
                    $json_param['txt'] = 'Attach a video to upload!';
                } else {
                    $json_param['txt'] = $errorMessage;
                }
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE);
        }

        echo json_encode($json_param);
    }

    /**
     * Method deleteVideo profile
     *
     * @return void
     */
    function deleteVideo(): void
    {

        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $userId = $_POST['id'];

                $param = array();
                $param['where']['signup_id'] = $userId;
                $signupDetail = $this->model_signup->find_one($param);

                if (!empty($signupDetail)) {
                    $affect_param = array();
                    $param_name = isset($_POST['param']) && $_POST['param'] ? $_POST['param'] : '';
                    if ($param_name) {
                        $affect_param[$param_name] = '';
                    } else {
                        $affect_param['signup_video'] = '';
                    }

                    $affected = $this->model_signup->update_by_pk($userId, $affect_param);
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
     * Method update_credentials profile
     *
     * @return void
     */
    public function update_credentials()
    {
        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0) {
                if (!empty($_POST)) {
                    $affected_count = 0;
                    $insert = 0;
                    $affect_param = $_POST['signup_credential'];

                    if (is_multi_array($affect_param)) {
                        for ($i = 0; $i <= array_key_last($affect_param); $i++) {

                            if (isset($affect_param[$i]) && !empty($affect_param[$i])) {

                                $insert_param = $affect_param[$i];

                                if (isset($_FILES['signup_credential']) && isset($_FILES['signup_credential']['name'][$i]['signup_credential_attachment'])) {
                                    if (isset($_FILES['signup_credential']['error'][$i]['signup_credential_attachment']) && $_FILES['signup_credential']['error'][$i]['signup_credential_attachment'] == 0 && $_FILES['signup_credential']['size'][$i]['signup_credential_attachment'] < MAX_FILE_SIZE) {
                                        $tmp = $_FILES['signup_credential']['tmp_name'][$i]['signup_credential_attachment'];
                                        $ext = pathinfo($_FILES['signup_credential']['name'][$i]['signup_credential_attachment'], PATHINFO_EXTENSION);
                                        $name = mt_rand() . '.' . $ext;
                                        $upload_path = 'assets/uploads/signup_credential/';

                                        if (move_uploaded_file($tmp, $upload_path . $name)) {
                                            $insert_param['signup_credential_attachment'] = $name;
                                            $insert_param['signup_credential_attachment_path'] = $upload_path;
                                        }
                                    }
                                }

                                if (isset($insert_param['signup_credential_id'])) {
                                    $pk = $insert_param['signup_credential_id'];
                                    unset($insert_param['signup_credential_id']);
                                    $affected = $this->model_signup_credential->update_by_pk($pk, $insert_param);
                                } else {
                                    $affected = $this->model_signup_credential->insert_record($insert_param);
                                    if ($affected)
                                        $insert++;
                                }
                                //
                                if ($affected) {
                                    $affected_count++;
                                }
                            }
                        }
                    } else if (!is_multi_array($affect_param)) {
                        $insert_param = $affect_param;
                        if (isset($insert_param['signup_credential_id'])) {
                            $pk = $insert_param['signup_credential_id'];
                            unset($insert_param['signup_credential_id']);
                            $affected = $this->model_signup_credential->update_by_pk($pk, $insert_param);
                        } else {
                            $affected = $this->model_signup_credential->insert_record($insert_param);
                            if ($affected)
                                $insert++;
                        }
                        //
                        if ($affected) {
                            $affected_count++;
                        }
                    }

                    if ($affected_count >= $insert) {
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
                $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method update_info profile
     *
     * @return void
     */
    public function update_info(): void
    {
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (!empty($_POST) && $this->userid > 0) {
                $updated_param = $_POST['signup_info'];

                if (isset($updated_param['signup_info_availablity_status'])) {
                    switch ($updated_param['signup_info_availablity_status']) {
                        case 'on':
                            $updated_param['signup_info_availablity_status'] = STATUS_TRUE;
                            break;
                        case 'off':
                            $updated_param['signup_info_availablity_status'] = STATUS_FALSE;
                            break;
                    }
                }

                $updated = $this->model_signup_info->update_model(
                    array(
                        'where' => array(
                            'signup_info_signup_id' => $this->userid
                        ),
                    ),
                    $updated_param
                );

                if ($updated > 0) {
                    // notification
                    $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_SETTING_UPDATE, 0, NOTIFICATION_SETTING_UPDATE_COMMENT);

                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = __("The record has been updated successfully.");
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method setting
     *
     * @return void
     */
    public function setting(): void
    {
        $data = array();

        $data['subscription_cost'] = 0;
        if (isset($this->user_data['signup_membership_id']) && $this->user_data['signup_type'] != ROLE_1) {
            // COST_ATTRIBUTE = 2, from databse, table = fb_membership_attribute
            $data['subscription_cost'] = $this->model_membership_pivot->raw_pivot_value((int) $this->user_data['signup_membership_id'], COST_ATTRIBUTE);
        }

        $data['availability_slots'] = $this->model_signup_availability->userAvailabilitySlots($this->userid);

        //
        $this->layout_data['title'] = 'Profile Setting | ' . $this->layout_data['title'];
        //
        $this->load_view("setting", $data);
    }

    /**
     * testimonial
     *
     * @param int $userId
     *
     * @return void
     */
    public function testimonial(string $userId = ''): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data = array();

            try {
                $data['userId'] = $userId = $userId ? JWT::decode($userId, CI_ENCRYPTION_SECRET) : $this->userid;
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

            $data['signup_testimonial'] = $this->model_signup_testimonial->find_all_active(
                array(
                    'where' => array(
                        'signup_testimonial_signup_id' => (int) $userId
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = signup_testimonial.signup_testimonial_signup_id',
                            'type' => 'both'
                        )
                    )
                )
            );

            $data['signup'] = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_id' => (int) $data['userId'],
                        'signup_isdeleted' => STATUS_INACTIVE
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup_company',
                            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                            'type' => 'left'
                        )
                    )
                )
            );

            if (empty($data['signup'])) {
                error_404();
            }

            //
            $this->layout_data['title'] = 'Testimonial | ' . $this->layout_data['title'];
            //
            $this->load_view("testimonial", $data);
        } else {
            $this->session->set_flashdata('error', ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            redirect(l('dashboard'));
        }
    }

    /**
     * Method update_testimonial profile
     *
     * @return void
     */
    public function update_testimonial(): void
    {
        if (!empty($_FILES) && $this->userid > 0) {
            $somError = false;
            $errorMessage = __(ERROR_MESSAGE);

            if (isset($_FILES['signup_testimonial']) && count($_FILES['signup_testimonial']['name']) > 0) {
                for ($i = 0; $i < count($_FILES['signup_testimonial']['name']); $i++) {
                    if (isset($_FILES['signup_testimonial']['error'][$i]) && $_FILES['signup_testimonial']['error'][$i] == 0) {
                        $tmp = $_FILES['signup_testimonial']['tmp_name'][$i];
                        $name = mt_rand() . $_FILES['signup_testimonial']['name'][$i];
                        $upload_path = 'assets/uploads/signup_testimonial/';

                        if (!move_uploaded_file($tmp, $upload_path . $name)) {
                            $somError = TRUE;
                        } else {
                            $this->model_signup_testimonial->insert_record(
                                array(
                                    'signup_testimonial_signup_id' => $this->userid,
                                    'signup_testimonial_attachment' => $name,
                                    'signup_testimonial_attachment_path' => $upload_path,
                                )
                            );
                        }

                        // notification
                        $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_TESTIMONIAL_ADDED, 0, NOTIFICATION_TESTIMONIAL_ADDED_COMMENT);

                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __("Testimonials added") . ($somError ? ' with an error.' : ' successfully.');
                    }
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = $errorMessage;
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
        }
        echo json_encode($json_param);
    }

    /**
     * Method get_testimonial
     *
     * @return void
     */
    public function get_testimonial(): void
    {
        $userId = $this->userid;
        if (isset($_POST['userid'])) {
            $userId = $_POST['userid'];
        }
        $signup_testimonial = $this->model_signup_testimonial->find_all_active(
            array(
                'where' => array(
                    'signup_testimonial_signup_id' => $userId
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = signup_testimonial.signup_testimonial_signup_id',
                        'type' => 'both'
                    )
                )
            )
        );
        foreach ($signup_testimonial as $key => $value) {
            $file_path = get_image($value['signup_testimonial_attachment_path'], $value['signup_testimonial_attachment']);
            // $file_size = str_replace(Array("\n", "\r", "\n\r"), '', $file_path);
            // $size = filesize($file_size),
            $size = get_remote_file_info($file_path);
            $file_list[] = array('name' => $value['signup_testimonial_attachment'], 'size' => $size['fileSize'], 'path' => $file_path);
        }
        echo json_encode($file_list);
    }

    /**
     * Method remove_testimonial
     *
     * @return void
     */
    public function remove_testimonial()
    {
        $userId = 0;
        if (isset($_POST['userId'])) {
            $userId = $_POST['userId'];
        }

        $signup_testimonial = $this->model_signup_testimonial->find_one_active(
            array(
                'where' => array(
                    'signup_testimonial_signup_id' => $userId,
                    'signup_testimonial_attachment' => $_POST['signup_testimonial_attachment']
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = signup_testimonial.signup_testimonial_signup_id',
                        'type' => 'both'
                    )
                )
            )
        );

        $updated = 0;
        if (!empty($signup_testimonial)) {
            $updated = $this->model_signup_testimonial->update_by_pk($signup_testimonial['signup_testimonial_id'], array('signup_testimonial_status' => 0));
            if ($updated) {
                if ($_POST['signup_testimonial_attachment']) {
                    // notification
                    $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_TESTIMONIAL_REMOVED, 0, NOTIFICATION_TESTIMONIAL_REMOVED_COMMENT);

                    unlink($signup_testimonial['signup_testimonial_attachment_path'] . basename($signup_testimonial['signup_testimonial_attachment']));
                }
            }
        }
        echo json_encode(array('status' => $updated));
    }

    /**
     * reset_password
     *
     * @return void
     */
    public function reset_password(): void
    {
        $data = array();

        if ($this->user_data['signup_is_phone_confirmed']) {
            //
            $this->layout_data['title'] = 'Reset Password | ' . $this->layout_data['title'];
            //
            $this->load_view("reset_password", $data);
        } else {
            $this->session->set_flashdata('error', __('You need confirm your phone number before resetting your password!'));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method update_password
     *
     * @return void
     */
    public function update_password(): void
    {
        $post = $_POST;

        if ($this->userid > 0) {
            $user = $this->model_signup->find_by_pk($this->userid);

            $hash = $user['signup_password'];

            if (isset($user) and array_filled($user) && password_verify($post['old_pass'], $hash)) {

                if ($post['new_pass'] == $post['confirm_pass']) {
                    $update_param['signup_password'] = password_hash($post['new_pass'], PASSWORD_BCRYPT);
                    if (isset($user['signup_password_updated']) && !$user['signup_password_updated'] && $user['signup_social_id']) {
                        // saving the password set on first registration
                        $update_param['signup_password_updated'] = 1;
                        $update_param['signup_previous_password'] = $hash;
                    }
                    $row = $this->model_signup->update_by_pk($this->userid, $update_param);
                    if ($row) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __("Password has been updated successfully!");
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __("Please try different password");
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __("Confirmation password is incorrect");
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __("Old password is incorrect");
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
        }

        echo json_encode($json_param);
    }

    /**
     * subscription
     *
     * @return void
     */
    public function subscription(): void
    {
        $data = array();

        $data['subscription_cost'] = 0;
        $data['signup_subscription_response'] = NULL;

        if (isset($this->user_data['signup_membership_id']) && $this->user_data['signup_type'] != ROLE_1 && isset($this->user_data['signup_subscription_response']) && $this->user_data['signup_subscription_response']) {
            $signup_subscription_response = json_decode($this->user_data['signup_subscription_response']);
            $data['signup_subscription_response'] = $this->resource('subscriptions', $signup_subscription_response->id);
        }

        //
        $this->layout_data['title'] = 'Subscription | ' . $this->layout_data['title'];
        //
        $this->load_view("subscription", $data);
    }

    /**
     * Method cancel_subscription - working method for subscription cancellation
     *
     * @return void
     */
    public function cancel_subscription(): void
    {
        global $config;
        $error = false;
        $errorMessage = __(ERROR_MESSAGE);

        if ($this->userid > 0) {
            // 1. active membership, 0. inactive, 2. cancelled
            if (
                isset($this->user_data['signup_subscription_id'])
                && $this->user_data['signup_subscription_id'] != NULL
                && isset($this->user_data['signup_membership_status'])
                && $this->user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE
            ) {

                $checkoutSessionId = $this->user_data['signup_subscription_id'];
                //
                $query = 'SELECT * FROM `fb_order`';
                $query .= ' where order_user_id = ' . $this->userid;
                $query .= ' AND (order_transaction_id = "' . $checkoutSessionId . '")';

                //
                $order = ($this->db->query($query)->row_array());

                if ($order) {
                    $subscriptionId = $this->user_data['signup_subscription_id'];
                    $subscriptionDetails = '';

                    switch ($order['order_merchant']) {
                        case STRIPE:
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
                                if ($subscriptionDetails->canceled_at == NULL) {
                                    try {

                                        $subscriptionDetails = $this->stripe->subscriptions->update(
                                            $subscriptionId,
                                            [
                                                "cancel_at_period_end" => true
                                            ]
                                        );

                                        // $subscriptionDetails = $this->stripe->subscriptions->cancel(
                                        //     $subscriptionId,
                                        //     []
                                        // );

                                        if ($subscriptionDetails->canceled_at != NULL) {
                                            // update signup
                                            // downgrade to general user
                                            $updated = $this->downgradeSubscription($subscriptionDetails);
                                            if ($updated) {

                                                // mail to admin
                                                try {
                                                    $to = g('db.admin.support_email');
                                                    $subject = $_POST['subject'] ?? $config['title'] . ' - Subscription Cancellation Alert!';
                                                    $message = 'Dear site administrator, A user has cancelled subscription on your website on : .' . date('d M, Y h:i a');
                                                    $title = 'User Detail';
                                                    $form_input = [
                                                        'firstname' => htmlentities(trim($this->user_data['signup_firstname'])),
                                                        'lastname' => htmlentities(trim($this->user_data['signup_lastname'])),
                                                        'email' => htmlentities(trim($this->user_data['signup_email']))
                                                    ];
                                                    $this->model_email->fireEmail($to, '', $subject, $message, $title, $form_input);
                                                } catch (Exception $e) {
                                                    log_message('ERROR', $e->getMessage());
                                                }

                                                // notification
                                                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_MEMBERSHIP_CANCELLED, 0, NOTIFICATION_MEMBERSHIP_CANCELLED_COMMENT);

                                                $this->session->set_flashdata('success', __('Your subscription has been cancelled!'));
                                                redirect(l('dashboard/profile/subscription'));
                                            } else {
                                                $this->session->set_flashdata('error', __(ERROR_MESSAGE));
                                                redirect(l('dashboard/profile/subscription'));
                                            }
                                        } else {
                                            $this->session->set_flashdata('error', __(ERROR_MESSAGE));
                                            redirect(l('dashboard/profile/subscription'));
                                        }
                                    } catch (\Exception $e) {
                                        $this->session->set_flashdata('error', $e->getMessage());
                                        redirect(l('dashboard/profile/subscription'));
                                    }
                                } else {
                                    $this->session->set_flashdata('error', __('Your subscription has already been cancelled!'));
                                    redirect(l('dashboard/profile/subscription'));
                                }
                            } else {
                                $this->downgradeSubscription(NULL);
                                $this->session->set_flashdata('error', $errorMessage);
                                redirect(l('dashboard/profile/subscription'));
                            }
                            break;
                        case PAYPAL:

                            $subscription_id = '';

                            $url = PAYPAL_URL . PAYPAL_SUBSCRIPTION_URL . '/' . $subscriptionId;
                            //
                            $headers = array();
                            $headers[] = 'Content-Type: application/json';
                            $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                            //
                            $subscription = $this->paypalResource($url, $headers, [], FALSE);
                            if (property_exists($subscription, 'message')) {
                                log_message('ERROR', $subscription->message);
                                $error = TRUE;
                            } else {
                                $subscription_id = $subscription->id;
                            }

                            if ($subscription_id) {

                                if ($subscription->status != 'CANCELLED' || $subscription->status == 'ACTIVE') {
                                    $url = PAYPAL_URL . str_replace('{subscription_id}', $subscriptionId, PAYPAL_SUBSCRIPTION_CANCEL_URL);

                                    //
                                    $headers = array();
                                    $headers[] = 'Content-Type: application/json';
                                    $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                                    //
                                    $body = array(
                                        "reason" => "Not satisfied with the service",
                                    );

                                    //
                                    $cancel_subscription = $this->paypalResource($url, $headers, $body);

                                    if ($this->session->userdata('last_http_status') == 204) {
                                        // success
                                        $updated = $this->downgradeSubscription($subscription, PAYPAL);

                                        if ($updated) {
                                            // notification
                                            $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_MEMBERSHIP_CANCELLED, 0, NOTIFICATION_MEMBERSHIP_CANCELLED_COMMENT);

                                            $this->session->set_flashdata('success', __('Your subscription has been cancelled!'));
                                            redirect(l('dashboard/profile/subscription'));
                                        } else {
                                            $this->session->set_flashdata('error', __(ERROR_MESSAGE));
                                            redirect(l('dashboard/profile/subscription'));
                                        }
                                    } else {
                                        $this->session->set_flashdata('error', ERROR_MESSAGE);
                                        redirect(l('dashboard/profile/subscription'));
                                    }
                                } else {
                                    $this->session->set_flashdata('error', __('Your subscription has already been cancelled!'));
                                    redirect(l('dashboard/profile/subscription'));
                                }
                            } else {
                                $this->session->set_flashdata('error', ERROR_MESSAGE_INVALID_PAYLOAD);
                                redirect(l('dashboard/profile/subscription'));
                            }
                            break;
                        default:
                            $this->session->set_flashdata('error', ERROR_MESSAGE_INVALID_PAYLOAD);
                            redirect(l('dashboard/profile/subscription'));
                    }
                } else {
                    $this->session->set_flashdata('error', ERROR_MESSAGE_INVALID_PAYLOAD);
                    redirect(l('dashboard/profile/subscription'));
                }
            } else {
                $this->session->set_flashdata('error', __('Your account do not have any active subscription.'));
                redirect(l('dashboard/profile/subscription'));
            }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE));
            redirect(l('dashboard/profile/subscription'));
        }
    }

    /**
     * force_cancel_subscription function - for user with invalid subscription id
     *
     * @return void
     */
    function force_cancel_subscription(): void
    {
        $error = false;

        if ($this->userid > 0) {
            if (
                isset($this->user_data['signup_subscription_id'])
                && $this->user_data['signup_subscription_id'] != NULL
                && isset($this->user_data['signup_membership_status'])
                && $this->user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE
            ) {

                $checkoutSessionId = $this->user_data['signup_subscription_id'];
                //
                $query = 'SELECT * FROM `fb_order`';
                $query .= ' where order_user_id = ' . $this->userid;
                $query .= ' AND (order_transaction_id = "' . $checkoutSessionId . '")';

                //
                $order = ($this->db->query($query)->row_array());

                if ($order) {

                    $subscriptionId = $this->user_data['signup_subscription_id'];
                    $subscriptionDetails = '';

                    switch ($order['order_merchant']) {
                        case STRIPE:
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
                                if ($subscriptionDetails->canceled_at == NULL) {
                                    try {
                                        $subscriptionDetails = $this->stripe->subscriptions->cancel(
                                            $subscriptionId,
                                            []
                                        );
                                        $subscriptionDetails = str_replace('Stripe\Subscription JSON:', '', (string) $subscriptionDetails);
                                    } catch(\Exception $e) {
                                        $errorMessage = $e->getMessage();
                                    }
                                }
                            }
                    }

                    //
                    $this->model_order->update_by_pk(
                        $order['order_id'],
                        array(
                            'order_payment_status' => PAYMENT_STATUS_CANCELLED
                        )
                    );

                    //
                    $update_array = array(
                        'signup_membership_id' => ROLE_1,
                        'signup_type' => ROLE_1,
                        'signup_membership_status' => SUBSCRIPTION_ACTIVE,
                        'signup_subscription_status' => SUBSCRIPTION_ACTIVE,
                        'signup_subscription_response' => $subscriptionDetails,
                        'signup_subscription_current_period_start' => NULL,
                        'signup_subscription_current_period_end' => NULL,
                    );

                    $updated = $this->model_signup->update_by_pk(
                        $this->userid,
                        $update_array
                    );

                    if ($updated) {
                        // notification
                        $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_MEMBERSHIP_CANCELLED, 0, NOTIFICATION_MEMBERSHIP_CANCELLED_COMMENT);

                        $this->session->set_flashdata('success', __('Your subscription has been cancelled!'));
                        redirect(l('dashboard/profile/subscription'));
                    } else {
                        $this->session->set_flashdata('error', __(ERROR_MESSAGE));
                        redirect(l('dashboard/profile/subscription'));
                    }
                } else {
                    $this->session->set_flashdata('error', ERROR_MESSAGE_INVALID_PAYLOAD);
                    redirect(l('dashboard/profile/subscription'));
                }
            } else {
                $this->session->set_flashdata('error', __('Your account do not have any active subscription.'));
                redirect(l('dashboard/profile/subscription'));
            }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l('dashboard/profile/subscription'));
        }
    }

    /**
     * Method downgradeSubscription
     *
     * @param object $subscriptionDetails
     *
     * @return int
     */
    private function downgradeSubscription(?object $subscriptionDetails, $merchant = STRIPE): int
    {
        // $update_array = array(
        //     'signup_membership_id' => ROLE_1,
        //     'signup_type' => ROLE_1,
        //     'signup_membership_status' => SUBSCRIPTION_CANCELLED,
        //     'signup_subscription_status' => SUBSCRIPTION_CANCELLED,
        // );

        if ($merchant == STRIPE) {
            $update_array['signup_subscription_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscriptionDetails);
            $updated = $this->model_signup->update_by_pk(
                $this->userid,
                $update_array
            );
        } else if ($merchant == PAYPAL) {
            $updated = TRUE;
        }

        $this->model_order->update_model(
            array(
                'where' => array(
                    'order_transaction_id' => $this->user_data['signup_subscription_id']
                )
            ),
            array(
                'order_payment_status' => PAYMENT_STATUS_CANCELLED
            )
        );

        return $updated;
    }

    /**
     * promotions function
     *
     * @return void
     */
    public function promotions()
    {
        $data = array();
        $data['promotions'] = $this->model_signup_promotion->find_all_active(
            array(
                'order' => 'signup_promotion_id desc',
                'where' => array(
                    'signup_promotion_signup_id' => $this->userid
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = signup_promotion.signup_promotion_signup_id',
                        'type'  => 'both'
                    )
                )
            )
        );
        //
        $this->layout_data['title'] = 'Promotions | ' . $this->layout_data['title'];
        //
        $this->load_view('promotions', $data);    
    }

    /**
     * availPromotion function
     *
     * @return void
     */
    function availPromotion() {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if(isset($_POST['id'])) {
                if($this->user_data['signup_subscription_id']) {

                    $promotion = $this->model_signup_promotion->find_one_active(
                        array(
                            'where' => array(
                                'signup_promotion_id' => $_POST['id'],
                                'signup_promotion_signup_id' => $this->userid,
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = signup_promotion.signup_promotion_signup_id',
                                    'type'  => 'both'
                                )
                            )
                        )
                    );

                    if($promotion) {

                        $subscription = $this->resource('subscriptions', $this->user_data['signup_subscription_id']);

                        if(isset($subscription->items) && isset($subscription->items->data) && $subscription->items->data[0]->id) {
                            $product = $this->createStripeResource('products', [
                                'name' => $promotion['signup_promotion_title'],
                            ]);
                            $price = $this->createStripeResource('prices', [
                                'unit_amount' => $promotion['signup_promotion_price'] * 100,
                                'currency' => DEFAULT_CURRENCY_CODE,
                                'product' => $product->id,
                                'recurring' => array(
                                    'interval' => SUBSCRIPTION_INTERVAL_TYPE,
                                    'interval_count' => SUBSCRIPTION_INTERVAL_1,
                                ),
                            ]);
                            $this->stripe->subscriptionItems->update($subscription->items->data[0]->id, ['price' => $price->id]);

                            $updated = $this->model_signup_promotion->update_by_pk(
                                $promotion['signup_promotion_id'],
                                array(
                                    'signup_promotion_status' => STATUS_AVAILED
                                )
                            );

                            if($updated) {
                                $json_param['status'] = TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            }
                        }
                    } else {
                        $json_param['txt'] = 'The requested offer is no longer available!';
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
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
