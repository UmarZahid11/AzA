<?php

/**
 * Model_signup
 */
class Model_signup extends MY_Model
{
    protected $_table = 'signup';
    protected $_field_prefix = 'signup_';
    protected $_pk = 'signup_id';
    protected $_status_field = 'signup_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
    public $_per_page = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "signup_id, signup_email, signup_type, signup_lifetime_subscription, signup_is_verified, signup_is_approved, signup_is_confirmed, signup_is_phone_confirmed, signup_status";
        parent::__construct();
    }

    /**
     * Method find_signup_type
     *
     * @param int $type - signup_type (1, 2, 3)
     *
     * @return string [general, associate, organization]
     */
    public function find_signup_type($type = 1): ?string
    {
        $user_data = $this->get_fields('signup_type');
        return isset($user_data['list_data'][$type]) ? $user_data['list_data'][$type] : NULL;
    }

    /**
     * Method getRole
     *
     * @param int $type
     * @param array $user_data
     *
     * @return ?string
     */
    public function getRole(int $type = NULL, array $user_data = NULL): ?string
    {
        $user_data = $user_data ?? $this->user_data;

        if ($this->userid > 0) {
            if (isset($type) && $type) {
                return $this->get_fields('signup_type')['list_data'][$type];
            } else {
                return $this->get_fields('signup_type')['list_data'][(isset($user_data['signup_type']) ? $user_data['signup_type'] : 1)];
            }
        }
        return NULL;
    }

    /**
     * Method getRawRole
     *
     * @param int $type
     * @param array $user_data
     *
     * @return ?string
     */
    public function getRawRole(int $type = NULL, array $user_data = NULL): ?string
    {
        $user_data = $user_data ?? $this->user_data;

        if ($this->userid > 0) {
            if (isset($type) && $type) {
                switch ($type) {
                    case 1:
                        return RAW_ROLE_1;
                        break;
                    case 3:
                        return RAW_ROLE_3;
                        break;
                }
            } else {
                return constant(RAW_ROLE_PREFIX . (isset($user_data['signup_type']) ? $user_data['signup_type'] : 1));
            }
        }
        return NULL;
    }

    /**
     * getUserRole function
     *
     * @param integer $user_id
     * @return array
     */
    function getUserRole(int $user_id = 0) {
        return $this->model_membership->find_one_active(
            array(
                'fields' => 'membership.*',
                'where' => array(
                    'signup_id' => $user_id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_type = membership.membership_id',
                        'type' => 'both'            
                    )
                )
            )
        );
    }

    /**
     * Method getRoleId
     *
     * @param string $type
     * @param array $user_data
     *
     * @return ?int
     */
    public function getRoleId(string $type = "", array $user_data = NULL): ?int
    {
        $user_data = $user_data ?? $this->user_data;

        if ($this->userid > 0) {
            if (isset($type) && $type) {
                return constant(ROLE_PREFIX . $type);
            } else {
                return constant(ROLE_PREFIX . (isset($user_data['signup_type']) ? $user_data['signup_type'] : 1));
            }
        }
        return NULL;
    }

    /**
     * Method hasRole
     *
     * @param int $role (1, 2, 3, 4)
     * @param array $user_data [signup data]
     *
     * @return bool
     */
    public function hasRole(int $role, array $user_data = NULL): bool
    {
        $user_data = $user_data ?? $this->user_data;

        if (!isset($user_data['signup_id']) || !isset($user_data['signup_type'])) {
            return FALSE;
        }

        return (((int) $user_data['signup_type'] === (int) $role) && $user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE);
    }

    /**
     * Method hasPremiumPermission
     *
     * @param array $user_data
     *
     * @return void
     */
    public function hasPremiumPermission(array $user_data = NULL)
    {
        $user_data = $user_data ?? $this->user_data;

        if (!isset($user_data['signup_id']) || !isset($user_data['signup_type'])) {
            return FALSE;
        }

        if (
            (
                $this->hasRole(ROLE_0) ||
                (
                    $this->hasRole(ROLE_1) &&
                    (
                        $user_data['signup_trial_expiry'] &&
                        isValidDate($user_data['signup_trial_expiry'], 'Y-m-d H:i:s') &&
                        (
                            strtotime($user_data['signup_trial_expiry']) > strtotime(date('Y-m-d H:i:s'))
                        )
                    )
                ) ||
                $this->hasRole(ROLE_3) ||
                $this->hasRole(ROLE_4) ||
                $this->hasRole(ROLE_5) 
            ) ||
            (
                ($user_data['signup_lifetime_subscription'])
            )
        ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Method inRole
     *
     * @param array $role => [1, 2, 3]
     * @param array $user_data [signup data]
     *
     * @return bool
     */
    public function inRole(array $role = array(), array $user_data = NULL): bool
    {
        $user_data = $user_data ?? $this->user_data;

        if (!isset($user_data['signup_id']) || !isset($user_data['signup_type'])) {
            return false;
        }
        return (in_array((int) $user_data['signup_type'], $role, TRUE) && $user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE);
    }

    /**
     * Method withRole
     *
     * @param string $role
     * @param array $where_param
     *
     * @return array
     */
    public function withRole(int $role, array $where_param = array()): array
    {
        $param = $where_param;
        $param['where']['signup_isdeleted'] = STATUS_INACTIVE;
        $param['where']['signup_type'] = constant(ROLE_PREFIX . $role);
        $param['where']['signup_id !='] = $this->userid;
        //
        $param['joins'][] = array(
            'table' => 'signup_info',
            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
            'type' => 'both'
        );

        return $this->model_signup->find_all_active($param);
    }

    /**
     * Method find_by_email
     *
     * @param string $email
     *
     * @return void
     */
    public function find_by_email(string $email)
    {
        return $this->model_signup->find_one_active(
            array(
                'where' => array(
                    'signup_email' => $email,
                    'signup_isdeleted' => STATUS_INACTIVE
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    )
                )
            )
        );
    }

    /**
     * Method find_by_pk
     *
     * @param int $id
     * @param bool $return_obj
     * @param array $params
     *
     * @return array
     */
    public function find_by_pk($id = 0, $return_obj = false, $params = array())
    {
        $where_param = $params;
        $where_param['signup_id'] = $id;
        $where_param['signup_isdeleted'] = STATUS_INACTIVE;

        return $this->model_signup->find_one_active(
            array(
                'where' => $where_param,
                'joins' => array(
                    0 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    ),
                )
            ),
            $return_obj
        );
    }

    /**
     * Method profileName - profile page (for either associate or organization)
     *
     * @param array $user
     * @param bool $isGeneral
     *
     * @return string
     */
    public function profileName($user, $isGeneral = true)
    {
        $isGeneral = !($this->model_signup->hasPremiumPermission() || $this->model_signup->hasRole(ROLE_4)) && $user['signup_id'] != $this->userid;
        if (!$isGeneral) {
            return isset($user['signup_company_name']) && $user['signup_company_name'] ? $user['signup_company_name'] : (isset($user['signup_company_representative_name']) && $user['signup_company_representative_name'] ? $user['signup_company_representative_name'] : (isset($user['signup_fullname']) ? $user['signup_fullname'] : (isset($user['signup_firstname']) && isset($user['signup_lastname']) ? (ucfirst($user['signup_firstname']) . ' ' . ucfirst($user['signup_lastname'])) : 'User')));
        } else {
            return isset($user['signup_company_name']) && $user['signup_company_name'] ? $user['signup_company_name'][0] : (isset($user['signup_company_representative_name']) && $user['signup_company_representative_name'] ? $user['signup_company_representative_name'][0] : (isset($user['signup_fullname']) ? $user['signup_fullname'][0] : (isset($user['signup_firstname']) && isset($user['signup_lastname']) ? (ucfirst($user['signup_firstname'][0]) . ' ' . ucfirst($user['signup_lastname'][0])) : 'User')));
        }
    }

    /**
     * Method listingName - listing page
     *
     * @param array $user
     * @param bool $isGeneral - is general user
     *
     * @return string
     */
    public function listingName($user, bool $isGeneral = true)
    {
        if (!$isGeneral) {
            return isset($user['signup_company_name']) && $user['signup_company_name'] ? $user['signup_company_name'] : (isset($user['signup_fullname']) && $user['signup_fullname'] ? ucfirst($user['signup_fullname']) : (isset($user['signup_firstname']) && isset($user['signup_lastname']) ? ucfirst($user['signup_firstname']) . ' ' . ucfirst($user['signup_lastname']) : 'User'));
        } else {
            return isset($user['signup_company_name']) && $user['signup_company_name'] ? $user['signup_company_name'][0] : (isset($user['signup_fullname']) && $user['signup_fullname'] ? ucfirst($user['signup_fullname'][0]) : (isset($user['signup_firstname']) && isset($user['signup_lastname']) ? ucfirst($user['signup_firstname'][0]) . ' ' . ucfirst($user['signup_lastname'][0]) : 'User'));
        }
    }

    /**
     * Method signupName - useable everywhere
     *
     * @param array $user
     * @param bool $isGeneral - is general user
     *
     * @return string
     */
    public function signupName($user, bool $isGeneral = true)
    {
        if (!$isGeneral) {
            return (isset($user['signup_fullname']) && $user['signup_fullname'] ? ucfirst($user['signup_fullname']) : (isset($user['signup_firstname']) && isset($user['signup_lastname']) ? ucfirst($user['signup_firstname']) . ' ' . ucfirst($user['signup_lastname']) : 'User'));
        } else {
            return (isset($user['signup_fullname']) && $user['signup_fullname'] ? ucfirst($user['signup_fullname'][0]) : (isset($user['signup_firstname']) && isset($user['signup_lastname']) ? ucfirst($user['signup_firstname'][0]) . ' ' . ucfirst($user['signup_lastname'][0]) : 'User'));
        }
    }

    /**
     * Method profileImage - profile page (for either associate or organization)
     *
     * @param array $user
     *
     * @return void
     */
    public function profileImage($user)
    {
        return get_user_image(
            (isset($user['signup_company_image_path']) && $user['signup_company_image_path']) ? ($user['signup_company_image_path']) : (isset($user['signup_logo_image_path']) && $user['signup_logo_image_path'] ? ($user['signup_logo_image_path']) : ''),
            (isset($user['signup_company_image']) && $user['signup_company_image']) ? ($user['signup_company_image']) : (isset($user['signup_logo_image']) && $user['signup_logo_image'] ? ($user['signup_logo_image']) : '')
        );
    }

    /**
     * Method login
     *
     * @param $data $data
     *
     * @return bool
     */
    public function login($data): bool
    {
        // Get CodeIgnier Instance
        $CI = &get_instance();

        $params['where']['signup_email'] = $data['signup_email'];
        $params['where']['signup_password'] = $data['signup_password'];
        $signup = $this->find_one($params, true);

        if (!$signup) {
            $CI->form_validation->set_message('signup_check', 'Incorrect signupname or ID');
            return FALSE;
        } else {
            $this->set_user_session($signup);
            return TRUE;
        }
    }

    /**
     * Method auto_login
     *
     * @param $user_id $user_id
     *
     * @return void
     */
    public function auto_login($user_id): void
    {
        // Set params
        $params['where']['signup_id'] = $user_id;
        $params['where']['signup_isdeleted'] = STATUS_INACTIVE;

        // Query
        $user = $this->model_signup->find_one_active($params);
        // Get CodeIgnier Instance

        $this->set_user_session($user);
    }

    /**
     * Method set_user_session - Set session for login user
     *
     * @param array $signup
     *
     * @return void
     */
    public function set_user_session($signup)
    {
        $array = array(
            'signup_id' => $signup['signup_id'],
            'signup_type' => $signup['signup_type'],
            'signup_is_paypal_onboarded' => $signup['signup_is_paypal_onboarded'],
            'signup_is_stripe_connected' => $signup['signup_is_stripe_connected'],
            'signup_paypal_email' => $signup['signup_paypal_email'],
            'signup_is_verified' => $signup['signup_is_verified'],
            'signup_is_approved' => $signup['signup_is_approved'],
            'signup_is_confirmed' => $signup['signup_is_confirmed'],
            'signup_is_phone_confirmed' => $signup['signup_is_phone_confirmed'],
            'signup_salutation' => $signup['signup_salutation'],
            'signup_middlename' => $signup['signup_middlename'],
            'signup_institution' => $signup['signup_institution'],
            'signup_department' => $signup['signup_department'],
            'signup_firstname' => ucfirst($signup['signup_firstname']),
            'signup_lastname' => ucfirst($signup['signup_lastname']),
            'signup_company' => $signup['signup_company'],
            'signup_email' => $signup['signup_email'],
            'signup_username' => isset($signup['signup_username']) ? $signup['signup_username'] : '',
            'signup_address' => $signup['signup_address'],
            'signup_zip' => $signup['signup_zip'],
            'signup_city' => $signup['signup_city'],
            'signup_state' => $signup['signup_state'],
            'signup_country' => $signup['signup_country'],
            'signup_contact' => $signup['signup_contact'],
            'signup_image' => $signup['signup_logo_image_path'] . $signup['signup_logo_image'],
            'signup_createdon' => $signup['signup_createdon'],
        );
        // Set session
        $this->session->set_userdata('userdata', $array);
    }

    /**
     * Method update_user_session
     *
     * @param array $signup
     *
     * @return void
     */
    public function update_user_session($signup)
    {
        // Get user session
        $user_session = $this->session->userdata('userdata');
        // Loop each session
        foreach ($signup as $key => $value) :
            $user_session[$key] = $value;
            $this->session->set_userdata('userdata', $user_session);
        endforeach;
    }

    /**
     * Method prepare_view_data
     *
     * @param $record $record
     *
     * @return array
     */
    public function prepare_view_data($record = []): array
    {
        $model_fields = $this->get_fields();
        if (array_filled($record)) {
            foreach ($record as $field => $value) {
                if ($value == '' || $value == NULL) {
                    continue;
                }
                $head = isset($model_fields[$field]['label']) ? $model_fields[$field]['label'] : '';
                $name = isset($model_fields[$field]['name']) ? $model_fields[$field]['name'] : '';
                if ($head) {
                    $return[$head] =  ((isset($this->model_signup->get_fields($name)['list_data'][$value])) ? $this->model_signup->get_fields($name)['list_data'][$value] : $value);
                }
            }
            return $return;
        }
    }

    /**
     * Method canView
     *
     * @param int $visitor
     * @param int $profile_id
     *
     * @return bool
     */
    function canView(int $visitor, int $profile_id): bool
    {
        $can_view = FALSE;
        if ($visitor == $profile_id) {
            $can_view = TRUE;
        } else {
            $profile_detail = $this->find_by_pk($profile_id);
            if ($profile_detail) {
                if ($profile_detail && $profile_detail['signup_privacy']) {
                    switch ($profile_detail['signup_privacy']) {
                        case 'public':
                            $can_view = TRUE;
                            break;
                        case 'follower':
                            if ($this->model_signup_follow->isFollowing($visitor, $profile_id, FOLLOW_REFERENCE_SIGNUP)) {
                                $can_view = TRUE;
                            }
                            break;
                        case 'private':
                            $can_view = FALSE;
                            break;
                    }
                }
            }
        }
        return $can_view;
    }

    /**
     * Method get_fields
     *
     * @param $string $specific_field
     *
     * @return array
     */
    public function get_fields($specific_field = "")
    {
        $flag = (($this->uri->segment(4) != null) && intval($this->uri->segment(4))) ? '' : 'required';

        $data =  array(

            'signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_lifetime_subscription' => array(
                'table' => $this->_table,
                'name' => 'signup_is_approved',
                'label' => 'Lifetime Subscription',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>No</span>",
                    1 =>  "<span class='label label-primary'>Yes</span>"
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),

            'signup_is_approved' => array(
                'table' => $this->_table,
                'name' => 'signup_is_approved',
                'label' => 'Approved User',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>No</span>",
                    1 =>  "<span class='label label-primary'>Yes</span>"
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),

            'signup_is_verified' => array(
                'table' => $this->_table,
                'name' => 'signup_is_verified',
                'label' => 'Identity Verified?',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>No</span>",
                    1 =>  "<span class='label label-primary'>Yes</span>"
                ),
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_is_confirmed' => array(
                'table' => $this->_table,
                'name' => 'signup_is_confirmed',
                'label' => 'Email Confirmed?',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>No</span>",
                    1 =>  "<span class='label label-primary'>Yes</span>"
                ),
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_is_phone_confirmed' => array(
                'table' => $this->_table,
                'name' => 'signup_is_phone_confirmed',
                'label' => 'Phone Number Confirmed?',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>No</span>",
                    1 =>  "<span class='label label-primary'>Yes</span>"
                ),
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_firstname' => array(
                'table' => $this->_table,
                'name' => 'signup_firstname',
                'label' => 'First Name',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'required|strtolower|trim|htmlentities|min_length[3]|callback_alpha_space'
            ),

            'signup_lastname' => array(
                'table' => $this->_table,
                'name' => 'signup_lastname',
                'label' => 'Last Name',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'required|strtolower|trim|htmlentities|min_length[3]|callback_alpha_space'
            ),

            'signup_phone' => array(
                'table' => $this->_table,
                'name' => 'signup_phone',
                'label' => ' Phone ',
                'type' => 'text',
                'attributes' => array(),
                'rules' => 'trim|htmlentities|callback_unique_phone[' . $this->_table . '.' . $this->_field_prefix . 'email]',
            ),

            'signup_birthday' => array(
                'table' => $this->_table,
                'name' => 'signup_birthday',
                'label' => 'BirthDay ',
                'type' => 'text',
                'attributes' => array(),
                'rules' => 'trim|htmlentities'
            ),

            'signup_about_me' => array(
                'table' => $this->_table,
                'name' => 'signup_about_me',
                'label' => 'About Info ',
                'type' => 'editor',
                'attributes' => array(),
                'rules' => 'trim|htmlentities'
            ),

            'signup_address' => array(
                'table' => $this->_table,
                'name' => 'signup_address',
                'label' => 'Address',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities|min_length[3]'
            ),

            // 'signup_location' => array(
            //     'table' => $this->_table,
            //     'name' => 'signup_location',
            //     'label' => 'Location',
            //     'type' => 'text',
            //     'attributes' => array(),
            //     'js_rules' => '',
            //     'rules' => 'strtolower|trim|htmlentities|min_length[3]'
            // ),

            'signup_city' => array(
                'table' => $this->_table,
                'name' => 'signup_city',
                'label' => ' City',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|htmlentities'
            ),

            'signup_email' => array(
                'table' => $this->_table,
                'name' => 'signup_email',
                'label' => 'Email',
                'type' => (!empty($flag) ? 'text' : 'readonly'),
                'attributes'   => array('class' => 'readonlytxt'),
                'js_rules' => 'required',
                'rules' => 'required|valid_email|strtolower|trim|htmlentities|callback_unique_email[' . $this->_table . '.' . $this->_field_prefix . 'email]',
            ),

            'signup_password' => array(
                'table' => $this->_table,
                'name' => 'signup_password',
                'label' => 'Password',
                'type' => (!empty($flag) ? 'password' : 'hidden'),
                'default' => '',
                'attributes' => array(),
                'rules' => 'required|trim|min_length[6]'
            ),

            'signup_state' => array(
                'table' => $this->_table,
                'name' => 'signup_state',
                'label' => 'State',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities'
            ),

            'signup_country' => array(
                'table' => $this->_table,
                'name' => 'signup_country',
                'label' => 'Country',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities'
            ),

            'signup_type' => array(
                'table' => $this->_table,
                'name' => 'signup_type',
                'label' => 'Membership Type',
                'type' => 'dropdown',
                'type_dt' => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data' => "signup_type",
                'list_data_key' => "signup_type",
                'list_data' => array(
                    ROLE_0 => RAW_ROLE_0,
                    ROLE_1 => RAW_ROLE_1,
                    ROLE_3 => RAW_ROLE_3,
                    ROLE_4 => RAW_ROLE_4,
                    ROLE_5 => RAW_ROLE_5,
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),

            'signup_profession' => array(
                'table' => $this->_table,
                'name' => 'signup_profession',
                'label' => 'Profession',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities|min_length[4]|max_length[100]'
            ),

            'signup_address' => array(
                'table' => $this->_table,
                'name' => 'signup_address',
                'label' => 'Address',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim'
            ),

            'signup_country' => array(
                'table' => $this->_table,
                'name' => 'signup_country',
                'label' => 'Country',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities'
            ),

            'signup_location_country' => array(
                'table' => $this->_table,
                'name' => 'signup_location_country',
                'label' => 'Country',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities'
            ),
            'signup_location_state' => array(
                'table' => $this->_table,
                'name' => 'signup_location_state',
                'label' => 'State',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities'
            ),
            'signup_location_city' => array(
                'table' => $this->_table,
                'name' => 'signup_location_city',
                'label' => 'City',
                'type' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'strtolower|trim|htmlentities'
            ),
            // 'signup_token' => array(
            //     'table'   => $this->_table,
            //     'name'   => 'signup_token',
            //     'label'   => 'Signup Token',
            //     'type'   => 'hidden',
            //     'list_data' => array(
            //     ) ,
            //     'attributes'   => array(),
            //     'dt_attributes'   => array("width"=>"7%"),
            //     'rules'   => 'trim'
            // ),

            // 'signup_logo_image' => array(
            //     'table' => $this->_table,
            //     'name' => 'signup_logo_image',
            //     'label' => 'Image',
            //     'name_path' => 'signup_logo_image_path',
            //     'upload_config' => 'site_upload_signup',
            //     'type' => 'fileupload',
            //     'type_dt' => 'image',
            //     'randomize' => true,
            //     'preview' => 'true',
            //     'attributes'   => array(
            //         'image_size_recommended'=>'1024px × 640px',
            //         'allow_ext'=>'png|jpeg|jpg',
            //     ),
            //     'thumb'   => array(array('name'=>'signup_logo_image_thumb','max_width'=>150, 'max_height'=>150),),
            //     'dt_attributes' => array("width" => "10%"),
            //     'rules' => 'trim|htmlentities',
            //     'js_rules'=>''
            // ),

            'signup_status' => array(
                'table' => $this->_table,
                'name' => 'signup_status',
                'label' => 'Status',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>Inactive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),
        );

        if ($this->uri->segment(4) != null) {
            unset($data['signup_password']);
        }

        if ($specific_field)
            return $data[$specific_field];
        else
            return $data;
    }
}
