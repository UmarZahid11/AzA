<?php

/**
 * Model_signup_company
 */
class Model_signup_company extends MY_Model
{

    protected $_table = 'signup_company';
    protected $_field_prefix = 'signup_company_';
    protected $_pk = 'signup_company_id';
    protected $_status_field = 'signup_company_status';
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
        $this->pagination_params['fields'] = "signup_company_id, signup_company_name, signup_company_representative_name, signup_company_representative_email, signup_company_status";
        parent::__construct();
    }

    /**
     * Method get_fields
     *
     * @param string $specific_field
     *
     * @return array
     */
    public function get_fields($specific_field = "")
    {
        $data =  array(

            'signup_company_id' => array(
                'table' => $this->_table,
                'name' => 'signup_company_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_company_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_company_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_company_representative_name' => array(
                'table' => $this->_table,
                'name' => 'signup_company_representative_name',
                'label' => 'Representative Name',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_representative_email' => array(
                'table' => $this->_table,
                'name' => 'signup_company_representative_email',
                'label' => 'Representative Email',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_representative_phone' => array(
                'table' => $this->_table,
                'name' => 'signup_company_representative_phone',
                'label' => 'Representative Phone',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_name' => array(
                'table' => $this->_table,
                'name' => 'signup_company_name',
                'label' => 'Company Name',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_detail' => array(
                'table' => $this->_table,
                'name' => 'signup_company_detail',
                'label' => 'Company Detail',
                'type' => 'editor',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_type' => array(
                'table' => $this->_table,
                'name' => 'signup_company_type',
                'label' => 'Type',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_location' => array(
                'table' => $this->_table,
                'name' => 'signup_company_location',
                'label' => 'Location',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_image' => array(
                'table' => $this->_table,
                'name' => 'signup_company_image',
                'label' => 'Company Image/Logo',
                'name_path' => 'signup_company_image_path',
                'upload_config' => 'site_upload_signup_company',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'image_size_recommended' => '1803px × 1046px',
                    'allow_ext' => 'png|jpeg|jpg',
                ),
                'thumb'   => array(
                    array('name' => 'partner_image_thumb', 'max_width' => 272, 'max_height' => 334),
                ),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => ''
            ),

            'signup_company_industry' => array(
                'table' => $this->_table,
                'name' => 'signup_company_industry',
                'label' => 'Industry',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_company_revenue' => array(
                'table' => $this->_table,
                'name' => 'signup_company_revenue',
                'label' => 'Revenue',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_company_size' => array(
                'table' => $this->_table,
                'name' => 'signup_company_size',
                'label' => 'Size',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_company_founded' => array(
                'table' => $this->_table,
                'name' => 'signup_company_founded',
                'label' => 'Founded on',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_company_website' => array(
                'table' => $this->_table,
                'name' => 'signup_company_website',
                'label' => 'Website',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|valid_url'
            ),

            'signup_company_facebook' => array(
                'table' => $this->_table,
                'name' => 'signup_company_facebook',
                'label' => 'Facebook',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|valid_url'
            ),

            'signup_company_twitter' => array(
                'table' => $this->_table,
                'name' => 'signup_company_twitter',
                'label' => 'Twitter',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|valid_url'
            ),

            'signup_company_vimeo' => array(
                'table' => $this->_table,
                'name' => 'signup_company_vimeo',
                'label' => 'Vimeo',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|valid_url'
            ),

            'signup_company_linkedin' => array(
                'table' => $this->_table,
                'name' => 'signup_company_linkedin',
                'label' => 'Linkedin',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|valid_url'
            ),

            'signup_company_status' => array(
                'table' => $this->_table,
                'name' => 'signup_company_status',
                'label' => 'Status',
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
            return $data[$specific_field];
        else
            return $data;
    }
}