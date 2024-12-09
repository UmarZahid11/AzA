<?php

/**
 * Model_fundraising
 */
class Model_fundraising extends MY_Model
{
    protected $_table    = 'fundraising';
    protected $_field_prefix    = 'fundraising_';
    protected $_pk    = 'fundraising_id';
    protected $_status_field    = 'fundraising_status';
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
        $this->pagination_params['fields'] = "fundraising_id, fundraising_title, fundraising_amount, fundraising_status";
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

            'fundraising_id' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'fundraising_title' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_title',
                'label'   => 'Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array("additional"=>'slugify="#'.$this->_table.'-'.$this->_field_prefix.'slug"'),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'fundraising_slug' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_slug',
                'label'   => 'Slug',
                'type'   => 'readonly',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|htmlentities|is_unique['.$this->_table.'.'.$this->_field_prefix.'slug]|callback_is_slug|strtolower'
            ),

            'fundraising_short_desc' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_short_desc',
                'label'   => 'Short Detail',
                'type'   => 'textarea',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'fundraising_desc' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_desc',
                'label'   => 'Detail',
                'type'   => 'editor',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => 'required',
                'rules'   => 'trim|required'
            ),

            'fundraising_amount' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_amount',
                'label'   => 'Amount to raise',
                'type'   => 'number',
                'type_dt'   => 'text',
                'min' => 0,
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => 'required',
                'rules'   => 'trim|required'
            ),

            'fundraising_attachment' => array(
                'table' => $this->_table,
                'name' => 'fundraising_attachment',
                'label' => 'Attachment',
                'name_path' => 'fundraising_attachment_path',
                'upload_config' => 'site_upload_fundraising',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'allow_ext' => 'png|jpeg|jpg|webp',
                ),
                'thumb'   => array(),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => ''
            ),

            'fundraising_status' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "fundraising_status",
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
