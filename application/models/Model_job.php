<?php

/**
 * Model_job
 */
class Model_job extends MY_Model
{
    protected $_table    = 'job';
    protected $_field_prefix    = 'job_';
    protected $_pk    = 'job_id';
    protected $_status_field    = 'job_status';
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
        $this->pagination_params['fields'] = "job_id, job_title, job_userid, job_status";
        $this->pagination_params['joins'][] = array(
            "table" => "signup",
            "joint" => "signup.signup_id = job.job_userid",
        );
        $this->pagination_params['where'] = array(
            'signup_type' => ROLE_3
        );

        parent::__construct();
    }

    /**
     * Method perferred_job_list
     *
     * @return void
     */
    public function perferred_job_list()
    {
        return array();
    }

    /**
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
            'job_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_userid' => array(
                'table'   => $this->_table,
                'name'   => 'job_userid',
                'label'   => 'Orgnization',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "job_userid",
                'list_data' => "job_userid",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_title' => array(
                'table'   => $this->_table,
                'name'   => 'job_title',
                'label'   => 'Job Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array("additional" => 'slugify="#' . $this->_table . '-' . $this->_field_prefix . 'slug"'),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|max_length[100]|callback_alpha_space'
            ),

            'job_slug' => array(
                'table'   => $this->_table,
                'name'   => 'job_slug',
                'label'   => 'Slug',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|htmlentities|is_unique[' . $this->_table . '.' . $this->_field_prefix . 'slug]|callback_is_slug|strtolower'
            ),

            'job_short_detail' => array(
                'table'   => $this->_table,
                'name'   => 'job_short_detail',
                'label'   => 'Short detail',
                'type'   => 'textarea',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|max_length[150]'
            ),

            'job_estimated_hours' => array(
                'table'   => $this->_table,
                'name'   => 'job_estimated_hours',
                'label'   => 'Estimated working hours',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|is_numeric'
            ),

            'job_estimated_days' => array(
                'table'   => $this->_table,
                'name'   => 'job_estimated_days',
                'label'   => 'Estimated working days',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|is_numeric'
            ),

            'job_estimated_weeks' => array(
                'table'   => $this->_table,
                'name'   => 'job_estimated_weeks',
                'label'   => 'Estimated working weeks',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|is_numeric'
            ),

            'job_detail' => array(
                'table'   => $this->_table,
                'name'   => 'job_detail',
                'label'   => 'Job description',
                'type'   => 'editor',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            // 'job_isparent' => array(
            //     'table'   => $this->_table,
            //     'name'   => 'job_isparent',
            //     'label'   => 'Is Parent Category?',
            //     'type'   => 'dropdown',
            //     'type_dt'   => 'dropdown',
            //     'type_filter_dt' => 'dropdown',
            //     'list_data_key' => "job_status",
            //     'list_data' => array(
            //         0 => "<span class='label label-danger'>No</span>",
            //         1 =>  "<span class='label label-primary'>Yes</span>"
            //     ),
            //     'default'   => '1',
            //     'attributes'   => array(),
            //     'dt_attributes'   => array("width" => "7%"),
            //     'rules'   => 'trim|required'
            // ),

            'job_category' => array(
                'table'   => $this->_table,
                'name'   => 'job_category',
                'label'   => 'Category',
                'type'   => 'multiselect',
                'type_dt'   => 'dropdown',
                'list_data' => "job_category",
                'list_data_key' => "job_category",
                'attributes'   => array(),
                'dt_attributes'   => array(
                    "width" => "100%",
                    "url" => base_url() . 'admin/job/job_categories'
                ),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_tags' => array(
                'table'   => $this->_table,
                'name'   => 'job_tags',
                'label'   => 'Tags',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_type' => array(
                'table'   => $this->_table,
                'name'   => 'job_type',
                'label'   => 'Type',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'attributes'   => array(),
                'list_data' => 'job_type',
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_level' => array(
                'table'   => $this->_table,
                'name'   => 'job_level',
                'label'   => 'Level',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'attributes'   => array(),
                'list_data' => array(
                    'Beginner' => "<span class='label label-danger'>Beginner</span>",
                    'Intermediate' => "<span class='label label-danger'>Intermediate</span>",
                    'Advanced' => "<span class='label label-danger'>Advanced</span>",
                ),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_language' => array(
                'table'   => $this->_table,
                'name'   => 'job_language',
                'label'   => 'Language',
                'type'   => 'multiselect',
                'type_dt'   => 'dropdown',
                'list_data' => "job_language",
                'list_data_key' => "job_language",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_url' => array(
                'table'   => $this->_table,
                'name'   => 'job_url',
                'label'   => 'Custom job URL',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_url'
            ),

            'job_application_email' => array(
                'table'   => $this->_table,
                'name'   => 'job_application_email',
                'label'   => 'Job contact email',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|valid_email'
            ),

            // 'job_submission_deadline' => array(
            //     'table'   => $this->_table,
            //     'name'   => 'job_submission_deadline',
            //     'label'   => 'Submission deadline',
            //     'type'   => 'date2',
            //     'type_dt'   => 'date',
            //     'attributes'   => array(),
            //     'dt_attributes'   => array("width" => "5%"),
            //     'js_rules'   => '',
            //     'rules'   => 'trim'
            // ),

            'job_salary_lower' => array(
                'table'   => $this->_table,
                'name'   => 'job_salary_lower',
                'label'   => 'Budget',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required|is_numeric'
            ),

            'job_salary_upper' => array(
                'table'   => $this->_table,
                'name'   => 'job_salary_upper',
                'label'   => 'Salary Upper Limit',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|is_numeric'
            ),

            'job_salary_interval' => array(
                'table'   => $this->_table,
                'name'   => 'job_salary_interval',
                'label'   => 'Salary Postfix',
                'type'   => 'hidden',
                'type_dt'   => 'select',
                'type_filter_dt' => 'select',
                'list_data' => array(
                    'hour' => "<span class='label label-danger'>hour</span>",
                    'day' => "<span class='label label-danger'>Day</span>",
                    'week' =>  "<span class='label label-primary'>Week</span>",
                    'month' =>  "<span class='label label-primary'>Month</span>",
                    'year' =>  "<span class='label label-primary'>Year</span>"
                ),
                'default'   => 'day',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim|required'
            ),

            'job_location' => array(
                'table'   => $this->_table,
                'name'   => 'job_location',
                'label'   => 'Address',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_company_detail' => array(
                'table'   => $this->_table,
                'name'   => 'job_company_detail',
                'label'   => 'Company Detail',
                'type'   => 'editor',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            // 'job_icon' => array(
            //     'table' => $this->_table,
            //     'name' => 'job_icon',
            //     'label' => 'Icon',
            //     'name_path' => 'job_image_path',
            //     'upload_config' => 'site_upload_job',
            //     'type' => 'fileupload',
            //     'type_dt' => 'image',
            //     'randomize' => true,
            //     'preview' => 'true',
            //     'attributes' => array(
            //         'image_size_recommended' => '1803px × 1046px',
            //         'allow_ext' => 'png|jpeg|jpg|webp',
            //     ),
            //     'thumb'   => array(),
            //     'dt_attributes' => array("width" => "10%"),
            //     'rules' => '',
            //     'js_rules' => ''
            // ),

            // 'job_image' => array(
            //     'table' => $this->_table,
            //     'name' => 'job_image',
            //     'label' => 'Image',
            //     'name_path' => 'job_image_path',
            //     'upload_config' => 'site_upload_job',
            //     'type' => 'fileupload',
            //     'type_dt' => 'image',
            //     'randomize' => true,
            //     'preview' => 'true',
            //     'attributes' => array(
            //         'image_size_recommended' => '1803px × 1046px',
            //         'allow_ext' => 'png|jpeg|jpg|webp',
            //     ),
            //     'thumb'   => array(),
            //     'dt_attributes' => array("width" => "10%"),
            //     'rules' => '',
            //     'js_rules' => ''
            // ),

            'job_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'job_expiry' => array(
                'table'   => $this->_table,
                'name'   => 'job_expiry',
                'label'   => 'Job expiry',
                'type'   => 'date2',
                'type_dt'   => 'date',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_attachment' => array(
                'table' => $this->_table,
                'name' => 'job_attachment',
                'label' => 'Video attachment (' . JOB_ATTACHMENT_SIZE_DESCIPTION . ')',
                'name_path' => 'job_image_path',
                'upload_config' => 'site_upload_job',
                'type' => 'videoupload',
                'type_dt' => 'file',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'image_size_recommended' => '1803px × 1046px',
                    'allow_ext' => 'mp4|mov|webm|mkv|avi|wmv',
                ),
                'thumb'   => array(),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => ''
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
