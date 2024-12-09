<?php

/**
 * Model_newsletter
 */
class Model_newsletter extends MY_Model
{
    protected $_table    = 'newsletter';
    protected $_field_prefix    = 'newsletter_';
    protected $_pk    = 'newsletter_id';
    protected $_status_field    = 'newsletter_status';
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
        $this->pagination_params['fields'] = "newsletter_id,newsletter_email,newsletter_createdon,newsletter_status";
        parent::__construct();
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
            'newsletter_id' => array(
                'table'   => $this->_table,
                'name'   => 'newsletter_id',
                'label'   => 'id #',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'newsletter_name' => array(
                'table'   => $this->_table,
                'name'   => 'newsletter_name',
                'label'   => 'Name',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => '',
                'rules'   => ''
            ),

            'newsletter_email' => array(
                'table'   => $this->_table,
                'name'   => 'newsletter_email',
                'label'   => 'Email',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => '',
                'dt_attributes'   => array("width" => "30%"),
                'rules'   => 'required|valid_email|trim|is_unique[' . $this->_table . '.' . $this->_field_prefix . 'email]'
            ),

            'newsletter_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'newsletter_createdon',
                'label'   => 'Created',
                'type'   => 'none',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "10%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'newsletter_status' => array(
                'table'   => $this->_table,
                'name'   => 'newsletter_status',
                'label'   => 'Status?',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt'   => 'dropdown',
                'list_data' => array(
                    0 => "<span class=\"label label-primary\">Read</span>",
                    1 =>  "<span class=\"label label-danger\">Unread</span>"
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
