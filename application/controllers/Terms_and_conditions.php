<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Terms_and_conditions
 */
class Terms_and_conditions extends MY_Controller
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
        $param['where_like'][] = array(
            'column' => 'inner_banner_name',
            'value' => 'Terms',
            'type' => 'both',
        );
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Terms and Conditions';
        $data['cms'] = $this->model_cms_page->find_one_active($param);

        //
        $this->layout_data['title'] = 'Terms and Conditions | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}
