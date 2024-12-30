<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Coaching_application
 */
class Coaching_application extends MY_Controller
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

        $this->dt_params['dt_headings'] = "coaching_application_id, coaching_application_signup_id, coaching_application_coaching_id, coaching_application_status";
        $this->dt_params['searchable'] = array("coaching_application_id", "coaching_application_signup_id", "coaching_application_coaching_id", "coaching_application_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => false,
            "hide" => false,
            "hide_save_new" => true,
            "hide_save_edit" => true,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['banner_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-danger\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );

        $this->_list_data['coaching_application_signup_id'] = $this->model_signup->find_all_list_active(array(), 'signup_email');
        $this->_list_data['coaching_application_coaching_id'] = $this->model_coaching->find_all_list_active(array(), 'coaching_title');

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }
}