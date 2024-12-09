<?php

/**
 * Model_faq
 */
class Model_faq extends MY_Model
{
    protected $_table = 'faq';
    protected $_field_prefix = 'faq_';
    protected $_pk = 'faq_id';
    protected $_status_field = 'faq_status';
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
        $this->pagination_params['fields'] = "faq_id,faq_title,faq_status";
        parent::__construct();
    }

    /**
     * Method get_page_faq
     *
     * @param string $page
     *
     * @return void
     */
    public function get_page_faq($page = '')
    {
        $params['fields'] = 'faq_id, faq_title, faq_status';
        $params['where']['faq_page'] = $page;
        return $this->model_faq->find_one_active($params);
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
        $fields = array(

            'faq_id' => array(
                'table' => $this->_table,
                'name' => 'faq_id',
                'label' => 'id #',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'dt_attributes' => array("width" => "5%"),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'faq_title' => array(
                'table'   => $this->_table,
                'name'   => 'faq_title',
                'label'   => 'Question',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|trim|htmlentities'
            ),

            'faq_content' => array(
                'table' => $this->_table,
                'name' => 'faq_content',
                'label' => 'Answer ',
                'type' => 'textarea',
                'attributes' => array(),
                'js_rules' => 'required',
                'rules' => 'required|trim|htmlentities'
            ),

            'faq_status' => array(
                'table' => $this->_table,
                'name' => 'faq_status',
                'label' => 'Status?',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>Inactive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
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
