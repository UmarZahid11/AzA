<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Quickbook_account_request
 */
class Quickbook_account_request extends MY_Controller
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

        $this->dt_params['dt_headings'] = "quickbook_account_request_id, quickbook_account_request_signup_id, quickbook_account_request_createdon";
        $this->dt_params['searchable'] = array("quickbook_account_request_id", "quickbook_account_request_signup_id");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => false,
			"extra" => array(
				'<a data-toggle="tooltip" title="Create credentials and send email" href="' . $config['admin_base_url'] . 'quickbook_account_request/email/%d/" class="btn-sm btn btn-warning"><i class="icon-paper-plane"></i></a>',
			),
        );

        $this->_list_data['quickbook_account_request_signup_id'] = $this->model_signup->find_all_custom_list(array(), ['signup_email']);

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
            unset($_POST['quickbook_account_request']['quickbook_account_request_password']);
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

		$data['quickbook_account_request'] = $this->model_quickbook_account_request->find_one_active(
            array(
                'where' => array(
                    'quickbook_account_request_id' => $id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = quickbook_account_request.quickbook_account_request_signup_id',
                        'type' => 'both'
                    ),
                )
            )
        );
        
		if(!$data['quickbook_account_request']) {
			error_404();
		}

        $data['quickbook_account'] = [];
        if($this->model_quickbook_account->accountExists($data['quickbook_account_request']['signup_id'])) {
            $data['quickbook_account'] = $this->model_quickbook_account->find_one_active(
                array(
                    'where' => array(
                        'quickbook_account_signup_id' => $data['quickbook_account_request']['signup_id']
                    )
                )
            );
        }


		$this->load_view('email', $data);
	}

	function sendEmail() : void {
		global $config;

		$json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if(isset($_POST['quickbook_account'])) {
			    
			    //
				$to = $_POST['to'];
				$subject = $_POST['subject'] ?? $config['title'] . ' - Quickbook Account Request Response';
				$message = 'Your quickbook email has been added to the system. <br/> Email: ' . $_POST['quickbook_account']['quickbook_account_email'] . '<br/>' . 'Password: ' . $_POST['quickbook_account']['quickbook_account_password'] . '<br/>';
				$message .= 'You can now use our quickbooks functionality through azaverze';
				$this->model_email->fire_email($to, '', $subject, $message);
                
                $quickbook_account_signup_id = $_POST['quickbook_account']['quickbook_account_signup_id'];
                $quickbook_account_id = 0;

                $account_param = array();
                $account_param['quickbook_account_signup_id'] = $_POST['quickbook_account']['quickbook_account_signup_id'];
                $account_param['quickbook_account_email'] = $_POST['quickbook_account']['quickbook_account_email'];
                $account_param['quickbook_account_password'] = $_POST['quickbook_account']['quickbook_account_password'];

                if(!$this->model_quickbook_account->accountExists($_POST['quickbook_account']['quickbook_account_signup_id'])) {
                    $quickbook_account_id = $this->model_quickbook_account->insert_record($account_param);
                } else {
                    $quickbook_account = $this->model_quickbook_account->find_one_active(
                        array(
                            'where' => array(
                                'quickbook_account_signup_id' => $_POST['quickbook_account']['quickbook_account_signup_id']
                            )
                        )
                    );
                    if($quickbook_account) {
                        $quickbook_account_id = $quickbook_account['quickbook_account_id'];
                    }
                    $updated = $this->model_quickbook_account->update_by_pk($quickbook_account_id, $account_param);
                }

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
