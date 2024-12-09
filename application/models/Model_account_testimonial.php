<?php

/**
 * Model_account_testimonial - testimonial uploaded by other for me or vice versa
 */
class Model_account_testimonial extends MY_Model
{
    protected $_table = 'account_testimonial';
    protected $_field_prefix = 'account_testimonial_';
    protected $_pk = 'account_testimonial_id';
    protected $_status_field = 'account_testimonial_status';
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
        $this->pagination_params['fields'] = "account_testimonial_id, account_testimonial_status";
        parent::__construct();
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
        $is_required_image = (($this->uri->segment(4) != null) && intval($this->uri->segment(4))) ? '' : 'required';

        $fields = array(

            'account_testimonial_id' => array(
                'table' => $this->_table,
                'name' => 'account_testimonial_id',
                'label' => 'id #',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'dt_attributes' => array("width" => "5%"),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'account_testimonial_attachment' => array(
                'table' => $this->_table,
                'name' => 'account_testimonial_attachment',
                'label' => 'Image',
                'name_path' => 'account_testimonial_attachment_path',
                'upload_config' => 'site_upload_account_testimonial',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes'   => array(
                    'allow_ext' => 'mp4|mov|mkv',
                ),
                'dt_attributes' => array("width" => "10%"),
                'rules' => 'trim|htmlentities',
                'js_rules' => $is_required_image
            ),

            'account_testimonial_status' => array(
                'table' => $this->_table,
                'name' => 'account_testimonial_status',
                'label' => 'Status?',
                'type' => 'switch',
                'type_dt' => 'switch',
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
