<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Newsletter
 */
class Newsletter extends MY_Controller
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

		$this->dt_params['dt_headings'] = "newsletter_id,newsletter_email,newsletter_createdon,newsletter_status";
		$this->dt_params['searchable'] = array("newsletter_id", "newsletter_email", "newsletter_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"order_field" => false,
			"show_delete" => true,
			"show_view" => true,
			"extra" => array(),
		);
		$this->_list_data['newsletter_status'] = array(
			STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
			STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
		);
		$this->form_params['action'] = array(
			'hide_save' => true,
			'hide_save_new' => false
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
		parent::add($id, $data = array());
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
			$this->model_newsletter->update_by_pk($id, array("newsletter_status" => 0));
		}
		parent::ajax_view($id);
	}
}

