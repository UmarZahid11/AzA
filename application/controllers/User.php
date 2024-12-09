<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User
 */
class User extends MY_Controller
{
    /**
     * json_param
     *
     * @var array
     */
    private $json_param = array();

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method forgot_password
     *
     * @return void
     */
    public function forgot_password()
    {
        // Get data
        $user_id = $this->input->get('id');
        $token   = $this->input->get('token');
        $usersession = $this->session->userdata('userdata');

        if ((!empty($user_id)) && (!empty($token))) {
            // Where condition for token expire
            $params['where']['token_user_id'] = $user_id;
            $params['where']['token_user']    = $token;

            // Token found
            if ($this->model_token->find_one_active($params)) {
                // Run query
                $params_user['where']['signup_id'] = $user_id;
                $query = $this->model_signup->find_one($params_user);

                // Set banner heading
                $data['banner_heading'] = "Reset Password";

                if (count($query) > 0) {
                    $data['token_user'] = $token;
                    $data['user_id'] = $user_id;

                    $this->layout_data['title'] = 'Forgot Password | ' . $this->layout_data['title'];

                    $param = array();
                    $param['where']['inner_banner_name'] = 'Forgot Password';
                    $data['banner'] = $this->model_inner_banner->find_one_active($param);

                    $this->load_view('forgot_password', $data);
                }
                // User ID not found
                else {
                    redirect(l('404'));
                }
            }
            // Token not found
            else {
                redirect(l('404'));
            }
        }
        // Invalid credentials
        else {
            redirect(l('404'));
        }
    }

    /**
     * Method reset_password
     *
     * @return void
     */
    public function reset_password()
    {
        // Get Post data
        $user_id  = $this->input->post('user_id');
        $token    = $this->input->post('token');
        $password = $this->input->post('password');

        // check Input data empty or not
        $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
        $this->form_validation->set_rules('token', 'Token', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        $this->form_validation->set_error_delimiters("<span style=\"color:white;\" for=\"%s\" class=\"has-error help-block\">", '</span>');

        // Validation error
        if ($this->form_validation->run() == FALSE) {
            $this->json_param['status'] = false;
            $this->json_param['txt'] = validation_errors();
        }
        // No validation
        else {
            //
            $user = $this->model_signup->find_by_pk($user_id);

            if(!empty($user)) {

                // Where condition for token expire
                $params['where']['token_user_id'] = $user_id;
                $params['where']['token_user']    = $token;

                // Token found
                if ($this->model_token->find_one_active($params)) {
                    // Set token status to 0
                    $this->model_token->update_model($params, array('token_status' => STATUS_INACTIVE));

                    $update_param = array();

                    $update_param['signup_password'] = password_hash($password, PASSWORD_BCRYPT);
                    if(isset($user['signup_password_updated']) && !$user['signup_password_updated'] && $user['signup_social_id']) {
                        // saving the password set on first registration
                        $update_param['signup_password_updated'] = 1;
                        $update_param['signup_previous_password'] = $user['signup_password'];
                    }

                    // Change password
                    $updated = $this->model_signup->update_by_pk($user_id, $update_param);

                    if($updated) {
                        $this->json_param['status'] = True;
                        $this->json_param['txt'] = SUCCESS_MESSAGE;
                    } else {
                        $this->json_param['status'] = False;
                        $this->json_param['txt'] = ERROR_MESSAGE;
                    }
                }
                // Token not found
                else {
                    $this->json_param['status'] = false;
                    $this->json_param['txt'] = 'Authentication failed.';
                }
            } else {
                $this->json_param['status'] = false;
                $this->json_param['txt'] = ERROR_MESSAGE;
            }
        }
        echo json_encode($this->json_param);
    }

    /**
     * Method logout
     *
     * @return void
     */
    public function logout()
    {
        //
        $user_id = $this->session->has_userdata('userdata') ? $this->session->userdata('userdata')['signup_id'] : 0;

        //
        $this->model_signup_info->setSignupOffline((int) $user_id ?? (int) $this->userid ?? 0);

        // Clear user session
        $this->session->unset_userdata('userdata');
        $this->session->sess_destroy();

        redirect(l(REDIRECT_AFTER_LOGOUT));
    }
}
