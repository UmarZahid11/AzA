<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Opportunity
 */
class Opportunity extends MY_Controller
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
        $data = array();

        $this->layout_data['title'] = 'Announcements | ' . $this->layout_data['title'];

        $param = array();
        $param['where']['inner_banner_name'] = 'Opportunity';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Opportunity';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        $this->load_view("index", $data);
    }
}