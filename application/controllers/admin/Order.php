<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Order
 */
class Order extends MY_Controller
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

		$this->dt_params['dt_headings'] = "order_id, order_reference_type, order_email, order_total, order_payment_status, order_status";
		$this->dt_params['searchable'] = array("order_id", "order_reference_type", "order_email", "order_payment_status", "order_status");
		$this->dt_params['action'] = array(
			"hide_add_button" => true,
			"hide" => false,
			"show_delete" => false,
			"show_edit" => false,
			"order_field" => false,
			"hide_view" => false,
			"extra" => array(
				'<a title="View" href="' . $config['admin_base_url'] . 'order/detail/%d/" class="btn-xs btn btn-primary order_details_btn"><i class="icon-picture"></i></a>',
			),
		);

		$this->_list_data['order_status'] = array(
			STATUS_INACTIVE => "<span class=\"label label-default\">No</span>",
			STATUS_ACTIVE =>  "<span class=\"label label-primary\">Yes</span>"
		);

		$this->_list_data['order_reference_type'] = array(
			ORDER_REFERENCE_MEMBERSHIP => "<span class=\"label label-danger\">Membership</span>",
			ORDER_REFERENCE_PRODUCT => "<span class=\"label label-primary\">Product</span>",
			ORDER_REFERENCE_TECHNOLOGY => "<span class=\"label label-primary\">Product</span>",
			ORDER_REFERENCE_SERVICE => "<span class=\"label label-primary\">Product</span>",
		);

		$config['js_config']['paginate'] = $this->dt_params['paginate'];
	}

	/**
	 * add
	 *
	 * @param  int $id
	 * @param  array $data
	 *
	 * @return void
	 */
	public function add($id = '', $data = array()): void
	{
		redirect(g('admin_base_url') . "order");
	}

	/**
	 * detail
	 *
	 * @param  int $order_id
	 *
	 * @return void
	 */
	public function detail($order_id = '')
	{
		$this->layout_data['template_config']['show_toolbar'] = false;

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
			"bootstrap-datetimepicker"
		));

		$data['order'] = $this->model_order->find_one(
			array(
				'where' => array(
					'order_id' => $order_id,
				),
				'joins' => array(
					0 => array(
						'table' => 'signup',
						'joint' => 'signup.signup_id = order.order_user_id',
						'type' => 'both'
					)

				)
			)
		);

		if (!array_filled($data['order']))
			error_404();

		$data['payment_method'] = '';

		if(isset($data['order']['signup_subscription_response']) && $data['order']['signup_subscription_response']) {
			$decoded_response = json_decode($data['order']['signup_subscription_response']);
			$default_payment_method = $decoded_response->default_payment_method;

			$data['payment_method'] = $this->model_stripe_log->resource('paymentMethods', $default_payment_method);
		}

		$data['page_title_min'] = "Detail";
		$data['page_title'] = "Order";
		$data['class_name'] = "order";
		$data['model_name'] = "model_order";
		$data['model_obj'] = $this->model_order;
		$data['dt_params'] = $this->dt_params;
		$data['id'] = $order_id;
		$data['object'] = $this;

		$data['order_items'] = $this->model_order_item->find_all_active(
			array(
				'where' => array(
					'order_item_order_id' => $order_id
				)
			)
		);

		$this->load_view("detail", $data);
	}

	/**
	 * index
	 *
	 * @return void
	 */
	public function index()
	{
		parent::index();
	}

	/**
	 * Method get_view
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function get_view($id = 0): array
	{
		global $config;

		$result = array();

		$class_name = $this->router->class;
		$model_name = 'model_' . $class_name;

		if ($id) {
			$result['record'] = $this->$model_name->find_by_pk($id);
			$result['record'] = $this->$model_name->prepare_view_data($result['record']);

			if (!$result['record'])
				$result['failure'] = "No Item Found";

			$relation_data = $this->$model_name->get_relation_data($id);

			if (array_filled($relation_data))
				$result['record']['relation_data'] = $relation_data;
		} else {
			$result['failure'] = "No Item Found";
		}

		return $result;
	}
}
