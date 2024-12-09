<?php

/**
 * Model_partner_image
 */
class Model_partner_image extends MY_Model
{
    protected $_table    = 'partner_image';
    protected $_field_prefix    = 'partner_image_';
    protected $_pk    = 'partner_image_id';
    protected $_status_field    = 'partner_image_status';
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
        $this->pagination_params['fields'] = "partner_image_id, partner_image_status";
        parent::__construct();
    }

    /**
     * Method bulk_image_fields
     *
     * @param string $specific_field
     *
     * @return void
     */
    public function bulk_image_fields($specific_field = "" )
    {
        $fields = array(
            'primary_key' => array( 'name' => 'partner_image_id' ),
            'foreign_key' => array( 'name' => 'partner_image_partner_id' , 'table' => 'partner' ),
            'image' => array( 'name' => 'partner_image_name' ),
            'image_path' => array( 'name' => 'partner_image_path' ),
            'image_thumb' => array( 'name' => 'partner_image_thumb' ),
        );

        if($specific_field)
            return $fields[ $specific_field ];
        else
            return $fields;
    }

    /**
     * Method get_images
     *
     * @param array $ret_params
     *
     * @return void
     */
    public function get_images($ret_params)
    {
        global $config;

        $result = array();
        if($ret_params)
        {
            $images = $this->find_all($ret_params);
            foreach ($images as $index => $img) {
                $token = $this->img_salt($img) ;
                $result[$index]['name'] = $img['partner_image_name'];
                $result[$index]['url'] = $config['base_url'].$img['partner_image_path'].$img['partner_image_name'];
                $result[$index]['thumbnailUrl'] = $config['base_url'].$img['partner_image_path']."thumb/".$img['partner_image_thumb'];
                $result[$index]['deleteUrl'] = $config['base_url']."admin/".$config['ci_class']."/delete_image/".$img['partner_image_id']."/".$token;

                $result[$index]['deleteType'] = 'DELETE';
                $result[$index]['featuredType'] = 'FEATURED';
            }
        }
        return $result;
    }

    /**
     * Method img_salt
     *
     * @param string $img
     *
     * @return void
     */
    public function img_salt($img)
    {
        return array_filled($img) ? md5( $img['partner_image_id'] . $img['partner_image_name'] . "IAmAWesome" ) : "" ;
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
            'partner_image_id' => array(
                'table'   => $this->_table,
                'name'   => 'partner_image_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'partner_image_partner_id' => array(
                'table'   => $this->_table,
                'name'   => 'partner_image_partner_id',
                'label'   => 'FK ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'partner_image_title' => array(
                'table'   => $this->_table,
                'name'   => 'partner_image_title',
                'label'   => 'Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'partner_image_description' => array(
                'table'   => $this->_table,
                'name'   => 'partner_image_description',
                'label'   => 'Description',
                'type'   => 'textarea',
                'type_dt'   => 'partner_image_description',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'partner_image_name' => array(
                'table' => $this->_table,
                'name' => 'partner_image_name',
                'label' => 'Image',
                'name_path' => 'partner_image_path',
                'upload_config' => 'site_upload_partner_image',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'image_size_recommended' => '1803px × 1046px',
                    'allow_ext' => 'png|jpeg|jpg',
                ),
                'thumb'   => array(
                    array('name'=>'partner_image_thumb','max_width'=>272, 'max_height'=>334),
                ),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => ''
            ),

            'partner_image_status' => array(
                'table'   => $this->_table,
                'name'   => 'partner_image_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "partner_image_status",
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
