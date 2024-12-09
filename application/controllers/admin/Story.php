<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Story
 */
class Story extends MY_Controller
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

        $this->dt_params['dt_headings'] = "story_id, story_title, story_image, story_status";
        $this->dt_params['searchable'] = array("story_id", "story_title", "story_status");
        $this->dt_params['action'] = array(
            "hide" => false,
            "hide_add_button" => false,
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


