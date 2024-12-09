<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * About
 */
class About extends MY_Controller
{
    /**
     * __construct
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
        global $config;

        $data = array();

        $param = array();
        $param['where']['cms_page_name'] = 'About';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        $param = array();
        $param['where']['inner_banner_name'] = 'About Us';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['testimonial'] = $this->model_testimonial->find_all_active();

        //
        $this->layout_data['title'] = 'About us | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}
