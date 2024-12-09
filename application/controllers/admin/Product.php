<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Product
 */
class Product extends MY_Controller
{
    /**
     * _list_data
     *
     * @var array
     */
    public $_list_data = array();

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        global $config;

        parent::__construct();
        $this->dt_params['dt_headings'] = "product_id, product_signup_id, product_reference_type, product_number, product_name, product_cost, product_quantity, product_status";
        $this->dt_params['searchable'] = array("product_id", "product_signup_id", "product_reference_type", "product_number", "product_name", "product_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => false,
            "show_edit" => false,
            "show_view" => true,
            "extra" => array(),
        );

        $this->_list_data['product_signup_id'] = $this->model_signup->find_all_custom_list_active(array('where' => array('signup_type' => ROLE_3)), array('signup_firstname', 'signup_lastname'));

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
		$data['class_name'] = 'Product/Technology/Service';
		$data['model_name'] = $model_name;
		$data['model_obj'] = $model_obj;
		$data['model_fields'] = $model_obj->get_fields();
		$data['dt_params'] = $this->dt_params;

		$data['model'] = "$model_name";
		$this->before_index_render($data);
		$this->load_view("datatable", $data);
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

        if ($id) {

            $parameter = array();
            if ($class_name == "product") {
                $parameter['fields'] = "product_reference_type, product_name, product_number, product_quantity, product_cost, product_quantity, product_industry, product_category, product_function, product_createdon";
            }
            $parameter['where'][$class_name . '_id'] = $id;
            $result['record'] = $this->$model_name->find_one($parameter);

            foreach ($result['record'] as $key => $argv) {
                switch ($key) {
                    case 'product_reference_type':
                        $result['record'][$key] = ucfirst($result['record'][$key]);
                        break;
                    case 'product_cost':
                        $result['record'][$key] = price($result['record'][$key]);
                        break;
                    case 'product_category':
                        $result['record'][$key] = unserialize($result['record'][$key]);
                        break;
                    case 'product_createdon':
                        $result['record'][$key] = validateDate($result['record'][$key], 'Y-m-d H:i:s') ? date('d M, Y h:i a', strtotime($result['record'][$key])) : $result['record'][$key];
                        break;
                }
            }

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
