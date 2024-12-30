<?php

/**
 * Model_coaching
 */
class Model_coaching extends MY_Model
{
    protected $_table = 'coaching';
    protected $_field_prefix = 'coaching_';
    protected $_pk = 'coaching_id';
    protected $_status_field = 'coaching_status';
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
        $this->pagination_params['fields'] = "coaching_id, coaching_title, coaching_start_time, coaching_duration, coaching_current_status, coaching_status";
        parent::__construct();
    }

    /**
     * Method get_coachings
     *
     * @param int $id
     *
     * @return void
     */
    public function get_coachings($id = 0)
    {
        $params['fields'] = "coaching_heading,coaching_image,coaching_image_path";
        $params['where']['coaching_status'] = 1;
        return $this->model_coaching->find_by_pk($id, false, $params);
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
        $fields['coaching_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_id',
            'label' => 'ID',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => 'required',
            'rules' => 'trim'
        );

        //
        $fields['coaching_uuid'] = array(
            'table' => $this->_table,
            'name' => 'coaching_uuid',
            'label' => 'UUID',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_fetchid'] = array(
            'table' => $this->_table,
            'name' => 'coaching_fetchid',
            'label' => 'Fetch Id',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );

        $fields['coaching_host_id'] = array(
            'table' => $this->_table,
            'name' => 'coaching_host_id',
            'label' => 'Host Id',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_host_email'] = array(
            'table' => $this->_table,
            'name' => 'coaching_host_email',
            'label' => 'Host Email',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_contact_email'] = array(
            'table' => $this->_table,
            'name' => 'coaching_contact_email',
            'label' => 'Contact Email',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_contact_name'] = array(
            'table' => $this->_table,
            'name' => 'coaching_contact_name',
            'label' => 'Contact Name',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_start_url'] = array(
            'table' => $this->_table,
            'name' => 'coaching_start_url',
            'label' => 'Start URL',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_join_url'] = array(
            'table' => $this->_table,
            'name' => 'coaching_join_url',
            'label' => 'Join URL',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );

        $fields['coaching_timezone'] = array(
            'table' => $this->_table,
            'name' => 'coaching_timezone',
            'label' => 'Timezone',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );

        $fields['coaching_response'] = array(
            'table' => $this->_table,
            'name' => 'coaching_response',
            'label' => 'Response',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        $fields['coaching_response2'] = array(
            'table' => $this->_table,
            'name' => 'coaching_response2',
            'label' => 'Response 2',
            'type' => 'hidden',
            'type_dt' => 'text',
            'attributes' => array(),
            'dt_attributes' => array("width" => "5%"),
            'js_rules' => '',
            'rules' => 'trim'
        );
        
        //
        
        $fields['coaching_title'] = array(
            'table' => $this->_table,
            'name' => 'coaching_title',
            'label' => 'Title',
            'type' => 'text',
            'attributes'   => array("additional"=>'slugify="#'.$this->_table.'-'.$this->_field_prefix.'slug"'),
            'js_rules' => 'required',
            'rules' => 'required|trim|htmlentities'
        );

        $fields['coaching_slug'] = array(
            'table'   => $this->_table,
            'name'   => 'coaching_slug',
            'label'   => 'Slug',
            'type'   => 'readonly',
            'type_dt'   => 'text',
            'attributes'   => array(),
            'dt_attributes'   => array("width" => "5%"),
            'js_rules'   => '',
            'rules'   => 'required|htmlentities|is_unique['.$this->_table.'.'.$this->_field_prefix.'slug]|callback_is_slug|strtolower'
        );
        
        $fields['coaching_cost'] = array(
            'table' => $this->_table,
            'name' => 'coaching_cost',
            'label' => 'Cost',
            'type' => 'number',
            'min' => 1,
            'attributes'   => array(),
            'js_rules' => 'required',
            'rules' => 'required|trim|htmlentities'
        );

        $fields['coaching_start_time'] = array(
            'table' => $this->_table,
            'name' => 'coaching_start_time',
            'label' => 'Start time',
            'type' => 'datetimelocal',
            'attributes' => array(),
            'js_rules' => 'required',
            'rules' => 'trim|htmlentities|required'
        );

        $fields['coaching_duration'] = array(
            'table' => $this->_table,
            'name' => 'coaching_duration',
            'label' => 'Duration (minutes)',
            'type' => 'number',
            'min' => 10,
            'attributes'   => array(),
            'js_rules' => 'required',
            'rules' => 'required|trim|htmlentities'
        );

        $fields['coaching_password'] = array(
            'table' => $this->_table,
            'name' => 'coaching_password',
            'label' => 'Password',
            'type' => 'text',
            'attributes'   => array(),
            'js_rules' => '',
            'rules' => 'trim'
        );

        $fields['coaching_short_description'] = array(
            'table' => $this->_table,
            'name' => 'coaching_short_description',
            'label' => 'Short Description',
            'type' => 'textarea',
            'attributes' => array(),
            'js_rules' => 'required',
            'rules' => 'trim|htmlentities'
        );

        $fields['coaching_description'] = array(
            'table' => $this->_table,
            'name' => 'coaching_description',
            'label' => 'Description',
            'type' => 'editor',
            'attributes' => array(),
            'js_rules' => 'required',
            'rules' => 'trim|htmlentities'
        );

        // $fields['coaching_image'] = array(
        //     'table' => $this->_table,
        //     'name' => 'coaching_image',
        //     'label' => 'Image',
        //     'name_path' => 'coaching_image_path',
        //     'upload_config' => 'site_upload_coaching',
        //     'type' => 'fileupload',
        //     'type_dt' => 'image',
        //     'randomize' => true,
        //     'preview' => 'true',
        //     'attributes' => array(
        //         'image_size_recommended' => '1803px × 1046px',
        //         'allow_ext' => 'png|jpeg|jpg|webp|gif',
        //     ),
        //     'thumb'   => array(),
        //     'dt_attributes' => array("width" => "10%"),
        //     'rules' => '',
        //     'js_rules' => ''
        // );

        $fields['coaching_current_status'] = array(
            'table' => $this->_table,
            'name' => 'coaching_current_status',
            'label' => 'Meeting state',
            'type' => 'dropdown',
            'type_dt' => 'dropdown',
            'type_filter_dt' => 'dropdown',
            'list_data_key' => "news_status",
            'list_data' => array(
                0 => "<span class='label label-danger'>Pending</span>",
                1 =>  "<span class='label label-primary'>Started</span>",
                2 =>  "<span class='label label-primary'>Ended</span>",
            ),
            'default' => '0',
            'attributes' => array(),
            'dt_attributes' => array("width" => "7%"),
            'rules' => 'trim'
        );

        $fields['coaching_status'] = array(
            'table' => $this->_table,
            'name' => 'coaching_status',
            'label' => 'Status',
            'type' => 'switch',
            'type_dt' => 'switch',
            'type_filter_dt' => 'dropdown',
            'list_data_key' => "coaching_status",
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
