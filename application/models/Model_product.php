<?php

/**
 * Model_product
 */
class Model_product extends MY_Model
{
    protected $_table    = 'product';
    protected $_field_prefix    = 'product_';
    protected $_pk    = 'product_id';
    protected $_status_field    = 'product_status';
    public $relations = array();
    public $pagination_params = array();
    public $dt_params = array();
    public $_per_page    = 20;

    function __construct()
    {
        $this->pagination_params['fields'] = "product_id, product_signup_id, product_reference_type, product_number, product_name, CONCAT('$', product_cost) as product_cost, product_quantity, product_status";
        parent::__construct();
    }

    /**
     * Method isProductInCart
     *
     * @param int $product_id
     *
     * @return bool
     */
    public function isProductInCart(int $product_id): bool
    {
        if (!empty($this->cart->contents())) {
            foreach ($this->cart->contents() as $content) {
                if ($content['id'] == $product_id) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Method sameProductOwnerInCart
     *
     * @param int $product_id
     *
     * @return bool
     */
    public function sameProductOwnerInCart(int $product_id): bool
    {
        $product = $this->find_by_pk($product_id);
        if (!empty($this->cart->contents())) {
            foreach ($this->cart->contents() as $content) {
                if ($content['options']['owner'] == $product['product_signup_id']) {
                    return true;
                }
            }
        } else {
            return true;
        }
        return false;
    }

    /**
     * Method getProductRow
     *
     * @param int $product_id
     *
     * @return ?string
     */
    public function getProductRow(int $product_id, $return_key = 'rowid'): ?string
    {
        if (!empty($this->cart->contents())) {
            foreach ($this->cart->contents() as $key => $value) {
                if ($value['id'] == $product_id) {
                    return $value[$return_key];
                }
            }
        }
        return NULL;
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
    public function get_fields(string $specific_field = "")
    {
        $fields = array(
            'product_id' => array(
                'table'   => $this->_table,
                'name'   => 'product_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'product_signup_id' => array(
                'table' => $this->_table,
                'name' => 'product_signup_id',
                'label' => 'Owner',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "product_signup_id",
                'list_data' => "product_signup_id",
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_number' => array(
                'table' => $this->_table,
                'name' => 'product_number',
                'label' => 'Number',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_name' => array(
                'table' => $this->_table,
                'name' => 'product_name',
                'label' => 'Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_slug' => array(
                'table' => $this->_table,
                'name' => 'product_slug',
                'label' => 'Slug',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_quantity' => array(
                'table' => $this->_table,
                'name' => 'product_quantity',
                'label' => 'Quantity',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_cost' => array(
                'table' => $this->_table,
                'name' => 'product_cost',
                'label' => 'Cost',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_quantity' => array(
                'table' => $this->_table,
                'name' => 'product_quantity',
                'label' => 'Quantity',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_industry' => array(
                'table' => $this->_table,
                'name' => 'product_industry',
                'label' => 'Industry',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_category' => array(
                'table' => $this->_table,
                'name' => 'product_category',
                'label' => 'Category',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_function' => array(
                'table' => $this->_table,
                'name' => 'product_function',
                'label' => 'Funcation',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'product_reference_type' => array(
                'table'   => $this->_table,
                'name'   => 'product_reference_type',
                'label'   => 'Type',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "product_status",
                'list_data' => array(
                    PRODUCT_REFERENCE_PRODUCT => PRODUCT_REFERENCE_PRODUCT,
                    PRODUCT_REFERENCE_SERVICE => PRODUCT_REFERENCE_SERVICE,
                    PRODUCT_REFERENCE_TECHNOLOGY => PRODUCT_REFERENCE_TECHNOLOGY,
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'product_status' => array(
                'table'   => $this->_table,
                'name'   => 'product_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "product_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'product_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'product_createdon',
                'label'   => 'Created on',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'product_updatedon' => array(
                'table'   => $this->_table,
                'name'   => 'product_updatedon',
                'label'   => 'Updated on',
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
