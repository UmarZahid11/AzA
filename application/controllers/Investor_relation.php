<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Investor_relation
 */
class Investor_relation extends MY_Controller
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

        $this->layout_data['title'] = 'Investor Relation | ' . $this->layout_data['title'];

        $param = array();
        $param['where']['inner_banner_name'] = 'Investor relation';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Investor relation';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        $this->load_view("index", $data);
    }
}