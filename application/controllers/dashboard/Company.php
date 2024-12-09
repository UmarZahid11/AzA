<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Company
 */
class Company extends MY_Controller
{
    /**
     * dataService
     *
     * @var mixed
     */
    protected $dataService;

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
     * create_company_profile
     *
     * @return void
     */
    public function create(): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            global $config;

            $data = array();

            $data['job_type'] = $this->model_job_type->find_all_active();
            $data['job_category'] = $this->model_job_category->find_all_active();

            $data['job_category_array'] = $this->model_job_category->find_all_list_active(array(), 'job_category_name');

            $data['organization_type'] = $this->model_organization_type->find_all_active();

            //
            $this->layout_data['title'] = 'Create Company Profile | ' . $this->layout_data['title'];
            //
            $this->load_view("create", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method update
     *
     * @return void
     */
    public function update(): void
    {
        if (!empty($_POST) && $this->userid > 0) {

            $updated = 0;
            $updated_param = $_POST['signup_company'];

            $companyExists = $this->model_signup_company->find_one_active(
                array(
                    'where' => array(
                        'signup_company_signup_id' =>  $this->userid,
                    )
                )
            );

            $error = false;
            $errorMessage = '';

            switch (true) {
                //
                case (isset($updated_param['signup_company_representative_phone']) && !$this->unique_representative_phone($updated_param['signup_company_representative_phone'])):
                    $error = true;
                    $errorMessage = __("The Phone number is already associated with another account.");
                    break;
                    //
                case (isset($updated_param['signup_company_representative_email']) && !$this->unique_representative_email($updated_param['signup_company_representative_email'])):
                    $error = true;
                    $errorMessage = __("The email is already associated with another account.");
                    break;
                    //
                case (isset($updated_param['signup_company_slug']) && !$this->unique_company_slug($updated_param['signup_company_slug'])):
                    $error = true;
                    $errorMessage = __("The company name is already associated with another account.");
                    break;
            }

            if (!$error) {
                if (!empty($companyExists)) {
                    // can't update primary key ..
                    unset($updated_param['signup_company_signup_id']);
                    $updated = $this->model_signup_company->update_by_pk($companyExists['signup_company_id'], $updated_param);
                } else {
                    $updated = $this->model_signup_company->insert_record($updated_param);
                }
            }

            if ($updated > 0) {
                // notification
                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_COMPANY_PROFILE_UPDATE, 0, NOTIFICATION_COMPANY_PROFILE_UPDATE_COMMENT);

                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __(SUCCESS_MESSAGE);
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = $errorMessage ? $errorMessage : __(ERROR_MESSAGE_UPTODATE);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method unique_representative_phone
     *
     * @param string $str
     *
     * @return bool
     */
    public function unique_representative_phone($str): bool
    {
        $param = array();
        $param['where']['signup_company_representative_phone'] = $str;
        $param['where']['signup_company_signup_id !='] = $this->userid;
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        $param['joins'][] = array(
            'table' => 'signup',
            'joint' => 'signup.signup_id = signup_company.signup_company_signup_id',
            'type' => 'both'
        );
        if (empty($this->model_signup_company->find_one_active($param))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method unique_representative_email
     *
     * @param string $str
     *
     * @return bool
     */
    public function unique_representative_email($str): bool
    {
        $param = array();
        $param['where']['signup_company_representative_email'] = $str;
        $param['where']['signup_company_signup_id !='] = $this->userid;
        $param['joins'][] = array(
            'table' => 'signup',
            'joint' => 'signup.signup_id = signup_company.signup_company_signup_id',
            'type' => 'both'
        );
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        if (empty($this->model_signup_company->find_one_active($param))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method unique_company_slug
     *
     * @param string $str
     *
     * @return bool
     */
    public function unique_company_slug(string $str): bool
    {
        $param = array();
        $param['where']['signup_company_slug'] = $str;
        $param['where']['signup_company_signup_id !='] = $this->userid;
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        $param['joins'][] = array(
            'table' => 'signup',
            'joint' => 'signup.signup_id = signup_company.signup_company_signup_id',
            'type' => 'both'
        );
        if (empty($this->model_signup_company->find_one_active($param))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method update_image
     *
     * @return void
     */
    public function update_image(): void
    {
        $user_id = $this->userid;
        $upload = STATUS_FALSE;
        $error = STATUS_FALSE;
        $errorMessage = __(ERROR_MESSAGE);

        $this->json_param['status'] = STATUS_FALSE;

        // Get upload path
        $upload_path = 'assets/uploads/signup_company/';

        $data = array(
            'signup_company_image' => '',
            'signup_company_image_path' => $upload_path,
        );

        if (($user_id != null)) {
            if ((isset($_FILES['file']) && $_FILES['file']['error'] == 0) && $_FILES['file']['size'] < MAX_FILE_SIZE) {

                $upload = STATUS_TRUE;

                // Get temp file
                $tmp = $_FILES['file']['tmp_name'];
                // Generate file name
                $name = mt_rand() . $_FILES['file']['name'];

                // Set data
                $data = array(
                    'signup_company_image' => $name,
                    'signup_company_image_path' => $upload_path,
                );
            }

            // Remove old file
            if (!empty($this->userdata['signup_company_image']) && $upload) {
                unlink($this->config->item('site_upload_signup_company') . basename($this->userdata['signup_company_image']));
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
                $companyExists = $this->model_signup_company->find_one_active(
                    array(
                        'where' => array(
                            'signup_company_signup_id' =>  $this->userid,
                        )
                    )
                );
                
                if($companyExists) {
                    $inserted_id = $this->model_signup_company->update_model(
                        array('where' => array('signup_company_signup_id' => $user_id)),
                        $data
                    );
                } else {
                    $inserted_id = $this->model_signup_company->insert_record($data);
                }

            }

            if ($inserted_id > 0) {
                // notification
                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_COMPANY_PROFILE_IMAGE_UPDATE, 0, NOTIFICATION_COMPANY_PROFILE_IMAGE_UPDATE_COMMENT);

                $this->json_param['status'] = STATUS_TRUE;
                $this->json_param['txt'] = __("Changes have been saved.");
            } else {
                $this->json_param['txt'] = $errorMessage;
            }
        } else {
            $this->json_param['txt'] = __(ERROR_MESSAGE);
        }

        echo json_encode($this->json_param);
    }
}
