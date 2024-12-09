<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Career
 */
class Career extends MY_Controller
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

		$this->dt_params['dt_headings'] = "career_id, career_job_title, career_createdon, career_status";
		$this->dt_params['searchable'] = array("career_id", "career_job_title", "career_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => false,
			"show_delete" => true,
			"show_edit" => true,
			"hide" => false,
			"order_field" => false,
			"show_view" => true,
			"extra" => array(),
		);
		$this->_list_data['career_category'] = $this->model_job_category->find_all_list_active(array(), 'job_category_name', 'job_category_name');
		$this->_list_data['career_job_type'] = $this->model_job_type->find_all_list_active(array(), 'job_type_name', 'job_type_name');
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
		parent::add($id, $data);
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
		parent::ajax_view($id);
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
		$result = array();
		$class_name = $this->router->class;
		$model_name = 'model_' . $class_name;
		$model_obj = $this->$model_name;
		$form_fields = $model_obj->get_fields();

		if ($id) {
			$parameter = array();
			if ($class_name == "career") {
				$parameter['fields'] = "career_job_title, career_category, career_job_type, career_application_deadline,career_salary_currency,career_description,career_company_name, career_website,career_industry,career_facebook,career_linkedin,career_twitter,career_instagram,career_company_description,career_recruiter_name,career_createdon";
			}
			$parameter['where'][$class_name . '_id'] = $id;
			$result['record'] = $this->$model_name->find_one($parameter);

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


