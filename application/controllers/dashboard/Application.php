<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Application
 */
class Application extends MY_Controller
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
     * @param string $jobId
     * @param int $userid
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function listing(string $jobId = '', int $userid = 0, int $page = 1, int $limit = PER_PAGE): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            if (!$userid)
                $userid = $this->userid;

            if (empty($this->model_signup->find_by_pk($userid))) {
                error_404();
            }

            $data['userid'] = $userid;

            if (!$jobId) {
                $this->session->set_flashdata('error', 'Requested job doesn\'t exists!');
                redirect(l('dashboard/job/posted'));
            }

            //
            try {
                $jobId = JWT::decode($jobId, CI_ENCRYPTION_SECRET);
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

            $job_details = $this->model_job->find_by_pk($jobId);

            if (empty($job_details)) {
                $this->session->set_flashdata('error', 'Requested job doesn\'t exists!');
                redirect(l('dashboard/job/posted'));
            }

            $data['job_details'] = $job_details;

            $data['page'] = $page;
            $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            $data['job_applications'] = $this->model_job_application->find_all_active(
                array(
                    'joins' => array(
                        0 => array(
                            'table' => 'job',
                            'joint' => 'job.job_id = job_application.job_application_job_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                            'type'  => 'both'
                        ),
                        2 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type'  => 'both'
                        )
                    ),
                    'where' => array(
                        'job_id' => $jobId,
                        'job_userid' => $userid
                    ),
                    'offset' => $paginationStart,
                    'limit' => $limit,
                )
            );

            $data['job_applications_count'] = $allRecrods = $this->model_job_application->find_count_active(
                array(
                    'joins' => array(
                        0 => array(
                            'table' => 'job',
                            'joint' => 'job.job_id = job_application.job_application_job_id',
                            'type'  => 'both'
                        ),
                        1 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = job_application.job_application_signup_id',
                            'type'  => 'both'
                        ),
                        2 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type'  => 'both'
                        )
                    ),
                    'where' => array(
                        'job_id' => $jobId,
                        'job_userid' => $userid
                    )
                )
            );

            $data['existed_email'] = true;
            $data['type'] = "Job Application";

            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            $this->layout_data['title'] = $data['type'] . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("listing", $data);
        } else {
            error_404();
        }
    }

    /**
     * Method job_application_details
     *
     * @param string $jobApplicationId
     * @param int $jobId
     *
     * @return void
     */
    public function detail(string $jobApplicationId = '', int $jobId = 0): void
    {
        if (!$this->model_signup->hasPremiumPermission()) {
            error_404();
        }
        if (!$jobApplicationId || !$jobId) {
            error_404();
        }

        try {
            $jobApplicationId = JWT::decode($jobApplicationId, CI_ENCRYPTION_SECRET);
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

        $data['job_application'] = $this->model_job_application->find_one_active(
            array(
                'where' => array(
                    'job_application_id' => (int) $jobApplicationId
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
                        'type'  => 'both'
                    ),
                    2 => array(
                        'table' => 'job',
                        'joint' => 'job.job_id = job_application.job_application_job_id',
                        'type'  => 'both'
                    ),
                )
            )
        );

        if (empty($data['job_application'])) {
            $this->session->set_flashdata('error', __('The requested job application doesn\'t exists.'));
            redirect(l('dashboard/application/listing/' . JWT::encode($jobId)));
        }

        $data['job_application_attachment'] = $this->model_job_application_attachment->find_all_active(
            array(
                'where' => array(
                    'job_application_attachment_application_id' => $jobApplicationId
                )
            )
        );

        $data['job_milestone'] = $this->model_job_milestone->find_all_active(
            array(
                'where' => array(
                    'job_milestone_job_id' => $jobId,
                    'job_milestone_application_id' => $jobApplicationId
                ),
                'order' => 'job_milestone_id DESC',
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
                )
            )
        );

        $data['job_question'] = $this->model_job_question->find_all_active(
            array(
                'where' => array(
                    'job_question_job_id' => $jobId,
                    'job_question_answer_signup_id' => $data['job_application']['job_application_signup_id'],
                ),
                'joins' => array(
                    '0' => array(
                        'table' => 'job_question_answer',
                        'joint' => 'job_question_answer.job_question_answer_question_id = job_question.job_question_id',
                        'type' => 'left'
                    )
                )
            )
        );

        $data['job_milestone_payment'] = $this->model_job_milestone_payment->find_all_active(
            array(
                'where' => array(
                    'job_milestone_job_id' => $jobId,
                    // 'job_application_id' => $data['job_application']['job_application_id']
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'job_milestone',
                        'joint' => 'job_milestone.job_milestone_id = job_milestone_payment.job_milestone_payment_milestone_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'job_application',
                        'joint' => 'job_milestone.job_milestone_application_id = job_application.job_application_id',
                        'type' => 'both'
                    )
                )
            )
        );

        //
        $this->layout_data['title'] = 'Job Application Details | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * Method delete
     *
     * @return void
     */
    function delete() {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        $error = FALSE;

        if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['id'])) {
                    $jobId = $_POST['id'];
                    $applicationDetail = $this->model_job_application->find_one_active(
                        array(
                            'where' => array(
                                'job_application_job_id' => $jobId,
                                'job_application_signup_id' => $this->userid,
                            )
                        )
                    );

                    if($applicationDetail) {
                        $affect_param['job_application_status'] = STATUS_INACTIVE;
                        $affected = $this->model_job_application->update_by_pk($applicationDetail['job_application_id'], $affect_param);
                        if($affected) {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }

        echo json_encode($json_param);
    }
}