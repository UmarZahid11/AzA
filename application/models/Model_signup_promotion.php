<?php

/**
 * Model_signup_promotion
 */
class Model_signup_promotion extends MY_Model
{
    protected $_table = 'signup_promotion';
    protected $_field_prefix = 'signup_promotion_';
    protected $_pk = 'signup_promotion_id';
    protected $_status_field = 'signup_promotion_status';
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
        $this->pagination_params['fields'] = "signup_promotion_id, signup_promotion_signup_id, CONCAT('$', signup_promotion_price) as signup_promotion_price, signup_promotion_status";
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

            'signup_promotion_id' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_promotion_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_signup_id',
                'label' => 'Signup',
                'type'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "signup_promotion_signup_id",
                'list_data' => $this->model_signup->find_all_list_active(array(), 'signup_email'),
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_promotion_type' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_type',
                'label' => 'Type',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_promotion_title' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_title',
                'label' => 'Title',
                'type' => 'text',
                'type_dt' => 'text',
                'min' => 0,
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            // 'signup_promotion_trial' => array(
            //     'table' => $this->_table,
            //     'name' => 'signup_promotion_trial',
            //     'label' => 'Trial days',
            //     'type' => 'number',
            //     'type_dt' => 'text',
            //     'min' => 0,
            //     'value' => 0,
            //     'attributes' => array(),
            //     'js_rules' => '',
            //     'rules' => 'trim'
            // ),

            'signup_promotion_url' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_url',
                'label' => 'Session URL',
                'type' => 'hidden',
                'type_dt' => 'url',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_promotion_price' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_price',
                'label' => 'Price',
                'type' => 'number',
                'type_dt' => 'text',
                'min' => 0,
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_promotion_description' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_description',
                'label' => 'Description (optional)',
                'type' => 'textarea',
                'type_dt' => 'textarea',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),            

            'signup_promotion_status' => array(
                'table' => $this->_table,
                'name' => 'signup_promotion_status',
                'label' => 'Status',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>Expired</span>",
                    1 =>  "<span class='label label-primary'>Active</span>",
                    2 =>  "<span class='label label-red'>Deleted</span>",
                    3 =>  "<span class='label label-order-reversed'>Availed</span>"
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
