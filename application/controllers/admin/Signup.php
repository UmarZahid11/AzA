<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Signup
 */
class Signup extends MY_Controller
{
    public $_list_data = array();

    public function __construct()
    {
        global $config;
        parent::__construct();

        $this->dt_params['dt_headings'] = "signup_id, signup_email, signup_type, signup_lifetime_subscription, signup_is_verified, signup_is_approved, signup_is_confirmed, signup_is_phone_confirmed, signup_status";
        $this->dt_params['searchable'] = array("signup_id", "signup_email", "signup_type", "signup_lifetime_subscription", "signup_is_verified", "signup_is_approved", "signup_is_confirmed", "signup_is_phone_confirmed", "signup_status");
        $this->dt_params['action'] = array(
            "lifetime_subscription_button" => true,
            "approve_button" => true,
            "extend_trial_button" => true,
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => true,
            "extra" => array(
                // one button in my_admin_controller
                '<a href="' . $config['admin_base_url'] . 'signup/privilege/%d/" class="btn-sm btn btn-default" data-toggle="tooltip" data-placement="top" title="Adjust this user\'s site privilege."><i class="fa fa-cog"></i></a>',
            ),
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
        if ($this->router->method == 'coming_soon') {
            $config['js_config']['paginate']['uri'] = 'paginate_comming_soon';
        }
        if ($this->router->method == 'affiliate') {
            $config['js_config']['paginate']['uri'] = 'paginate_affiliate';
        }
    }

    /**
     * Method coming_soon
     *
     * @return void
     */
    public function coming_soon()
    {
        global $config;

        $class_name = $this->router->class;
        $model_name = "model_" . $class_name;

        $model_obj = $this->$model_name;

        $this->layout_data['bread_crumbs'] = array(
            array(
                "home/" => "Home",
                $class_name => humanize('Signup coming soon')
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
        $data['page_title'] = 'Comming soon';
        $data['class_name'] = $class_name;
        $data['model_name'] = $model_name;
        $data['model_obj'] = $model_obj;
        $data['model_fields'] = $model_obj->get_fields();
        $data['dt_params'] = $this->dt_params;

        $data['model'] = "$model_name";
        $this->before_index_render($data);

        $this->load_view("coming-soon", $data);
    }
    
    /**
     * Method affiliate
     *
     * @return void
     */
    public function affiliate()
    {
        global $config;

        $class_name = $this->router->class;
        $model_name = "model_" . $class_name;

        $model_obj = $this->$model_name;

        $this->layout_data['bread_crumbs'] = array(
            array(
                "home/" => "Home",
                $class_name => humanize('Signup affiliate')
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
        $data['page_title'] = 'Comming soon';
        $data['class_name'] = $class_name;
        $data['model_name'] = $model_name;
        $data['model_obj'] = $model_obj;
        $data['model_fields'] = $model_obj->get_fields();
        $data['dt_params'] = $this->dt_params;

        $data['model'] = "$model_name";
        $this->before_index_render($data);

        $this->load_view("affiliate", $data);
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

        $params['where']['signup_for_future'] = 0;

        $length = intval($pg_request['length']);

        if ($model_obj) {
            $model_obj->_per_page = $length ? $length : ($model_obj ? $model_obj->_per_page : 20);
        }

        $records = $model_obj ? $model_obj->pagination_query($params) : [];

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

    function paginate_comming_soon()
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

        $params['where']['signup_for_future'] = 1;

        $length = intval($pg_request['length']);

        if ($model_obj) {
            $model_obj->_per_page = $length ? $length : ($model_obj ? $model_obj->_per_page : 20);
        }

        $records = $model_obj ? $model_obj->pagination_query($params) : [];

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


    function paginate_affiliate()
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

        $params['where']['signup_affiliate'] = 1;

        $length = intval($pg_request['length']);

        if ($model_obj) {
            $model_obj->_per_page = $length ? $length : ($model_obj ? $model_obj->_per_page : 20);
        }

        $records = $model_obj ? $model_obj->pagination_query($params) : [];

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
            unset($_POST['signup']['signup_password']);
        }

        parent::add($id, $data);
    }

    /**
     * Method update_password
     *
     * @return void
     */
    public function update_password(): void
    {
        $data = $this->input->post('signup');

        if ((count($_POST) > 0) && (isset($data['signup_password'])) && (!empty($data['signup_password']))) {
            $param['signup_password'] = md5($data['signup_password']);
            $status = $this->model_signup->update_by_pk($data['signup_id'], $param);

            if ($status) {
                $msg = 'Password changed successfully.';
                redirect($this->admin_path . "?msgtype=success&msg=$msg", 'refresh');
            } else {
                $msg = "Unable to change password. Please user different password";
                redirect($this->admin_path . "?msgtype=error&msg=$msg", 'refresh');
            }
        } else {
            $msg = "Record not updated.";
            redirect($this->admin_path . "?msgtype=error&msg=$msg", 'refresh');
        }
    }

    /**
     * Method get_view
     *
     * @param int $id
     *
     * @return array
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

            $parameter = array();
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
            $result['failure'] = "No item found.";
        }

        return $result;
    }

    /**
     * Method privilege
     *
     * @param int $signup_id
     *
     * @return void
     */
    function privilege(int $signup_id): void
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

        $data['signup'] = $this->model_signup->find_by_pk($signup_id);

        if (!array_filled($data['signup']))
            not_found("Invalid user id");

        $data['page_title_min'] = "Detail";
        $data['page_title'] = "Signup";
        $data['class_name'] = "signup privilege";
        $data['model_name'] = "model_signup";
        $data['model_obj'] = $this->model_signup;
        $data['dt_params'] = $this->dt_params;
        $data['id'] = $signup_id;
        $data['object'] = $this;

        foreach (PRIVILEGE_TYPE as $value) {
            $data['signup_bypass_privilege'][$value]['status'] = $this->model_signup_bypass_privilege->get($signup_id, $value);
            $data['signup_bypass_privilege'][$value]['label'] = PRIVILEGE_TYPE_LABEL[$value];
        }

        $this->load_view('privilege', $data);
    }

    /**
     * Method savePrivileges
     *
     * @return void
     */
    function savePrivileges(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $affected = FALSE;
        $with_error = FALSE;
        $error = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST) && isset($_POST['signup_id'])) {

                //
                $signup = $this->model_signup->find_by_pk($_POST['signup_id']);

                if (isset($_POST['signup_bypass_privilege']) && is_array($_POST['signup_bypass_privilege']) && $signup) {
                    foreach ($_POST['signup_bypass_privilege'] as $key => $value) {

                        $signup_bypass_privilege = $this->model_signup_bypass_privilege->get($signup['signup_id'], $key, FALSE);

                        //
                        $affect_param = array(
                            'signup_bypass_privilege_signup_id' => $signup['signup_id'],
                            'signup_bypass_privilege_type' => $key,
                            'signup_bypass_privilege_status' => $value,
                        );

                        if ($signup_bypass_privilege) {
                            $current_privilege = $this->model_signup_bypass_privilege->find_one(
                                array(
                                    'where' => array(
                                        'signup_bypass_privilege_signup_id' => $signup['signup_id'],
                                        'signup_bypass_privilege_type' => $key
                                    )
                                )
                            );

                            if (!$current_privilege && $key != PRIVILEGE_TYPE_TESTIMONIAL) {
                                $error = TRUe;
                            } else {
                                if ($key != PRIVILEGE_TYPE_TESTIMONIAL) {
                                    $affected = $this->model_signup_bypass_privilege->update_by_pk($current_privilege['signup_bypass_privilege_id'], $affect_param);
                                }
                            }
                        } else {
                            $affected = $this->model_signup_bypass_privilege->insert_record($affect_param);
                        }

                        if (!$affected) {
                            $with_error = TRUE;
                        }
                    }
                } else {
                    $error = TRUE;
                }

                if ($error) {
                    $json_param['txt'] = ERROR_MESSAGE;
                } else if ($with_error) {
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = 'The request has been proccessed successfuly with an error.';
                } else {
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = SUCCESS_MESSAGE;
                }
            } else {
                $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);
    }

    /**
     * Method approve_disapprove_signup
     *
     * @return void
     */
    public function approve_disapprove_signup(): void
    {
        if (isset($_POST)) {
            $json_param = array();
            $param = array();
            $id = $_POST['id'];
            $signup = $this->model_signup->find_by_pk($id);

            if (!empty($signup)) {
                if ($signup['signup_is_approved']) {
                    $param['signup_is_approved'] = 0;
                } else {
                    $param['signup_is_approved'] = 1;
                }

                $updated = $this->model_signup->update_by_pk($id, $param);
                if ($updated) {
                    $json_param['status'] = 1;
                } else {
                    $json_param['status'] = 0;
                    $json_param['txt'] = 'Error in updating user status.';
                }
            } else {
                $json_param['status'] = 0;
                $json_param['txt'] = "Requested user doesn't exists.";
            }
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = "Error occurred while processing your request.";
        }
        echo json_encode($json_param);
    }

    /**
     * Method lifetimeAccess
     *
     * @return void
     */
    public function lifetimeAccess(): void
    {
        if (isset($_POST)) {
            $json_param = array();
            $param = array();
            $id = $_POST['id'];
            $signup = $this->model_signup->find_by_pk($id);

            if (!empty($signup)) {
                if ($signup['signup_lifetime_subscription']) {
                    $param['signup_lifetime_subscription'] = 0;
                } else {
                    $param['signup_lifetime_subscription'] = 1;
                }

                $updated = $this->model_signup->update_by_pk($id, $param);
                if ($updated) {
                    $json_param['status'] = 1;
                } else {
                    $json_param['status'] = 0;
                    $json_param['txt'] = 'Error in updating user status.';
                }
            } else {
                $json_param['status'] = 0;
                $json_param['txt'] = "Requested user doesn't exists.";
            }
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = "Error occurred while processing your request.";
        }
        echo json_encode($json_param);
    }

    /**
     * getSignupById function
     *
     * @return void
     */
    function getSignupById()
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $json_param['data'] = [];

        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $signup = $this->model_signup->find_by_pk($id);

            if (!empty($signup)) {
                if (in_array($signup['signup_type'], [ROLE_1, ROLE_3])) {
                    if(($signup['signup_type'] == ROLE_1 && $signup['signup_trial_expiry'] != '') || ($signup['signup_type'] == ROLE_3 && $signup['signup_subscription_status'] == SUBSCRIPTION_TRIAL && $signup['signup_trial_expiry'] != '')) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = SUCCESS_MESSAGE;
                        $json_param['data'] = $signup;
                    } else {
                        $json_param['txt'] = 'The requested user do not have an active trial subscription.';
                    }
                } else {
                    $json_param['txt'] = 'Error in fetching user details.';
                }
            } else {
                $json_param['txt'] = "Requested user doesn't exists.";
            }
        }

        echo json_encode($json_param);
    }

    /**
     * saveData function
     *
     * @return void
     */
    function saveData()
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $error = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['signup_id']) && isset($_POST['signup'])) {
                $id = $_POST['signup_id'];
                $signup = $this->model_signup->find_by_pk($id);

                if (!empty($signup)) {
                    $signup_param = $_POST['signup'];

                    if(isset($_POST['type']) && $_POST['type'] == 'premium') {
                        //
                        if($signup['signup_subscription_id']) {
                            $subscription = $this->resource('subscriptions', $signup['signup_subscription_id']);
                            if($subscription) {
                                // extend stripe trial expiry
                                $this->stripe->subscriptions->update(
                                    $subscription->id,
                                    ['trial_end' => strtotime($signup_param['signup_trial_expiry'])]
                                );
                            } else {
                                $error = TRUE;
                                $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;    
                            }
                        } else {
                            $error = TRUE;
                            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                        }
                    }

                    //
                    if(!$error) {
                        $this->model_signup->update_by_pk($id, $signup_param);
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = SUCCESS_MESSAGE;
                    }
                } else {
                    $json_param['txt'] = "Requested user doesn't exists.";
                }
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }

        echo json_encode($json_param);
    }
}
