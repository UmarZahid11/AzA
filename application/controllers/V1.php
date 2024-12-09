<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * V1 - API class
 */
class V1 extends MY_Controller
{
    /**
     * json_param
     *
     * @var array
     */
    private $json_param = array(
        'status' => 400,
        'message' => 'Bad Request',
        'query' => '',
        'response' => array(
            'data' => array()
        )
    );

    /**
     * decodedData
     *
     * @var mixed
     */
    private $decodedData = array();

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        //
        if (isset($_GET['_token'])) {
            try {
                $this->decodedData = JWT::decode($_GET['_token'], CI_ENCRYPTION_SECRET);
                if (JWT::encode($this->decodedData, CI_ENCRYPTION_SECRET) !== $_GET['_token']) {
                    http_response_code(400);
                    //
                    echo json_encode($this->json_param);
                    die;
                }
            } catch (\Exception $e) {
                //
                $this->_log_message(
                    LOG_TYPE_SERVER_API,
                    LOG_SOURCE_SERVER,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
                http_response_code(401);
                //
                echo json_encode(
                    array(
                        'status' => 401,
                        'message' => 'Unauthorized',
                        'query' => '',
                        'response' => array(
                            'data' => $e->getMessage()
                        )
                    )
                );
                die;
            }
        } else {
            echo json_encode($this->json_param);
            die;
        }
    }

    /**
     * Method getSignup
     *
     * @return void
     */
    public function getSignup()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => rawurldecode($_GET['email']),
                    ),
                )
            );

            if (!empty($signup_details)) {
                $data = $this->model_signup->find_by_pk($signup_details['signup_id']);

                if (!empty($signup_details)) {
                    http_response_code(200);
                    //
                    $this->json_param = array(
                        'status' => 200,
                        'message' => 'Your request has been processed successfully.',
                        'query' => $this->db->last_query(),
                        'response' => array(
                            'data' => $data,
                        )
                    );
                } else {
                    log_message('ERROR', 'user details not found.');
                }
            } else {
                log_message('ERROR', 'user not found.');
            }
        } else {
            log_message('ERROR', 'invalid payload.');
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method getSignupTestimonial
     *
     * @return void
     */
    public function getSignupTestimonial()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );
            if (!empty($signup_details) && isset($signup_details['signup_id'])) {
                $data = $this->model_signup_testimonial->find_all_active(
                    array(
                        'where' => array(
                            'signup_testimonial_signup_id' => (int) $signup_details['signup_id']
                        )
                    )
                );
                http_response_code(200);
                //
                $this->json_param = array(
                    'status' => 200,
                    'message' => 'Your request has been processed successfully',
                    'query' => $this->db->last_query(),
                    'response' => array(
                        'data' => $data,
                    )
                );
            }
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method getSignupJob
     *
     * @return void
     */
    public function getSignupJob()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );
            if (!empty($signup_details) && isset($signup_details['signup_id'])) {
                $limit = PER_PAGE;
                $page = isset($_GET['offset']) ? $_GET['offset'] : 0;
                $offset = isset($_GET['offset']) ? $_GET['offset'] * $limit : 0;

                $data = $this->model_job->find_all_active(
                    array(
                        'order' => 'job_id DESC',
                        'limit' => $limit,
                        'offset' => $offset,
                        'where' => array(
                            'job_userid' => (int) $signup_details['signup_id']
                        )
                    )
                );
                $allRecrods = $this->model_job->find_count_active(
                    array(
                        'where' => array(
                            'job_userid' => (int) $signup_details['signup_id']
                        )
                    )
                );
                $totalPages = ceil($allRecrods / $limit);
                http_response_code(200);
                //
                $this->json_param = array(
                    'status' => 200,
                    'message' => 'Your request has been processed successfully',
                    'query' => $this->db->last_query(),
                    'response' => array(
                        'data' => $data,
                        'prev' => $page - 1,
                        'next' => $page + 1,
                        'page' => $page,
                        'totalPages' => $totalPages,
                    )
                );
            }
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method getSignupJobApplication
     *
     * @return void
     */
    public function getSignupJobApplication()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );
            if (!empty($signup_details) && isset($signup_details['signup_id'])) {
                $limit = PER_PAGE;
                $page = isset($_GET['offset']) ? $_GET['offset'] : 0;
                $offset = isset($_GET['offset']) ? $_GET['offset'] * $limit : 0;
                $data = $this->model_job_application->find_all_active(
                    array(
                        'order' => 'job_application_id DESC',
                        'limit' => $limit,
                        'offset' => $offset,
                        'where' => array(
                            'job_application_signup_id' => (int) $signup_details['signup_id']
                        ),
                        'joins' => array(
                            0 => array(
                                'table' => 'job',
                                'joint' => 'job.job_id = job_application.job_application_job_id',
                                'type'  => 'both'
                            )
                        )
                    )
                );
                $allRecrods = $this->model_job_application->find_count_active(
                    array(
                        'where' => array(
                            'job_application_signup_id' => (int) $signup_details['signup_id']
                        ),
                        'joins' => array(
                            0 => array(
                                'table' => 'job',
                                'joint' => 'job.job_id = job_application.job_application_job_id',
                                'type'  => 'both'
                            )
                        )
                    )
                );
                $totalPages = ceil($allRecrods / $limit);
                http_response_code(200);
                //
                $this->json_param = array(
                    'status' => 200,
                    'message' => 'Your request has been processed successfully',
                    'query' => $this->db->last_query(),
                    'response' => array(
                        'data' => $data,
                        'prev' => $page - 1,
                        'next' => $page + 1,
                        'page' => $page,
                        'totalPages' => $totalPages,
                    )
                );
            }
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method getJobCategory
     *
     * @return void
     */
    public function getJobCategory()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );
            if (!empty($signup_details) && isset($signup_details['signup_id'])) {
                $data = $this->model_job_category->find_by_pk($_GET['category_id']);
                http_response_code(200);
                //
                $this->json_param = array(
                    'status' => 200,
                    'message' => 'Your request has been processed successfully',
                    'query' => $this->db->last_query(),
                    'response' => array(
                        'data' => $data,
                    )
                );
            }
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method setCalendar
     *
     * @return void
     */
    public function setCalendar()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );

            if (!empty($signup_details) && isset($signup_details['signup_id'])) {
                if (!empty($this->decodedData)) {
                    //
                    $affect_param = array();
                    $affected = 0;
                    $meeting_error = FALSE;
                    $error_message = 'An error occurred while trying to process your request.';

                    //
                    if (property_exists($this->decodedData->data, 'id') && null !== $this->decodedData->data->id) {
                        $signup_availability_id = $this->decodedData->data->id;
                        $signup_availability = $this->model_signup_availability->find_by_pk($signup_availability_id);
                        //
                        if (!empty($signup_availability)) {
                            if (null !== $this->decodedData->data->requester_email) {
                                $requester_email = $this->model_signup->find_by_email($this->decodedData->data->requester_email);
                                if (!empty($requester_email)) {
                                    $affect_param['signup_availability_requester_id'] = $requester_email['signup_id'];
                                }
                            }
                            $affect_param['signup_availability_type'] = null !== $this->decodedData->data->type ? $this->decodedData->data->type : '';
                            $affect_param['signup_availability_purpose'] = null !== $this->decodedData->data->type ? $this->decodedData->data->purpose : '';

                            // create zoom meeting here
                            $post_fields = array(
                                'agenda' => $affect_param['signup_availability_purpose'],
                                'settings' => array(
                                    'auto_recording' => 'cloud',
                                    'contact_email' => $signup_details['signup_email'],
                                    'contact_name' => $this->model_signup->profileName($signup_details),
                                    'join_before_host' => 'true',
                                    'meeting_authentication' => 'false',
                                    'mute_upon_entry' => 'true',
                                ),
                                'start_time' => date('Y-m-d\TH:i:s\Z', strtotime($this->decodedData->data->start)),
                                'type' => 2,
                            );

                            //
                            $headers = $this->getZoomBearerHeader();
                            $url = str_replace('{userId}', 'me', ZOOM_CREATE_MEETING_URL);
                            $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                            $decoded_response = json_decode($response);

                            $affect_param['signup_availability_meeting_response'] = $response;
                            if (($decoded_response && isset($decoded_response->start_url) && NULL !== $decoded_response->start_url) || (in_array($this->session->userdata['last_http_status'], [200, 204], TRUE))) {
                                $affect_param['signup_availability_meeting_start_url'] = $decoded_response->start_url;
                                $affect_param['signup_availability_meeting_join_url'] = $decoded_response->join_url;
                                $affect_param['signup_availability_meeting_uuid'] = $decoded_response->uuid;
                                $affect_param['signup_availability_meeting_fetchid'] = $decoded_response->id;
                            } else {
                                $meeting_error = TRUE;
                                $error_message = (isset($decoded_response->message) && null !== $decoded_response->message) ? 'The zoom functionality is currently unavailable.' : 'An error occurred while trying to process your request.';
                            }
                            //
                            $affected = $this->model_signup_availability->update_by_pk($signup_availability_id, $affect_param);
                        }
                    } else {
                        $affect_param['signup_availability_signup_id'] = $signup_details['signup_id'];
                        $affect_param['signup_availability_title'] = null !== $this->decodedData->data->title ? $this->decodedData->data->title : '';
                        $affect_param['signup_availability_type'] = null !== $this->decodedData->data->type ? $this->decodedData->data->type : '';
                        $affect_param['signup_availability_start'] = null !== $this->decodedData->data->start ? $this->decodedData->data->start : '';
                        $affect_param['signup_availability_end'] = null !== $this->decodedData->data->end ? $this->decodedData->data->end : '';
                        //
                        $affected = $this->model_signup_availability->insert_record($affect_param);
                    }

                    //
                    if ($affected) {

                        //
                        $slots = array();
                        // 'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
                        $slots = $this->model_signup_availability->find_all_active(
                            array(
                                'where' => array(
                                    'signup_availability_signup_id' => $signup_details['signup_id'],
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
                        $data['slots'] = array();
                        //
                        if (!empty($slots)) {
                            foreach ($slots as $key => $value) {
                                $meeting_recording = NULL;
                                try {
                                    if ($value['signup_availability_meeting_uuid']) {
                                        $meeting_recording = $this->getZoomMeetingRecording($value['signup_availability_meeting_uuid']);
                                    }
                                } catch (\Exception $e) {
                                    log_message('ERROR', $e->getMessage());
                                }

                                $data['slots'][] = [
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

                        if ($meeting_error) {
                            http_response_code(400);
                            //
                            $this->json_param = array(
                                'status' => 400,
                                'message' => $error_message,
                                'query' => $this->db->last_query(),
                                'response' => array(
                                    'data' => $data['slots'],
                                )
                            );
                        } else {
                            http_response_code(200);
                            //
                            $this->json_param = array(
                                'status' => 200,
                                'message' => 'Your request has been processed successfully.',
                                'query' => $this->db->last_query(),
                                'response' => array(
                                    'data' => $data['slots'],
                                )
                            );
                        }
                    }
                }
            }
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method getCalendar
     *
     * @return void
     */
    public function getCalendar()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );
            if (!empty($signup_details) && isset($signup_details['signup_id'])) {

                // 'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
                $slots = $this->model_signup_availability->find_all_active(
                    array(
                        'where' => array(
                            'signup_availability_signup_id' => $signup_details['signup_id'],
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
                $data['slots'] = array();
                //
                if (!empty($slots)) {
                    foreach ($slots as $key => $value) {
                        $meeting_recording = NULL;
                        if ($value['signup_availability_meeting_uuid']) {
                            $meeting_recording = $this->getZoomMeetingRecording($value['signup_availability_meeting_uuid']);
                        }

                        $data['slots'][] = [
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

                http_response_code(200);
                //
                $this->json_param = array(
                    'status' => 200,
                    'message' => 'Your request has been processed successfully',
                    'query' => $this->db->last_query(),
                    'response' => array(
                        'data' => $data['slots'],
                    )
                );
            }
        }
        echo json_encode($this->json_param);
        die;
    }

    /**
     * Method updateCalendar
     *
     * @return void
     */
    function updateCalendar()
    {
        if (isset($_GET['email'])) {
            $signup_details = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_email' => $_GET['email'],
                    )
                )
            );
            if (!empty($signup_details) && isset($signup_details['signup_id'])) {
                $slot = $this->model_signup_availability->find_one_active(
                    array(
                        'where' => array(
                            'signup_availability_signup_id' => $signup_details['signup_id'],
                            'signup_availability_id' => $this->decodedData->data->id,
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

                $updated = '';
                $update_param = isset($this->decodedData->data->data) ? $this->decodedData->data->data : [];

                if (!empty($slot) && isset($slot['signup_availability_id'])) {
                    $updated = $this->model_signup_availability->update_by_pk($slot['signup_availability_id'], ['signup_availability_status' => $update_param->signup_availability_status]);
                }

                if ($updated) {
                    http_response_code(200);
                    //
                    $this->json_param = array(
                        'status' => 200,
                        'message' => 'Your request has been processed successfully',
                        'query' => $this->db->last_query(),
                        'response' => array(
                            'data' => [],
                        )
                    );
                }
            }
        }

        echo json_encode($this->json_param);
        die;
    }
}
