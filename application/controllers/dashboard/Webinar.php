<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Webinar
 */
class Webinar extends MY_Controller
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
     * Method save
     *
     * @param string $type - [CREATE, UPDATE]
     * @param int $webinar_id
     *
     * @return void
     */
    public function save(string $type = '', string $webinar_id = ''): void
    {
        if
        (
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

        if ((!in_array($type, [CREATE, UPDATE], TRUE))) {
            error_404();
        }

        try {
            if ($webinar_id)
                $webinar_id = JWT::decode($webinar_id, CI_ENCRYPTION_SECRET);
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
            $updateStatus = TRUE;
            if (!$this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY) || (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) < strtotime(date('Y-m-d H:i:s')))) {
                $updateStatus = $this->updateZoomConfigValue();
            }

            if ($updateStatus) {
                $data = array();

                $data['type'] = $type;
                $data['button_text'] = $type;
                $data['timezones'] = $this->model_timezones->find_all_active();

                if ($webinar_id || $type === UPDATE) {
                    $data['webinar'] = $this->model_webinar->find_one_active(
                        array(
                            'where' => array(
                                'webinar_id' => $webinar_id,
                                'webinar_userid' => $this->userid
                            )
                        )
                    );
                    if (empty($data['webinar'])) {
                        error_404();
                    }
                }

                //
                $this->layout_data['title'] = ucfirst($type) . ' Webinar | ' . $this->layout_data['title'];
                //
                $this->load_view("save", $data);
            } else {
                $this->session->set_flashdata('error', __('Zoom is currently unavailable.'));
                redirect(l('dashboard/webinar/listing'));
            }
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
     *
     * @return void
     */
    public function listing(int $page = 1, int $limit = PER_PAGE)
    {
        if ($this->model_signup->hasPremiumPermission()) {
            $data = array();

            $data['page'] = $page;
            $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            $data['webinars'] = $this->model_webinar->find_all_active(
                array(
                    'where' => array(
                        'webinar_userid' => $this->userid
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = webinar.webinar_userid',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                    ),
                    'order' => 'webinar_id DESC',
                    'offset' => $paginationStart,
                    'limit' => $limit,
                )
            );

            $data['webinars_count'] = $allRecrods = $this->model_webinar->find_count_active(
                array(
                    'where' => array(
                        'webinar_userid' => $this->userid
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = webinar.webinar_userid',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                    ),
                )
            );

            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            if (empty($data['webinars'])) {
                // error_404();
            }

            //
            $this->layout_data['title'] = 'Webinar Listing | ' . $this->layout_data['title'];
            //
            $this->load_view("listing", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method attendance
     *
     * @return void
     */
    public function attendance(int $page = 1, int $limit = PER_PAGE)
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data = array();

            $data['page'] = $page;
            $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;


            $data['webinar_attendance'] = $this->model_webinar_attendance->find_all_active(
                array(
                    'where' => array(
                        'webinar_attendance_userid' => $this->userid
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = webinar_attendance.webinar_attendance_userid',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        2 => array(
                            'table' => 'webinar',
                            'joint' => 'webinar.webinar_id = webinar_attendance.webinar_attendance_id',
                            'type' => 'both'
                        )
                    ),
                    'order' => 'webinar_attendance_id DESC',
                    'offset' => $paginationStart,
                    'limit' => $limit,
                )
            );

            $data['webinar_attendance_count'] = $allRecrods = $this->model_webinar_attendance->find_count_active(
                array(
                    'where' => array(
                        'webinar_attendance_userid' => $this->userid
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = webinar_attendance.webinar_attendance_userid',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                        2 => array(
                            'table' => 'webinar',
                            'joint' => 'webinar.webinar_id = webinar_attendance.webinar_attendance_id',
                            'type' => 'both'
                        )
                    )
                )
            );

            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            $this->layout_data['title'] = 'Webinar Attendance | ' . $this->layout_data['title'];
            //
            $this->load_view("attendance", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method detail
     *
     * @param string $webinar_id
     *
     * @return void
     */
    public function detail(string $webinar_id = '')
    {
        if (!$webinar_id) {
            error_404();
        }

        try {
            $webinar_id = JWT::decode($webinar_id, CI_ENCRYPTION_SECRET);
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

            $data['webinar'] = $this->model_webinar->find_one_active(
                array(
                    'where' => array(
                        'webinar_id' => $webinar_id,
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = webinar.webinar_userid',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        ),
                    )
                )
            );

            if (empty($data['webinar'])) {
                error_404();
            }

            // if the organizer is viewing
            if ($data['webinar']['webinar_userid'] != $this->userid) {
                // error_404();
            }

            //
            $data['webinar_recording'] = NULL;
            // $webinar_response = $this->getZoomwebinarRecording($data['webinar']['webinar_uuid']);
            // if ($webinar_response) {
            //     $data['webinar_recording'] = json_decode($webinar_response);
            // }

            //
            $this->layout_data['title'] = 'Webinar Details | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method processURL
     *
     * @param string $webinar_id
     * @param string $type
     *
     * @return void
     */
    public function processURL(string $webinar_id = '', string $type): void
    {
        if (!$webinar_id) {
            error_404();
        }

        if (!in_array($type, [ZOOM_TYPE_START_WEBINAR, ZOOM_TYPE_JOIN_WEBINAR])) {
            error_404();
        }

        try {
            $webinar_id = JWT::decode($webinar_id, CI_ENCRYPTION_SECRET);
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
            $data['webinar'] = array();
            $data['attendance_type'] = '';
            switch ($type) {
                case ZOOM_TYPE_START_WEBINAR:
                    $data['webinar'] = $this->model_webinar->find_one_active(
                        array(
                            'where' => array(
                                'webinar_userid' => $this->userid,
                                'webinar_id' => $webinar_id
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = webinar.webinar_userid',
                                    'type'  => 'both'
                                ),
                                1 => array(
                                    'table' => 'signup_info',
                                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                    'type' => 'both'
                                ),
                            )
                        )
                    );
                    $data['attendance_type'] = ZOOM_TYPE_ORGANIZER;
                    break;
                case ZOOM_TYPE_JOIN_WEBINAR:
                    $data['webinar'] = $this->model_webinar->find_one_active(
                        array(
                            'where' => array(
                                'webinar_id' => $webinar_id
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'signup',
                                    'joint' => 'signup.signup_id = webinar.webinar_userid',
                                    'type'  => 'both'
                                ),
                                1 => array(
                                    'table' => 'signup_info',
                                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                                    'type' => 'both'
                                ),
                            )
                        )
                    );
                    $data['attendance_type'] = ZOOM_TYPE_ATTENDEE;
                    break;
            }

            if (empty($data['webinar']) || !$data['attendance_type']) {
                error_404();
            }

            if (empty($this->model_webinar_attendance->find_one_active(array('where' => array('webinar_attendance_userid' => $this->userid, 'webinar_attendance_webinar_id' => $webinar_id))))) {
                // insert attendance
                $data['inserted'] = $this->model_webinar_attendance->insert_record(
                    array(
                        'webinar_attendance_userid' => $this->userid,
                        'webinar_attendance_webinar_id' => $webinar_id,
                        'webinar_attendance_type' => $data['attendance_type']
                    )
                );
            } else {
                $data['inserted'] = 1;
            }

            //
            $this->layout_data['title'] = 'Webinar | ' . $this->layout_data['title'];
            //
            $this->load_view("process_url", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method saveData
     *
     * @return void
     */
    public function saveData()
    {
        $json_param = array();
        $webinar_updated = FALSE;
        $successMessage = 'success';
        $errorMessage = 'error';

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                // either user is verified or has special privilege from admin
                if ((($this->user_data['signup_vouched_token']) && ($this->user_data['signup_is_verified'])) || ($this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_IDENTITY))) {
                    if (isset($_POST['webinar'])) {
                        if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
                            if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) < (strtotime(date('Y-m-d H:i a')))) {
                                $this->updateZoomConfigValue();
                            }
                        }

                        $affect_webinar = $_POST['webinar'];

                        $webinar = array();
                        if (validateDate($affect_webinar['webinar_start_time'], 'Y-m-d\TH:i')) {
                            //
                            if (isset($affect_webinar['webinar_id']) && $affect_webinar['webinar_id']) {
                                $param = array();
                                $param['where']['webinar_id'] = $affect_webinar['webinar_id'];
                                $webinar = $this->model_webinar->find_one($param);
                                if (isset($webinar['webinar_fetchid'])) {
                                    $webinar_updated = TRUE;
                                }
                            }

                            //
                            $post_fields = array(
                                'agenda' => isset($affect_webinar['webinar_agenda']) && $affect_webinar['webinar_agenda'] ? $affect_webinar['webinar_agenda'] : '',
                                'duration' => isset($affect_webinar['webinar_duration']) && $affect_webinar['webinar_duration'] ? (int) $affect_webinar['webinar_duration'] : 2,
                                'password' => isset($affect_webinar['webinar_password']) && $affect_webinar['webinar_password'] ? $affect_webinar['webinar_password'] : '',
                                'settings' => array(
                                    'approval_type' => isset($affect_webinar['webinar_approval_type']) ? $affect_webinar['webinar_approval_type'] : 2,
                                    'auto_recording' => isset($affect_webinar['webinar_auto_recording']) ? $affect_webinar['webinar_auto_recording'] : 'none',
                                    'contact_email' => isset($affect_webinar['webinar_contact_email']) ? $affect_webinar['webinar_contact_email'] : $this->user_data['signup_email'],
                                    'contact_name' => isset($affect_webinar['webinar_contact_name']) ? $affect_webinar['webinar_contact_name'] : $this->model_signup->profileName($this->user_data, FALSE),
                                    'mute_upon_entry' => $affect_webinar['webinar_mute_upon_entry'] ? 'true' : 'false',
                                ),
                                'question_and_answer' => array(
                                    'allow_anonymous_questions' => isset($affect_webinar['webinar_contact_name']) && $affect_webinar['webinar_contact_name'] ? $affect_webinar['webinar_contact_name'] : false,
                                    'answer_questions' => isset($affect_webinar['webinar_contact_name']) && $affect_webinar['webinar_contact_name'] ? 'true' : 'false',
                                ),
                                'meeting_authentication' => isset($affect_webinar['webinar_meeting_authentication']) && $affect_webinar['webinar_meeting_authentication'] ? 'true' : 'false',
                                'panelist_authentication' => isset($affect_webinar['webinar_panelist_authentication']) && $affect_webinar['webinar_panelist_authentication'] ? 'true' : 'false',
                                'webinar_practice_session' => isset($affect_webinar['webinar_practice_session']) && $affect_webinar['webinar_practice_session'] ? 'true' : 'false',
                                'start_time' => date('Y-m-d\TH:i:s\Z', strtotime($affect_webinar['webinar_start_time'])),
                                'timezone' => isset($affect_webinar['webinar_timezone']) && $affect_webinar['webinar_timezone'] ? $affect_webinar['webinar_timezone'] : 'Pacific/Midway',
                                'topic' => isset($affect_webinar['webinar_topic']) && $affect_webinar['webinar_topic'] ? $affect_webinar['webinar_topic'] : '',
                                'type' => isset($affect_webinar['webinar_type']) && $affect_webinar['webinar_type'] ? $affect_webinar['webinar_type'] : 5,
                            );
                            //
                            $headers = $this->getZoomBearerHeader();

                            //
                            if ($webinar_updated) {
                                $url = str_replace('{webinarId}', $webinar['webinar_fetchid'], ZOOM_WEBINAR_URL);
                                $response = $this->curlRequest($url, $headers, $post_fields, FALSE, TRUE, REQUEST_PATCH);
                            } else {
                                $url = str_replace('{userId}', 'me', ZOOM_CREATE_WEBINAR_URL);
                                $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                            }
                            $decoded_response = json_decode($response);
                            //

                            if (($decoded_response && isset($decoded_response->start_url) && NULL !== $decoded_response->start_url) || (in_array($this->session->userdata['last_http_status'], [200, 204], TRUE))) {
                                //
                                if ($webinar_updated) {
                                    $affect_webinar['webinar_updatedon'] = date('Y-m-d H:i:s');
                                    $inserted_webinar = $this->model_webinar->update_by_pk($webinar['webinar_id'], $affect_webinar);
                                    $successMessage = __('Webinar updated successfully.');
                                    $errorMessage = __(ERROR_MESSAGE_UPTODATE);
                                } else {
                                    //
                                    if (isset($affect_webinar['webinar_id']) && $affect_webinar['webinar_id']) {
                                        $this->model_webinar->delete_by_pk($affect_webinar['webinar_id']);
                                        unset($affect_webinar['webinar_id']);
                                    }
                                    $affect_webinar['webinar_start_url'] = $decoded_response->start_url;
                                    $affect_webinar['webinar_join_url'] = $decoded_response->join_url;
                                    $affect_webinar['webinar_timezone'] = $decoded_response->timezone;
                                    $affect_webinar['webinar_uuid'] = $decoded_response->uuid;
                                    $affect_webinar['webinar_fetchid'] = $decoded_response->id;
                                    $affect_webinar['webinar_host_id'] = $decoded_response->host_id;
                                    $affect_webinar['webinar_host_email'] = $decoded_response->host_email;
                                    $affect_webinar['webinar_response'] = $response;
                                    $affect_webinar['webinar_userid'] = $this->userid;

                                    $inserted_webinar = $this->model_webinar->insert_record($affect_webinar);
                                    $successMessage = __('The webinar has been created successfully.');
                                    $errorMessage = __(ERROR_MESSAGE);
                                }

                                if ($inserted_webinar) {
                                    // notification
                                    if(!$webinar_updated) {
										foreach($this->model_signup->find_all_active(array('where' => array('signup_type !=' => ROLE_1, 'signup_id !=' => $this->userid))) as $key => $value) {
											$this->model_notification->sendNotification($value['signup_id'], $this->userid, NOTIFICATION_WEBINAR, $inserted_webinar, NOTIFICATION_WEBINAR_COMMENT);
										}
										$this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_WEBINAR_SCHEDULED, $inserted_webinar, NOTIFICATION_WEBINAR_SCHEDULED_COMMENT);
									} else {
										$this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_WEBINAR_UPDATED, $inserted_webinar, NOTIFICATION_WEBINAR_UPDATED_COMMENT);
                                    }

                                    $json_param['status'] = STATUS_TRUE;
                                    $json_param['txt'] = $successMessage;
                                    $json_param['webinar_id'] = $webinar_updated ? JWT::encode($webinar['webinar_id']) : JWT::encode($inserted_webinar);
                                    $json_param['refresh'] = !$webinar_updated;
                                } else {
                                    $json_param['status'] = STATUS_FALSE;
                                    $json_param['txt'] = $errorMessage;
                                    $json_param['refresh'] = STATUS_FALSE;
                                }
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['txt'] = (isset($decoded_response->message) && null !== $decoded_response->message) ? $decoded_response->message : __(ERROR_MESSAGE);
                                $json_param['refresh'] = STATUS_TRUE;
                            }
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __('The webinar start time is invalid.');
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
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
                $webinar_id = $_POST['id'];

                $param = array();
                $param['where']['webinar_id'] = $webinar_id;
                $webinar = $this->model_webinar->find_one($param);

                if (!empty($webinar)) {
                    if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
                        if (strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) < (strtotime(date('Y-m-d H:i:s')))) {
                            $this->updateZoomConfigValue();
                        }
                        //
                        $updateParam = array();
                        $whereParam = array();
                        $updateParam['webinar_status'] = STATUS_DELETE;
                        $updateParam['webinar_deletedon'] = date('Y-m-d H:i:s');
                        $whereParam['where']['webinar_id'] = $webinar['webinar_id'];
                        $updatedWebinar = $this->model_webinar->update_model($whereParam, $updateParam);

                        if ($updatedWebinar) {
                            if ($webinar['webinar_fetchid']) {
                                //
                                $headers = $this->getZoomBearerHeader();
                                $url = str_replace('{webinarId}', $webinar['webinar_fetchid'], ZOOM_WEBINAR_URL);
                                $response = $this->curlRequest($url, $headers, array(), FALSE, TRUE, REQUEST_DELETE);
                                //
                                $decoded_response = json_decode($response);
                            } else {
                                $bypass = TRUE;
                            }

                            //
                            if ($this->session->userdata['last_http_status'] === 204 || $bypass) {
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['txt'] = __(ERROR_MESSAGE);
                            }
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = __("Error in deleting requested webinar.");
                        }
                    } else {
                        $this->session->set_userdata('zoom_intended', JWT::encode(l('dashboard/webinar/listing')));
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
}
