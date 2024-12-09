<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Partner
 */
class Partner extends MY_Controller
{
    public $_list_data = array();

    public function __construct()
    {
        global $config;
        parent::__construct();

        $this->dt_params['dt_headings'] = "partner_id,partner_title,partner_status";
        $this->dt_params['searchable'] = array("partner_id", "partner_title", "partner_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "hide_save_new" => true,
            "hide_save_edit" => true,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
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
