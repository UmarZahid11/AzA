<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Faq
 */
class Faq extends MY_Controller
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

        $this->dt_params['dt_headings'] = "faq_id,faq_title,faq_status";
        $this->dt_params['searchable'] = array("faq_id", "faq_title", "faq_status");
        $this->dt_params['action'] = array(
            "hide" => false,
            "hide_add_button" => true,
            "hide_save_new" => true,
            "hide_save_edit" => true,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['faq_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );
        $this->_list_data['faq_is_featured'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">No</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Yes</span>"
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }
}



