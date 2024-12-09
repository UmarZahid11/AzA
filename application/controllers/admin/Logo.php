<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Logo
 */
class Logo extends MY_Controller
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

		$this->dt_params['dt_headings'] = "logo_id,logo_name,logo_src,logo_status";
		$this->dt_params['searchable'] = array("logo_id", "logo_name", "logo_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"show_delete" => true,
			"show_edit" => true,
			"order_field" => false,
			"show_view" => false,
			"extra" => array(),
		);

		$this->form_params['action'] = array(
			'hide_save' => true,
			'hide_save_new' => true
		);

		$this->_list_data['logo_status'] = array(
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
		$this->add_script(array("jquery.validate.js", "form-validation-script.js"), "js");

		parent::add($id, $data);
	}

	/**
	 * Method before_add_render
	 *
	 * @param &$data $data
	 *
	 * @return void
	 */
	public function before_add_render(&$data)
	{
		$this->layout_data['bread_crumbs'] = array(
			array(
				"home/" => "Home",
				'logo/add/1' => "Logo",
			)
		);
		return true;
	}

	/**
	 * Method index
	 *
	 * @return void
	 */
	public function index()
	{
		parent::index();
	}
}


