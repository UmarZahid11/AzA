<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Inquiry
 */
class Inquiry extends MY_Controller
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

		$this->dt_params['dt_headings'] = "inquiry_id, inquiry_fullname, inquiry_email, inquiry_createdon, inquiry_status";
		$this->dt_params['searchable'] = array("inquiry_id", "inquiry_fullname", "inquiry_email", "inquiry_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"show_delete" => true,
			"hide" => false,
			"order_field" => false,
			"show_view" => true,
			"extra" => array(
				'<a data-toggle="tooltip" title="Send email" href="' . $config['admin_base_url'] . 'inquiry/email/%d/" class="btn-sm btn btn-warning"><i class="icon-paper-plane"></i></a>',
			),
		);

		$config['js_config']['paginate'] = $this->dt_params['paginate'];
	}

	/**
	 * Method index
	 *
	 * @return void
	 */
	public function index()
	{
		global $config;

		$class_name = $this->router->class;
		$model_name = "model_" . $class_name;

		$model_obj = $this->$model_name;

		$this->layout_data['bread_crumbs'] = array(
			array(
				"home/" => "Home",
				$class_name => humanize($class_name)
			)
		);

		$this->register_plugins(array(
			"jquery-ui",
			"bootstrap",
			"bootstrap-hover-dropdown",
			"jquery-slimscroll",
			"uniform",
			"bootstrap-switch",
			"bootstrap-datepicker",
			"boots",
			"font-awesome",
			"simple-line-icons",
			"select2",
			"datatables",
			"bootbox",
			"bootstrap-toastr",

		));

		$this->add_script("pages/tasks.css");
		$this->add_script(array("table-ajax.js", "datatable.js"), "js");

		$data['page_title_min'] = "Management";
		$data['page_title'] = $class_name;
		$data['class_name'] = $class_name;
		$data['model_name'] = $model_name;
		$data['model_obj'] = $model_obj;
		$data['model_fields'] = $model_obj->get_fields();
		$data['dt_params'] = $this->dt_params;

		$data['model'] = "$model_name";
		$this->before_index_render($data);
		$this->load_view("datatable", $data);
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
		redirect(g('admin_base_url') . "inquiry");
	}

	/**
	 * Method ajax_view
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function ajax_view($id = 0)
	{
		if ($id) {
			$this->model_inquiry->update_by_pk(
				$id,
				array(
					"inquiry_status" => 0
				)
			);
		}
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
			if ($class_name == "inquiry") {
				$parameter['fields'] = "inquiry_fullname, inquiry_email, inquiry_phone, inquiry_ip, inquiry_address, inquiry_comments";
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

	function email(int $id = 0) : void {
		$data = array();

        $this->register_plugins(array(
            "jquery-ui",
            "bootstrap",
            "bootstrap-hover-dropdown",
            "jquery-slimscroll",
            "uniform",
            "bootstrap-switch",
            "bootstrap-datepicker",
            "boots",
            "font-awesome",
            "simple-line-icons",
            "select2",
            "datatables",
            "bootbox",
            "bootstrap-toastr",
        ));

		$data['inquiry'] = $this->model_inquiry->find_by_pk($id);

		if(!$data['inquiry']) {
			error_404();
		}

		$this->load_view('email', $data);
	}

	function sendEmail() : void {
		global $config;

		$json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
			if(isset($_POST['email']) && $_POST['email']) {
				$to = $_POST['to'];
				$subject = $_POST['subject'] ?? $config['title'] . ' - Inquiry Response';
				$message = $_POST['email'];
				$this->model_email->fire_email($to, '', $subject, $message);

				$json_param['status'] = STATUS_TRUE;
				$json_param['txt'] = SUCCESS_MESSAGE;

			} else {
				$json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
			}
		} else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
	}
}
