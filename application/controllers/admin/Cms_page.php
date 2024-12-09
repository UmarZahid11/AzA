<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Cms_page
 */
class Cms_page extends MY_Controller
{
	/**
	 * _list_data
	 *
	 * @var array
	 */
	public $_list_data = array();

	/**
	 * class
	 *
	 * @var mixed
	 */
	private $class;

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		global $config;
		parent::__construct();

		$this->dt_params['dt_headings'] = "cms_page_id, cms_page_name, cms_page_title, cms_page_status";
		$this->dt_params['searchable'] = array("cms_page_id", "cms_page_name", "cms_page_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"show_delete" => false,
			"show_edit" => true,
			"order_field" => false,
			"show_view" => false,
			"extra" => array(),
		);

		$this->_list_data['cms_page_status'] = array(
			STATUS_INACTIVE => "<span class=\"label label-danger\">Inactive</span>",
			STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
		);

		$this->form_params['action'] = array(
			'hide_save' => true,
			'hide_save_new' => false
		);

		$config['js_config']['paginate'] = $this->dt_params['paginate'];
		$this->class = $this->router->class;
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
				'cms_page/' => "Cms Page",
				'/' => "Add " . humanize($this->class),
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

	/**
	 * Method upload_images
	 *
	 * @return void
	 */
	public function upload_images()
	{

		$formdata = $_POST['cms_page'];
		$filedata = $_FILES['cms_page'];
		$cmsID = $formdata['cms_page_id'];

		$uploads_dir = 'assets/uploads/cms_page';
		$tmp_name = $filedata["tmp_name"]['cms_page_image'];
		$name = microtime() . "_" . $filedata["name"]['cms_page_image'];
		move_uploaded_file($tmp_name, "$uploads_dir/$name");

		$insertImage['cms_page_image'] = $name;
		$insertImage['cms_page_image_path'] = 'assets/uploads/cms_page/';

		$where['where']['cms_page_id'] = $cmsID;
		$status = $this->model_cms_page->update_model($where, $insertImage);
		if ($status) {
			echo json_encode(array('status' => 1, 'message' => 'image updated successfully.'));
		} else {
			echo json_encode(array('status' => 0, 'message' => 'Please try again.'));
		}
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
		$this->register_plugins("jquery-file-upload");
		$this->register_plugins("bootstrap-fileupload");

		if (!$id) {
			$this->form_params = array(
				"action" => array(
					"save_edit_attr" => "#tab_1",
					"hide_save" => true,
					"hide_save_new" => true,
					"hide_cancel" => true,
				),
			);
		}
		parent::add($id, $data = array());
	}
}
