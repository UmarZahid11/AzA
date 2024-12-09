<?php

/**
 * Model_signup_testimonial - self uploaded testimonials
 */
class Model_signup_testimonial extends MY_Model
{
    protected $_table = 'signup_testimonial';
    protected $_field_prefix = 'signup_testimonial_';
    protected $_pk = 'signup_testimonial_id';
    protected $_status_field = 'signup_testimonial_status';
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
        $this->pagination_params['fields'] = "signup_testimonial_id, signup_testimonial_status";
        parent::__construct();
    }

    /**
     * Method getSignupTestimonial
     *
     * @param int $userid
     * @param bool $count
     *
     * @return mixed
     */
    function getSignupTestimonial(int $userid, bool $count = FALSE)
    {
        $where_param = array(
            'where' => array(
                'signup_testimonial_signup_id' => $userid
            )
        );

        if ($count) {
            return $this->find_count_active($where_param);
        } else {
            return $this->find_all_active($where_param);
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

            'signup_testimonial_id' => array(
                'table' => $this->_table,
                'name' => 'signup_testimonial_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_testimonial_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_testimonial_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_testimonial_status' => array(
                'table' => $this->_table,
                'name' => 'signup_testimonial_status',
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
