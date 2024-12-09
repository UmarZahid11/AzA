<?php

/**
 * Model_product_request
 */
class Model_product_request extends MY_Model
{
    protected $_table    = 'product_request';
    protected $_field_prefix    = 'product_request_';
    protected $_pk    = 'product_request_id';
    protected $_status_field    = 'product_request_status';
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
        $this->pagination_params['fields'] = "product_request_id, product_request_signup_id, product_request_status";
        parent::__construct();
    }

    /**
     * Method requestExists
     *
     * @param int $user_id
     * @param int $product_id
     * @param boolean $return_details
     *
     * @return bool
     */
    function requestExists(int $user_id = 0, int $product_id = 0, bool $return_details = FALSE)
    {
        $details = array();
        if ($product_id && $user_id) {
            $details = $this->find_one_active(
                array(
                    'where' => array(
                        'product_request_signup_id' => $user_id,
                        'product_request_product_id' => $product_id,
                        'product_request_current_status !=' => REQUEST_COMPLETE,
                    )
                )
            );
            
            if(!$return_details) {
                return $details ? TRUE : FALSE;
            }
            return $details;
        }
        if(!$return_details) {
            return FALSE;
        }
        return $details;
    }

    /**
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
    public function get_fields(string $specific_field = "")
    {
        $fields = array(
            'product_request_id' => array(
                'table'   => $this->_table,
                'name'   => 'product_request_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'product_request_signup_id' => array(
                'table' => $this->_table,
                'name' => 'product_request_signup_id',
                'label' => 'Owner',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "product_request_signup_id",
                'list_data' => "product_request_signup_id",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_request_status' => array(
                'table'   => $this->_table,
                'name'   => 'product_request_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "product_request_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'product_request_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'product_request_createdon',
                'label'   => 'Created on',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),
        );
        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
