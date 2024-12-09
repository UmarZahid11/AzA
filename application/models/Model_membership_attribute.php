<?php

/**
 * Model_membership_attribute
 */
class Model_membership_attribute extends MY_Model
{
    protected $_table    = 'membership_attribute';
    protected $_field_prefix    = 'membership_attribute_';
    protected $_pk    = 'membership_attribute_id';
    protected $_status_field    = 'membership_attribute_status';
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
        $this->pagination_params['fields'] = "membership_attribute_id, membership_attribute_section_id, membership_attribute_name, membership_attribute_status";
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
            'membership_attribute_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_attribute_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_attribute_section_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_attribute_section_id',
                'label'   => 'Section',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "membership_attribute_section_id",
                'list_data' => "membership_attribute_section_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),
            
            'membership_attribute_identifier_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_attribute_identifier_id',
                'label'   => 'Identifier',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "membership_attribute_identifier_id",
                'list_data' => "membership_attribute_identifier_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim|is_unique[' . $this->_table . '.' . $this->_field_prefix . 'identifier_id]'
            ),
            
            'membership_attribute_name' => array(
                'table'   => $this->_table,
                'name'   => 'membership_attribute_name',
                'label'   => 'Attribute Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'membership_attribute_status' => array(
                'table'   => $this->_table,
                'name'   => 'membership_attribute_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "membership_attribute_status",
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
