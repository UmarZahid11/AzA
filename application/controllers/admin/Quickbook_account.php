<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Quickbook_account
 */
class Quickbook_account extends MY_Controller
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

        $this->dt_params['dt_headings'] = "quickbook_account_id, quickbook_account_signup_id, quickbook_account_email, quickbook_account_password, quickbook_account_createdon";
        $this->dt_params['searchable'] = array("quickbook_account_id", "quickbook_account_signup_id", "quickbook_account_email");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['quickbook_account_signup_id'] = $this->model_signup->find_all_custom_list(array(), ['signup_email']);

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
        if ((isset($_POST)) && (count($_POST) > 0) && (!empty($id))) {
            unset($_POST['quickbook_account']['quickbook_account_password']);
        }

        parent::add($id, $data);
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
		$pg_request = $_POST;

		$class_name = $this->router->class;
		$model_name = "model_" . $class_name;
		$model_obj = $this->$model_name;

		$params = $model_obj->pagination_params;
		if (!isset($params['order'])) {
			$sort_col = $pg_request['order'][0]['column'];

			if ($sort_col !== null) {
				$sort_type = $pg_request['order'][0]['dir'];
				$params['order'] = $sort_col . " " . $sort_type;
			}
		}

		$length = intval($pg_request['length']);

		$model_obj->_per_page = $length ? $length : $model_obj->_per_page;

		$records = $model_obj->pagination_query($params);

		if (is_array($records['data']))
			$data = $this->prepare_datatable($records['data']);

		$dt_record = array();
		$dt_record["data"] = $data;
		$dt_record["draw"] = $pg_request["draw"];
		$dt_record["recordsTotal"] = $records["count"];
		$dt_record["recordsFiltered"] = $records["count"];
		echo json_encode($dt_record);

		exit();
	}
}
