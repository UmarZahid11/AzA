<?php

/**
 * Model_blog
 */
class Model_blog extends MY_Model
{
    protected $_table    = 'blog';
    protected $_field_prefix    = 'blog_';
    protected $_pk    = 'blog_id';
    protected $_status_field    = 'blog_status';
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
        $this->pagination_params['fields'] = "blog_id, blog_title, CONCAT(blog_image_path, blog_image) AS blog_image, blog_approved, blog_status";
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
            'blog_approved' => array(
                'table'   => $this->_table,
                'name'   => 'blog_approved',
                'label'   => 'Approved',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "blog_approved",
                'list_data' => array(
                    0 => "<span class='label label-danger'>No</span>",
                    1 =>  "<span class='label label-primary'>Yes</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'blog_id' => array(
                'table'   => $this->_table,
                'name'   => 'blog_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'blog_title' => array(
                'table'   => $this->_table,
                'name'   => 'blog_title',
                'label'   => 'Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array("additional"=>'slugify="#'.$this->_table.'-'.$this->_field_prefix.'slug"'),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'blog_slug' => array(
                'table'   => $this->_table,
                'name'   => 'blog_slug',
                'label'   => 'Slug',
                'type'   => 'readonly',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|htmlentities|is_unique['.$this->_table.'.'.$this->_field_prefix.'slug]|callback_is_slug|strtolower'
            ),

            'blog_author' => array(
                'table'   => $this->_table,
                'name'   => 'blog_author',
                'label'   => 'Author',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'blog_short_detail' => array(
                'table'   => $this->_table,
                'name'   => 'blog_short_detail',
                'label'   => 'Short Detail',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'blog_detail' => array(
                'table'   => $this->_table,
                'name'   => 'blog_detail',
                'label'   => 'Detail',
                'type'   => 'editor',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => 'required',
                'rules'   => 'trim|required'
            ),

            'blog_image' => array(
                'table' => $this->_table,
                'name' => 'blog_image',
                'label' => 'Image',
                'name_path' => 'blog_image_path',
                'upload_config' => 'site_upload_blog',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'image_size_recommended' => '1803px × 1046px',
                    'allow_ext' => 'png|jpeg|jpg|webp',
                ),
                'thumb'   => array(),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => 'required'
            ),

            'blog_status' => array(
                'table'   => $this->_table,
                'name'   => 'blog_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "blog_status",
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
