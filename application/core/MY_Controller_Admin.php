<?php

/**
 * Admin Controller Wrapper Class.
 *
 * @package		Admin Controller
 * @author
 * @version		1.0
 * @since		Version 1.0 2022
 *
 */
class MY_Controller_Admin extends CI_Controller
{
	private static $instance;

	/**
	 * csrf_token
	 *
	 * @var mixed
	 */
	public $csrf_token;

	/**
	 * layout_data
	 *
	 * @var array
	 */
	protected $layout_data = array();

	/**
	 * layout
	 *
	 * @var mixed
	 */
	protected $layout;

	// FOR Add methods, to prevent_return on Success
	/**
	 * prevent_return_on_success
	 *
	 * @var bool
	 */
	public $prevent_return_on_success = false;

	/**
	 * dt_params
	 *
	 * @var array
	 */
	public $dt_params = array();

	/**
	 * _list_data
	 *
	 * @var array
	 */
	public $_list_data = array();

	/**
	 * form_data
	 *
	 * @var array
	 */
	public $form_data = array();

	/**
	 * _validation_models_add
	 *
	 * @var array
	 */
	public $_validation_models_add = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $config;
		parent::__construct();

		$config = $this->config->config;
		$this->session_data = $this->layout_data['session_data'] = $this->session->userdata('logged_in');
		$this->user_data = $this->session->userdata("logged_in");
		$this->chk_currency();
		$config['js_config']['ci_class'] = $config['ci_class'] = $this->router->class;
		$config['js_config']['ci_method'] = $config['ci_method'] = $this->router->method;
		$config['js_config']['ci_index_page'] = $config['ci_index_page'] = $config['ci_class'] . "_" . $config['ci_method'];

		$this->layout_data['query_string'] = $_SERVER['QUERY_STRING'];
		$this->layout_data['additional_tools'] = array();

		if (!isset($this->dt_params['paginate']))
			$this->dt_params['paginate'] = array();

		$this->dt_params['paginate']['class'] = $config['ci_class'];
		$this->dt_params['paginate']['uri'] = "paginate";
		$this->dt_params['paginate']['update_status_uri'] = "update_status";
		$this->layout_data['template_config'] = array(
			'show_toolbar' => true,
		);
		$config['js_config']['paginate'] = $this->dt_params['paginate'];

		// csrf_token - Custom
		$this->csrf_token = $this->create_csrf_token((isset($_REQUEST['_token'])) ? FALSE : TRUE);

		// override config value by admin configuration value
		$config['title'] = $config['site_name'] = $config['admin_title'] = $this->getConfigValueByVariable('title');
	}

	/**
	 * Method index
	 *
	 * @return void
	 */
	public function index()
	{
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

	/* ======================== CSRF START ======================== */

	/**
	 * Method create_csrf_token
	 *
	 * @param bool $forceRefresh
	 *
	 * @return string
	 */
	protected function create_csrf_token(bool $forceRefresh = FALSE): string
	{
		// csrf_token
		if (!$this->session->has_userdata('csrf_token') || $forceRefresh) {

			// $this->session->set_userdata('csrf_token', csrf_token());
			$this->session->set_userdata('csrf_token', JWT::encode(CI_ENCRYPTION_PUBLIC));

			// log_message('error', '_token: ' . $this->session->userdata('csrf_token'));
			// log_message('error', 'refreshing csrf ' . $this->session->userdata['csrf_token']);
		}
		return $this->session->userdata('csrf_token');
	}

	/**
	 * Method verify_csrf_token
	 *
	 * @param string $_token
	 *
	 * @return bool
	 */
	protected function verify_csrf_token(string $_token): bool
	{
		$token = filter_var($_token, FILTER_SANITIZE_STRING);

		if ($token && (JWT::decode($token, CI_ENCRYPTION_SECRET) === JWT::decode($this->session->userdata['csrf_token'], CI_ENCRYPTION_SECRET))) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ======================== CSRF END ======================== */

	/* ======================== CONFIG START ======================== */

	/**
	 * Method getConfigValue
	 *
	 * @param int $config_id
	 *
	 * @return ?string
	 */
	public function getConfigValue($config_id = 0): ?string
	{
		if ($config_id) {
			$config_detail = $this->model_config->find_by_pk($config_id);
			if (!empty($config_detail) && isset($config_detail['config_value'])) {
				return $config_detail['config_value'];
			}
			return NULL;
		}
		return NULL;
	}

	/**
	 * Method getConfigValueByVariable
	 *
	 * @param string $config_variable
	 *
	 * @return ?string
	 */
	public function getConfigValueByVariable($config_variable = ''): ?string
	{
		if ($config_variable) {
			$config_detail = $this->model_config->find_one_active(
				array(
					'where' => array(
						'config_variable' => $config_variable
					)
				)
			);
			if (!empty($config_detail) && isset($config_detail['config_value'])) {
				return $config_detail['config_value'];
			}
			return NULL;
		}
		return NULL;
	}

	/* ======================== CONFIG END ======================== */

	/**
	 * Method client_email
	 *
	 * @param $to $to
	 * @param $template $template
	 * @param $title $title
	 *
	 * @return void
	 */
	public function client_email($to, $template, $title)
	{
		$this->load->library('email');

		$db_to = g("db.admin.email");
		$name = g('site_name');
		$send_to = $to;
		$message = $template;
		$this->email->from($db_to, $name);
		$this->email->to($send_to);
		$this->email->subject($title);
		$this->email->set_mailtype("html");
		$this->email->message($message);
		return $this->email->send();
	}

	/**
	 * Method paginate
	 *
	 * @param array $dt_params
	 *
	 * @return void
	 */
	public function paginate($dt_params = array())
	{
		global $config;
		$params = array();
		$data = array();
		$pg_request = $_POST;

		$class_name = $this->router->class;
		$model_name = "model_" . $class_name;
		$model_obj = property_exists($this, $model_name) ? $this->$model_name : NULL;

		$params = $model_obj ? $model_obj->pagination_params : [];
		if (!isset($params['order'])) {
			$sort_col = $pg_request['order'][0]['column'];

			if ($sort_col !== null) {
				$sort_type = $pg_request['order'][0]['dir'];
				$params['order'] = $sort_col . " " . $sort_type;
			}
		}

		$length = intval($pg_request['length']);

		if ($model_obj) {
			$model_obj->_per_page = $length ? $length : ($model_obj ? $model_obj->_per_page : 20);
		}

		$records = $model_obj ? $model_obj->pagination_query($params) : [];

		// $dt_params['order_field'] = $model_obj->get_order_field_name();
		if ($records && is_array($records['data']))
			$data = $this->prepare_datatable($records['data']);

		$dt_record = array();
		$dt_record["data"] = $data;
		$dt_record["draw"] = $pg_request["draw"];
		$dt_record["recordsTotal"] = (isset($records['count']) ? $records['count'] : 0);
		$dt_record["recordsFiltered"] = (isset($records['count']) ? $records['count'] : 0);
		echo json_encode($dt_record);

		exit();
	}

	/**
	 * Method get_mysqli
	 *
	 * @return void
	 */
	public function get_mysqli()
	{
		$db = (array)get_instance()->db;
		return mysqli_connect('localhost', $db['username'], $db['password'], $db['database']);
	}

	/**
	 * Method prepare_datatable
	 *
	 * @param array $record
	 * @param array $dt_params
	 *
	 * @return void
	 */
	public function prepare_datatable($record, $dt_params = array())
	{
		global $config;
		$class = $this->router->class;

		$model_name = "model_" . $class;
		$model_obj = $this->$model_name;
		$model_fields = $model_obj->get_fields();
		$model_pk = $model_obj->get_pk();

		if (!array_filled($dt_params))
			$dt_params = $this->dt_params;

		// if record is an array
		if (is_array($record)) {
			$dt_row = array();

			foreach ($record as $row_key => $row) {

				$dt_row[$row_key] = array();

				$field_key = 0;

				foreach ($row as $field => $value) {

					$value = mysqli_real_escape_string($this->get_mysqli(), $value);
                    if($field == 'fundraising_title') {
                        if($value == '') {
                            $value = 'AzAverze';
                        }
                    }
					$field_attr = $model_fields[$field];
					$field_type = (isset($field_attr['type_dt'])) ? $field_attr['type_dt'] : $field_attr['type'];

					//  if field is PK, generate checkbox for multiple row section
					if ($model_pk == $field) {
						// Do PK related types
						$itemId = intval($value);
						$dt_row[$row_key][$field_key] = '<input type="checkbox" value="' . $itemId . '" name="selected[' . $model_pk . '][]">';
						$field_key++;
					}

					switch ($field_type) {
					    case 'date':
							$value = date('d M, Y h:i a', strtotime($value));
					        break;
						case 'url':
							$value = '<a href="'.$value.'" target="_blank">Go</a>';
							break;
						case 'text':
						case 'textarea':
						case 'label':
						case 'label_custom':
						case 'editor':
							$value = html_entity_decode(strip_tags($value));
							$value = truncate($value, 256);
							break;

						case 'image':
							$image_url = $value ? $config['base_url'] . $value : $config['image_not_found'];
							$value = '<img src="' . $image_url . '" style="max-height:30px;"/>';
							break;

						case 'switch':
							$list_data = (isset($field_attr['list_data'])) ? $field_attr['list_data'] : array();
							if (!array_filled($list_data)) {
								$list_data = array(
									STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>",
									STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
								);
							}
							$value = $list_data[$value];
							break;
						case 'dropdown':
							$list_data = (isset($field_attr['list_data'])) ? $field_attr['list_data'] : array();
							if (!array_filled($list_data)) {
								$list_data_key = (isset($field_attr['list_data_key'])) ? $field_attr['list_data_key'] : $field;
								$list_data = $this->_list_data[$list_data_key];
							}
							$value = isset($list_data[$value]) ? $list_data[$value] : '';
							break;

						case 'hidden':
							continue 2;
							break;
					}

					$dt_row[$row_key][$field_key] = $value;

					$field_key++;
				}

				if (!$dt_params['action']['hide']) {
					$delete_button = "";
					$edit_button = "";
					$order_field = "";
					$toggle_button = "";
					$view_button = "";
					$lifetime_subscription_button = "";
					

					// Delete button
					if ((isset($dt_params['action']['show_delete'])) && ($dt_params['action']['show_delete']))
						$delete_button = '<button title="Delete" class="btn_delete_product btn-sm btn red"' .
							' data-model="model_' . $class . '" data-pk="' . $itemId . '"  >' .
							'<i class="icon-trash "></i></button>';

					// Edit button
					if ((isset($dt_params['action']['show_edit'])) && ($dt_params['action']['show_edit']))
						$edit_button = '<a title="Edit" href="' . $config['admin_base_url'] . $class . '/add/' . $itemId . '/"' .
							' target="_blank"><button class="btn-sm btn yellow" ' .
							'data-model="model_' . $class . '" data-pk="' . $itemId . '" ' .
							'>' .
							'<i class="fa fa-edit"></i></button></a>';

					// Order field - Hidden
					if ((isset($dt_params['action']['order_field'])) && ($dt_params['action']['order_field']))
						$order_field = '<input type="hidden" class="order_field_val" value="' . '' . '" data-item-id="' . $itemId . '">';

					// View Button
					if ((isset($dt_params['action']['show_view'])) && ($dt_params['action']['show_view']))
						$view_button = '<button title="View" data-href="' . $config['admin_base_url'] . $class . '/ajax_view/' . $itemId . '/" class="btn-sm btn btn_view_product btn-primary" data-pk="' . $itemId . '"><i class="icon-picture"></i></button>';

					// Approve Button
					if ((isset($dt_params['action']['approve_button'])) && ($dt_params['action']['approve_button'])) {
						if ($model_name == 'model_signup') {
							if (isset($this->$model_name->find_by_pk($itemId)['signup_is_approved']) && $this->$model_name->find_by_pk($itemId)['signup_is_approved']) {
								$toggle_button = '<button class="btn-sm btn btn-dark approveSignuptoggle" data-id="' . $itemId . '" data-toggle="tooltip" data-placement="top" title="Approve/Disapprove User"><i class="fa fa-toggle-on"></i></button>';
							} else {
								$toggle_button = '<button class="btn-sm btn btn-dark approveSignuptoggle" data-id="' . $itemId . '" data-toggle="tooltip" data-placement="top" title="Approve/Disapprove User"><i class="fa fa-toggle-off"></i></button>';
							}
						}
					}

					// lifetime_subscription_button
					if ((isset($dt_params['action']['lifetime_subscription_button'])) && ($dt_params['action']['lifetime_subscription_button'])) {
						if ($model_name == 'model_signup') {
							if (isset($this->$model_name->find_by_pk($itemId)['signup_lifetime_subscription']) && $this->$model_name->find_by_pk($itemId)['signup_lifetime_subscription']) {
								$lifetime_subscription_button = '<button class="btn-sm btn btn-dark lifetimeSubscriptiontoggle" data-id="' . $itemId . '" data-toggle="tooltip" data-placement="top" title="Disable lifetime subscription"><i class="fa fa-user-times"></i></button>';
							} else {
								$lifetime_subscription_button = '<button class="btn-sm btn btn-dark lifetimeSubscriptiontoggle" data-id="' . $itemId . '" data-toggle="tooltip" data-placement="top" title="Enable lifetime subscription"><i class="fa fa-user-plus"></i></button>';
							}
						}
					}

					$extend_trial_button = '';
					if ((isset($dt_params['action']['extend_trial_button'])) && ($dt_params['action']['extend_trial_button'])) {
						if ($model_name == 'model_signup') {

							//
							$itemDetail =  $this->$model_name->find_by_pk($itemId);
							$type = '';

							if (
								(isset($itemDetail['signup_type'])) 
							) {
								$type = $itemDetail['signup_type'] == ROLE_1 ? 'consumer' : ($itemDetail['signup_type'] == ROLE_3 ? 'premium' : '');
							}

							if($type && (($itemDetail['signup_type'] == ROLE_1 && $itemDetail['signup_trial_expiry'] != '') || ($itemDetail['signup_type'] == ROLE_3 && $itemDetail['signup_subscription_status'] == SUBSCRIPTION_TRIAL && $itemDetail['signup_trial_expiry'] != ''))) {
								$extend_trial_button = '<button class="btn-sm btn btn-dark extendTrialBtn" data-type="'.$type.'" id="extendTrialBtn' . $itemId . ' " data-id="' . $itemId . '" data-toggle="tooltip" data-placement="top" title="Extend free trial"><i class="fa fa-calendar"></i></button>';
							}
						}
					}

					$extra_buttons = '';

					// if controller has extra buttons
					if (array_filled($dt_params['action']['extra'])) {
						foreach ($dt_params['action']['extra'] as $btn) {
							if(isset($btn['type']) && $btn['type'] == 'application') {
								$job_application = $this->model_job_application->find_by_pk($itemId);
								if($job_application) {
									$extra_buttons .= '<a title="View application details" datat-toggle="tooltip" href="' . $config['base_url'] . 'dashboard/application/detail/' . JWT::encode($itemId) . '/' . $job_application['job_application_job_id'] . '" class="btn-sm btn btn-primary"><i class="icon-doc"></i></a>';
								}
							} else {
								$extra_buttons .= sprintf($btn, $itemId);
							}
						}
					}

					if (!$dt_params['action']['hide'])
						$dt_row[$row_key][$field_key] = $extend_trial_button . $lifetime_subscription_button . $toggle_button . $view_button . $edit_button . $delete_button . $extra_buttons . $order_field;
				}
			}
		}

		return $dt_row;
	}

	/**
	 * Method configure_add_page
	 *
	 * @return void
	 */
	public function configure_add_page()
	{
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

		# code...
	}

	/**
	 * Method add
	 *
	 * @param int $id
	 * @param array $data
	 *
	 * @return void
	 */
	public function add($id = 0, $data = array())
	{
		global $config;
		$id = intval($id);
		$this->configure_add_page();
		$class_name = $this->router->class;
		$model_name = 'model_' . $class_name;
		$model_obj = $this->$model_name;
		$form_fields = $model_obj->get_fields();

		// Prepare models used in this action
		$model_array = array();
		//$model_array = $this->_extra_models_add;
		$model_array[] = $model_name;

		$this->_validation_models_add[] = $model_name;


		$pk = $model_obj->get_pk();

		if ($id) {

			$params['where'][$pk] = $id;
			$this->form_data[$class_name] = $this->$model_name->find_one($params);

			// Load relation fields data
			$this->form_data['relation_data'] = $this->$model_name->get_relation_data($id);

			if (!is_array($this->form_data[$class_name]) || empty($this->form_data[$class_name])) {
				redirect($this->admin_path . "?msgtype=error&msg=404+-+Record+not+found.", 'refresh');
				exit();
			}
		}

		$this->layout_data['bread_crumbs'] = array(
			array(
				"home/" => "Home",
				$class_name => humanize($class_name),
				$class_name . "/add/" => "Add " . humanize($class_name),
			)
		);

		$user_data = $this->input->post(NULL, true);

		$data['form_data'] = (isset($this->form_data)) ? $this->form_data : array();

		$data['user_input'] = (isset($user_data['login'])) ? $user_data['login'] : array();

		if ($_POST) {
			if ($this->bulk_validate($this->_validation_models_add)) {
				// Validation Successful
				$user_data = $_POST[$class_name];

				// Merge FILES field with POST DATA
				if ((isset($user_data)) && (is_array($user_data)) && (isset($_FILES[$class_name]['name'])))
					$user_data = $user_data + $_FILES[$class_name]['name'];


				$this->$model_name->set_attributes($user_data);

				$insertId = $this->$model_name->save();

				if ($insertId) {
					$this->$model_name->update_relations($insertId);
					$this->afterSave($insertId, $this->$model_name);

					// Prevent Return From Parent Add Method(current),
					// since we need to perform operations in Child Class's Method
					if ($this->prevent_return_on_success)
						return $insertId;

					if ($class_name == 'job_testimonial_request') {
						switch ($user_data['job_testimonial_request_current_status']) {
							case REQUEST_ACCEPTED:
								$this->model_notification->sendNotification($user_data['job_testimonial_request_signup_id'], $user_data['job_testimonial_request_signup_id'], NOTIFICATION_TESTIMONIAL_REQUEST_ACCEPTED, $user_data['job_testimonial_request_id'], NOTIFICATION_TESTIMONIAL_REQUEST_ACCEPTED_COMMENT);
								break;
							case REQUEST_REJECTED:
								$this->model_notification->sendNotification($user_data['job_testimonial_request_signup_id'], $user_data['job_testimonial_request_signup_id'], NOTIFICATION_TESTIMONIAL_REQUEST_REJECTED, $user_data['job_testimonial_request_id'], NOTIFICATION_TESTIMONIAL_REQUEST_REJECTED_COMMENT);
								break;
							case REQUEST_EXTENDED:
								$this->model_notification->sendNotification($user_data['job_testimonial_request_signup_id'], $user_data['job_testimonial_request_signup_id'], NOTIFICATION_TESTIMONIAL_REQUEST_EXTENDED, $user_data['job_testimonial_request_id'], NOTIFICATION_TESTIMONIAL_REQUEST_EXTENDED_COMMENT);
								break;
						}
					}

					$this->add_redirect_success($insertId);
				} else {
					redirect($this->admin_path . "?msgtype=error&msg=Couldnt Save Data.", 'refresh');
					exit();
				}
			} else {
				$data['error'] = validation_errors();
			}
		}

		$data['page_title_min'] = "Management";
		$data['page_title'] = $class_name;
		$data['class_name'] = $class_name;
		$data['model_name'] = $model_name;
		$data['model_obj'] = $model_obj;
		$data['form_fields'][$class_name] = $form_fields;
		$data['dt_params'] = $this->dt_params;
		$data['id'] = $id;

		$this->before_add_render($data);
		$this->load_view("_form", $data);
	}

	/**
	 * Method afterSave
	 *
	 * @param int $insertId
	 * @param array $model
	 *
	 * @return void
	 */
	public function afterSave($insertId, $model)
	{
		return true;
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
		echo json_encode($this->get_view($id));
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
			if (!$result['record'])
				$result['failure'] = "No Item Found";
			// Load relation fields data
			$relation_data = $this->$model_name->get_relation_data($id);
			if (array_filled($relation_data))
				$result['record']['relation_data'] = $relation_data;
		} else {
			$result['failure'] = "No Item Found";
		}

		return $result;
	}

	/**
	 * Method add_redirect_success
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function add_redirect_success($id)
	{
		$path = $this->admin_path;
		if (isset($_POST['submit'])) {
			switch ($_POST['submit']) {
				case "SaveNEdit":
					$path = $this->admin_current . $id;
					break;
				case "SaveNNew":
					$path = $this->admin_current;
					break;
				default:
					$path = $this->admin_path;
					break;
			}
		}
		redirect($path . "?msgtype=success&msg=Record updated successfully.", 'refresh');
		return $id;
	}

	/**
	 * Method delete_selected - Default Action to Bulk Delete from admin
	 *
	 * @return void
	 */
	public function delete_selected()
	{
		$id_array = explode(",", rtrim($_POST['params']['pk'], ","));

		foreach ($id_array as $id) {
			$_POST['params']['pk'] = intval($id);
			if (intval($id))
				$this->delete();
		}
	}

	/**
	 * Method before_add_render
	 * BeforeRender Hook to manipulate Overrides... for Add Method
	 *
	 * @param &$data $data
	 *
	 * @return void
	 */
	public function before_add_render(&$data)
	{
		// To access from Child Class
		return true;
	}

	/**
	 * Method before_index_render
	 * BeforeRender Hook to manipulate Overrides... for Index method
	 *
	 * @param &$data $data
	 *
	 * @return void
	 */
	public function before_index_render(&$data)
	{
		// To access from Child Class
		return true;
	}

	/**
	 * Method delete
	 *
	 * @return void
	 */
	public function delete()
	{

		$id = intval($_POST['params']['pk']);

		if ($id) {
			$model = $_POST['params']['model'];
			$model_obj = $this->{$model};
			$pk = $model_obj->get_pk();
			$status_field = $model_obj->get_status_field();
			$data[$status_field] = 2;

			if ($this->router->class == $model) {

				$this->db->where($pk, $id);
				$update = $this->db->update($model, $data);
				if ($update == true)
					echo "1";
			}
		}
	}

	/**
	 * Method permanent_delete
	 *
	 * @return void
	 */
	public function permanent_delete()
	{

		$id = intval($_POST['params']['pk']);

		if ($id) {
			$class_name = $this->router->class;
			$model_name = 'model_' . $class_name;
			$model_obj = $this->$model_name;
			return $model_obj->delete_by_pk($id);
		}
	}

	/**
	 * Method update_status
	 * Default Action to Update Record
	 * Mostly to update Status
	 *
	 * @return void
	 */
	public function update_status()
	{
		extract($_POST);
		if (array_filled($idList) && $model) {

			$updateVal = intval($updateVal);
			$model_obj = $this->{$model};
			$status_field = $model_obj->get_status_field();
			$pk = $model_obj->get_pk();
			if ($status_field && $pk) {
				$record[$status_field] = $updateVal;
				$params['where_in'][$pk] = $idList;
				$ret['affected'] = $model_obj->update_model($params, $record);
				end_script(json_encode($ret));
			}
		}
	}

	/**
	 * Method update
	 * Default Action to Update Record
	 * Mostly to update Status
	 *
	 * @return void
	 */
	public function update()
	{
		if (is_array($_POST['params']) && count($_POST['params'])) {
			$model = $_POST['params']['model'];
			$pk = $_POST['params']['pk'];
			$val = $_POST['params']['val'] == 0 ? 1 : 0;
			$field = $_POST['params']['field'];

			$data[$field] = $val;

			if ($this->router->class == $model) {
				$this->db->where($pk, $pk);
				$update = $this->db->update($model, $data);
				if ($update == true) echo 1;
				else echo 0;
			}
		}
	}

	/**
	 * Method reorder - Default Action to Reorder Objects. Requre DnD plugin in datatables
	 *
	 * @return void
	 */
	public function reorder()
	{

		global $config;
		extract($_POST);
		$this->load->model(array($model));
		$effected = $this->$model->reorder_records($_POST);

		echo $effected;
		exit();
	}

	/**
	 * Method parse_for_excel
	 * Parse Records for Excel
	 * $record MUST HAVE key : $records['data'], that  hold record
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	public function parse_for_excel($records = array())
	{
		$data = $records['data'];
		if (array_filled($data)) {
			if ($data[0]) {
				foreach ($data[0] as $heads => $value) {
					$records['headers'][] = $heads;
				}
			}
		}
		$records['heading'] = $records['heading'] ? $records['heading'] : $this->router->class;
		return $records;
	}

	/**
	 * Method export_excel
	 * Default Admin Action to Export files to Excel
	 * Export to Excel
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	public function export_excel($params = array())
	{
		$model_name = "model_" . $this->router->class;

		$this->load->model($model_name);
		$model_obj = $this->$model_name;
		$model_obj->pagination_params['limit'] = 1000000000;
		// Merge $params with child params
		$params += $model_obj->pagination_params;
		$records = $model_obj->pagination_query($params);
		$data['records'] = $this->parse_for_excel($records);
		$data['filename'] = $this->router->class;

		$this->load_view("excel_export", $data, false, false);
	}
}
