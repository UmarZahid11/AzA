<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Membership_section
 */
class Membership_section extends MY_Controller
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

        $this->dt_params['dt_headings'] = "membership_section_id, membership_section_name, membership_section_status";
        $this->dt_params['searchable'] = array("membership_section_id", "membership_section_name", "membership_section_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => false,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['membership_section_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        parent::index();
    }

    /**
     * Method add
     *
     * @param int $id
     * @param array $data
     *
     * @return void
     */
    public function add($id = '', $data = array())
    {
        $this->add_script(array("jquery.validate.js", "form-validation-script.js"), "js");
        $this->register_plugins("jquery-file-upload");
        parent::add($id, $data);
    }
}
