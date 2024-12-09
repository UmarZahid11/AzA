<?php

/**
 * Model_logo
 */
class Model_logo extends MY_Model
{
    protected $_table    = 'logo';
    protected $_field_prefix    = 'logo_';
    protected $_pk    = 'logo_id';
    protected $_status_field    = 'logo_status';
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
        $this->pagination_params['fields'] = "logo_id,logo_name, CONCAT(logo_image_path,logo_image) AS logo_image, logo_status";
        $this->pagination_params['where']['logo_status !='] = 2;
        parent::__construct();
    }

    /**
     * Method get_logo
     *
     * @return void
     */
    public function get_logo()
    {
        $params['fields'] = "CONCAT(logo_image_path, '', logo_image) as logo";
        $result = $this->find_one_active($params);
        return g('base_url') . $result['logo'];
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
    *
    * list_data         For dropdown etc, data in key-value pair that will populate dropdown
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
    *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    */
    public function get_fields($specific_field = "")
    {

        $fields = array(

            'logo_id' => array(
                'table'   => $this->_table,
                'name'   => 'logo_id',
                'label'   => 'ID #',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'logo_image' => array(
                'table'   => $this->_table,
                'name'   => 'logo_image',
                'label'   => 'Logo',
                'name_path'   => 'logo_image_path',
                'upload_config'   => 'site_upload_logo',
                'type'   => 'fileupload',
                'type_dt'   => 'image',
                'thumb'   => array(array('name' => 'logo_image_thumb', 'max_width' => 150, 'max_height' => 150),),
                'attributes'   => array(
                    'image_size_recommended' => '171px x 157px',
                    'allow_ext' => 'png|jpeg|jpg|svg|gif',
                ),
                'randomize' => true,
                'preview'   => 'true',
                'dt_attributes'   => array("width" => "10%"),
                'rules'   => 'trim|htmlentities'
            ),
            'logo_favicon' => array(
                'table'   => $this->_table,
                'name'   => 'logo_favicon',
                'label'   => 'Favicon',
                'name_path'   => 'logo_image_path',
                'upload_config'   => 'site_upload_logo',
                'type'   => 'fileupload',
                'type_dt'   => 'image',
                //'thumb'   => array(array('name'=>'logo_image_thumb','max_width'=>150, 'max_height'=>150),),
                'attributes'   => array(
                    'image_size_recommended' => '64px × 64px',
                    'allow_ext' => 'png|jpeg|jpg|svg',
                ),
                'randomize' => true,
                'preview'   => 'true',
                'dt_attributes'   => array("width" => "10%"),
                'rules'   => 'trim|htmlentities'
            ),

            'logo_image_footer' => array(
                'table'   => $this->_table,
                'name'   => 'logo_image_footer',
                'label'   => 'Footer Logo',
                'name_path'   => 'logo_image_path',
                'upload_config'   => 'site_upload_logo',
                'type'   => 'fileupload',
                'type_dt'   => 'image',
                'thumb'   => array(array('name' => 'logo_image_thumb', 'max_width' => 150, 'max_height' => 150),),
                'attributes'   => array(
                    'image_size_recommended' => '171px × 157px',
                    'allow_ext' => 'png|jpeg|jpg|svg|gif',
                ),
                'randomize' => true,
                'preview'   => 'true',
                'dt_attributes'   => array("width" => "10%"),
                'rules'   => 'trim|htmlentities'
            ),

            'logo_status' => array(
                'table'   => $this->_table,
                'name'   => 'logo_status',
                'label'   => 'Status?',
                'type'   => 'hidden',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "logo_status",
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
