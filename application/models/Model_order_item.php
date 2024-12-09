<?php

/**
 * Model_order_item
 */
class Model_order_item extends MY_Model
{
	protected $_table	= 'order_item';
	protected $_field_prefix	= 'order_item_';
	protected $_pk	= 'order_item_id';
	protected $_status_field	= 'order_item_status';
	public $pagination_params = array();
	public $relations = array();
	public $dt_params = array();
	public $_per_page	= 20;

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	function __construct()
	{
		$this->pagination_params['fields'] = "order_item_id,order_item_user_id,order_item_total_items,order_item_total,order_item_ostatus_id";
		parent::__construct();
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
		$fields = array(

			'order_item_id' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_id',
				'label'   => 'id #',
				'type'   => 'hidden',
				'type_dt'   => 'text',
				'attributes'   => array(),
				'dt_attributes'   => array("width" => "5%"),
				'js_rules'   => '',
				'rules'   => 'trim'
			),

			'order_item_order_id' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_order_id',
				'label'   => 'order_item_order_id',
				'type'   => 'dropdown',
				'type_dt'   => 'text',
				'type_filter_dt'   => 'dropdown',
				'rules'   => 'intval|required'
			),

			'order_item_product_id' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_product_id',
				'label'   => 'order_item_product_id',
				'type'   => 'dropdown',
				'type_dt'   => 'text',
				'type_filter_dt'   => 'dropdown',
				'rules'   => 'intval|required'
			),

			'order_item_qty' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_qty',
				'label'   => 'order_item_qty',
				'type'   => 'text',
				'rules'   => 'intval'
			),

			'order_item_color' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_color',
				'label'   => 'order_item_color',
				'type'   => 'text',
				'rules'   => 'intval'
			),

			'order_item_type' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_type',
				'label'   => 'type',
				'type'   => 'text',
				'rules'   => ''
			),

			'order_item_total' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_total',
				'label'   => 'order_item_total',
				'type'   => 'text',
				'rules'   => 'floatval'
			),


			'order_item_name' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_name',
				'label'   => 'order_item_name',
				'type'   => 'text',
				'rules'   => ''
			),


			'order_item_image' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_image',
				'label'   => 'order_item_image',
				'type'   => 'text',
				'rules'   => ''
			),

			'order_item_price' => array(
				'table'   => $this->_table,
				'name'   => 'order_item_price',
				'label'   => 'order_item_price',
				'type'   => 'text',
				'rules'   => 'floatval'
			),

		);

		if ($specific_field)
			return $fields[$specific_field];
		else
			return $fields;
	}
}