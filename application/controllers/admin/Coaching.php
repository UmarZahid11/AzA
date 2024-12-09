<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Coaching
 */
class Coaching extends MY_Controller
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

        $this->dt_params['dt_headings'] = "coaching_id, coaching_title, coaching_start_time, coaching_status";
        $this->dt_params['searchable'] = array("coaching_id", "coaching_title", "coaching_status");
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
        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }
}