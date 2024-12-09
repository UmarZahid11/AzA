<?php

/**
 * Model_log
 */
class Model_log extends MY_Model
{
    protected $_table    = 'log';
    protected $_field_prefix    = 'log_';
    protected $_pk    = 'log_id';
    protected $_status_field    = 'log_status';
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
        $this->pagination_params['fields'] = "log_id, log_type, log_source, log_level, log_message, DATE_FORMAT(log_createdon, '%M, %d %Y %h:%i %p') as log_createdon";
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
    *
    * list_data         For dropdown etc, data in key-value pair that will populate dropdown
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
    *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    */
    public function get_fields($specific_field = "")
    {
        $fields = array(

            'log_id' => array(
                'table'   => $this->_table,
                'name'   => 'log_id',
                'label'   => 'ID #',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'log_type' => array(
                'table'   => $this->_table,
                'name'   => 'log_type',
                'label'   => 'Type',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'attributes'   => array(),
                'list_data' => array(
                    LOG_TYPE_GENERAL =>  "<span class='label label-primary'>" . LOG_TYPE_GENERAL . "</span>",
                    LOG_TYPE_PAYMENT => "<span class='label label-danger'>" . LOG_TYPE_PAYMENT . "</span>",
                    LOG_TYPE_API =>  "<span class='label label-primary'>" . LOG_TYPE_API . "</span>",
                    LOG_TYPE_SERVER_API =>  "<span class='label label-primary'>" . LOG_TYPE_SERVER_API . "</span>",
                ),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'log_source' => array(
                'table'   => $this->_table,
                'name'   => 'log_source',
                'label'   => 'Source',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'attributes'   => array(),
                'list_data' => array(
                    LOG_SOURCE_SERVER =>  "<span class='label label-primary'>" . LOG_SOURCE_SERVER . "</span>",
                    LOG_SOURCE_STRIPE => "<span class='label label-danger'>" . LOG_SOURCE_STRIPE . "</span>",
                    LOG_SOURCE_CRON => "<span class='label label-danger'>" . LOG_SOURCE_CRON . "</span>",
                    LOG_SOURCE_CURL => "<span class='label label-danger'>" . LOG_SOURCE_CURL . "</span>",
                    LOG_SOURCE_TWILIO => "<span class='label label-danger'>" . LOG_SOURCE_TWILIO . "</span>",
                    LOG_SOURCE_QUICKBOOK =>  "<span class='label label-primary'>" . LOG_SOURCE_QUICKBOOK . "</span>",
                    LOG_SOURCE_PLAID =>  "<span class='label label-primary'>" . LOG_SOURCE_PLAID . "</span>",
                    LOG_SOURCE_ZOOM =>  "<span class='label label-primary'>" . LOG_SOURCE_ZOOM . "</span>",
                    LOG_SOURCE_ZOOM_CRON =>  "<span class='label label-primary'>" . LOG_SOURCE_ZOOM_CRON . "</span>",
                    LOG_SOURCE_BOX_CRON =>  "<span class='label label-primary'>" . LOG_SOURCE_BOX_CRON . "</span>",
                ),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'log_level' => array(
                'table'   => $this->_table,
                'name'   => 'log_level',
                'label'   => 'Level',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'attributes'   => array(),
                'list_data' => array(
                    LOG_LEVEL_ERROR => "<span class='label label-danger'>" . LOG_LEVEL_ERROR . "</span>",
                    LOG_LEVEL_INFO => "<span class='label label-danger'>" . LOG_LEVEL_INFO . "</span>",
                    LOG_LEVEL_WARNING =>  "<span class='label label-primary'>" . LOG_LEVEL_WARNING . "</span>"
                ),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'log_message' => array(
                'table'   => $this->_table,
                'name'   => 'log_message',
                'label'   => 'Message',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'log_text' => array(
                'table'   => $this->_table,
                'name'   => 'log_text',
                'label'   => 'Detail',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'log_status' => array(
                'table'   => $this->_table,
                'name'   => 'log_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "log_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'log_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'log_createdon',
                'label'   => 'Createdon',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
