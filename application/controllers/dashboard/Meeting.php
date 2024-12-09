<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Meeting
 */
class Meeting extends MY_Controller
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

    public function index() {
        error_404();
    }

    /**
     * Method save
     *
     * @param string $type - [CREATE, UPDATE]
     * @param string $meeting_reference_id
     * @param int $meeting_id
     *
     * @return void
     */
    public function save(string $type = '', string $meeting_reference_id = '', int $meeting_id = 0, string $meeting_reference_type = MEETING_REFERENCE_APPLICATION): void
    {
        if (
            (
                (
                    // user hasn't verified their identity
                    (!$this->user_data['signup_vouched_token']) ||
                    (!$this->user_data['signup_is_verified'])
                ) &&
                // and admin has made reverification required
                $this->model_config->getConfigValueByVariable('identity_reverification')
            ) &&
            // and the user donot have the special privilege
            !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_IDENTITY)
        ) {
            redirect(l('dashboard/profile/setting?verify=false'));
        }

        if (!$meeting_reference_id || (!in_array($type, [CREATE, UPDATE], TRUE))) {
            error_404();
        }

        try {
            $meeting_reference_id = JWT::decode($meeting_reference_id, CI_ENCRYPTION_SECRET);
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

        if (!in_array($meeting_reference_type, [MEETING_REFERENCE_PRODUCT, MEETING_REFERENCE_APPLICATION]))
            error_404();

        if ($this->model_signup->hasPremiumPermission()) {
            $updateStatus = TRUE;
            if (!$this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY) || strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) < strtotime(date('Y-m-d H:i:s'))) {
                $updateStatus = $this->updateZoomConfigValue();
            }

            if ($updateStatus) {
                $data = array();
                $has_interview_access = TRUE;

                switch ($meeting_reference_type) {
                    case MEETING_REFERENCE_APPLICATION:
                        $data['meeting_reference'] = $this->model_job_application->find_one_active(
                            array(
                                'where' => array(
                                    'job_application_id' => $meeting_reference_id,
                                    'job_userid' => $this->userid
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'job',
                                        'joint' => 'job.job_id = job_application.job_application_job_id',
                                        'type' => 'both'
                                    ),
                                    1 => array(
                                        'table' => 'signup',
                                        'joint' => 'signup.signup_id = job.job_userid',
                                        'type' => 'both'
                                    ),
                                    2 => array(
                                        'table' => 'signup_info',
                                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                        'type'  => 'both'
                                    )
                                )
                            )
                        );
                        break;
                    case MEETING_REFERENCE_PRODUCT:
                        $data['meeting_reference'] = $this->model_product_request->find_one_active(
                            array(
                                'where' => array(
                                    'product_request_id' => $meeting_reference_id,
                                    'product_signup_id' => $this->userid
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'fb_product',
                                        'joint' => 'product.product_id = product_request.product_request_product_id',
                                        'type' => 'both',
                                    ),
                                    1 => array(
                                        'table' => 'fb_signup',
                                        'joint' => 'signup.signup_id = product_request.product_request_signup_id',
                                        'type' => 'both',
                                    )
                                )
                            )
                        );
                        break;
                }

                if (empty($data['meeting_reference'])) {
                    error_404();
                }

                switch ($meeting_reference_type) {
                    case MEETING_REFERENCE_PRODUCT:
                        $data['meeting_reference_id'] = $meeting_reference_id = $data['meeting_reference']['product_request_id'];
                        break;
                    case MEETING_REFERENCE_APPLICATION:
                        if($data['meeting_reference']['job_has_interview_access']) {
                            $has_interview_access = TRUE;
                        } else {
                            $has_interview_access = FALSE;
                        }
                        $data['meeting_reference_id'] = $meeting_reference_id = $data['meeting_reference']['job_application_id'];
                        break;
                }

                if($has_interview_access || 1) {
                    $data['type'] = $type;
                    $data['button_text'] = $type;
                    $data['timezones'] = $this->model_timezones->find_all_active();

                    if ($meeting_id || $type === UPDATE) {
                        $data['meeting'] = $this->model_meeting->find_one_active(
                            array(
                                'where' => array(
                                    'meeting_id' => $meeting_id,
                                    'meeting_signup_id' => $this->userid,
                                    'meeting_reference_type' => $meeting_reference_type
                                )
                            )
                        );

                        if (empty($data['meeting'])) {
                            error_404();
                        }
                    }

                    $data['meeting_reference_type'] = $meeting_reference_type;

                    //
                    $this->layout_data['title'] = ucfirst($type) . ' Meeting | ' . $this->layout_data['title'];
                    //
                    $this->load_view("save", $data);
                } else {
                    $this->session->set_flashdata('error', __('Interview access is required to create a zoom meeting.'));
                    redirect(l('dashboard/meeting/listing/' . JWT::encode($meeting_reference_id) . '/1/' . PER_PAGE . '/' . $meeting_reference_type));
                }
            } else {
                $this->session->set_flashdata('error', __('Zoom is currently unavailable.'));
                redirect(l('dashboard/meeting/listing/' . JWT::encode($meeting_reference_id) . '/1/' . PER_PAGE . '/' . $meeting_reference_type));
            }
            // } else {
            //     //
            //     // $this->session->set_flashdata('error', __(ERROR_MESSAGE));
            //     // redirect(l('dashboard/job/posted'));
            //     $this->session->set_userdata('zoom_intended', l('dashboard/meeting/save/' . $type . '/' . JWT::encode($meeting_reference_id) . '/' . $meeting_id . '/' . $meeting_reference_type));
            //     redirect(ZOOM_OAUTH_AUTHORIZE_URL);
            // }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard/job/posted'));
        }
    }

    /**
     * Method listing
     *
     * @param string $meeting_reference_id
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function listing(string $meeting_reference_id = '', int $page = 1, int $limit = PER_PAGE, string $meeting_reference_type = MEETING_REFERENCE_APPLICATION): void
    {
        if (!$meeting_reference_id) {
            error_404();
        }

        if (!in_array($meeting_reference_type, [MEETING_REFERENCE_PRODUCT, MEETING_REFERENCE_APPLICATION]))
            error_404();

        try {
            $meeting_reference_id = JWT::decode($meeting_reference_id, CI_ENCRYPTION_SECRET);
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

            // $this->model_meeting->find_all_active()

            $data['page'] = $page;
            $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            switch ($meeting_reference_type) {
                case MEETING_REFERENCE_PRODUCT:
                    $join_param = array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = meeting.meeting_signup_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        2 => array(
                            'table' => 'product_request',
                            'joint' => 'product_request.product_request_id = meeting.meeting_reference_id',
                            'type'  => 'both'
                        ),
                        3 => array(
                            'table' => 'product',
                            'joint' => 'product.product_id = product_request.product_request_product_id',
                            'type'  => 'both'
                        )
                    );
                    break;
                case MEETING_REFERENCE_APPLICATION:
                    $join_param = array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = meeting.meeting_signup_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        2 => array(
                            'table' => 'job_application',
                            'joint' => 'job_application.job_application_id = meeting.meeting_reference_id',
                            'type'  => 'both'
                        ),
                        3 => array(
                            'table' => 'job',
                            'joint' => 'job.job_id = job_application.job_application_job_id',
                            'type'  => 'both'
                        )
                    );
            }

            $data['meetings'] = $this->model_meeting->find_all_active(
                array(
                    'where' => array(
                        'meeting_reference_id' => $meeting_reference_id,
                        'meeting_reference_type' => $meeting_reference_type
                        // 'meeting_signup_id' => $this->userid
                    ),
                    'joins' => $join_param,
                    'order' => 'meeting_id DESC',
                    'offset' => $paginationStart,
                    'limit' => $limit,
                )
            );

            $data['meetings_count'] = $allRecrods = $this->model_meeting->find_count_active(
                array(
                    'where' => array(
                        'meeting_reference_id' => $meeting_reference_id,
                        'meeting_reference_type' => $meeting_reference_type
                        // 'meeting_signup_id' => $this->userid
                    ),
                    'joins' => $join_param,
                    'order' => 'meeting_id DESC',
                )
            );

            $data['totalPages'] = ceil($allRecrods / $limit);

            $data['meeting_reference_id'] = $meeting_reference_id;
            $data['meeting_reference'] = array();

            switch ($meeting_reference_type) {
                case MEETING_REFERENCE_PRODUCT:
                    $data['meeting_reference'] = $this->model_product_request->find_one_active(
                        array(
                            'where' => array(
                                'product_request_id' => $meeting_reference_id
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = product_request.product_request_signup_id',
                                    'type'  => 'both'
                                ),
                                1 => array(
                                    'table' => 'signup_info',
                                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                    'type' => 'both'
                                ),
                                2 => array(
                                    'table' => 'signup_company',
                                    'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                                    'type' => 'left'
                                ),
                            ),
                        )
                    );
                    break;
                case MEETING_REFERENCE_APPLICATION:
                    $data['meeting_reference'] = $this->model_job_application->find_one_active(
                        array(
                            'where' => array(
                                'job_application_id' => $meeting_reference_id
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                                    'type'  => 'both'
                                ),
                                1 => array(
                                    'table' => 'signup_info',
                                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                    'type' => 'both'
                                ),
                                2 => array(
                                    'table' => 'signup_company',
                                    'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                                    'type' => 'left'
                                ),
                            ),
                        )
                    );
                    break;
            }

            if (empty($data['meeting_reference']))
                error_404();

            switch ($meeting_reference_type) {
                case MEETING_REFERENCE_PRODUCT:
                    $data['applicant_id'] = $data['meeting_reference']['product_request_signup_id'];
                    break;
                case MEETING_REFERENCE_APPLICATION:
                    $data['applicant_id'] = $data['meeting_reference']['job_application_signup_id'];
                    break;
            }

            $data['meeting_reference_type'] = $meeting_reference_type;

            //
            $this->layout_data['title'] = 'Meeting Listing | ' . $this->layout_data['title'];
            //
            $this->load_view("listing", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method detail
     *
     * @param string $meeting_id
     *
     * @return void
     */
    public function detail(string $meeting_id = ''): void
    {
        if (!$meeting_id) {
            error_404();
        }

        try {
            $meeting_id = JWT::decode($meeting_id, CI_ENCRYPTION_SECRET);
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

            $meeting_details = $this->model_meeting->find_by_pk($meeting_id);

            switch ($meeting_details['meeting_reference_type']) {
                case MEETING_REFERENCE_PRODUCT:
                    $join_param = array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = meeting.meeting_signup_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        2 => array(
                            'table' => 'product_request',
                            'joint' => 'product_request.product_request_id = meeting.meeting_reference_id',
                            'type'  => 'both'
                        ),
                        3 => array(
                            'table' => 'product',
                            'joint' => 'product.product_id = product_request.product_request_product_id',
                            'type'  => 'both'
                        )
                    );
                    break;
                case MEETING_REFERENCE_APPLICATION:
                    $join_param = array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = meeting.meeting_signup_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        2 => array(
                            'table' => 'job_application',
                            'joint' => 'job_application.job_application_id = meeting.meeting_reference_id',
                            'type'  => 'both'
                        ),
                        3 => array(
                            'table' => 'job',
                            'joint' => 'job.job_id = job_application.job_application_job_id',
                            'type'  => 'both'
                        )
                    );
            }

            $data['meeting'] = $this->model_meeting->find_one_active(
                array(
                    'where' => array(
                        'meeting_id' => $meeting_id,
                    ),
                    'joins' => $join_param
                )
            );

            if (empty($data['meeting'])) {
                error_404();
            }

            // if the organizer is viewing
            if ($data['meeting']['meeting_signup_id'] != $this->userid) {
                // error_404();
            }

            switch ($data['meeting']['meeting_reference_type']) {
                case MEETING_REFERENCE_PRODUCT:
                    $data['applicant_id'] = $data['meeting']['product_request_signup_id'];
                    break;
                case MEETING_REFERENCE_APPLICATION:
                    $data['applicant_id'] = $data['meeting']['job_application_signup_id'];
                    break;
            }

            //
            $data['meeting_recording'] = NULL;
            $meeting_response = $this->getZoomMeetingRecording($data['meeting']['meeting_uuid']);
            if ($meeting_response) {
                $data['meeting_recording'] = json_decode($meeting_response);
            }

            //
            $this->layout_data['title'] = 'Meeting details | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method saveData
     *
     * @return void
     */
    public function saveData(): void
    {
        $meeting_updated = FALSE;
        $meeting = array();
        $json_param = array();
        $successMessage = 'success';
        $errorMessage = 'error';

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                // either user is verified or has special privilege from admin
                if ((($this->user_data['signup_vouched_token']) && ($this->user_data['signup_is_verified'])) || ($this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_IDENTITY))) {
                    if (isset($_POST['meeting'])) {
                        if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
                            if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) < (strtotime(date('Y-m-d H:i:s')))) {
                                $this->updateZoomConfigValue();
                            }

                            $affect_meeting = $_POST['meeting'];
                            $affect_meeting['meeting_reference_type'] = isset($affect_meeting['meeting_reference_type']) && in_array($affect_meeting['meeting_reference_type'], [MEETING_REFERENCE_PRODUCT, MEETING_REFERENCE_APPLICATION]) ? $affect_meeting['meeting_reference_type'] : MEETING_REFERENCE_APPLICATION;

                            switch ($affect_meeting['meeting_reference_type']) {
                                case MEETING_REFERENCE_PRODUCT:
                                    $meeting_reference = $this->model_product_request->find_by_pk($affect_meeting['meeting_reference_id']);
                                    break;
                                case MEETING_REFERENCE_APPLICATION:
                                    $meeting_reference = $this->model_job_application->find_by_pk($affect_meeting['meeting_reference_id']);
                                    break;
                            }

                            if (!empty($meeting_reference)) {

                                //
                                if (isset($affect_meeting['meeting_id']) && $affect_meeting['meeting_id']) {

                                    $param = array();
                                    $param['where']['meeting_id'] = $affect_meeting['meeting_id'];

                                    switch ($affect_meeting['meeting_reference_type']) {
                                        case MEETING_REFERENCE_PRODUCT:
                                            $param['joins'] = array(
                                                0 => array(
                                                    'table' => 'fb_product_request',
                                                    'joint' => 'product_request.product_request_id = meeting.meeting_reference_id',
                                                    'type' => 'both'
                                                ),
                                                1 => array(
                                                    'table' => 'fb_product',
                                                    'joint' => 'product.product_id = product_request.product_request_product_id',
                                                    'type' => 'both'
                                                )
                                            );
                                            break;
                                        case MEETING_REFERENCE_APPLICATION:
                                            $param['joins'] = array(
                                                0 => array(
                                                    'table' => 'fb_job_application',
                                                    'joint' => 'job_application.job_application_id = meeting.meeting_reference_id',
                                                    'type' => 'both'
                                                ),
                                                1 => array(
                                                    'table' => 'fb_job',
                                                    'joint' => 'job.job_id = job_application.job_application_job_id',
                                                    'type' => 'both'
                                                )
                                            );
                                            break;
                                    }

                                    $meeting = $this->model_meeting->find_one($param);
                                    if (isset($meeting['meeting_fetchid'])) {
                                        $meeting_updated = TRUE;
                                    }
                                }

                                //
                                $post_fields = array(
                                    'agenda' => isset($affect_meeting['meeting_agenda']) && $affect_meeting['meeting_agenda'] ? $affect_meeting['meeting_agenda'] : '',
                                    'duration' => isset($affect_meeting['meeting_duration']) && $affect_meeting['meeting_duration'] ? (int) $affect_meeting['meeting_duration'] : 2,
                                    'password' => isset($affect_meeting['meeting_password']) && $affect_meeting['meeting_password'] ? $affect_meeting['meeting_password'] : '',
                                    'settings' => array(
                                        'auto_recording' => isset($affect_meeting['meeting_auto_recording']) && $affect_meeting['meeting_auto_recording'] ? $affect_meeting['meeting_auto_recording'] : 'none',
                                        'contact_email' => isset($affect_meeting['meeting_contact_email']) && $affect_meeting['meeting_contact_email'] ? $affect_meeting['meeting_contact_email'] : $this->user_data['signup_email'],
                                        'contact_name' => isset($affect_meeting['meeting_contact_name']) && $affect_meeting['meeting_contact_name'] ? $affect_meeting['meeting_contact_name'] : $this->model_signup->profileName($this->user_data, FALSE),
                                        'join_before_host' => isset($affect_meeting['meeting_join_before_host']) && $affect_meeting['meeting_join_before_host'] ? 'true' : 'false',
                                        'jbh_time' => isset($affect_meeting['meeting_jbh_time']) ? (int) $affect_meeting['meeting_jbh_time'] : 0,
                                        'meeting_authentication' => isset($affect_meeting['meeting_meeting_authentication']) && $affect_meeting['meeting_meeting_authentication'] ? 'true' : 'false',
                                        'mute_upon_entry' => isset($affect_meeting['meeting_mute_upon_entry']) && $affect_meeting['meeting_mute_upon_entry'] ? 'true' : 'false',
                                    ),
                                    'start_time' => date('Y-m-d\TH:i:s\Z', strtotime($affect_meeting['meeting_start_time'])),
                                    'timezone' => isset($affect_meeting['meeting_timezone']) && $affect_meeting['meeting_timezone'] ? $affect_meeting['meeting_timezone'] : 'Pacific/Midway',
                                    'topic' => isset($affect_meeting['meeting_topic']) && $affect_meeting['meeting_topic'] ? $affect_meeting['meeting_topic'] : '',
                                    'type' => isset($affect_meeting['meeting_type']) && $affect_meeting['meeting_type'] ? $affect_meeting['meeting_type'] : 2,
                                );

                                //
                                $headers = $this->getZoomBearerHeader();

                                //
                                if ($meeting_updated) {
                                    $url = str_replace('{meetingId}', $meeting['meeting_fetchid'], ZOOM_MEETING_URL);
                                    $response = $this->curlRequest($url, $headers, $post_fields, FALSE, TRUE, REQUEST_PATCH);
                                } else {
                                    $url = str_replace('{userId}', 'me', ZOOM_CREATE_MEETING_URL);
                                    $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                                }
                                $decoded_response = json_decode($response);
                                //

                                if (($decoded_response && isset($decoded_response->start_url) && NULL !== $decoded_response->start_url) || (in_array($this->session->userdata['last_http_status'], [200, 204], TRUE))) {
                                    //
                                    if ($meeting_updated) {
                                        $affect_meeting['meeting_updatedon'] = date('Y-m-d H:i:s');
                                        $inserted_meeting = $this->model_meeting->update_by_pk($meeting['meeting_id'], $affect_meeting);
                                        $successMessage = __('The meeting has been updated successfully.');
                                        $errorMessage = __(ERROR_MESSAGE_UPTODATE);
                                    } else {
                                        //
                                        if (isset($affect_meeting['meeting_id']) && $affect_meeting['meeting_id']) {
                                            $this->model_meeting->delete_by_pk($affect_meeting['meeting_id']);
                                            unset($affect_meeting['meeting_id']);
                                        }
                                        $affect_meeting['meeting_start_url'] = $decoded_response->start_url;
                                        $affect_meeting['meeting_join_url'] = $decoded_response->join_url;
                                        $affect_meeting['meeting_timezone'] = $decoded_response->timezone;
                                        $affect_meeting['meeting_uuid'] = $decoded_response->uuid;
                                        $affect_meeting['meeting_fetchid'] = $decoded_response->id;
                                        $affect_meeting['meeting_host_id'] = $decoded_response->host_id;
                                        $affect_meeting['meeting_host_email'] = $decoded_response->host_email;
                                        $affect_meeting['meeting_response'] = $response;
                                        $affect_meeting['meeting_signup_id'] = $this->userid;

                                        $inserted_meeting = $this->model_meeting->insert_record($affect_meeting);
                                        $successMessage = __('A meeting has been created successfully.');
                                        $errorMessage = __(ERROR_MESSAGE);
                                    }

                                    if ($inserted_meeting) {
                                        switch ($affect_meeting['meeting_reference_type']) {
                                            case MEETING_REFERENCE_PRODUCT:
                                                $notification_to = $meeting_reference['product_request_signup_id'];
                                                break;
                                            case MEETING_REFERENCE_APPLICATION:
                                                $notification_to = $meeting_reference['job_application_signup_id'];
                                                break;
                                        }
                                        if ($meeting_updated) {
                                            $this->model_notification->sendNotification($notification_to, $this->userid, NOTIFICATION_MEETING, $meeting['meeting_id'], NOTIFICATION_MEETING_COMMENT);
                                        } else {
                                            $this->model_notification->sendNotification($notification_to, $this->userid, NOTIFICATION_MEETING, $inserted_meeting, NOTIFICATION_MEETING_COMMENT);
                                        }

                                        $json_param['status'] = STATUS_TRUE;
                                        $json_param['txt'] = $successMessage;
                                        $json_param['meeting_id'] = $meeting_updated ? JWT::encode($meeting['meeting_id']) : JWT::encode($inserted_meeting);
                                        $json_param['refresh'] = !$meeting_updated;
                                    } else {
                                        $json_param['status'] = STATUS_FALSE;
                                        $json_param['txt'] = $errorMessage;
                                        $json_param['refresh'] = STATUS_FALSE;
                                    }
                                } else {
                                    $json_param['status'] = STATUS_FALSE;
                                    $json_param['txt'] = (isset($decoded_response->message) && null !== $decoded_response->message) ? ERROR_MESSAGE_ZOOM_UNAVAILABLE : __(ERROR_MESSAGE);
                                    $json_param['refresh'] = STATUS_TRUE;
                                }
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                                $json_param['refresh'] = STATUS_FALSE;
                            }
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __(ERROR_MESSAGE);
                            $json_param['refresh'] = STATUS_TRUE;
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                        $json_param['refresh'] = STATUS_FALSE;
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE_VERIFICATION);
                    $json_param['refresh'] = STATUS_FALSE;
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
                $json_param['refresh'] = STATUS_TRUE;
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            $json_param['refresh'] = STATUS_FALSE;
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete
     *
     * @return void
     */
    public function delete()
    {
        $bypass = FALSE;
        $json_param = array();

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $meeting_id = $_POST['id'];

                $param = array();
                $param['where']['meeting_id'] = $meeting_id;
                $param['where']['meeting_reference_type'] = MEETING_REFERENCE_APPLICATION;
                $param['joins'] = array(
                    0 => array(
                        'table' => 'fb_job_application',
                        'joint' => 'job_application.job_application_id = meeting.meeting_reference_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'fb_job',
                        'joint' => 'job.job_id = job_application.job_application_job_id',
                        'type' => 'both'
                    )
                );
                $meeting = $this->model_meeting->find_one($param);

                if (!empty($meeting)) {
                    if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
                        if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) < (strtotime(date('Y-m-d H:i:s')))) {
                            $this->updateZoomConfigValue();
                        }
                        //
                        $updateParam = array();
                        $whereParam = array();
                        $updateParam['meeting_status'] = STATUS_DELETE;
                        $updateParam['meeting_deletedon'] = date('Y-m-d H:i:s');
                        $whereParam['where']['meeting_id'] = $meeting['meeting_id'];
                        $updatedMeeting = $this->model_meeting->update_model($whereParam, $updateParam);

                        if ($updatedMeeting) {
                            if ($meeting['meeting_fetchid']) {
                                //
                                $headers = $this->getZoomBearerHeader();
                                $url = str_replace('{meetingId}', $meeting['meeting_fetchid'], ZOOM_MEETING_URL);
                                $response = $this->curlRequest($url, $headers, array(), FALSE, TRUE, REQUEST_DELETE);
                                //
                                $decoded_response = json_decode($response);
                            } else {
                                $bypass = TRUE;
                            }

                            //
                            if ($this->session->userdata['last_http_status'] === 204 || $bypass) {
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = __("Meeting deleted successfully!");
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['txt'] = __(ERROR_MESSAGE);
                            }
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __("Error in deleting requested meeting.");
                        }
                    } else {
                        $this->session->set_userdata('zoom_intended', JWT::encode(l('dashboard/meeting/listing/' . $meeting['job_application_id'])));
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                        // $json_param['txt'] = __('Zoom authentication required to perform this action.');
                        // $json_param['url'] = (ZOOM_OAUTH_AUTHORIZE_URL);
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
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

    // Meeting request

    function request_listing(int $page = 1, int $limit = PER_PAGE, $userid = '') {
        $data = array();

        if(!$userid) {
            $userid = $this->userid;
        } else {
            try {
                $userid = JWT::decode($userid, CI_ENCRYPTION_SECRET);
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
        $data['userid'] = $userid;

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;


        $data['meeting_requests'] = $this->model_meeting_request->find_all_active(
            array(
                'order' => 'meeting_request_id DESC',
                'offset' => $paginationStart,
                'limit' => $limit,
                'where' => array(
                    'meeting_request_signup_id' => $userid
                )
            )
        );
        $data['meeting_request_count'] = $allRecrods = $this->model_meeting_request->find_count_active(
            array(
                'where' => array(
                    'meeting_request_signup_id' => $userid
                )
            )
        );

        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = 'Meeting requests | ' . $this->layout_data['title'];
        //
        $this->load_view('request/listing', $data);
    }


    /**
     * Method saveMeetingRequest
     *
     * @return void
     */
    function saveMeetingRequest(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $affected = 0;
        $updated = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST) && isset($_POST['meeting_request']['meeting_request_reference_id'])) {

                    $reference = $this->model_product->find_one_active(
                        array(
                            'where' => array(
                                'product_id' => (int) $_POST['meeting_request']['meeting_request_reference_id']
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'fb_signup',
                                    'joint' => 'signup.signup_id = product.product_signup_id',
                                    'type' => 'both'
                                )
                            )
                        )
                    );

                    if (!empty($reference)) {
                        $reference_request = $this->model_product_request->find_one_active(
                            array(
                                'where' => array(
                                    'product_request_id' => (int) $_POST['meeting_request']['meeting_request_reference_request_id'],
                                    'product_request_product_id' => (int) $reference['product_id'],
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'fb_signup',
                                        'joint' => 'signup.signup_id = product_request.product_request_signup_id',
                                        'type' => 'both'
                                    )
                                )
                            )
                        );

                        if($reference_request) {
                            $affect_param = $_POST['meeting_request'];
                            $request_exists = array();

                            if ($this->userid == $reference_request['product_request_signup_id'] && !isset($_POST['meeting_request_id'])) {
                                // pending request exists
                                $request_exists = $this->model_meeting_request->find_one_active(
                                    array(
                                        'where' => array(
                                            'meeting_request_signup_id' => $affect_param['meeting_request_signup_id'],
                                            'meeting_request_reference' => $affect_param['meeting_request_reference'],
                                            'meeting_request_reference_id' => $affect_param['meeting_request_reference_id'],
                                            'meeting_request_reference_request_id' => $affect_param['meeting_request_reference_request_id'],
                                            'meeting_request_current_status != ' => REQUEST_COMPLETE
                                        )
                                    )
                                );
                            }

                            if (empty($request_exists)) {
                                if (isset($_POST['meeting_request_id']) && $_POST['meeting_request_id']) {
                                    $meeting_request_detail = $this->model_meeting_request->find_by_pk($_POST['meeting_request_id']);
                                    if (!empty($meeting_request_detail)) {
                                        $affected = $this->model_meeting_request->update_by_pk(
                                            $_POST['meeting_request_id'],
                                            $affect_param
                                        );
                                        $updated = TRUE;
                                        $requestor_id = $meeting_request_detail['meeting_request_signup_id'];
                                        $meeting_request_id = $meeting_request_detail['meeting_request_id'];
                                    }
                                } else {
                                    $requestor_id = $affect_param['meeting_request_signup_id'];
                                    $affected = $this->model_meeting_request->insert_record($affect_param);
                                    $meeting_request_id = $affected;
                                }

                                if ($affected) {

                                    if ($updated) {
                                        $comment = str_replace('{item}', 'meeting', NOTIFICATION_MEETING_RESPONSE_COMMENT);
                                        if ($requestor_id != $this->userid) {
                                            $this->model_notification->sendNotification($requestor_id, $reference['signup_id'], NOTIFICATION_MEETING_RESPONSE, $reference['product_id'], $comment, '', $reference_request['product_request_id']);
                                        } else {
                                            $this->model_notification->sendNotification($reference['signup_id'], $requestor_id, NOTIFICATION_MEETING_RESPONSE, $reference['product_id'], $comment, '', $reference_request['product_request_id']);
                                        }
                                    } else {
                                        $comment = str_replace('{item}', 'meeting', NOTIFICATION_MEETING_REQUEST_COMMENT);
                                        $this->model_notification->sendNotification($reference['signup_id'], $requestor_id, NOTIFICATION_MEETING_REQUEST, $reference['product_id'], $comment, '', $reference_request['product_request_id']);
                                    }

                                    $json_param['status'] = STATUS_TRUE;
                                    $json_param['txt'] = SUCCESS_MESSAGE;
                                } else {
                                    $json_param['txt'] = ERROR_MESSAGE;
                                }
                            } else {
                                $json_param['txt'] = 'A request has already been sent.';
                            }
                        } else {
                            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
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
}
