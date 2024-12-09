<?php

/**
 * Model_testimonial
 */
class Model_testimonial extends MY_Model
{
    protected $_table    = 'testimonial';
    protected $_field_prefix    = 'testimonial_';
    protected $_pk    = 'testimonial_id';
    protected $_status_field    = 'testimonial_status';
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
        $this->pagination_params['fields'] = "testimonial_id, testimonial_name, testimonial_designation, CONCAT(testimonial_image_path,testimonial_image) AS testimonial_image, testimonial_status";
        parent::__construct();
    }

    /**
     * Method get_testimonial
     *
     * @return array
     */
    public function get_testimonial()
    {
        $params['fields'] = "testimonial_name, testimonial_name, testimonial_designation, testimonial_description, testimonial_image, testimonial_image_path";
        $result = $this->model_testimonial->find_all_active($params);

        return $result;
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

            'testimonial_id' => array(
                'table'   => $this->_table,
                'name'   => 'testimonial_id',
                'label'   => 'id #',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'testimonial_name' => array(
                'table'   => $this->_table,
                'name'   => 'testimonial_name',
                'label'   => 'Name',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|trim|htmlentities'
            ),

            'testimonial_designation' => array(
                'table'   => $this->_table,
                'name'   => 'testimonial_designation',
                'label'   => 'Designation',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|trim|htmlentities|max_length[20]'
            ),

            'testimonial_description' => array(
                'table'   => $this->_table,
                'name'   => 'testimonial_description',
                'label'   => 'Description',
                'type'   => 'textarea',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|trim|htmlentities'
            ),

            'testimonial_image' => array(
                'table'   => $this->_table,
                'name'   => 'testimonial_image',
                'label'   => 'Image',
                'name_path'   => 'testimonial_image_path',
                'upload_config'   => 'site_upload_testimonial',
                'type'   => 'fileupload',
                'type_dt'   => 'image',
                'randomize' => true,
                'preview'   => 'true',
                'attributes'   => array('image_size' => 'Recommended image size : 96px × 96px', 'allow_ext' => 'png|jpeg|jpg',),
                'dt_attributes'   => array("width" => "10%"),
                'rules'   => 'trim|htmlentities',
                'js_rules' => ''
            ),

            'testimonial_status' => array(
                'table'   => $this->_table,
                'name'   => 'testimonial_status',
                'label'   => 'Status?',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "testimonial_status",
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
