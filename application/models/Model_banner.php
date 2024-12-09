<?php

/**
 * Model_banner
 */
class Model_banner extends MY_Model
{
    protected $_table = 'banner';
    protected $_field_prefix = 'banner_';
    protected $_pk = 'banner_id';
    protected $_status_field = 'banner_status';
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
        $this->pagination_params['fields'] = "banner_id,,banner_heading,CONCAT(banner_image_path,banner_image) AS banner_image,banner_status";
        parent::__construct();
    }

    /**
     * Method get_banners
     *
     * @param int $id
     *
     * @return void
     */
    public function get_banners($id = 0)
    {
        $params['fields'] = "banner_heading,banner_image,banner_image_path";
        $params['where']['banner_status'] = 1;
        return $this->model_banner->find_by_pk($id, false, $params);
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
        // Use when add new image
        $is_required = (($this->uri->segment(4) != null) && intval($this->uri->segment(4))) ? '' : 'required';

        $fields['banner_id'] = array(
            'table' => $this->_table,
            'name' => 'banner_id',
            'label' => 'ID',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'trim'
        );

        $fields['banner_heading'] = array(
            'table' => $this->_table,
            'name' => 'banner_heading',
            'label' => 'Heading',
            'type' => 'readonly',
            'attributes' => array(),
            'js_rules' => 'required',
            'rules' => 'required|trim|htmlentities'
        );

        $fields['banner_sub_heading'] = array(
            'table' => $this->_table,
            'name' => 'banner_sub_heading',
            'label' => 'Sub Heading',
            'type' => 'editor',
            'attributes' => array(),
            'rules' => 'trim|htmlentities|required'
        );

        $fields['banner_description'] = array(
            'table' => $this->_table,
            'name' => 'banner_description',
            'label' => 'Description',
            'type' => 'editor',
            'attributes' => array(),
            'js_rules' => 'required',
            'rules' => 'trim|htmlentities'
        );

        $fields['banner_button_1'] = array(
            'table' => $this->_table,
            'name' => 'banner_button_1',
            'label' => 'Button Label',
            'type' => 'text',
            'attributes' => array(),
            'js_rules' => '',
            'rules' => 'trim'
        );

        $fields['banner_button_1_link'] = array(
            'table' => $this->_table,
            'name' => 'banner_button_1_link',
            'label' => 'Button Link',
            'type' => 'text',
            'attributes' => array(),
            'rules' => 'trim'
        );

        $fields['banner_image'] = array(
            'table' => $this->_table,
            'name' => 'banner_image',
            'label' => 'Image',
            'name_path' => 'banner_image_path',
            'upload_config' => 'site_upload_banner',
            'type' => 'fileupload',
            'type_dt' => 'image',
            'randomize' => true,
            'preview' => 'true',
            'attributes' => array(
                'image_size_recommended' => '1803px × 1046px',
                'allow_ext' => 'png|jpeg|jpg|webp|gif',
            ),
            'thumb'   => array(array('name' => 'banner_image_thumb', 'max_width' => 320, 'max_height' => 200, "destination_path" => ''),),
            'dt_attributes' => array("width" => "10%"),
            'rules' => '',
            'js_rules' => $is_required
        );

        $fields['banner_status'] = array(
            'table' => $this->_table,
            'name' => 'banner_status',
            'label' => 'Status',
            'type' => 'switch',
            'type_dt' => 'switch',
            'type_filter_dt' => 'dropdown',
            'list_data_key' => "news_status",
            'list_data' => array(
                0 => "<span class='label label-danger'>Inactive</span>",
                1 =>  "<span class='label label-primary'>Active</span>"
            ),
            'default' => '1',
            'attributes' => array(),
            'dt_attributes' => array("width" => "7%"),
            'rules' => 'trim'
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
