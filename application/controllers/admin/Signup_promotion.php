<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Signup_promotion
 */
class Signup_promotion extends MY_Controller
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

        $this->dt_params['dt_headings'] = "signup_promotion_id, signup_promotion_signup_id, signup_promotion_price, signup_promotion_status";
        $this->dt_params['searchable'] = array("signup_promotion_id", "signup_promotion_signup_id");
        $this->dt_params['action'] = array(
            "hide_add_button" => false,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
            "hide_save_edit" => true,
            "hide_save_new" => true,
        );

        $this->_list_data['signup_promotion_signup_id'] = $this->model_signup->find_all_custom_list(array(), ['signup_email']);

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
        $this->register_plugins(array(
            "select2",
        ));

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

		$post_data = $this->input->post(NULL, true);

		$data['form_data'] = (isset($this->form_data)) ? $this->form_data : array();

		$data['user_input'] = (isset($post_data['login'])) ? $post_data['login'] : array();

		if ($_POST) {
			if ($this->bulk_validate($this->_validation_models_add)) {

				// Validation Successful
				$post_data = $_POST[$class_name];

                $promotion_exist =  $this->$model_name->find_count_active(
                    array(
                        'where' => array(
                            'signup_promotion_signup_id' => $_POST[$class_name]['signup_promotion_signup_id']
                        )
                    )
                );

                if(!$promotion_exist) {
                    
                    $signup = $this->model_signup->find_by_pk($_POST[$class_name]['signup_promotion_signup_id']);

                    $post_data['signup_promotion_type'] = 'promotion';
                    //
                    if($signup['signup_subscription_id']) {
                        $subscription = $this->resource('subscriptions', $signup['signup_subscription_id']);
                        if($subscription) {
                            $post_data['signup_promotion_type'] = 'discount';
                        } else {
                            $session = $this->stripeSession($signup, $_POST, $class_name);
                            $post_data['signup_promotion_url'] = $session->url;
                        }
                    } else {
                        $session = $this->stripeSession($signup, $_POST, $class_name);
                        $post_data['signup_promotion_url'] = $session->url;
                    }
                    //

                    // Merge FILES field with POST DATA
                    if ((isset($post_data)) && (is_array($post_data)) && (isset($_FILES[$class_name]['name'])))
                        $post_data = $post_data + $_FILES[$class_name]['name'];

                    $this->$model_name->set_attributes($post_data);

                    $insertId = $this->$model_name->save();

                    if ($insertId) {
                        //
                        $this->model_notification->sendNotification($signup['signup_id'], $signup['signup_id'], NOTIFICATION_NEW_PROMOTION, '', NOTIFICATION_NEW_PROMOTION_COMMENT, '', '');

                        // mail
                        $to = $signup['signup_email'];
                        $subject = $config['title'] . ' - ' . $post_data['signup_promotion_title'];
                        $message = 'Hi ' . (ucfirst($signup['signup_firstname']) . ' ' . ucfirst($signup['signup_lastname'])) . ',<br/>';
                        $message .= $post_data['signup_promotion_description'] . '<br/>';
                        $message .= 'Visit <a href="'.l('').'">' . $config['title'] . '</a> for more details.';
                        $this->model_email->fire_email($to, '', $subject, $message);
        
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
                    redirect($this->admin_path . "?msgtype=error&msg=A promotion request has already been created for the requested user.", 'refresh');
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
     * stripeSession function
     *
     * @param array $signup
     * @param array $post
     * @param string $class_name
     * @return ?object
     */
    private function stripeSession($signup, $post, $class_name)
    {
        if($signup['signup_customer_id']) {
            $customer = $this->resource('customers', $signup['signup_customer_id']);
            if(!$customer) {
                //
                $customer = $this->model_stripe_log->createStripeResource('customers', [
                    'email' => $signup['signup_email']
                ]);
            }
        } else {
            //
            $customer = $this->model_stripe_log->createStripeResource('customers', [
                'email' => $signup['signup_email']
            ]);
        }
        $product = $this->model_stripe_log->createStripeResource('products', [
            'name' => $post[$class_name]['signup_promotion_title'],
        ]);
        $price = $this->model_stripe_log->createStripeResource('prices', [
            'unit_amount' => $post[$class_name]['signup_promotion_price'] * 100,
            'currency' => DEFAULT_CURRENCY_CODE,
            'product' => $product->id,
            'recurring' => array(
                'interval' => SUBSCRIPTION_INTERVAL_TYPE,
                'interval_count' => SUBSCRIPTION_INTERVAL_1,
            ),
        ]);
        //
        $checkoutSessionPayload = [
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
            'success_url' => base_url() . 'membership/result/' . ROLE_3 . '/' . ORDER_SUCCESS . '/{CHECKOUT_SESSION_ID}',
            'cancel_url' => base_url() . 'membership/result/' . ROLE_3 . '/' . ORDER_FAILED . '/{CHECKOUT_SESSION_ID}',
            'mode' => 'subscription',
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'payment_method_collection' => 'always',
        ];

        if (isset($post[$class_name]['signup_promotion_trial'])) {
            $checkoutSessionPayload['subscription_data'] = [
                'trial_settings' => ['end_behavior' => ['missing_payment_method' => 'cancel']],
                'trial_period_days' => $post[$class_name]['signup_promotion_trial'],
            ];
        }
        $session = $this->stripe->checkout->sessions->create($checkoutSessionPayload);        
        return $session;
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
