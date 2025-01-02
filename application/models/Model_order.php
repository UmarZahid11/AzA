<?php

/**
 * Model_order
 */
class Model_order extends MY_Model
{
    protected $_table = 'order';
    protected $_field_prefix = 'order_';
    protected $_pk = 'order_id';
    protected $_status_field = 'order_status';
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
        $this->pagination_params['fields'] = "order_id, order_reference_type, order_email, CONCAT(order_currency,' ',order_total) as order_total, order_payment_status, order_status";
        $this->pagination_params['group'] = 'order_id';
        $this->pagination_params['joins'][] = array(
            "table" => "order_item",
            "joint" => "order_item_order_id = order_id",
            "type" => "left"
        );
        parent::__construct();
    }

    /**
     * Method get_order_detail
     *
     * @param int $order_id
     * @param array $params
     *
     * @return array
     */
    public function get_order_detail(int $order_id, array $params = array()): array
    {
        $params['fields'] = "order.* ";
        return $this->find_by_pk($order_id, false, $params);
    }

    /**
     * Method auto_login
     *
     * @param int $order_id
     *
     * @return bool
     */
    public function auto_login(int $order_id): bool
    {
        $order = $this->find_by_pk($order_id, true);

        if (!$order) {
            return FALSE;
        } else {
            $this->set_order_session($order);
            return true;
        }
    }

    /**
     * Method login
     *
     * @return bool
     */
    public function login(): bool
    {
        $CI = &get_instance();

        $params['where']['order_email'] = $this->input->post('order_email');
        $params['where']['order_password'] = md5($this->input->post('order_password'));
        $order = $this->find_one($params, true);

        if (!$order) {
            $CI->form_validation->set_message('order_check', 'Incorrect ordername or ID');
            return FALSE;
        } else {
            $this->set_order_session($order);
            return true;
        }
    }

    /**
     * Method get_payment_status
     *
     * @param int $status
     *
     * @return void
     */
    public function get_payment_status(int $status)
    {
        switch ($status) {
            case PAYMENT_STATUS_PENDING:
            case PAYMENT_STATUS_COMPLETED:
            case PAYMENT_STATUS_CANCELLED:
            case PAYMENT_STATUS_TRIAL:
            case PAYMENT_STATUS_FAILED:
                $message = PAYMENT_STATUS[$status];
                break;
            default:
                $message = 'Order Placed';
                break;
        }
        return $message;
    }

    /**
     * Method set_order_session
     *
     * @param object $order
     *
     * @return void
     */
    public function set_order_session($order): void
    {
        $CI = &get_instance();

        $sess_array = array(
            'id' => $order->order_id,
            'ordername' => $order->order_ordername,
            'first_name' => $order->order_firstname,
            'last_name' => $order->order_lastname,
            'nameprefix' => $order->order_nameprefix,
            'email' => $order->order_email,
            'country' => $order->order_country,
            'dob' => $order->order_dob,
            'order_title' => $order->order_title,
            'profile_image' => $order->order_profile_image_path . $order->order_profile_image,
            'is_admin' => $order->order_is_admin,
        );

        $CI->session->set_orderdata('logged_in', $sess_array);
    }

    /**
     * Method verify_code
     *
     * @param int $oid
     * @param string $code
     *
     * @return array
     */
    public function verify_code($oid = 0, $code = null): array
    {
        $param['where']['order_id'] = $oid;
        $param['where']['order_access_code'] = $code;
        $result = $this->find_one($param);

        return $result;
    }

    /**
     * table	     Table Name
     * Name		 FIeld Name
     * label	     Field Label / Textual Representation in form and DT headings
     * type		 Field type : hidden, text, textarea, editor, etc etc.
     *						   Implementation in form_generator.php
     * type_dt	 Type used by prepare_datatables method in controller to prepare DT value
     *						   If left blank, prepare_datatable Will opt to use 'type'
     * attributes HTML Field Attributes
     * js_rules	 Rules to be aplied in JS (form validation)
     * rules	     Server side Validation. Supports CI Native rules
     */
    public function get_fields($specific_field = "")
    {
        $fields =  array(

            'order_id' => array(
                'table' => $this->_table,
                'name' => 'order_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'order_user_id' => array(
                'table' => $this->_table,
                'name' => 'order_user_id',
                'label' => 'User Name',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'dt_attributes' => array("width" => "5%"),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'order_session_checkout_id' => array(
                'table' => $this->_table,
                'name' => 'order_session_checkout_id',
                'label' => 'Checkout Id',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'dt_attributes' => array("width" => "5%"),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'order_reference_type' => array(
                'table' => $this->_table,
                'name' => 'order_reference_type',
                'label' => 'Type',
                'type' => 'switch',
                'type_dt' => 'dropdown',
                'list_data' => array(
                    ORDER_REFERENCE_MEMBERSHIP => "<span class=\"label label-danger\">" . PRODUCT_REFERENCE_MEMBERSHIP . "</span>",
                    ORDER_REFERENCE_PRODUCT => "<span class=\"label label-primary\">" . PRODUCT_REFERENCE_PRODUCT . "</span>",
                    // ORDER_REFERENCE_TECHNOLOGY => "<span class=\"label label-primary\">" . PRODUCT_REFERENCE_TECHNOLOGY . "</span>",
                    // ORDER_REFERENCE_SERVICE => "<span class=\"label label-primary\">" . PRODUCT_REFERENCE_SERVICE . "</span>",
                    ORDER_REFERENCE_JOB => "<span class=\"label label-primary\">" . PRODUCT_REFERENCE_JOB . "</span>",
                    ORDER_REFERENCE_COACHING => "<span class=\"label label-danger\">" . PRODUCT_REFERENCE_COACHING . "</span>",
                ),
                'default' => '',
                'attributes' => array(),
                'rules' => 'trim'
            ),

            'order_amount' => array(
                'table' => $this->_table,
                'name' => 'order_amount',
                'label' => 'order amount',
                'type' => 'text',
                'default' => '',
                'attributes' => array(),
                'rules' => 'trim'
            ),

            'order_total' => array(
                'table' => $this->_table,
                'name' => 'order_total',
                'label' => 'Total',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'order_firstname' => array(
                'table' => $this->_table,
                'name' => 'order_firstname',
                'label' => 'Firstname',
                'type' => 'text',
                'default' => '',
                'attributes' => array(),
                'rules' => 'required|trim'
            ),

            'order_lastname' => array(
                'table' => $this->_table,
                'name' => 'order_lastname',
                'label' => 'Lastname',
                'type' => 'text',
                'default' => '',
                'attributes' => array(),
                'rules' => 'required|trim'
            ),

            'order_fullname' => array(
                'table' => $this->_table,
                'name' => 'order_fullname',
                'label' => 'Full name',
                'type' => 'text',
                'default' => '',
                'attributes' => array(),
                'rules' => 'required|trim'
            ),

            'order_company' => array(
                'table' => $this->_table,
                'name' => 'order_company',
                'label' => 'Company',
                'type' => 'text',
                'default' => '',
                'attributes' => array(),
                'rules' => 'trim'
            ),

            'order_address1' => array(
                'table' => $this->_table,
                'name' => 'order_address1',
                'label' => 'Address',
                'type' => 'text',
                'attributes' => array(),
                'rules' => 'trim|htmlentities'
            ),

            'order_city' => array(
                'table' => $this->_table,
                'name' => 'order_city',
                'label' => 'City',
                'type' => 'textarea',
                'attributes' => array(),
                'rules' => 'trim'
            ),

            'order_country' => array(
                'table' => $this->_table,
                'name' => 'order_country',
                'label' => 'Country',
                'type' => 'text',
                'attributes' => array(),
                'rules' => 'trim'
            ),

            'order_phone' => array(
                'table' => $this->_table,
                'name' => 'order_phone',
                'label' => 'Phone',
                'type' => 'text',
                'attributes' => array(),
                'rules' => 'trim|regex_match[/^[\d\(\)\-+ ]+$/]'
            ),

            'order_email' => array(
                'table' => $this->_table,
                'name' => 'order_email',
                'label' => 'Email',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => 'required',
                'type_filter_dt' => 'text',
                'rules' => 'strtolower|trim|htmlentities'
            ),

            'order_quantity' => array(
                'table' => $this->_table,
                'name' => 'order_quantity',
                'label' => 'Quantity',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'order_merchant' => array(
                'table' => $this->_table,
                'name' => 'order_merchant',
                'label' => 'Merchant',
                'type' => 'text',
                'attributes' => array(),
                'rules' => 'trim|htmlentities'
            ),

            'order_createdon' => array(
                'table' => $this->_table,
                'name' => 'order_createdon',
                'label' => 'Registered On',
                'type' => 'hidden',
                'attributes' => array(),
                'rules' => ''
            ),

            'order_payment_status' => array(
                'table' => $this->_table,
                'name' => 'order_payment_status',
                'label' => 'Payment Status',
                'type' => 'switch',
                'type_dt' => 'dropdown',
                'list_data' => array(
                    PAYMENT_STATUS_COMPLETED => "<span class=\"label label-order-completed\">" . PAYMENT_STATUS[PAYMENT_STATUS_COMPLETED] . "</span>",
                    PAYMENT_STATUS_CANCELLED => "<span class=\"label label-order-pending\">" . PAYMENT_STATUS[PAYMENT_STATUS_CANCELLED] . "</span>",
                    PAYMENT_STATUS_TRIAL => "<span class=\"label label-order-denied\">" . PAYMENT_STATUS[PAYMENT_STATUS_TRIAL] . "</span>",
                    PAYMENT_STATUS_FAILED => "<span class=\"label label-order-failed\">" . PAYMENT_STATUS[PAYMENT_STATUS_FAILED] . "</span>",
                    PAYMENT_STATUS_PENDING => "<span class=\"label label-order-place\">" . PAYMENT_STATUS[PAYMENT_STATUS_PENDING] . "</span>",
                ),
                'type_filter_dt' => 'dropdown',
                'attributes' => array(),
                'rules' => 'text|trim|htmlentities'
            ),

            'order_dispatch_status' => array(
                'table' => $this->_table,
                'name' => 'order_dispatch_status',
                'label' => 'Is Dispatched?',
                'type' => 'switch',
                'type_dt' => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "order_dispatch_status",
                'list_data' => array(),
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),

            'order_status' => array(
                'table' => $this->_table,
                'name' => 'order_status',
                'label' => 'Status',
                'type' => 'switch',
                'default' => '1',
                'attributes' => array(),
                'rules' => 'trim'
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
