<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Cms_content
 */
class Cms_content extends MY_Controller
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

		$this->dt_params['dt_headings'] = "cms_content_id,cms_content_page_id,cms_content_position,cms_content_status";
		$this->dt_params['searchable'] = array("cms_content_id", "cms_content_name", "cms_content_desc_short", "cms_content_meta_title", "cms_content_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"show_delete" => true,
			"show_edit" => true,
			"order_field" => false,
			"show_view" => false,
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
		$this->layout_data['additional_tools'][] = "jstree";
		$this->_list_data['cms_content_page_id'] = $this->model_cms_page->find_all_list_active(array(), "cms_page_name");
		$this->add_script(array("jquery.validate.js", "form-validation-script.js"), "js");
		parent::add($id);
	}
}
