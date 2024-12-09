<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Testimonial
 */
class Testimonial extends MY_Controller
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

        $this->dt_params['dt_headings'] = "testimonial_id, testimonial_name, testimonial_designation, testimonial_image, testimonial_status";
        $this->dt_params['searchable'] = array("testimonial_id", "testimonial_name", "testimonial_status");
        $this->dt_params['action'] = array(
            "hide" => false,
            "hide_save_new" => true,
            "hide_save_edit" => true,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['testimonial_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }
}



