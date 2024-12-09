<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Application
 */
class Application extends MY_Controller
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
		$this->dt_params['dt_headings'] = "application_id,application_name,application_image,application_status";
		$this->dt_params['searchable'] = array("application_id", "application_name", "application_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"show_delete" => true,
			"show_edit" => true,
			"order_field" => false,
			"show_view" => false,
			"extra" => array(),
		);

		$this->_list_data['application_status'] = array(
			STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
			STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
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
}


