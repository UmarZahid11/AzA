<?php

class Model_user extends MY_Model
{
    protected $_table    = 'user';
    protected $_field_prefix    = 'user_';
    protected $_pk    = 'user_id';
    protected $_status_field    = 'user_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
    public $_per_page    = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "user_id, user_username, user_email, user_createdon, user_status";
        parent::__construct();
    }

    /**
     * Method auto_login
     *
     * @param int $user_id
     *
     * @return void
     */
    public function auto_login($user_id)
    {
        $user = $this->find_by_pk($user_id, true);
        if (!$user) {
            return FALSE;
        } else {
            $this->set_user_session($user);
            return true;
        }
    }

    /**
     * Method login
     *
     * @return bool
     */
    public function login(): bool
    {
        // Get CodeIgnier Instance
        $CI = &get_instance();

        $params = array();
        $params['where']['user_email'] = $this->input->post('user_email');
        $user = $this->find_one_active($params, true);

        if (!$user) {
            return false;
        } else {
            $password = $this->input->post('user_password');
            if (!password_verify($password, $user->user_password)) {
                $CI->form_validation->set_message('user_check', 'Incorrect Username or ID');
                return FALSE;
            }
        }

        $this->set_user_session($user);
        // creating front session for administrator
        try {
            $signup = $this->model_signup->find_one_active(
                array(
                    'where' => array(
                        'signup_type' => ROLE_0,
                        'signup_email' => $this->input->post('user_email'),
                        'signup_isdeleted' => 0
                    )
                )
            );
            if ($signup) {
                $user_id = $signup['signup_id'];
                $this->model_signup->auto_login($user_id);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            //
            $this->_log_message(
                LOG_TYPE_GENERAL,
                LOG_SOURCE_SERVER,
                LOG_LEVEL_ERROR,
                $e->getMessage(),
                ''
            );
        }
        // creating front session for administrator
        return true;
    }

    /**
     * Method set_user_session
     *
     * @param object $user
     *
     * @return void
     */
    public function set_user_session(object $user): void
    {
        $CI = &get_instance();
        $sess_array = array(
            'id' => $user->user_id,
            'username' => $user->user_username,
            'first_name' => $user->user_firstname,
            'last_name' => $user->user_lastname,
            'nameprefix' => $user->user_nameprefix,
            'email' => $user->user_email,
            'country' => $user->user_country,
            'dob' => $user->user_dob,
            'user_title'  => $user->user_title,
            'profile_image' => $user->user_profile_image_path . $user->user_profile_image,
            'is_admin'  => $user->user_is_admin,
        );
        $CI->session->set_userdata('logged_in', $sess_array);
    }

    /**
     * Method get_fields
     *
     * @return array
     */
    public function get_fields(): array
    {
        return array(

            'user_id'  => array(
                'table'   => $this->_table,
                'name'   => 'user_id',
                'label'   => 'ID',
                'primary'   => 'primary',
                'type'   => 'hidden',
                'attributes'   => array(),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'user_email'  => array(
                'table'   => $this->_table,
                'name'   => 'user_email',
                'label'   => 'Email',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|valid_email|strtolower|trim|htmlentities|is_unique[' . $this->_table . '.' . $this->_field_prefix . 'email]'
            ),

            'user_username'  => array(
                'table'   => $this->_table,
                'name'   => 'user_username',
                'label'   => 'Username',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|strtolower|trim|htmlentities|is_unique[' . $this->_table . '.' . $this->_field_prefix . 'username]'
            ),

            'user_password'  => array(
                'table'   => $this->_table,
                'name'   => 'user_password',
                'label'   => 'Password',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'required|trim|matches[retype]|callback_bcrypt[' . $this->_table . '.' . $this->_field_prefix . 'password]'
            ),

            'user_nameprefix'  => array(
                'table'   => $this->_table,
                'name'   => 'user_nameprefix',
                'label'   => 'Name Prefix',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_gender'  => array(
                'table'   => $this->_table,
                'name'   => 'user_gender',
                'label'   => 'User Gender',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_newsletter_subscribed'  => array(
                'table'   => $this->_table,
                'name'   => 'user_newsletter_subscribed',
                'label'   => 'User Newsletter Subscribed',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_profile_image'  => array(
                'table'   => $this->_table,
                'name'   => 'user_profile_image',
                'label'   => 'Profile Image',
                'name_path'   => 'profile_image_path',
                'upload_config'   => 'site_upload_user_photo',
                'type'   => 'fileupload',
                'randomize' => true,
                'preview'   => 'true',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_firstname'  => array(
                'table'   => $this->_table,
                'name'   => 'user_firstname',
                'label'   => 'Firstname',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'required|trim'
            ),

            'user_lastname'  => array(
                'table'   => $this->_table,
                'name'   => 'user_lastname',
                'label'   => 'Lastname',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'required|trim'
            ),

            'user_message'  => array(
                'table'   => $this->_table,
                'name'   => 'user_message',
                'label'   => 'Message',
                'type'   => 'text',
                'default'   => '',
                'attributes'   => array(),
                'rules'   => 'trim|strip_tags'
            ),

            'user_bussiness_name'  => array(
                'table'   => $this->_table,
                'name'   => 'user_bussiness_name',
                'label'   => 'Bussiness Name',
                'type'   => 'text',
                'attributes'   => array(),
                'rules'   => 'trim|strip_tags'
            ),

            'user_bussiness_type'  => array(
                'table'   => $this->_table,
                'name'   => 'user_bussiness_type',
                'label'   => 'Bussiness Type',
                'type'   => 'editor',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_mobile'  => array(
                'table'   => $this->_table,
                'name'   => 'user_mobile',
                'label'   => 'Mobile',
                'type'   => 'editor',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_telephone'  => array(
                'table'   => $this->_table,
                'name'   => 'user_telephone',
                'label'   => 'Telephone',
                'type'   => 'editor',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_telephone2'  => array(
                'table'   => $this->_table,
                'name'   => 'user_telephone2',
                'label'   => 'Telephone2',
                'type'   => 'editor',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_fax'  => array(
                'table'   => $this->_table,
                'name'   => 'user_fax',
                'label'   => 'Fax',
                'type'   => 'editor',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_address1'  => array(
                'table'   => $this->_table,
                'name'   => 'user_address1',
                'label'   => 'Address1',
                'type'   => 'text',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_address2'  => array(
                'table'   => $this->_table,
                'name'   => 'user_address2',
                'label'   => 'Address2',
                'type'   => 'textarea',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_city'  => array(
                'table'   => $this->_table,
                'name'   => 'user_city',
                'label'   => 'City',
                'type'   => 'textarea',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_state'  => array(
                'table'   => $this->_table,
                'name'   => 'user_state',
                'label'   => 'State',
                'type'   => 'textarea',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_port'  => array(
                'table'   => $this->_table,
                'name'   => 'user_port',
                'label'   => 'Port',
                'type'   => 'textarea',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_url'  => array(
                'table'   => $this->_table,
                'name'   => 'user_url',
                'label'   => 'URL',
                'type'   => 'textarea',
                'attributes'   => array(),
                'rules'   => 'trim|htmlentities'
            ),

            'user_country'  => array(
                'table'   => $this->_table,
                'name'   => 'user_country',
                'label'   => 'Country',
                'type'   => 'textarea',
                'attributes'   => array(),
                'rules'   => 'trim|intval'
            ),

            'user_status'  => array(
                'table'   => $this->_table,
                'name'   => 'user_status',
                'label'   => 'Status?',
                'type'   => 'switch',
                'default'   => '1',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_subscription'  => array(
                'table'   => $this->_table,
                'name'   => 'user_subscription',
                'label'   => 'Subscription',
                'type'   => 'switch',
                'default'   => '1',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),
            'user_provider_id'  => array(
                'table'   => $this->_table,
                'name'   => 'user_provider_id',
                'label'   => 'User Provider ID',
                'type'   => 'text',
                'rules'   => 'intval'
            ),

            'user_provider_uid'  => array(
                'table'   => $this->_table,
                'name'   => 'user_provider_uid',
                'label'   => 'Provider UID',
                'type'   => 'text',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_dob'  => array(
                'table'   => $this->_table,
                'name'   => 'user_dob',
                'label'   => 'USER DOB',
                'type'   => 'text',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_createdon'  => array(
                'table'   => $this->_table,
                'name'   => 'user_createdon',
                'label'   => 'Createdon',
                'type'   => 'label',
                'attributes'   => array(),
                'rules'   => 'trim'
            ),

            'user_provider_username'  => array(
                'table'   => $this->_table,
                'name'   => 'user_provider_username',
                'label'   => 'Provider Username',
                'type'   => 'text',
                'attributes'   => array(),
                'rules'   => 'trim'
            )
        );
    }
}
