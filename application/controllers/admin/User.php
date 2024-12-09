<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * User
 */
class User extends MY_Controller
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

		$this->dt_params['dt_headings'] = "user_id,user_username,user_email,user_createdon,user_status";
		$this->dt_params['searchable'] = array("user_id", "user_username", "user_email", "user_status");
		$this->dt_params['action'] = array(
			"hide" => false,
			"show_delete" => false,
			"show_edit" => false,
			"order_field" => false,
			"show_view" => true,
			"extra" => array(),
		);

		$this->_list_data['user_status'] = array(
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
		parent::add($id);
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
	 * Method get_view
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function get_view($id = 0)
	{
		global $config;

		$result = array();
		$class_name = $this->router->class;
		$model_name = 'model_' . $class_name;
		$model_obj = $this->$model_name;
		$form_fields = $model_obj->get_fields();

		if ($id) {
			$result['record'] = $this->$model_name->find_by_pk($id);
			$result['record'] = $this->$model_name->prepare_view_data($result['record']);
			if (!$result['record']) {
				$result['failure'] = "No Item Found";
			}

			$relation_data = $this->$model_name->get_relation_data($id);
			if (array_filled($relation_data)) {
				$result['record']['relation_data'] = $relation_data;
			}
		} else {
			$result['failure'] = "No Item Found";
		}
		return $result;
	}
}
