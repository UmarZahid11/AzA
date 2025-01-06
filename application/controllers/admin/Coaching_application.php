<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Coaching_application
 */
class Coaching_application extends MY_Controller
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

        $this->dt_params['dt_headings'] = "coaching_application_id, coaching_application_signup_id, coaching_application_coaching_id, coaching_application_status";
        $this->dt_params['searchable'] = array("coaching_application_id", "coaching_application_signup_id", "coaching_application_coaching_id", "coaching_application_status");
        $this->dt_params['action'] = array(
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

        $this->_list_data['coaching_application_signup_id'] = $this->model_signup->find_all_list_active(array(), 'signup_email');
        $this->_list_data['coaching_application_coaching_id'] = $this->model_coaching->find_all_list_active(array(), 'coaching_title');

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

                $where['where']['coaching_application_coaching_id'] = $user_data['coaching_application_coaching_id'];
                $where['where']['coaching_application_signup_id'] = $user_data['coaching_application_signup_id'];
                if($id) {
                    $where['where']['coaching_application_id != '] = $id;
                }

                if(empty($this->$model_name->find_one($where))) {

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
                    redirect($this->admin_path . "?msgtype=error&msg=The application already exists.", 'refresh');
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
}