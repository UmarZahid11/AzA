<?php

/**
 * Model_membership
 */
class Model_membership extends MY_Model
{
    protected $_table    = 'membership';
    protected $_field_prefix    = 'membership_';
    protected $_pk    = 'membership_id';
    protected $_status_field    = 'membership_status';
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
        $this->pagination_params['fields'] = "membership_id, membership_title, CONCAT('$', membership_cost) as membership_cost, membership_interval_id, membership_status";
        parent::__construct();
    }

    /**
     * Method membership_by_pk
     *
     * @param int $id
     *
     * @return string
     */
    public function membership_by_pk(int $id = 0): string
    {
        $membershipDetails = $this->model_membership->find_by_pk($id);
        if (!empty($membershipDetails)) {
            return $membershipDetails['membership_title'];
        }
        return NA;
    }

    /**
     * isCurrentMembership function
     *
     * @param integer $membership_id
     * @return boolean
     */
    public function isCurrentMembership(int $membership_id) : bool {
        if($this->userid > 0) {
            $membership = $this->model_signup->find_one(
                array(
                    'where' => array(
                        'signup_id' => $this->userid,
                        'signup_type' => $membership_id,
                        'signup_membership_status' => SUBSCRIPTION_ACTIVE
                    )
                )
            );

            if($membership) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method displayStripeButtons
     *
     * @param int $interval
     *
     * @return string
     */
    function displayStripeButtons(int $interval = SUBSCRIPTION_INTERVAL_1): string
    {
        $membership = $this->model_membership->find_all_active(
            array(
                'order' => 'membership_id asc',
                'limit' => 3,
                'where' => array(
                    'membership_id' => 3
                )
            )
        );

        $return_html = '<div class="row">';

        if (isset($membership) && count($membership) > 0) {

            $return_html .= '<div class="col-3"></div>';

            foreach ($membership as $value) {

                $return_html .= '<div class="col-9">';

                if (isset($value['membership_id'])) {

                    $order_details = $this->model_order->find_one_active(
                        array(
                            'where' => array(
                                'order_user_id' => $this->userid,
                                'order_reference_type' => ORDER_REFERENCE_MEMBERSHIP,
                                'order_reference_id' => $value['membership_id'],
                                'order_payment_status' => 1,
                                'order_status' => 1,
                                'order_status_message' => 'Completed',
                            )
                        )
                    );

                    $setActive = FALSE;
                    if ($order_details && $order_details['order_quantity'] == $interval) {
                        $setActive = TRUE;
                    } elseif ((isset($this->user_data['signup_membership_status']) && $this->user_data['signup_membership_status'] && isset($this->user_data['signup_type']) && $this->user_data['signup_type'] == ROLE_1)) {
                        $setActive = TRUE;
                    }

                    $cost = $this->model_membership_pivot->raw_pivot_value($value['membership_id'], COST_ATTRIBUTE);
                    switch ($interval) {
                        case SUBSCRIPTION_INTERVAL_1:
                            $cost = $cost == 0 ? price(0) : (price($cost));
                            break;
                        case SUBSCRIPTION_INTERVAL_2:
                            $cost = $cost == 0 ? price(0) : price(SUBSCRIPTION_INTERVAL_2_COST);
                            break;
                        case SUBSCRIPTION_INTERVAL_3:
                            $cost = $cost == 0 ? price(0) : price(SUBSCRIPTION_INTERVAL_3_COST);
                            break;
                    }
                    $return_html .= '<a href="' . l('membership/payment/') . $value['membership_id'] . '/' . JWT::encode($interval) . '" class="btn-mem '
                        . ((isset($this->user_data['signup_membership_status']) && $this->user_data['signup_membership_status'] && ($this->user_data['signup_type'] != ROLE_1 || $this->user_data['signup_type'] == $value['membership_id'])) ? 'pe-none' : '')
                        . (((isset($this->user_data['signup_type']) && ($this->user_data['signup_type'] == $value['membership_id']) && ($setActive)) ? ' active' : '')) . '">'
                        . (((isset($this->user_data['signup_type']) && $this->user_data['signup_type'] == $value['membership_id']) && ($setActive)) ? 'Active' : ('Pay ' . ($cost) . ' ' . 'with Credit/Debit card'));
                    $return_html .= '</a>';
                }

                $return_html .= '</div>';
            }
        }

        $return_html .= '</div>';

        return $return_html;
    }
    
    function displayPaypalButtons(int $interval = SUBSCRIPTION_INTERVAL_1): string
    {
        $order_details = array();

        $membership = $this->model_membership->find_all_active(
            array(
                'order' => 'membership_id asc',
                'limit' => 3,
                'where' => array(
                    'membership_id' => 3
                )
            )
        );

        $return_html = '';

    if (isset($membership) && count($membership) > 0) {

            foreach ($membership as $value) {

                if (isset($value['membership_id'])) {

                    if($this->userid) {
                        $order_details = $this->model_order->find_one_active(
                            array(
                                'where' => array(
                                    'order_user_id' => $this->userid,
                                    'order_reference_type' => ORDER_REFERENCE_MEMBERSHIP,
                                    'order_reference_id' => $value['membership_id'],
                                    'order_payment_status' => 1,
                                    'order_status' => 1,
                                    'order_status_message' => 'Completed',
                                )
                            )
                        );
                    }

                    $setActive = FALSE;
                    if ($order_details && $order_details['order_quantity'] == $interval) {
                        $setActive = TRUE;
                    } elseif ((isset($this->user_data['signup_membership_status']) && $this->user_data['signup_membership_status'] && isset($this->user_data['signup_type']) && $this->user_data['signup_type'] == ROLE_1)) {
                        $setActive = TRUE;
                    }

                    $cost = $this->model_membership_pivot->raw_pivot_value($value['membership_id'], COST_ATTRIBUTE);
                    switch ($interval) {
                        case SUBSCRIPTION_INTERVAL_1:
                            $cost = $cost == 0 ? price(0) : (price($cost));
                            break;
                        case SUBSCRIPTION_INTERVAL_2:
                            $cost = $cost == 0 ? price(0) : price(SUBSCRIPTION_INTERVAL_2_COST);
                            break;
                        case SUBSCRIPTION_INTERVAL_3:
                            $cost = $cost == 0 ? price(0) : price(SUBSCRIPTION_INTERVAL_3_COST);
                            break;
                    }

                    if($setActive) {
                        $return_html .= '<div class="col-5 offset-5">OR</div>';
                        $return_html .= '<div class="row">';
                        $return_html .= '<div class="col-3"></div>';
                        $return_html .= '<div class="col-9">';
                        $return_html .= '<a href="' . l('membership/payment/') . $value['membership_id'] . '/' . JWT::encode($interval) . '/' . PAYPAL . '" >';
                        $return_html .= '<img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" border="0" alt="PayPal Acceptance Mark" />';
                        $return_html .= '</a>';
                        $return_html .= '</div>';
                    }
                }
            }
        }

        $return_html .= '</div>';

        return $return_html;
    }

    /**
     * Method subscriptionStatus
     *
     * @param string $status
     *
     * @return int
     */
    function subscriptionStatus(string $status = 'pending'): int
    {
        switch ($status) {
            case 'active':
            case 'complete':
            case 'REGULAR':
                return SUBSCRIPTION_ACTIVE;
                break;
            case 'canceled':
                return SUBSCRIPTION_CANCELLED;
                break;
            case 'TRIAL':
            case 'trialing':
                return SUBSCRIPTION_TRIAL;
                break;
            default:
                return SUBSCRIPTION_INACTIVE;
        }
    }

    /**
     * Method subscriptionStatusString
     *
     * @param int $status
     *
     * @return string
     */
    function subscriptionStatusString(int $status = 0): string
    {
        switch ($status) {
            case SUBSCRIPTION_ACTIVE:
                return 'active';
                break;
            case SUBSCRIPTION_CANCELLED:
                return 'canceled';
                break;
            case SUBSCRIPTION_TRIAL:
                return 'trialing';
                break;
            default:
                return 'pending';
        }
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
            'membership_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_title' => array(
                'table'   => $this->_table,
                'name'   => 'membership_title',
                'label'   => 'Title',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim|alpha_numeric_spaces'
            ),

            'membership_cost' => array(
                'table'   => $this->_table,
                'name'   => 'membership_cost',
                'label'   => 'Cost',
                'type'   => 'number',
                'type_dt'   => 'number',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'membership_interval_id' => array(
                'table'   => $this->_table,
                'name'   => 'membership_interval_id',
                'label'   => 'Interval',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "membership_interval_id",
                'list_data' => "membership_interval_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'membership_interval_count' => array(
                'table'   => $this->_table,
                'name'   => 'membership_interval_count',
                'label'   => 'interval count',
                'type'   => 'number',
                'default' => 1,
                'type_dt'   => 'number',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_custom_description' => array(
                'table'   => $this->_table,
                'name'   => 'membership_custom_description',
                'label'   => 'Custom interval description',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'membership_icon' => array(
                'table' => $this->_table,
                'name' => 'membership_icon',
                'label' => 'Image',
                'name_path' => 'membership_image_path',
                'upload_config' => 'site_upload_membership',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes' => array(
                    'image_size_recommended' => '1803px × 1046px',
                    'allow_ext' => 'png|jpeg|jpg|webp|gif',
                ),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules' => ''
            ),

            'membership_status' => array(
                'table'   => $this->_table,
                'name'   => 'membership_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "membership_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'membership_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'membership_createdon',
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
