<?php

/**
 * Model_job_question
 */
class Model_job_question extends MY_Model
{
    protected $_table    = 'job_question';
    protected $_field_prefix    = 'job_question_';
    protected $_pk    = 'job_question_id';
    protected $_status_field    = 'job_question_status';
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
        $this->pagination_params['fields'] = "job_question_id, job_question_job_id, job_question_title, job_question_status";
        $this->pagination_params['joins'] = array(
            0 => array(
                'table' => 'job',
                'joint' => 'job.job_id = job_question.job_question_job_id',
                'type' => 'both'
            )
        );
        parent::__construct();
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
            'job_question_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_question_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_question_job_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_question_job_id',
                'label'   => 'Job',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "job_question_job_id",
                'list_data' => 'job_question_job_id',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_question_title' => array(
                'table'   => $this->_table,
                'name'   => 'job_question_title',
                'label'   => 'Title',
                'type'   => 'textarea',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'job_question_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_question_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_question_status",
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
