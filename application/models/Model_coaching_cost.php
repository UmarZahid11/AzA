<?php

/**
 * Model_coaching_cost
 */
class Model_coaching_cost extends MY_Model
{
    protected $_table = 'coaching_cost';
    protected $_field_prefix = 'coaching_cost_';
    protected $_pk = 'coaching_cost_id';
    protected $_status_field = 'coaching_cost_status';
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
        $this->pagination_params['fields'] = "coaching_cost_id, coaching_cost_coaching_id, coaching_cost_membership_id, coaching_cost_status";
        parent::__construct();
    }

    /**
     * Method get_coaching_costs
     *
     * @param int $id
     *
     * @return void
     */
    public function get_coaching_costs($id = 0)
    {
        $params['fields'] = "coaching_cost_value";
        $params['where']['coaching_cost_status'] = 1;
        return $this->model_coaching_cost->find_by_pk($id, false, $params);
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
        // Use when add new image
        $is_required = (($this->uri->segment(4) != null) && intval($this->uri->segment(4))) ? '' : 'required';

        $fields['coaching_cost_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_cost_id',
            'label' => 'ID',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'trim'
        );
        
        $fields['coaching_cost_coaching_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_cost_coaching_id',
            'label' => 'Coaching',
            'type' => 'dropdown',
            'type_dt' => 'dropdown',
            'list_data_key' => "coaching_cost_coaching_id",
            'list_data' => "coaching_cost_coaching_id",
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'required|trim'
        );
        
        $fields['coaching_cost_membership_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_cost_membership_id',
            'label' => 'Membership',
            'type' => 'dropdown',
            'type_dt' => 'dropdown',
            'list_data_key' => "coaching_cost_membership_id",
            'list_data' => "coaching_cost_membership_id",
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'required|trim'
        );


        $fields['coaching_cost_value'] = array(
            'table' => $this->_table,
            'name' => 'coaching_cost_value',
            'label' => 'Cost',
            'type' => 'number',
            'attributes'   => array(),
            'js_rules' => 'required',
            'rules' => 'required|trim|htmlentities'
        );

        $fields['coaching_cost_status'] = array(
            'table' => $this->_table,
            'name' => 'coaching_cost_status',
            'label' => 'Status',
            'type' => 'switch',
            'type_dt' => 'switch',
            'type_filter_dt' => 'dropdown',
            'list_data_key' => "news_status",
            'list_data' => array(
                0 => "<span class='label label-danger'>Inactive</span>",
                1 =>  "<span class='label label-primary'>Active</span>"
            ),
            'default' => '1',
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
