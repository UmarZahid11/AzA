<?php

/**
 * Model_career
 */
class Model_career extends MY_Model
{
    protected $_table    = 'career';
    protected $_field_prefix    = 'career_';
    protected $_pk    = 'career_id';
    protected $_status_field    = 'career_status';
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
        $this->pagination_params['fields'] = "career_id, career_job_title, DATE_FORMAT(career_createdon, '%M, %d %Y %h:%i %p') as career_createdon, career_status";
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
            'career_id' => array(
                'table'   => $this->_table,
                'name'   => 'career_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_job_title' => array(
                'table'   => $this->_table,
                'name'   => 'career_job_title',
                'label'   => 'Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array("additional" => 'slugify="#' . $this->_table . '-' . $this->_field_prefix . 'slug"'),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim|alpha_numeric_spaces'
            ),

            'career_slug' => array(
                'table'   => $this->_table,
                'name'   => 'career_slug',
                'label'   => 'Title',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|htmlentities|is_unique[' . $this->_table . '.' . $this->_field_prefix . 'slug]|callback_is_slug|strtolower'
            ),

            'career_category' => array(
                'table'   => $this->_table,
                'name'   => 'career_category',
                'label'   => 'Category',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "career_category",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'career_job_type' => array(
                'table'   => $this->_table,
                'name'   => 'career_job_type',
                'label'   => 'Job Type',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "career_job_type",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'career_application_deadline' => array(
                'table'   => $this->_table,
                'name'   => 'career_application_deadline',
                'label'   => 'Application Deadline',
                'type'   => 'date2',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_salary_currency' => array(
                'table'   => $this->_table,
                'name'   => 'career_salary_currency',
                'label'   => 'Salary Currency',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'career_description' => array(
                'table'   => $this->_table,
                'name'   => 'career_description',
                'label'   => 'Description',
                'type'   => 'textarea',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'career_company_name' => array(
                'table'   => $this->_table,
                'name'   => 'career_company_name',
                'label'   => 'Company Name ',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_industry' => array(
                'table'   => $this->_table,
                'name'   => 'career_industry',
                'label'   => 'Industry',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_website' => array(
                'table'   => $this->_table,
                'name'   => 'career_website',
                'label'   => 'Company Website',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_url'
            ),

            'career_facebook' => array(
                'table'   => $this->_table,
                'name'   => 'career_facebook',
                'label'   => 'Company Facebook',
                'type'   => 'url',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_url'
            ),

            'career_linkedin' => array(
                'table'   => $this->_table,
                'name'   => 'career_linkedin',
                'label'   => 'Company Linkedin',
                'type'   => 'url',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_url'
            ),

            'career_twitter' => array(
                'table'   => $this->_table,
                'name'   => 'career_twitter',
                'label'   => 'Twitter',
                'type'   => 'url',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_url'
            ),

            'career_instagram' => array(
                'table'   => $this->_table,
                'name'   => 'career_instagram',
                'label'   => 'Company Instagram',
                'type'   => 'url',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_url'
            ),

            'career_company_description' => array(
                'table'   => $this->_table,
                'name'   => 'career_company_description',
                'label'   => 'Company Description',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_company_logo' => array(
                'table' => $this->_table,
                'name' => 'career_image',
                'label' => 'Company Logo',
                'name_path' => 'career_company_logo_path',
                'upload_config' => 'site_upload_career',
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
                'js_rules' => ''
            ),

            'career_recruiter_name' => array(
                'table'   => $this->_table,
                'name'   => 'career_recruiter_name',
                'label'   => 'Recruiter Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_recruiter_business' => array(
                'table'   => $this->_table,
                'name'   => 'career_recruiter_business',
                'label'   => 'Recruiter Business',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'career_status' => array(
                'table'   => $this->_table,
                'name'   => 'career_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "career_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'career_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'career_createdon',
                'label'   => 'Created on',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
