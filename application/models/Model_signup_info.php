<?php

/**
 * Model_signup_info
 */
class Model_signup_info extends MY_Model
{
    protected $_table = 'signup_info';
    protected $_field_prefix = 'signup_info_';
    protected $_pk = 'signup_info_id';
    protected $_status_field = 'signup_info_status';
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
        $this->pagination_params['fields'] = "signup_info_id, signup_info_status";
        parent::__construct();
    }

    /**
     * Method setSignupOnline
     *
     * @param int $userid
     * @param bool $withExpiry
     *
     * @return void
     */
    public function setSignupOnline(int $userid, bool $withExpiry = false): bool
    {
        $param = array();
        $param['signup_info_isonline'] = STATUS_ACTIVE;
        $param['signup_info_lastonline'] = date("Y-m-d H:i:s");
        $param['signup_info_signup_attempt'] = 0;

        if ($withExpiry) {
            $param['signup_info_session_expiry'] = date("Y-m-d H:i:s", strtotime('+' . SESSION_EXPIRY . ' seconds'));
        }

        $param_where['where']['signup_info_signup_id'] = $userid;

        $updated = $this->update_model($param_where, $param);

        return $updated ? true : false;
    }

    /**
     * Method updateAttemptLimit - login attempt limit
     *
     * @param array $signup_info
     * @param int $signupId
     *
     * @return void
     */
    public function updateAttemptLimit(array $signup_info, int $signupId)
    {
        $signup_info_signup_attempt = (int) $signup_info['signup_info_signup_attempt'] >= LOGIN_ATTEMPT_LIMIT ? 0 : (int) $signup_info['signup_info_signup_attempt'];
        //
        $this->model_signup_info->update_model(
            array(
                'where' => array(
                    'signup_info_signup_id' => $signupId
                ),
            ),
            array(
                'signup_info_signup_attempt' => $signup_info_signup_attempt + 1,
                'signup_info_last_signup_attempt' => date("Y-m-d H:i:s"),
            )
        );
    }

    /**
     * Method insertSignupInfo
     *
     * @param int $userid
     *
     * @return void
     */
    public function insertSignupInfo(int $userid): void
    {
        $insert_param = array();
        $insert_param['signup_info_signup_id'] = $userid;
        $insert_param['signup_info_isonline'] = STATUS_ACTIVE;
        $insert_param['signup_info_lastonline'] = date("Y-m-d H:i:s");
        $insert_param['signup_info_session_expiry'] = date("Y-m-d H:i:s", strtotime('+' . SESSION_EXPIRY . ' seconds'));
        $this->model_signup_info->insert_record($insert_param);
    }

    /**
     * Method setSignupOffline
     *
     * @param int $userid
     *
     * @return void
     */
    public function setSignupOffline(int $userid = 0, $unset_session = false)
    {
        $this->expireSession($userid);
        if ($unset_session) {
            $this->session->unset_userdata('userdata');
        }
    }

    /**
     * Method expireSession
     *
     * @param int $userid
     *
     * @return void
     */
    public function expireSession($userid)
    {
        $this->model_signup_info->update_model(array(
            'where' => array(
                'signup_info_signup_id' => $userid
            )
        ), array(
            'signup_info_lastonline' => date("Y-m-d H:i:s"),
            'signup_info_isonline' => 0,
            'signup_info_session_expiry' => NULL,
        ));
    }

    /**
     * Method get_fields
     *
     * @param string $specific_field
     *
     * @return void
     */
    public function get_fields($specific_field = "")
    {
        $data =  array(

            'signup_info_id' => array(
                'table' => $this->_table,
                'name' => 'signup_info_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_info_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_info_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_info_status' => array(
                'table' => $this->_table,
                'name' => 'signup_info_status',
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

        if ($specific_field)
            return $data[$specific_field];
        else
            return $data;
    }
}
