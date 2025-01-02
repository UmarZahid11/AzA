<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Coaching
 */
class Coaching extends MY_Controller
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

        $this->dt_params['dt_headings'] = "coaching_id, coaching_title, coaching_start_time, coaching_duration, coaching_current_status, coaching_status";
        $this->dt_params['searchable'] = array("coaching_id", "coaching_title", "coaching_status");
        $this->dt_params['action'] = array(
            "view_webinar_button" => true,
            "hide_add_button" => false,
            "hide" => false,
            "hide_save_new" => true,
            "hide_save_edit" => true,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['banner_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-danger\">Inactive</span>",
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
                
                $webinar_status = $this->saveWebinar($user_data);
                
                if($webinar_status['status']) {
                    
                    //
                    $user_data = $webinar_status['coaching'];
                    
    				$this->$model_name->set_attributes($user_data);
    
    				$insertId = $this->$model_name->save();
    
    				if ($insertId) {
    					$this->$model_name->update_relations($insertId);
    					$this->afterSave($insertId, $this->$model_name);
    
    					// Prevent Return From Parent Add Method(current),
    					// since we need to perform operations in Child Class's Method
    					if ($this->prevent_return_on_success)
    						return $insertId;
    
    					$this->add_redirect_success($insertId);
    				} else {
    					redirect($this->admin_path . "?msgtype=error&msg=Couldnt Save Data.", 'refresh');
    					exit();
    				}
                } else {
                    $message = $webinar_status['message'] ?? ERROR_MESSAGE;
					redirect($this->admin_path . "?msgtype=error&msg=" . $message, 'refresh');
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
     * Method saveWebinar
     *
     * @return void
     */
    public function saveWebinar(array $coaching)
    {
        $status = FALSE;
        $message = ERROR_MESSAGE;
        $update = FALSE;

        $affect = $coaching;

        if (validateDate($affect['coaching_start_time'], 'Y-m-d\TH:i')) {
            //
            if (isset($affect['coaching_id']) && $affect['coaching_id']) {

                $param = array();
                $param['where']['coaching_id'] = $affect['coaching_id'];
                $coaching = $this->model_coaching->find_one($param);

                if (isset($coaching['coaching_fetchid']) && $coaching['coaching_fetchid']) {
                    $update = TRUE;
                }
            }

            //
            $post_fields = array(
                'agenda' => isset($affect['coaching_title']) && $affect['coaching_title'] ? $affect['coaching_title'] : '',
                'duration' => isset($affect['coaching_duration']) && $affect['coaching_duration'] ? (int) $affect['coaching_duration'] : 2,
                'password' => isset($affect['coaching_password']) && $affect['coaching_password'] ? $affect['coaching_password'] : '123',
                'settings' => array(
                    'approval_type' => 2,
                    'auto_recording' => 'none',
                    'contact_email' => isset($affect['coaching_contact_email']) ? $affect['coaching_contact_email'] : g('db.admin.email'),
                    'contact_name' => isset($affect['coaching_contact_name']) ? $affect['coaching_contact_name'] : 'AzAverze',
                    'mute_upon_entry' => 'true',
                ),
                'question_and_answer' => array(
                    'allow_anonymous_questions' => false,
                    'answer_questions' => 'true',
                ),
                'meeting_authentication' => 'false',
                'panelist_authentication' => 'false',
                // 'webinar_practice_session' => isset($affect['webinar_practice_session']) && $affect['webinar_practice_session'] ? 'true' : 'false',
                'start_time' => date('Y-m-d\TH:i:s\Z', strtotime($affect['coaching_start_time'])),
                'timezone' => isset($affect['coaching_timezone']) && $affect['coaching_timezone'] ? $affect['coaching_timezone'] : 'Pacific/Midway',
                'topic' => isset($affect['coaching_title']) && $affect['coaching_title'] ? $affect['coaching_title'] : '',
                'type' => isset($affect['coaching_type']) && $affect['coaching_type'] ? $affect['coaching_type'] : 5,
            );
            //
            $headers = $this->getZoomBearerHeader();

            //
            if ($update) {
                $url = str_replace('{webinarId}', $coaching['coaching_fetchid'], ZOOM_WEBINAR_URL);
                $response = $this->curlRequest($url, $headers, $post_fields, FALSE, TRUE, REQUEST_PATCH);
            } else {
                $url = str_replace('{userId}', 'me', ZOOM_CREATE_WEBINAR_URL);
                $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
            }
            $decoded_response = json_decode($response);
            //

            if (($decoded_response && isset($decoded_response->start_url) && NULL !== $decoded_response->start_url) || (in_array($this->session->userdata['last_http_status'], [200, 204], TRUE))) {
                $affect['coaching_start_url'] = $decoded_response->start_url;
                $affect['coaching_join_url'] = $decoded_response->join_url;
                $affect['coaching_timezone'] = $decoded_response->timezone;
                $affect['coaching_uuid'] = $decoded_response->uuid;
                $affect['coaching_fetchid'] = $decoded_response->id;
                $affect['coaching_host_id'] = $decoded_response->host_id;
                $affect['coaching_host_email'] = $decoded_response->host_email;
                $affect['coaching_response'] = $response;
                //
                $status = TRUE;
            } else {
                $message = (isset($decoded_response->message) && null !== $decoded_response->message) ? $decoded_response->message : __(ERROR_MESSAGE);
            }
        } else {
            $message = __('The webinar start time is invalid.');
        }
        
        return ['status' => $status, 'message' => $message, 'coaching' => $affect];
    }
}