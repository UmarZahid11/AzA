<?php

/**
 * Model_admin_event
 */
class Model_admin_event extends MY_Model
{
    protected $_table    = 'admin_event';
    protected $_field_prefix    = 'admin_event_';
    protected $_pk    = 'admin_event_id';
    protected $_status_field    = 'admin_event_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
    public $_per_page    = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "admin_event_id, admin_event_name, admin_event_status";
        parent::__construct();
    }

    /**
     * Method get_admin_event_list
     *
     * @return void
     */
    public function get_admin_event_list()
    {
        $result = $this->model_admin_event->find_all_active();
        $resultant = array();
        foreach ($result as $key => $value) {
            $resultant[] = array(
                'id' => $value['admin_event_id'],
                'title' => $value['admin_event_name'],
                'start' => date('m/d/Y H:i:s', strtotime($value['admin_event_created'])),
                'className' => $value['admin_event_category'],
            );
        }
        return $resultant;
    }

    /**
     * table       Table Name
     * Name        FIeld Name
     * label       Field Label / Textual Representation in form and DT headings
     * type        Field type : hidden, text, textarea, editor, etc etc.
     * Implementation in form_generator.php
     * type_dt     Type used by prepare_datatables method in controller to prepare DT value
     * If left blank, prepare_datatable Will opt to use 'type'
     * attributes  HTML Field Attributes
     * js_rules    Rules to be aplied in JS (form validation)
     * rules       Server side Validation. Supports CI Native rules
     */
    public function get_fields($specific_field = "")
    {
        $fields = array(
            'admin_event_id' => array(
                'table'   => $this->_table,
                'name'   => 'admin_event_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'admin_event_name' => array(
                'table'   => $this->_table,
                'name'   => 'admin_event_name',
                'label'   => 'Name',
                'type'   => 'text',
                'attributes'   => array(),
                'default'   => '',
                'rules'   => 'trim|htmlentities|required|max_length[30]',
                'js_rules'   => 'required'
            ),

            'admin_event_category' => array(
                'table'   => $this->_table,
                'name'   => 'admin_event_category',
                'label'   => 'Category',
                'type'   => 'dropdown',
                'type_dt'   => 'text',
                'type_filter_dt'   => 'dropdown',
                'js_rules'   => 'required',
                'rules'   => 'required|trim'
            ),

            'admin_event_created' => array(
                'table'   => $this->_table,
                'name'   => 'admin_event_created',
                'label'   => 'Date',
                'type'   => 'text',
                'type_dt'   => 'text',
                'type_filter_dt'   => 'dropdown',
                'js_rules'   => 'required',
                'rules'   => 'required|trim'
            ),

            'admin_event_status' => array(
                'table'   => $this->_table,
                'name'   => 'admin_event_status',
                'label'   => 'Status?',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt'   => 'dropdown',
                'list_data' => array(),
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
