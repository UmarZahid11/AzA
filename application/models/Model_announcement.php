<?php

/**
 * Model_announcement
 */
class Model_announcement extends MY_Model
{
    protected $_table    = 'announcement';
    protected $_field_prefix    = 'announcement_';
    protected $_pk    = 'announcement_id';
    protected $_status_field    = 'announcement_status';
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
        $this->pagination_params['fields'] = "announcement_id, announcement_title, CONCAT(announcement_attachment_path, announcement_attachment) AS announcement_attachment, announcement_status";
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

            'announcement_id' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'announcement_title' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_title',
                'label'   => 'Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array("additional"=>'slugify="#'.$this->_table.'-'.$this->_field_prefix.'slug"'),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'announcement_slug' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_slug',
                'label'   => 'Slug',
                'type'   => 'readonly',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|htmlentities|is_unique['.$this->_table.'.'.$this->_field_prefix.'slug]|callback_is_slug|strtolower'
            ),

            'announcement_subtitle' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_subtitle',
                'label'   => 'Short Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'announcement_short_desc' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_short_desc',
                'label'   => 'Short Detail',
                'type'   => 'textarea',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'announcement_desc' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_desc',
                'label'   => 'Detail',
                'type'   => 'editor',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => 'required',
                'rules'   => 'trim|required'
            ),

            'announcement_attachment' => array(
                'table' => $this->_table,
                'name' => 'announcement_attachment',
                'label' => 'Attachment',
                'name_path' => 'announcement_attachment_path',
                'upload_config' => 'site_upload_announcement',
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
            
            'announcement_attachment_video' => array(
                'table' => $this->_table,
                'name' => 'announcement_attachment_video',
                'label' => 'Video',
                'name_path' => 'announcement_attachment_path',
                'upload_config' => 'site_upload_announcement',
                'type' => 'videoupload',
                'type_dt' => 'video',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'allow_ext' => 'mp4|mov|mkv',
                    'max_size' => 2097152
                ),
                'thumb'   => array(),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => ''
            ),

            'announcement_status' => array(
                'table'   => $this->_table,
                'name'   => 'announcement_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "announcement_status",
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
