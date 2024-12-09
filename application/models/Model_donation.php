<?php

/**
 * Model_donation
 */
class Model_donation extends MY_Model
{
    protected $_table = 'donation';
    protected $_field_prefix = 'donation_';
    protected $_pk = 'donation_id';
    protected $_status_field = 'donation_status';
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
        $this->pagination_params['fields'] = "donation_id, fundraising_title, donation_email, CONCAT('$ ', donation_amount) as donation_amount, donation_status, DATE_FORMAT(donation_createdon, '%d %M, %Y %h:%i %p') as donation_createdon";
        $this->pagination_params['joins'][] = array(
            "table" => "fundraising",
            "joint" => "fundraising.fundraising_id = donation.donation_reference",
            "type" => "left"
        );
        parent::__construct();
    }

    function donationByActivity($activity = 0) : int {
        $amount = 0;

        if($activity) {
            $query = 'SELECT sum(donation_amount) as donation_amount FROM fb_donation where donation_type = "'. DONATION_FUNDRAISING .'" AND donation_reference = '.$activity.' AND donation_status = 1 group by donation_reference';
        } else {
            $query = 'SELECT sum(donation_amount) as donation_amount FROM fb_donation where donation_type = "'. DONATION_GENERAL .'" AND donation_status = 1 group by donation_reference';
        }
        $row = ($this->db->query($query)->row());
        if($row) {
            $amount = $row->donation_amount;
        }
        return $amount;
    }

    /*
    * table       Table Name
    * Name        FIeld Name
    * label       Field Label / Textual Representation in form and DT headings
    * type        Field type : hidden, text, textarea, editor, etc etc.
    *                           Implementation in form_generator.php
    * type_dt     Type used by prepare_datatables method in controller to prepare DT value
    *                           If left blank, prepare_datatable Will opt to use 'type'
    * attributes  HTML Field Attributes
    * js_rules    Rules to be aplied in JS (form validation)
    * rules       Server side Validation. Supports CI Native rules
    */
    public function get_fields($specific_field = "")
    {
        $fields = array(

            'donation_id' => array(
                'table' => $this->_table,
                'name' => 'donation_id',
                'label' => 'id #',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'dt_attributes' => array("width" => "5%"),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'fundraising_title' => array(
                'table'   => $this->_table,
                'name'   => 'fundraising_title',
                'label'   => 'Fundraising Activity',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'donation_email' => array(
                'table'   => $this->_table,
                'name'   => 'donation_email',
                'label'   => 'Donor',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'donation_amount' => array(
                'table'   => $this->_table,
                'name'   => 'donation_amount',
                'label'   => 'Amount',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'donation_status' => array(
                'table' => $this->_table,
                'name' => 'donation_status',
                'label' => 'Status?',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>Incomplete</span>",
                    1 =>  "<span class='label label-primary'>Completed</span>"
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),
           
            'donation_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'donation_createdon',
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
