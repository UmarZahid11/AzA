<?php

/**
 * Model_job_application
 */
class Model_job_application extends MY_Model
{
    protected $_table    = 'job_application';
    protected $_field_prefix    = 'job_application_';
    protected $_pk    = 'job_application_id';
    protected $_status_field    = 'job_application_status';
    public $relations = array();
    public $pagination_params = array();
    public $dt_params = array();
    public $_per_page    = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "job_application_id, job_application_signup_id, job_application_job_id, job_application_request_status, job_application_status";
        $this->pagination_params['joins'][] = array(
            "table" => "signup",
            "joint" => "signup.signup_id = job_application.job_application_signup_id",
        );
        $this->pagination_params['joins'][] = array(
            "table" => "job",
            "joint" => "job.job_id = job_application.job_application_job_id",
        );

        parent::__construct();
    }

    /**
     * Method jobApplicationStatus
     *
     * @param int $request_status
     *
     * @return void
     */
    public function jobApplicationStatus(int $request_status = 0)
    {
        switch ($request_status) {
            case '0':
                return APPLICATION_PENDING;
                break;
            case '1':
                return APPLICATION_ASSIGNED;
                break;
            case '2':
                return APPLICATION_REJECTED;
                break;
            default:
                return NOT_AVAILABLE;
        }
    }

    /**
     * Method hasSendApplication
     *
     * @param $userid $userid
     * @param $jobId $jobId
     *
     * @return bool|array
     */
    public function hasSendApplication($userid, $jobId, $return_array = FALSE)
    {
        $job_application = $this->model_job_application->find_one_active(
            array(
                'where' => array(
                    'job_application_signup_id' => $userid,
                    'job_application_job_id' => $jobId,
                )
            )
        );
        if (empty($job_application)) {
            if ($return_array) {
                return $job_application;
            } else {
                return false;
            }
        } else {
            if ($return_array) {
                return $job_application;
            } else {
                return true;
            }
        }
    }

    /**
     * Method jobAlreadyAssigned
     *
     * @param int $jobId
     *
     * @return bool
     */
    public function jobAlreadyAssigned($jobId): bool
    {
        $job_applications = $this->model_job_application->find_all_active(
            array(
                'where' => array(
                    'job_application_job_id' => $jobId,
                )
            )
        );

        if (!empty($job_applications)) {
            foreach ($job_applications as $value) {
                if ($value['job_application_request_status'] == 1) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Method jobAssignedToThis
     *
     * @param int $jobId
     * @param int $signupId
     *
     * @return bool
     */
    public function jobAssignedToThis($jobId, $signupId): bool
    {
        $job_application = $this->model_job_application->find_one_active(
            array(
                'where' => array(
                    'job_application_job_id' => $jobId,
                    'job_application_signup_id' => $signupId,
                    'job_application_request_status' => 1,
                )
            )
        );

        if (!empty($job_application)) {
            return true;
        }
        return false;
    }

    /**
     * Method appliedJobsIds
     *
     * @param int $userId
     *
     * @return void
     */
    public function appliedJobsIds($userId)
    {
        $job_application = $this->model_job_application->find_all_list_active(
            array(
                'where' => array(
                    'job_application_signup_id' => $userId,
                )
            ),
            'job_application_job_id'
        );
        return $job_application;
    }

    /*
    * table             Table Name
    * Name              FIeld Name
    * label             Field Label / Textual Representation in form and DT headings
    * type              Field type : hidden, text, textarea, editor, etc etc.
    *                                 Implementation in form_generator.php
    * type_dt           Type used by prepare_datatables method in controller to prepare DT value
    *                                 If left blank, prepare_datatable Will opt to use 'type'
    * type_filter_dt    Used by DT FILTER PREPRATION IN datatables.php
    * attributes        HTML Field Attributes
    * js_rules          Rules to be aplied in JS (form validation)
    * rules             Server side Validation. Supports CI Native rules

    * list_data         For dropdown etc, data in key-value pair that will populate dropdown
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
    *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    */
    public function get_fields($specific_field = "")
    {

        $fields = array(
            'job_application_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_application_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_title' => array(
                'table'   => 'job',
                'name'   => 'job_title',
                'label'   => 'Job',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'signup_fullname' => array(
                'table'   => 'job',
                'name'   => 'signup_fullname',
                'label'   => 'Applicant',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_application_signup_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_application_signup_id',
                'label'   => 'Applicant',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "job_application_signup_id",
                'list_data' => "job_application_signup_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_application_job_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_application_job_id',
                'label'   => 'Job',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "job_application_job_id",
                'list_data' => "job_application_job_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_application_request_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_application_request_status',
                'label'   => 'Request Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_application_request_status",
                'list_data' => array(
                    JOB_PENDING => "<span class='label label-danger'>" . JOB_PENDING_VALUE . "</span>",
                    JOB_APPROVED =>  "<span class='label label-primary'>" . JOB_APPROVED_VALUE . "</span>",
                    JOB_DECLINED =>  "<span class='label label-order-failed'>" . JOB_DECLINED_VALUE .  "</span>",
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'job_application_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_application_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_application_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>Inactive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
