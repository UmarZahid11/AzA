<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Box
 */
class Box extends MY_Controller
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
        // $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        $this->layout_data['template_config']['show_toolbar'] = false;

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

        $data = array();
        $data['class_name'] = 'Box';
        $data['users'] = NULL;

        if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)))) {
            //
            $headers = array(
                'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
            );

            $url = BOX_USER_URL;
            $data['users'] = json_decode($this->curlRequest($url, $headers));
        }

        if (!$data['users']) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_BOX_UNAUTHORIZED));
            redirect(la(''));
        }

        $this->load_view('index', $data);
    }

    /**
     * Method save
     *
     * @param int $user_id
     *
     * @return void
     */
    function save(int $user_id = 0): void
    {
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

        $data['user'] = NULL;
        if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)))) {
            //
            $headers = array(
                'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
            );

            $url = BOX_USER_URL;
            $url .= '/' . $user_id;
            $data['user'] = json_decode($this->curlRequest($url, $headers));
        }

        if ($user_id && !$data['user']) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_BOX_UNAUTHORIZED));
            redirect(la('box'));
        }

        $data['non_box_users'] = array();
        if (!$user_id) {
            $data['non_box_users'] = $this->model_signup->find_all_active(
                array(
                    'where_not_in' => array(
                        'signup_type' => [ROLE_1, ROLE_0],
                    ),
                    'where_is_null' => array(
                        'signup_box_id'
                    )
                )
            );
        }

        $this->load_view('save', $data);
    }

    /**
     * Method delete
     *
     * @return void
     */
    function saveData(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $json_param['redirect'] = STATUS_FALSE;

        $post_fields = array();

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)))) {

                //
                $headers = array(
                    'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
                );
                $url = BOX_USER_URL;

                if ((isset($_POST['user_id']) && $_POST['user_id'])) {
                    $user_id = $_POST['user_id'];
                    $method = isset($_POST['method']) ? strtoupper($_POST['method']) : '';

                    if ($method) {
                        if ($method == REQUEST_PUT) {
                            $post_fields = array(
                                'name' => $_POST['name'],
                                'login' => $_POST['login'],
                                'phone' => $_POST['phone'],
                                'address' => $_POST['address'],
                                'status' => $_POST['status'],
                            );
                        }

                        $url .= '/' . $user_id . '?force=true';
                        $decoded_response = json_decode($this->curlRequest($url, $headers, $post_fields, FALSE, TRUE, $method));

                        $json_param['last_http_status'] = $this->session->userdata('last_http_status');
                        $json_param['response'] = $decoded_response;
                        if ($decoded_response && property_exists($decoded_response, 'type') && $decoded_response->type == 'error') {
                            $json_param['txt'] = $decoded_response->message;
                        } else {
                            if($method == REQUEST_DELETE) {
                                $updated = $this->model_signup->update_model(
                                    array(
                                        'where' => array(
                                            'signup_box_id' => $_POST['user_id']
                                        )
                                    ),
                                    array(
                                        'signup_box_id' => NULL
                                    )
                                );
                            } else {
                                $updated = 1;
                            }
                            if ($updated) {
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            } else {
                                $json_param['txt'] = SUCCESS_MESSAGE . ' with an error';
                            }
                        }
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                    }
                } else if (isset($_POST['login']) && $_POST['login'] && isset($_POST['name']) && $_POST['name']) {
                    if ($this->model_signup->find_by_email($_POST['login'])) {
                        $post_fields = array(
                            'name' => $_POST['name'],
                            'login' => $_POST['login'],
                            'phone' => $_POST['phone'],
                            'address' => $_POST['address'],
                            'status' => $_POST['status'],
                        );

                        $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                        $decoded_response = json_decode($response);

                        if ($decoded_response && multiple_property_exists($decoded_response, ['type', 'message']) && $decoded_response->type == 'error') {
                            if (property_exists($decoded_response, 'context_info') && property_exists($decoded_response->context_info, 'errors') && property_exists($decoded_response->context_info->errors[0], 'message')) {
                                $json_param['txt'] = $decoded_response->context_info->errors[0]->message;
                            } else {
                                $json_param['txt'] = $decoded_response->message;
                            }
                        } else if (property_exists($decoded_response, 'id')) {
                            $updated = $this->model_signup->update_model(
                                array(
                                    'where' => array(
                                        'signup_email' => $_POST['login']
                                    )
                                ),
                                array(
                                    'signup_box_id' => $decoded_response->id
                                )
                            );
                            if ($updated) {
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = 'A box account for reqeusted user has been created successfully. Check respected email for password creation process.';
                                $json_param['redirect'] = la('box/save/'. $decoded_response->id);
                            } else {
                                $json_param['txt'] = ERROR_MESSAGE;
                            }
                        }
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                    }
                } else {
                    $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_BOX_UNAUTHORIZED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }
}
