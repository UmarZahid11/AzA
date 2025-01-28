<?php

/**
 * Model_stripe_log
 */
class Model_stripe_log extends MY_Model
{
    protected $_table = 'stripe_log';
    protected $_field_prefix = 'stripe_log_';
    protected $_pk = 'stripe_log_id';
    protected $_status_field = 'stripe_log_status';
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
        $this->pagination_params['fields'] = "stripe_log_id, stripe_log_signup_id, stripe_log_createdon";
        parent::__construct();
    }

    /**
     * Method createStripeResource
     *
     * @param string $resourceType
     * @param array $resourcePayload
     * @param bool $debug
     *
     * @return object
     */
    function createStripeResource(string $resourceType = '', array $resourcePayload = [], bool $debug = FALSE): ?object
    {
        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
        $resourceDetail = NULL;
        if($resourceType) {
            try {
                $resourceDetail = $stripe->{$resourceType}->create($resourcePayload);
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
            }

            if ($debug) {
                echo '<pre>';
                print_r($resourceDetail);
                echo '</pre>';
            }
        }
        return $resourceDetail;
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

            'stripe_log_id' => array(
                'table' => $this->_table,
                'name' => 'stripe_log_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'stripe_log_signup_id' => array(
                'table' => $this->_table,
                'name' => 'stripe_log_signup_id',
                'label' => 'Login email',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "stripe_log_signup_id",
                'list_data' => "stripe_log_signup_id",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'stripe_log_status' => array(
                'table' => $this->_table,
                'name' => 'stripe_log_status',
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

            'stripe_log_createdon' => array(
                'table' => $this->_table,
                'name' => 'stripe_log_createdon',
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
