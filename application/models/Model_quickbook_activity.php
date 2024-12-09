<?php

/**
 * Model_quickbook_activity
 */
class Model_quickbook_activity extends MY_Model
{
    protected $_table    = 'quickbook_activity';
    protected $_field_prefix    = 'quickbook_activity_';
    protected $_pk    = 'quickbook_activity_id';
    protected $_status_field    = 'quickbook_activity_status';
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
        $this->pagination_params['fields'] = "quickbook_activity_id, quickbook_activity_entity, quickbook_activity_userid, quickbook_activity_status";
        $this->pagination_params['joins'][] = array(
            "table" => "signup",
            "joint" => "signup.signup_id = quickbook_activity.quickbook_activity_userid",
            "type" => "both"
        );

        parent::__construct();
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
    public function get_fields($specific_field = "")
    {
        $fields = array(
            'quickbook_activity_id' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'quickbook_activity_userid' => array(
                'table' => $this->_table,
                'name' => 'quickbook_activity_userid',
                'label' => 'User',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "quickbook_activity_userid",
                'list_data' => "quickbook_activity_userid",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'quickbook_activity_entity_class' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_entity_class',
                'label'   => 'Class',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_entity_id' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_entity_id',
                'label'   => 'Entity ID',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_entity_data' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_entity_data',
                'label'   => 'Data1',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_account_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_account_ref',
                'label'   => 'Account Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_class_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_class_ref',
                'label'   => 'Class Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_company_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_company_ref',
                'label'   => 'Company Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_customer_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_customer_ref',
                'label'   => 'Customer Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_department_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_department_ref',
                'label'   => 'Department Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_employee_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_employee_ref',
                'label'   => 'Employee Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_item_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_item_ref',
                'label'   => 'Item Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_term_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_term_ref',
                'label'   => 'Term Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_vendor_ref' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_vendor_ref',
                'label'   => 'Vendor Ref',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_status' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "quickbook_activity_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'quickbook_activity_entity' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_entity',
                'label'   => 'Activity Type',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "quickbook_activity_entity",
                'list_data' => QUICKBOOK_ENTITY_TYPE_LIST,
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_createdon',
                'label'   => 'Created on',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'quickbook_activity_updatedon' => array(
                'table'   => $this->_table,
                'name'   => 'quickbook_activity_updatedon',
                'label'   => 'Updated on',
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
