<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Privacy
 */
class Privacy extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data = array();

        $param = array();
        $param['where']['inner_banner_name'] = 'Privacy';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Privacy';
        $data['cms'] = $this->model_cms_page->find_one_active($param);

        //
        $this->layout_data['title'] = 'Privacy Policy | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}
