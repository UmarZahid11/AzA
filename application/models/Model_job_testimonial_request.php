<?php

/**
 * Model_job_testimonial_request
 */
class Model_job_testimonial_request extends MY_Model
{
    protected $_table    = 'job_testimonial_request';
    protected $_field_prefix    = 'job_testimonial_request_';
    protected $_pk    = 'job_testimonial_request_id';
    protected $_status_field    = 'job_testimonial_request_status';
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
        $this->pagination_params['fields'] = "job_testimonial_request_id, signup_email as job_testimonial_request_signup_id, job_testimonial_request_status";
        $this->pagination_params['joins'] = array(
            0 => array(
                'table' => 'fb_signup',
                'joint' => 'signup.signup_id = job_testimonial_request.job_testimonial_request_signup_id',
                'type' => 'both'
            )
        );
        parent::__construct();
    }

    /**
     * Method getUseRequestrApprovalById
     *
     * @param int $userid
     *
     * @return bool
     */
    function getUseRequestrApprovalById(int $userid): bool
    {
        if ($this->userid) {
            $job_testimonial_request = $this->model_job_testimonial_request->find_one_active(
                array(
                    'where' => array(
                        'job_testimonial_request_signup_id' => $userid,
                    )
                )
            );

            if (!empty($job_testimonial_request)) {
                if (
                    $job_testimonial_request['job_testimonial_request_current_status'] == REQUEST_ACCEPTED ||
                    ($job_testimonial_request['job_testimonial_request_extention'] &&
                        validateDate($job_testimonial_request['job_testimonial_request_extention'], 'Y-m-d H:i:s') &&
                        (strtotime($job_testimonial_request['job_testimonial_request_extention']) > strtotime(date('Y-m-d H:i:s')))
                    )
                ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Method getRequest
     *
     * @param int $userid
     *
     * @return array
     */
    function getRequest(int $userid): ?array
    {
        return $this->model_job_testimonial_request->find_one_active(
            array(
                'where' => array(
                    'job_testimonial_request_signup_id' => $userid,
                )
            )
        );
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
            'job_testimonial_request_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_testimonial_request_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_testimonial_request_signup_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_testimonial_request_signup_id',
                'label'   => 'Signup',
                'type'   => 'select_readonly',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'list_data_key' => "job_testimonial_request_signup_id",
                'list_data' => $this->model_signup->find_all_list_active(array(), 'signup_email'),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_testimonial_request_desc' => array(
                'table'   => $this->_table,
                'name'   => 'job_testimonial_request_desc',
                'label'   => 'Request Description',
                'type'   => 'textarea_readonly',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_testimonial_request_current_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_testimonial_request_current_status',
                'label'   => 'Request Status',
                'type'   => 'select',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_testimonial_request_current_status",
                'list_data' => array(
                    REQUEST_PENDING => "<span class='label label-danger'>Pending</span>",
                    REQUEST_ACCEPTED =>  "<span class='label label-primary'>Accepted</span>",
                    REQUEST_REJECTED =>  "<span class='label label-primary'>Rejected</span>",
                    REQUEST_EXTENDED =>  "<span class='label label-primary'>Extended</span>",
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'job_testimonial_request_extention' => array(
                'table'   => $this->_table,
                'name'   => 'job_testimonial_request_extention',
                'label'   => 'Extension until allowed to apply',
                'type'   => 'date2',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_testimonial_request_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_testimonial_request_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_testimonial_request_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
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
