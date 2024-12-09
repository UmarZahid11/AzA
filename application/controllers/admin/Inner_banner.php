<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Inner_banner
 */
class Inner_banner extends MY_Controller
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

        $this->dt_params['dt_headings'] = "inner_banner_id,inner_banner_name,inner_banner_status";
        $this->dt_params['searchable'] = array("inner_banner_id", "inner_banner_name", "inner_banner_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => false,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->form_params['action'] = array(
            "hide_save_new" => true
        );
        $this->form_params['action'] = array(
            'hide_save' => true,
            'hide_save_new' => false
        );

        $this->_list_data['inner_banner_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-danger\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];

        $this->_list_data['inner_banner_page'] = array(
            '1' => 'Home',
            '2' => 'Terms & Condition',
            '3' => 'Privacy Policy',
            '4' => 'Resources',
            '5' => 'About Us',
            '6' => 'Contact Us',
            '7' => 'Sign Up',
            '8' => 'Login',
        );
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
        parent::add($id, $data);
    }
}


