<?php

/**
 * Model_coaching_application
 */
class Model_coaching_application extends MY_Model
{
    protected $_table = 'coaching_application';
    protected $_field_prefix = 'coaching_application_';
    protected $_pk = 'coaching_application_id';
    protected $_status_field = 'coaching_application_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
    public $_per_page = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "coaching_application_id, coaching_application_signup_id, coaching_application_coaching_id, coaching_application_status";
        parent::__construct();
    }

    /**
     * userApplicationExists function
     *
     * @param integer $signup_id
     * @param integer $coaching_id
     * @return bool
     */
    function userApplicationExists(int $signup_id = 0, int $coaching_id = 0) {
        $coaching = $this->find_one(
            array(
                'where' => array(
                    'coaching_application_signup_id' => $signup_id,
                    'coaching_application_coaching_id' => $coaching_id
                )
            )
        );
        if($coaching) {
            return true;
        }

        return false;
    }

    /**
     * getUserApplication function
     *
     * @param integer $signup_id
     * @param integer $coaching_id
     * @return array
     */
    function getUserApplication(int $signup_id = 0, int $coaching_id = 0) {
        $coaching = $this->find_one(
            array(
                'where' => array(
                    'coaching_application_signup_id' => $signup_id,
                    'coaching_application_coaching_id' => $coaching_id
                )
            )
        );
        if($coaching) {
            return $coaching;
        }

        return [];
    }

    /**
     * getTotalApplicationsByUser function
     *
     * @param integer $signup_id
     * @return int
     */
    function getTotalApplicationsByUser(int $signup_id = 0) {
        $coaching_count = $this->find_count_active(
            array(
                'where' => array(
                    'coaching_application_signup_id' => $signup_id,
                )
            )
        );
        return $coaching_count;
    }

    /*
    * table       Table Name
    * Name        FIeld Name
    * label       Field Label / Textual Representation in form and DT headings
    * type        Field type : hidden, text, textarea, editor, etc etc.
    *                           Implementation in form_generator.php
    * type_dt     Type used by prepare_datatables method in controller to prepare DT value
    *                           If left blank, prepare_datatable Will opt to use 'type'
    * attributes  HTML Field Attributes
    * js_rules    Rules to be aplied in JS (form validation)
    * rules       Server side Validation. Supports CI Native rules
    */
    public function get_fields($specific_field = "")
    {
        $fields['coaching_application_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_application_id',
            'label' => 'ID',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'trim'
        );

        $fields['coaching_application_signup_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_application_signup_id',
            'label' => 'Requestor',
            'type' => 'dropdown',
            'type_dt' => 'dropdown',
            'list_data_key' => "coaching_application_signup_id",
            'list_data' => "coaching_application_signup_id",
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'required|trim'
        );

        $fields['coaching_application_coaching_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_application_coaching_id',
            'label' => 'Coaching',
            'type' => 'dropdown',
            'type_dt' => 'dropdown',
            'list_data_key' => "coaching_application_coaching_id",
            'list_data' => "coaching_application_coaching_id",
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'required|trim'
        );

        $fields['coaching_application_status'] = array(
            'table' => $this->_table,
            'name' => 'coaching_application_status',
            'label' => 'Status',
            'type' => 'dropdown',
            'type_dt' => 'switch',
            'type_filter_dt' => 'dropdown',
            'list_data_key' => "news_status",
            'list_data' => array(
                STATUS_INACTIVE => "<span class='label label-danger'>Pending</span>",
                STATUS_ACTIVE => "<span class='label label-primary'>Accepted</span>",
                STATUS_REJECTED => "<span class='label label-red'>Rejected</span>"
            ),
            'default' => '0',
            'attributes' => array(),
            'dt_attributes' => array("width" => "7%"),
            'rules' => 'trim'
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
