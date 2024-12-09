<?php

/**
 * Model_signup_quickbooks
 */
class Model_signup_quickbooks extends MY_Model
{
    protected $_table = 'signup_quickbooks';
    protected $_field_prefix = 'signup_quickbooks_';
    protected $_pk = 'signup_quickbooks_id';
    protected $_status_field = 'signup_quickbooks_status';
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
        $this->pagination_params['fields'] = "signup_quickbooks_id, signup_quickbooks_signup_id, signup_quickbooks_email, signup_quickbooks_password, signup_quickbooks_createdon";
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

            'signup_quickbooks_id' => array(
                'table' => $this->_table,
                'name' => 'signup_quickbooks_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_quickbooks_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_quickbooks_signup_id',
                'label' => 'User email',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "signup_quickbooks_signup_id",
                'list_data' => "signup_quickbooks_signup_id",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_quickbooks_email' => array(
                'table' => $this->_table,
                'name' => 'signup_quickbooks_email',
                'label' => 'QUicbooks email',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_quickbooks_password' => array(
                'table' => $this->_table,
                'name' => 'signup_quickbooks_password',
                'label' => 'QUicbooks password',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_quickbooks_status' => array(
                'table' => $this->_table,
                'name' => 'signup_quickbooks_status',
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

            'signup_quickbooks_createdon' => array(
                'table' => $this->_table,
                'name' => 'signup_quickbooks_createdon',
                'label' => 'Creation time',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

        );

        if ($specific_field)
            return $data[$specific_field];
        else
            return $data;
    }
}
