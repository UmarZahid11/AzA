<?php

/**
 * Model_signup_bypass_privilege
 */
class Model_signup_bypass_privilege extends MY_Model
{
    protected $_table = 'signup_bypass_privilege';
    protected $_field_prefix = 'signup_bypass_privilege_';
    protected $_pk = 'signup_bypass_privilege_id';
    protected $_status_field = 'signup_bypass_privilege_status';
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
        $this->pagination_params['fields'] = "signup_bypass_privilege_id, signup_bypass_privilege_status";
        parent::__construct();
    }

    /**
     * Method get
     *
     * @param int $signup_id
     * @param int $type
     * @param bool $active
     * @param bool $return_array
     *
     * @return bool
     */
    function get(int $signup_id, int $type, $active = TRUE, $return_array = FALSE)
    {
        $privilege = array();
        $status = FALSE;
        $signup = $this->model_signup->find_by_pk($signup_id);

        if ($signup) {
            $where_param = array(
                'signup_bypass_privilege_signup_id' => $signup_id,
                'signup_bypass_privilege_type' => $type,
            );
            if ($active) {
                $where_param['signup_bypass_privilege_status'] = STATUS_ACTIVE;
            }
            $privilege = $this->find_one(
                array(
                    'where' => $where_param
                )
            );
        }

        // || condition for omitting PRIVILEGE_TYPE_TESTIMONIAL for now
        if (!empty($privilege) || $type == PRIVILEGE_TYPE_TESTIMONIAL) {
            $status = TRUE;
        }

        if ($return_array) {
            return $privilege;
        } else {
            return $status;
        }
    }

    /**
     * Method get_fields
     *
     * @param string $specific_field
     *
     * @return array
     */
    public function get_fields($specific_field = "")
    {
        $data =  array(

            'signup_bypass_privilege_id' => array(
                'table' => $this->_table,
                'name' => 'signup_bypass_privilege_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_bypass_privilege_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_bypass_privilege_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_bypass_privilege_status' => array(
                'table' => $this->_table,
                'name' => 'signup_bypass_privilege_status',
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
