<?php

/**
 * Model_token
 */
class Model_token extends MY_Model
{
    protected $_table = 'token';
    protected $_field_prefix = 'token_';
    protected $_pk = 'token_id';
    protected $_status_field = 'token_status';
    public $pagination_params = array();

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     *
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
            'token_id' => array(
                'table' => $this->_table,
                'name' => 'token_id',
                'label' => 'ID #',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'dt_attributes' => array("width" => "5%"),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'token_user' => array(
                'table' => $this->_table,
                'name' => 'token_user',
                'label' => 'User Token',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => '|required|trim|htmlentities'
            ),

            'token_user_id' => array(
                'table' => $this->_table,
                'name' => 'token_user_id',
                'label' => 'User ID',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => '|required|trim|htmlentities'
            ),

            'token_createdon' => array(
                'table' => $this->_table,
                'name' => 'token_createdon',
                'label' => 'Token Createdon',
                'type' => 'label',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => '|trim'
            ),

            'token_status' => array(
                'table' => $this->_table,
                'name' => 'token_status',
                'label' => 'Status?',
                'type' => 'switch',
                'type_dt' => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}