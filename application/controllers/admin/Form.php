<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Form
 */
class Form extends MY_Controller
{
	/**
	 * _list_data
	 *
	 * @var array
	 */
	public $_list_data = array();

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		global $config;
		parent::__construct();

		$this->dt_params['dt_headings'] = "form_id,form_name,form_email,form_message,form_status";
		$this->dt_params['searchable'] = array("form_id", "form_name", "form_heading", "form_email", "form_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"order_field" => false,
			"show_view" => true,
			"extra" => array(),
		);
		$config['js_config']['paginate'] = $this->dt_params['paginate'];
	}

	/**
	 * Method add
	 *
	 * @param int $id
	 * @param array $data
	 *
	 * @return void
	 */
	public function add($id = '', $data = array())
	{
		parent::add($id);
	}

	/**
	 * Method ajax_view
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function ajax_view($id = '')
	{
		if ($id) {
			$this->model_form->update_by_pk($id, array("form_status" => 0));
		}
		parent::ajax_view($id);
	}
}


