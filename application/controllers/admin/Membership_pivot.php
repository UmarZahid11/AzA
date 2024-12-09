<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Membership_pivot
 */
class Membership_pivot extends MY_Controller
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

        $this->dt_params['dt_headings'] = "membership_pivot_id, membership_pivot_membership_id, membership_pivot_attribute_id, membership_pivot_value, membership_pivot_status";
        $this->dt_params['searchable'] = array("membership_pivot_id", "membership_pivot_membership_id", "membership_pivot_attribute_id", "membership_pivot_value", "membership_pivot_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => false,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['membership_pivot_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>" ,
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );
        $this->_list_data['membership_pivot_membership_id'] = $this->model_membership->find_all_list_active(array(), 'membership_title');
        $this->_list_data['membership_pivot_attribute_id'] = $this->model_membership_attribute_identifier->find_all_list_active(
            array(
                'joins' => array(
                    0 => array(
                        "table" => "membership_attribute" ,
                        "joint" => "membership_attribute.membership_attribute_id = membership_attribute_identifier.membership_attribute_identifier_id",
                        "type" => "both"
                    )
                )
            ), 
            'membership_attribute_name'
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
        parent::index();
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

                $check_param = [];
                $check_param['where'][$pk . ' !='] = $id;
                $check_param['where']['membership_pivot_attribute_id'] = $user_data['membership_pivot_attribute_id'];
                $check_param['where']['membership_pivot_membership_id'] = $user_data['membership_pivot_membership_id'];
                $check_pivot = $this->$model_name->find_one_active($check_param);

                if($check_pivot) {
                    redirect($this->admin_path . "?msgtype=error&msg=Pivot values already exists.", 'refresh');
                    exit();
                } else {

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
