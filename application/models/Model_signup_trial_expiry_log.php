<?php

/**
 * Model_signup_trial_expiry_log
 */
class Model_signup_trial_expiry_log extends MY_Model
{
    protected $_table = 'signup_trial_expiry_log';
    protected $_field_prefix = 'signup_trial_expiry_log_';
    protected $_pk = 'signup_trial_expiry_log_id';
    protected $_status_field = 'signup_trial_expiry_log_status';
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
        $this->pagination_params['fields'] = "signup_trial_expiry_log_id, signup_trial_expiry_log_signup_id, signup_trial_expiry_log_status, signup_trial_expiry_log_createdon";
        parent::__construct();
    }

    /**
     * getBySignup function
     *
     * @param integer $userId
     * @return array
     */
    function getBySignupId($userId = 0): array
    {
        return $this->find_all_active(
            array(
                'signup_trial_expiry_log_signup_id' => $userId
            )
        );
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

            'signup_trial_expiry_log_id' => array(
                'table' => $this->_table,
                'name' => 'signup_trial_expiry_log_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_trial_expiry_log_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_trial_expiry_log_signup_id',
                'label' => 'Login email',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "signup_trial_expiry_log_signup_id",
                'list_data' => "signup_trial_expiry_log_signup_id",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_trial_expiry_log_status' => array(
                'table' => $this->_table,
                'name' => 'signup_trial_expiry_log_status',
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

            'signup_trial_expiry_log_createdon' => array(
                'table' => $this->_table,
                'name' => 'signup_trial_expiry_log_createdon',
                'label' => 'Attempt time',
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
