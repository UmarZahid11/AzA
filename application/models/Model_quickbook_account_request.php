<?php

/**
 * Model_quickbook_account_request
 */
class Model_quickbook_account_request extends MY_Model
{
    protected $_table = 'quickbook_account_request';
    protected $_field_prefix = 'quickbook_account_request_';
    protected $_pk = 'quickbook_account_request_id';
    protected $_status_field = 'quickbook_account_request_status';
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
        $this->pagination_params['fields'] = "quickbook_account_request_id, quickbook_account_request_signup_id, quickbook_account_request_createdon";
        parent::__construct();
    }

    /**
     * requestExists
     *
     * @param integer $userid
     * @return boolean
     */
    function requestExists(int $userid = 0) : bool {
        if($userid) {
            $account = $this->getAccount($userid);
            if($account) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * getAccount function
     *
     * @param integer $userid
     * @return array
     */
    function getAccount(int $userid = 0) : array {
        $account = [];
        if($userid) {
            $account = $this->find_one_active(
                array(
                    'quickbook_account_request_signup_id' => $userid
                )
            );
        }
        return $account;
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

            'quickbook_account_request_id' => array(
                'table' => $this->_table,
                'name' => 'quickbook_account_request_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'quickbook_account_request_signup_id' => array(
                'table' => $this->_table,
                'name' => 'quickbook_account_request_signup_id',
                'label' => 'Requestor email',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "quickbook_account_request_signup_id",
                'list_data' => "quickbook_account_request_signup_id",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'quickbook_account_request_status' => array(
                'table' => $this->_table,
                'name' => 'quickbook_account_request_status',
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

            'quickbook_account_request_createdon' => array(
                'table' => $this->_table,
                'name' => 'quickbook_account_request_createdon',
                'label' => 'Request time',
                'type' => 'date',
                'type_dt' => 'date',
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
