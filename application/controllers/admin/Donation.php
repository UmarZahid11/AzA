<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Donation
 */
class Donation extends MY_Controller
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

        $this->dt_params['dt_headings'] = "donation_id, fundraising_title, donation_email, donation_amount, donation_status, donation_createdon";
        $this->dt_params['searchable'] = array("donation_id", "fundraising_title", "donation_email", "donation_status");
        $this->dt_params['action'] = array(
            "hide" => true,
            "hide_add_button" => false,
            "hide_save_new" => true,
            "hide_save_edit" => true,
            "show_delete" => false,
            "show_edit" => false,
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
        parent::add($id, $data = array());
    }
}


