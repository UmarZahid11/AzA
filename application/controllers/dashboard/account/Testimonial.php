<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Testimonial
 */
class Testimonial extends MY_Controller
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
     * Method index
     *
     * @return void
     */
    function index(): void
    {
        redirect(l('dashboard/account/testimonial/listing'));
    }

    /**
     * Method listing
     *
     * @param int $page
     * @param int $limit
     * @param string $userid [userid to fetch about]
     * @param int $to [listing for testimonial received to this userid]
     *
     * @return void
     */
    function listing(int $page = 1, int $limit = PER_PAGE, string $userid = '', int $to = 0): void
    {
        $data = array();

        if ($userid) {
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
        } else {
            $userid = $this->userid;
        }

        $data['user'] = $this->model_signup->find_by_pk($userid);

        if (!empty($data['user'])) {
            if((($userid != $this->userid) && $to == 1) || ($userid == $this->userid)) {

                //
                $data['page'] = $page;
                $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

                $data['limit'] = $limit;

                $data['prev'] = $page - 1;
                $data['next'] = $page + 1;
                //

                if($to) {
                    $where_param = array(
                        'account_testimonial_to' => $userid
                    );
                    $join_param = array(
                        0 => array(
                            'table' => 'fb_signup',
                            'joint' => 'signup.signup_id = account_testimonial.account_testimonial_signup_id',
                            'type' => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_company',
                            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                            'type'  => 'left'
                        )
                    );
                } else {
                    $where_param = array(
                        'account_testimonial_signup_id' => $userid
                    );
                    $join_param = array(
                        0 => array(
                            'table' => 'fb_signup',
                            'joint' => 'signup.signup_id = account_testimonial.account_testimonial_to',
                            'type' => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_company',
                            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                            'type'  => 'left'
                        )
                    );
                }

                $data['account_testimonials'] = $this->model_account_testimonial->find_all_active(
                    array(
                        'where' => $where_param,
                        'order' => 'account_testimonial_id DESC',
                        'limit' => $limit,
                        'offset' => $paginationStart,
                        'joins' => $join_param
                    )
                );

                $data['account_testimonials_count'] = $allRecrods = $this->model_account_testimonial->find_count_active(
                    array(
                        'where' => $where_param
                    )
                );

                $data['userid'] = $userid;

                $data['to'] = $to;

                // Calculate total pages
                $data['totalPages'] = ceil($allRecrods / $limit);

                //
                $this->layout_data['title'] = 'Testimonials | ' . $this->layout_data['title'];
                //
                $this->load_view("listing", $data);
            } else {
                $this->session->set_flashdata('error', __(ERROR_MESSAGE));
                redirect(l('dashboard'));
            }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method save
     *
     * @param int $id
     *
     * @return void
     */
    function save(string $to = '', int $id = 0): void
    {
        $data = array();
        $data['account_testimonial'] = [];

        if (!$to) {
            error_404();
        }

        try {
            $to = JWT::decode($to, CI_ENCRYPTION_SECRET);
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

        if ($to == $this->userid) {
            error_404();
        }

        if ($id) {
            $data['account_testimonial'] = $this->model_account_testimonial->find_one_active(
                array(
                    'where' => array(
                        'account_testimonial_to' => $to,
                        'account_testimonial_id' => $id,
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'fb_signup',
                            'joint' => 'signup.signup_id = account_testimonial.account_testimonial_to',
                            'type' => 'both'
                        ),
                    )
                )
            );
        } else {
            $data['account_testimonial'] = $this->model_account_testimonial->find_one_active(
                array(
                    'where' => array(
                        'account_testimonial_to' => $to,
                        'account_testimonial_signup_id' => $this->userid,
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'fb_signup',
                            'joint' => 'signup.signup_id = account_testimonial.account_testimonial_to',
                            'type' => 'both'
                        ),
                    )
                )
            );
        }

        if (empty($data['account_testimonial']) && $id) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE));
            redirect(l('dashboard/account/testimonial/listing'));
        }

        $data['to'] = $to;

        //
        $this->layout_data['title'] = 'Save Testimonial | ' . $this->layout_data['title'];
        //
        $this->load_view("save", $data);
    }

    /**
     * Method saveData
     *
     * @return void
     */
    function saveData(): void
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $attachment_error = FALSE;
        $uptodate_error = FALSE;
        $account_testimonial_id = 0;
        $refresh = FALSE;

        if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['account_testimonial'])) {
                    $affect_data = $_POST['account_testimonial'];

                    if (isset($_FILES['account_testimonial_attachment']['error']) && $_FILES['account_testimonial_attachment']['error'] == 0) {

                        if($_FILES['account_testimonial_attachment']['size'] < MAX_FILE_SIZE) {

                            $tmp = $_FILES['account_testimonial_attachment']['tmp_name'];
                            $ext = pathinfo($_FILES['account_testimonial_attachment']['name'], PATHINFO_EXTENSION);
                            $name = mt_rand() . '.' . $ext;
                            $upload_path = 'assets/uploads/testimonial/';
    
                            if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                $attachment_error = TRUE;
                            } else {
                                $refresh = TRUE;
                                $affect_data['account_testimonial_attachment'] = $name;
                                $affect_data['account_testimonial_attachment_path'] = $upload_path;
                            }
                        } else {
                            $attachment_error = TRUE;
                        }
                    }

                    if (isset($_POST['account_testimonial_id'])) {
                        $account_testimonial_id = $_POST['account_testimonial_id'];
                        $affected = $this->model_account_testimonial->update_by_pk($account_testimonial_id, $affect_data);
                        if(!$refresh) {
                            $refresh = FALSE;
                        }
                        if (!$affected) {
                            $uptodate_error = TRUE;
                        }
                    } else {
                        $refresh = TRUE;
                        $affected = $this->model_account_testimonial->insert_record($affect_data);
                    }

                    if ($affected && !$uptodate_error) {
                        if (!$account_testimonial_id) {
                            $this->model_notification->sendNotification($affect_data['account_testimonial_to'], $this->userid, NOTIFICATION_TESTIMONIAL_RECEIVED, $affected, NOTIFICATION_TESTIMONIAL_RECEIVED_COMMENT);
                            $json_param['redirect_url'] = l('dashboard/account/testimonial/save/' . JWT::encode($affect_data['account_testimonial_to']) . '/' . $affected);
                        }
                        $json_param['status'] = TRUE;
                        $json_param['txt'] = SUCCESS_MESSAGE . ($attachment_error ? ', with attachment error' : '');
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

        $json_param['refresh'] = $refresh;

        echo json_encode($json_param);
    }

    /**
     * Method deleteAttachment
     *
     * @return void
     */
    function deleteAttachment(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);
        $account_testimonial_attachment = '';

        if (isset($_REQUEST['_token']) && $this->verify_csrf_token($_REQUEST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST)) {
                    $testimonialId = $_POST['id'];

                    $param = array();
                    $param['where']['account_testimonial_id'] = $testimonialId;
                    $param['where']['account_testimonial_signup_id'] = $this->userid;
                    $testimonialDetail = $this->model_account_testimonial->find_one($param);

                    if (!empty($testimonialDetail)) {

                        $affect_param = array();
                        $param_name = isset($_POST['param']) && $_POST['param'] ? $_POST['param'] : '';

                        if ($param_name) {
                            $account_testimonial_attachment = $testimonialDetail[$param_name];
                            $affect_param[$param_name] = '';
                        } else {
                            $affect_param['account_testimonial_attachment'] = '';
                        }
                        $affected = $this->model_account_testimonial->update_by_pk($testimonialId, $affect_param);
                        if ($affected) {
                            unlink($testimonialDetail['account_testimonial_attachment_path'] . basename($account_testimonial_attachment));

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
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }
}
