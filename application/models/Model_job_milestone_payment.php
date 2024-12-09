<?php

/**
 * Model_job_milestone_payment
 */
class Model_job_milestone_payment extends MY_Model
{
    protected $_table    = 'job_milestone_payment';
    protected $_field_prefix    = 'job_milestone_payment_';
    protected $_pk    = 'job_milestone_payment_id';
    protected $_status_field    = 'job_milestone_payment_status';
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
        $this->pagination_params['fields'] = "job_milestone_payment_id, upper(CONCAT(signup_firstname, ' ' ,signup_lastname)) as signup_fullname, job_title, CONCAT('$', job_milestone_payment_amount) as job_milestone_payment_amount, CONCAT('$', job_milestone_payment_due) as job_milestone_payment_due, DATE_FORMAT(job_milestone_payment_createdon, '%M, %d %Y %h:%i:%s') as job_milestone_payment_createdon, DATE_FORMAT(job_milestone_payment_updatedon, '%M, %d %Y %h:%i:%s') as job_milestone_payment_updatedon, job_milestone_payment_money_position_status";
        $this->pagination_params['joins'] = array(
            0 => array(
                "table" => "job_milestone",
                "joint" => "job_milestone.job_milestone_id = job_milestone_payment.job_milestone_payment_milestone_id",
                "type"  => "both"
            ),
            1 => array(
                "table" => "job",
                "joint" => "job.job_id = job_milestone.job_milestone_job_id",
                "type"  => "both"
            ),
            2 => array(
                "table" => "job_application",
                "joint" => "job_application.job_application_id = job_milestone.job_milestone_application_id",
                "type"  => "both"
            ),
            3 => array(
                "table" => "signup",
                "joint" => "signup.signup_id = job_application.job_application_signup_id",
                "type"  => "both"
            )
        );
        parent::__construct();
    }

    /**
     * Method milestone_payment
     *
     * @param array $data
     * @param int $status
     * @param string $index
     * @param bool $isGeneral
     *
     * @return int
     */
    public function milestone_payment(array $data, int $status = 0, string $index = '', bool $isGeneral = false): int
    {
        $resultant_array = NULL;
        foreach ($data as $value) {
            if (!$isGeneral) {
                if (!isset($resultant_array[$value['job_milestone_payment_money_position_status']])) {
                    $resultant_array[$value['job_milestone_payment_money_position_status']] = 0;
                }
                $resultant_array[$value['job_milestone_payment_money_position_status']] += $value[$index];
            } else {
                if (!$resultant_array) {
                    $resultant_array = 0;
                }
                $resultant_array += $value[$index];
            }
        }

        if (is_array($resultant_array) && !empty($resultant_array[$status]))
            return $resultant_array[$status];
        elseif (!is_array($resultant_array) && is_int($resultant_array))
            return $resultant_array;
        else
            return 0;
    }

    /**
     * Method get_payment_status
     *
     * @param int $status
     *
     * @return void
     */
    public function get_payment_status($status)
    {
        switch ($status) {
            case 1:
                $message = 'Payment Accepted';
                break;
            case 2:
                $message = 'Payment Declined';
                break;
            case 3:
                $message = 'Transaction Failed';
                break;
            case 4:
                $message = 'Held for Review';
                break;
            default:
                $message = 'Order Placed';
                break;
        }

        return $message;
    }

    /**
     * Method get_money_status
     *
     * @param int $status
     *
     * @return void
     */
    public function get_money_status(int $status)
    {
        switch ($status) {
            case 1:
                $message = '<label class="label label-primary">Paid</label>';
                break;
            case 2:
                $message = '<label class="label label-warning">In Escrow</label>';
                break;
            default:
                $message = '<label class="label label-default">Pending</label>';
                break;
        }

        return $message;
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
            'job_milestone_payment_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_title' => array(
                'table'   => 'job',
                'name'   => 'job_title',
                'label'   => 'Job',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'signup_fullname' => array(
                'table'   => 'job',
                'name'   => 'signup_fullname',
                'label'   => 'Applicant',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_milestone_payment_amount' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_amount',
                'label'   => 'Milestone amount',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_milestone_payment_due' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_due',
                'label'   => 'Payment due',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_milestone_payment_money_position_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_money_position_status',
                'label'   => 'Due Payment Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_milestone_payment_money_position_status",
                'list_data' => array(
                    MILESTONE_PAYMENT_PENDING => "<span class='label label-danger'>Pending</span>",
                    MILESTONE_PAYMENT_PAID => "<span class='label label-primary'>Paid</span>",
                    MILESTONE_PAYMENT_ESCROW => "<span class='label label-warning'>Escrow</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'job_milestone_payment_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_milestone_payment_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>Inactive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'job_milestone_payment_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_createdon',
                'label'   => 'Createdon',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'job_milestone_payment_updatedon' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_payment_updatedon',
                'label'   => 'Updatedon',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
