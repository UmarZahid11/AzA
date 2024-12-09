<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Job
 */
class Job extends MY_Controller
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
     * index
     *
     * @return void
     */
    public function index($page = 1, $limit = 9, $categoryId = 0, $search = ""): void
    {
        if($this->userid == 0) {
            error_404();
        }

        $data = array();

        $this->layout_data['title'] = 'Jobs | ' . $this->layout_data['title'];

        $param = array();
        $param['where']['cms_page_name'] = 'job';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        $param = array();
        $param['where']['inner_banner_name'] = 'job';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $param = array();
        $count_param = array();

        $param['order'] = 'job_id DESC';
        $param['offset'] = $paginationStart;
        $param['limit'] = $limit;
        $count_param['where']['job_userid'] = $param['where']['job_userid'] = 0;

        $data['search'] = $search;

        if ($search) {
            $param['where_like'][] = array(
                'column' => 'job_title',
                'value' => $search,
                'type' => 'both',
            );
            $count_param['where_like'][] = array(
                'column' => 'job_title',
                'value' => $search,
                'type' => 'both',
            );
        }
        $job = $this->model_job->find_all_active($param);
        $job_count = $this->model_job->find_all_active($count_param);

        $data['job'] = array();
        $data['job_count'] = array();

        if ($categoryId) {
            foreach ($job as $key => $value) {
                $jobCategories = unserialize($value['job_category']);
                if (is_array($jobCategories) && in_array($categoryId, $jobCategories)) {
                    array_push($data['job'], $value);
                    array_push($data['job_count'], $value);
                }
            }
        } else {
            $data['job'] = $job;
            $data['job_count'] = $job_count;
        }

        $data['job_count'] = count($data['job_count']);
        $allRecrods = $data['job_count'];

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        $data['selected_category'] = $categoryId;
        $data['job_category'] = $this->model_job_category->find_all_active();

        $this->load_view("index", $data);
    }

    /**
     * detail
     *
     * @return void
     */
    public function detail($slug = NULL): void
    {
        if (!$slug) {
            error_404();
        }

        $data = array();

        $data['faqs'] = $this->model_faq->find_all_active();

        $param = array();
        $param['where']['inner_banner_name'] = 'Job Detail';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['job_slug'] = $slug;
        $param['joins'][] = array(
            'table' => 'signup',
            'joint' => 'signup.signup_id = job.job_userid',
            'type' => 'both'
        );
        $param['joins'][] = array(
            'table' => 'signup_company',
            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
            'type' => 'both'
        );
        $data['job'] = $this->model_job->find_one_active($param);

        if (empty($data['job'])) {
            $data['job'] = $this->model_job->find_one_active(
                array(
                    'where' => array(
                        'job_slug' => $slug
                    )
                )
            );
            if (empty($data['job']))
                error_404();
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

        $job_id = $data['job']['job_id'];
        //
        $data['comment'] = $this->model_comment->find_all_active(
            array(
                'order' => 'comment_id DESC',
                'where' => array(
                    'comment_parent_id' => 0,
                    'comment_reference_id' => $job_id,
                    'comment_reference_type' => REFERENCE_TYPE_JOB
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
        $data['reference_id'] = $job_id;

        //
        $data['ideal_candidate'] = array();
        if (isset($data['job']['signup_company_type']) && $data['job']['signup_company_type']) {
            $data['ideal_candidate'] = $this->model_signup->find_all_active(
                array(
                    'where' => array(
                        'signup_type' => ROLE_3,
                        'signup_worktype' => $data['job']['job_type']
                    ),
                    'or_where' => array(
                        'signup_preferred_organization' => $data['job']['signup_company_type']
                    ),
                    'or_where_in' => array(
                        'signup_sciencework' => unserialize($data['job']['job_category'])
                    )
                )
            );
        }

        //
        $this->layout_data['title'] = $data['job']['job_title'] . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * Method apply_job
     *
     * @return void
     */
    public function apply_job(): void
    {
        $somError = FALSE;

        $json_param['status'] = STATUS_FALSE;
        $json_param['show_request_btn'] = STATUS_FALSE;

        if ($this->userid > 0) {
            if ($this->model_signup->hasPremiumPermission()) {
                if ($this->user_data['signup_is_stripe_connected']) {
                    if (isset($_POST['job_id']) && $_POST['job_id'] != 0) {

                        // user has the required number of testimonial or user has the approval from admin or the user has special bypass privilege from admin
                        if (($this->model_signup_testimonial->getSignupTestimonial($this->userid, TRUE) >= MINIMUM_SIGNUP_TESTIMONIAL) || ($this->model_job_testimonial_request->getUseRequestrApprovalById($this->userid)) || ($this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_TESTIMONIAL, TRUE))) {

                            $jobId = $_POST['job_id'];
                            $jobDetails = $this->model_job->find_one_active(
                                array(
                                    'where' => array(
                                        'job_id' => $jobId
                                    ),
                                    'joins' => array(
                                        0 => array(
                                            'table' => 'fb_signup',
                                            'joint' => 'signup.signup_id = job.job_userid',
                                            'type' => 'both'
                                        ),
                                    )
                                )
                            );

                            if (!empty($jobDetails)) {
                                if($this->userid != $jobDetails['job_userid']) {
                                    $jobExpired = TRUE;
                                    if (isset($jobDetails['job_expiry'])) {
                                        if ($jobDetails['job_expiry'] && $jobDetails['job_expiry'] >= date('Y-m-d')) {
                                            $jobExpired = FALSE;
                                        }
                                    }

                                    if (!$jobExpired) {
                                        $job_application = $this->model_job_application->find_one_active(
                                            array(
                                                'where' => array(
                                                    'job_application_signup_id' => $this->userid,
                                                    'job_application_job_id' => $jobId,
                                                )
                                            )
                                        );

                                        $deadlinePassed = FALSE;
                                        if (empty($job_application)) {
                                            // if (isset($jobDetails['job_submission_deadline'])) {
                                            //     if ($jobDetails['job_submission_deadline']) {
                                            //         if ($jobDetails['job_submission_deadline'] >= date('Y-m-d')) {
                                            //             $deadlinePassed = FALSE;
                                            //         } else {
                                            //             $deadlinePassed = TRUE;
                                            //         }
                                            //     } else {
                                            //         $deadlinePassed = FALSE;
                                            //     }
                                            // }

                                            if (!$deadlinePassed) {
                                                $insert_param['job_application_signup_id'] = $this->userid;
                                                $insert_param['job_application_job_id'] = $jobId;
                                                $insert_param['job_application_is_cover_letter_file'] = (isset($_POST['isFile']) && $_POST['isFile']);

                                                $insert_param['job_application_cover_letter'] = '';
                                                if(isset($_POST['isFile']) && $_POST['isFile']) {
                                                    if (isset($_FILES['job_application_cover_letter']['error']) && $_FILES['job_application_cover_letter']['error'] == 0) {
                                                        $tmp = $_FILES['job_application_cover_letter']['tmp_name'];
                                                        $name = mt_rand() . $_FILES['job_application_cover_letter']['name'];
                                                        $upload_path = 'assets/uploads/job_application/';

                                                        if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                                            $somError = TRUE;
                                                        } else {
                                                            $insert_param['job_application_cover_letter'] = $upload_path . $name;
                                                        }
                                                    }
                                                }
                                                if(!$insert_param['job_application_cover_letter']) {
                                                    $insert_param['job_application_cover_letter'] = isset($_POST['job_application_cover_letter']) ? $_POST['job_application_cover_letter'] : '';
                                                }

                                                if (isset($_FILES['job_application_resume']['error']) && $_FILES['job_application_resume']['error'] == 0) {
                                                    $tmp = $_FILES['job_application_resume']['tmp_name'];
                                                    $name = mt_rand() . $_FILES['job_application_resume']['name'];
                                                    $upload_path = 'assets/uploads/job_application/';

                                                    if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                                        $somError = TRUE;
                                                    } else {
                                                        $insert_param['job_application_resume'] = $upload_path . $name;
                                                    }
                                                }

                                                if (isset($_FILES['job_application_attachment']['error']) && $_FILES['job_application_attachment']['error'] == 0) {
                                                    $tmp = $_FILES['job_application_attachment']['tmp_name'];
                                                    $name = mt_rand() . $_FILES['job_application_attachment']['name'];
                                                    $upload_path = 'assets/uploads/job_application/';

                                                    if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                                        $somError = TRUE;
                                                    } else {
                                                        $insert_param['job_application_attachment'] = $name;
                                                        $insert_param['job_application_attachment_path'] = $upload_path;
                                                    }
                                                }

                                                $inserted_application = $this->model_job_application->insert_record($insert_param);

                                                if ($inserted_application) {
                                                    //
                                                    if (isset($_FILES['job_application_attachments']) && count($_FILES['job_application_attachments']['name']) > 0) {
                                                        for ($i = 0; $i < count($_FILES['job_application_attachments']['name']); $i++) {
                                                            if (isset($_FILES['job_application_attachments']['error'][$i]) && $_FILES['job_application_attachments']['error'][$i] == 0) {

                                                                $tmp = $_FILES['job_application_attachments']['tmp_name'][$i];
                                                                $name = mt_rand() . $_FILES['job_application_attachments']['name'][$i];
                                                                $upload_path = 'assets/uploads/job_application/';

                                                                if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                                                    $somError = TRUE;
                                                                } else {
                                                                    $this->model_job_application_attachment->insert_record(
                                                                        array(
                                                                            'job_application_attachment_application_id' => $inserted_application,
                                                                            'job_application_attachment_name' => $name,
                                                                            'job_application_attachment_path' => $upload_path,
                                                                        )
                                                                    );
                                                                }
                                                            }
                                                        }
                                                    }

                                                    //
                                                    if (isset($_FILES['job_question_answer_attachment']) && count($_FILES['job_question_answer_attachment']['name']) > 0) {
                                                        for ($i = 0; $i < count($_FILES['job_question_answer_attachment']['name']); $i++) {

                                                            $name = '';
                                                            $upload_path = '';

                                                            if (isset($_FILES['job_question_answer_attachment']['error'][$i]) && $_FILES['job_question_answer_attachment']['error'][$i] == 0 && $_FILES['job_question_answer_attachment']['size'][$i] < MAX_FILE_SIZE) {

                                                                $tmp = $_FILES['job_question_answer_attachment']['tmp_name'][$i];
                                                                $ext = pathinfo($_FILES['job_question_answer_attachment']['name'][$i], PATHINFO_EXTENSION);
                                                                $name = mt_rand() . '.' . $ext;
                                                                $upload_path = 'assets/uploads/job_question_answer/';

                                                                if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                                                    $somError = TRUE;
                                                                }
                                                            }
                                                            $this->model_job_question_answer->insert_record(
                                                                array(
                                                                    'job_question_answer_question_id' => $_POST['job_question_answer']['job_question_answer_question_id'][$i],
                                                                    'job_question_answer_signup_id' => $this->userid,
                                                                    'job_question_answer_desc' => $_POST['job_question_answer']['job_question_answer_desc'][$i],
                                                                    'job_question_answer_attachment' => $name,
                                                                    'job_question_answer_attachment_path' => $upload_path,
                                                                )
                                                            );
                                                        }
                                                    }

                                                    // notify here
                                                    if (ENVIRONMENT != "development" && $jobDetails['signup_email']) {
                                                        $this->model_email->notification_job_application($jobDetails['signup_email'], $jobDetails['job_title'], $this->model_signup->profileName($this->user_data, FALSE), l('dashboard/application/listing/' . JWT::encode($jobDetails['job_id'])));
                                                    }
                                                    $this->model_notification->sendNotification($jobDetails['job_userid'], $this->userid, NOTIFICATION_JOB_APPLICATION, $inserted_application, NOTIFICATION_JOB_APPLICATION_COMMENT, '', $jobId);

                                                    $json_param['status'] = STATUS_TRUE;
                                                    $json_param['txt'] = __("Job application sent") . ($somError ? ' with an error.' : ' successfully.');
    												$json_param['redirect_url'] = l('dashboard/application/detail/'. JWT::encode($inserted_application) . '/' . $jobDetails['job_id']);
                                                } else {
                                                    $json_param['txt'] = __(ERROR_MESSAGE);
                                                }
                                            } else {
                                                $json_param['txt'] = __("The deadline for application submission has passed.");
                                            }
                                        } else {
                                            $json_param['txt'] = __("A job application has already been sent.");
                                        }
                                    } else {
                                        $json_param['txt'] = __("The requested job has been expired.");
                                    }
                                } else {
                                    $json_param['txt'] = "Cannot apply to your own posted job.";
                                }
                            } else {
                                $json_param['txt'] = "The requested job doesn't exists.";
                            }
                        } else {
                            $json_param['show_request_btn'] = STATUS_TRUE;
                            $json_param['txt'] = __('Your profile lacks the required number of testimonials to apply for this job.');
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_STRIPE_CONNECT_ERROR);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_STRIPE_CONNECT_ERROR);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LOGIN);
        }
        echo json_encode($json_param);
    }

    /**
     * Method assign_job
     *
     * @return void
     */
    public function assign_job(): void
    {
        if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['job_application_id'])) {
                $jobApplicationId = $_POST['job_application_id'];
                $job_application = $this->model_job_application->find_one_active(
                    array(
                        'where' => array(
                            'job_application_id' => $jobApplicationId,
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
                if (!empty($job_application)) {
                    if (isset($job_application['job_userid']) && $job_application['job_userid'] == $this->userid) {

                        $job_organizer = $this->model_signup->find_by_pk($job_application['job_userid']);

                        $assigning = false;
                        $setToPending = false;
                        $update = false;
                        $updated = '';
                        $successMessage = __(SUCCESS_MESSAGE);
                        $url = l('dashboard/application/detail/' . JWT::encode($job_application['job_application_id']) . '/' . $job_application['job_id']);
                        $emailMessage = '';

                        switch (true) {
                            case (isset($_POST['job_application_request_status']) && $_POST['job_application_request_status'] == '0'):
                                $update_param['job_application_request_status'] = $_POST['job_application_request_status'];
                                $successMessage = __('The job has been set to pending successfully.');
                                $emailMessage = __('The job application request for "' . $job_application['job_title'] . '" has been set to pending. <a href="' . $url . '">Click here</a> to view.');
                                $assigning = false;
                                $setToPending = TRUE;
                                break;
                            case (isset($_POST['job_application_request_status']) && $_POST['job_application_request_status'] == 1):
                                $update_param['job_application_request_status'] = $_POST['job_application_request_status'];
                                $successMessage = __('The job has been assigned successfully.');
                                $emailMessage = __('The job application request for "' . $job_application['job_title'] . '" has been approved. <a href="' . $url . '">Click here</a> to view.');
                                $assigning = true;
                                break;
                            case (isset($_POST['job_application_request_status']) && $_POST['job_application_request_status'] == 2):
                                $update_param['job_application_request_status'] = $_POST['job_application_request_status'];
                                $successMessage = __('The job has been un-assigned successfully.');
                                $emailMessage = __('The job application request for "' . $job_application['job_title'] . '" has been declined. <a href="' . $url . '">Click here</a> to view.');
                                $assigning = false;
                                break;
                        }

                        // action is to assign job
                        if ($assigning) {
                            if (!$this->model_job_application->jobAlreadyAssigned($_POST['job_id'])) {
                                $update = true;
                            } else {
                                $errorMessage = __('The requested job has already been assigned.');
                            }
                            // if action is to decline/remove assignment, then check if already been assigned before to this
                        } else {
                            if ($this->model_job_application->jobAssignedToThis($_POST['job_id'], $_POST['job_application_signup_id'])) {
                                $update = true;
                            } else {
                                if($setToPending) {
                                    $update = true;
                                } else {
                                    $errorMessage = __('The requested job hasn\'t been assigned to this user.');
                                }
                            }
                        }

                        if ($update) {
                            $updated = $this->model_job_application->update_by_pk($_POST['job_application_id'], $update_param);
                        }

                        if ($updated) {
                            // notify here
                            if (ENVIRONMENT != 'development' && !empty($job_organizer)) {
                                $this->model_email->notification_job_application_action($job_application['signup_email'], $emailMessage);
                            }

                            if ($assigning) {
                                $this->model_notification->sendNotification($job_application['job_application_signup_id'], $this->userid, NOTIFICATION_JOB_APPLICATION_APPROVED, $job_application['job_application_id'], NOTIFICATION_JOB_APPLICATION_APPROVED_COMMENT, '', $job_application['job_id']);
                            } else {
                                if(!$setToPending) {
                                    $this->model_notification->sendNotification($job_application['job_application_signup_id'], $this->userid, NOTIFICATION_JOB_APPLICATION_DECLINED, $job_application['job_application_id'], NOTIFICATION_JOB_APPLICATION_DECLINED_COMMENT, '', $job_application['job_id']);
                                } else {
                                    $this->model_notification->sendNotification($job_application['job_application_signup_id'], $this->userid, NOTIFICATION_JOB_APPLICATION_PENDING, $job_application['job_application_id'], NOTIFICATION_JOB_APPLICATION_PENDING_COMMENT, '', $job_application['job_id']);
                                }
                            }

                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = $successMessage;
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = isset($errorMessage) && $errorMessage ? $errorMessage : __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
                    }
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
     * Method updateJobTestimonialRequest
     *
     * @return void
     */
    function saveJobTestimonialRequest(): void
    {
        $json_param['status'] = STATUS_FALSE;
        if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {
            if (isset($_POST)) {
                if (empty($this->model_job_testimonial_request->getRequest($this->userid))) {
                    $affected = FALSE;

                    if (!isset($_POST['job_testimonial_request_id'])) {
                        $affected = $this->model_job_testimonial_request->insert_record($_POST['job_testimonial_request']);
                    } else {
                        if (!empty($this->model_job_testimonial_request->find_by_pk($_POST['job_testimonial_request_id']))) {
                            $affected = $this->model_job_testimonial_request->update_by_pk($_POST['job_testimonial_request_id'], $_POST['job_testimonial_request']);
                        }
                    }

                    if ($affected) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __('A request already against your user.');
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * mapbox
     *
     * @return void
     */
    public function mapbox()
    {
        $returnData = array();
        $relevant = array();

        // if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {
            $searchTerm = urlencode($_GET['term']);

            $getUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . $searchTerm . '.json?worldview=cn&access_token=' . MAP_BOX_API_KEY;
            $ch = curl_init();
            $jsonBody = "";

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 80);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                log_message('error', "cURL Error #:" . $err);
            } else {
                $jsonBody = json_decode($response);
            }

            if (property_exists($jsonBody, 'features')) {
                foreach ($jsonBody->features as $value) {
                    if (isset($_GET['validate'])) {
                        if (property_exists($value, 'relevance')) {
                            $relevant[] = $value->relevance;
                        }
                    }
                    $data['id'] = $value->place_name;
                    $data['value'] = $value->place_name;
                    array_push($returnData, $data);
                }
            }
        // }
        if (isset($_GET['validate'])) {
            echo json_encode(array('relevance' => $relevant));
        } else {
            echo json_encode($returnData);
        }
    }
}
