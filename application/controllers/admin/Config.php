<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Config
 */
class Config extends MY_Controller
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

		$this->dt_params['dt_headings'] = "config_id,config_variable,config_value,config_status";
		$this->dt_params['searchable'] = array("config_id", "config_variable");
		$this->dt_params['action'] = array(
			"hide" => false,
			"show_delete" => false,
			"show_edit" => true,
			"order_field" => false,
			"show_view" => false,
			"extra" => array(),
		);

		$config['js_config']['paginate'] = $this->dt_params['paginate'];
	}

	/**
	 * Method update
	 *
	 * @return void
	 */
	public function update()
	{
		if (isset($_POST['config_attr'])) {
			if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
				$updated = $this->model_config->update_config($_POST['config_attr']);
				redirect("admin/config/update?msgtype=success&msg=$updated Record%20updated%20successfully.");
			} else {
				redirect("admin/config/update?msgtype=error&msg=" . ERROR_MESSAGE_LINK_EXPIRED);
			}
		}

		$this->layout_data['additional_tools'][] = "jstree";
		$this->add_script(array("jquery.validate.min.js"), "js");
		$this->register_plugins(array(
			"jquery-ui",
			"bootstrap",
			"bootstrap-hover-dropdown",
			"jquery-slimscroll",
			"uniform",
			"boots",
			"font-awesome",
			"simple-line-icons",
			"select2",
			"bootbox",
			"bootstrap-toastr",
		));

		$data['page_title'] = "Configuration";
		$data['class_name'] = "config";
		$data['model_name'] = "model_config";
		$data['model_obj'] = $this->model_config;
		$data['configuration'] = $this->model_config->get_admin_config();

		$this->load_view("_form", $data);
	}
}
