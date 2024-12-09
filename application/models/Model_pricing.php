<?php

/**
 * Model_pricing
 */
class Model_pricing extends MY_Model
{
    protected $_table    = 'pricing';
    protected $_field_prefix    = 'pricing_';
    protected $_pk    = 'pricing_id';
    protected $_status_field    = 'pricing_status';
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
        $this->pagination_params['fields'] = "pricing_id,pricing_plan_name,pricing_status";
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
            'pricing_id' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'pricing_plan_name' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_plan_name',
                'label'   => 'Plan Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'pricing_plan_price_month' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_plan_price_month',
                'label'   => 'Plan Price Per Month',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|numeric'
            ),

            'pricing_plan_price_year' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_plan_price_year',
                'label'   => 'Plan Price Per Year <br/> (In case of empty "price per month" times 12 will be used)',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|numeric'
            ),

            'pricing_description' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_description',
                'label'   => 'Plan Description <br/> (Services offered). <br/> Note: Add Bullets only.',
                'type'   => 'editor',
                'type_dt'   => 'editor',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'pricing_description_excluded' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_description_excluded',
                'label'   => 'Plan Description <br/> (Service not offered). <br/> Note: Add Bullets only.',
                'type'   => 'editor',
                'type_dt'   => 'editor',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'pricing_status' => array(
                'table'   => $this->_table,
                'name'   => 'pricing_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "pricing_status",
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
