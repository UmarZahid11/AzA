<?php

/**
 * Model_membership_pivot
 */
class Model_membership_pivot extends MY_Model
{
    protected $_table    = 'membership_pivot';
    protected $_field_prefix    = 'membership_pivot_';
    protected $_pk    = 'membership_pivot_id';
    protected $_status_field    = 'membership_pivot_status';
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
        $this->pagination_params['fields'] = "membership_pivot_id, membership_pivot_membership_id, membership_pivot_attribute_id, membership_pivot_value, membership_pivot_status";
		$this->pagination_params['where'] = array(
			'membership.membership_status' => STATUS_ACTIVE
		);
        $this->pagination_params['joins'][] = array(
            "table" => "membership" ,
            "joint" => "membership.membership_id = membership_pivot.membership_pivot_membership_id",
			"type" => "both"
        );
        $this->pagination_params['joins'][] = array(
            "table" => "membership_attribute",
            "joint" => "membership_attribute.membership_attribute_id = membership_pivot.membership_pivot_attribute_id",
        );

        parent::__construct();
    }

    /**
     * Method pivot_value
     *
     * @param int $membershipId
     * @param int $attributeId
     *
     * @return ?string
     */
    public function pivot_value(int $membershipId, int $attributeId): ?string
    {
        $param = array();
        $param['where']['membership_pivot_membership_id'] = $membershipId;
        $param['where']['membership_pivot_attribute_id'] = $attributeId;
        $pivot_value = $this->find_one_active($param);
        if($pivot_value) {
            switch(true) {
                case ($pivot_value['membership_pivot_value'] == '0'):
                    return price(0);
                case (intVal($pivot_value['membership_pivot_value'])):
                    return price($pivot_value['membership_pivot_value']);
                default:
                    return $pivot_value['membership_pivot_value'];
            }
        }
        return NULL;
    }

    /**
     * Method raw_pivot_value
     *
     * @param int $membershipId
     * @param int $attributeId
     *
     * @return string
     */
    public function raw_pivot_value(int $membershipId, int $attributeId): ?string
    {
        $param = array();
        $param['where']['membership_pivot_membership_id'] = $membershipId;
        $param['where']['membership_pivot_attribute_id'] = $attributeId;
        $pivot_value = $this->find_one_active($param);
        if($pivot_value) {
            return $pivot_value['membership_pivot_value'];
        }
        return NULL;
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
    public function get_fields(string $specific_field = "")
    {
        $fields = array(
            'membership_pivot_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_pivot_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_pivot_membership_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_pivot_membership_id',
                'label'   => 'Membership',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "membership_pivot_membership_id",
                'list_data' => "membership_pivot_membership_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'membership_title' => array(
                'table'   => $this->_table,
                'name'   => 'membership_title',
                'label'   => 'Membership',
                'type'   => 'none',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_attribute_name' => array(
                'table'   => $this->_table,
                'name'   => 'membership_attribute_name',
                'label'   => 'Attribute',
                'type'   => 'none',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_pivot_attribute_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_pivot_attribute_id',
                'label'   => 'Attribute',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "membership_pivot_attribute_id",
                'list_data' => 'membership_pivot_attribute_id', //$this->model_membership_attribute_identifier->find_all_list_active(array(), 'membership_attribute_identifier_name'),
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'membership_pivot_value' => array(
                'table'   => $this->_table,
                'name'   => 'membership_pivot_value',
                'label'   => 'Value',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "membership_pivot_status",
                'list_data' => array(
                    0 => "No",
                    1 =>  "Yes"
                ),
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_pivot_status' => array(
                'table'   => $this->_table,
                'name'   => 'membership_pivot_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "membership_pivot_status",
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
