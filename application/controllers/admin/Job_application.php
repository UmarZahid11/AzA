<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Job_application
 */
class Job_application extends MY_Controller
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

		$this->dt_params['dt_headings'] = "job_application_id, job_application_signup_id, job_application_job_id, job_application_request_status, job_application_status";
		$this->dt_params['searchable'] = array("job_application_id", "job_application_request_status", "job_application_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"show_delete" => true,
			"hide" => false,
			"order_field" => false,
			"show_view" => true,
			"extra" => array(
				0 => array(
					'type' => 'application',
					'button' => '<a title="View application details" datat-toggle="tooltip" href="' . $config['base_url'] . 'dashboard/application/detail/' . '%1$d' . '/' . '%2$d' . '" class="btn-sm btn btn-primary"><i class="icon-doc"></i></a>',
				)
			),
		);

        $this->_list_data['job_application_signup_id'] = $this->model_signup->find_all_custom_list_active(array('where' => array('signup_type' => ROLE_3)), array('signup_firstname', 'signup_lastname'));
        $this->_list_data['job_application_job_id'] = $this->model_job->find_all_custom_list_active(array(), array('job_title'));

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
		redirect(g('admin_base_url') . "job_application");
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
			// $this->model_job_application->update_by_pk($id, array("job_application_status" => 0));
		}
		parent::ajax_view($id);
	}

	/**
	 * Method get_view
	 *
	 * @param int $id
	 *
	 * @return array
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
			if ($class_name == "job_application") {
                $parameter['joins'][] = array(
                    "table"=> "signup" ,
                    "joint"=> "signup.signup_id = job_application.job_application_signup_id",
                );
                $parameter['joins'][] = array(
                    "table"=> "job" ,
                    "joint"=> "job.job_id = job_application.job_application_job_id",
                );
				$parameter['fields'] = "job_application_id, CONCAT(signup_firstname, ' ', signup_lastname) As signup_fullname, job_title";
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

