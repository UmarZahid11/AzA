<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Partner extends MY_Controller
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
     * Method index
     *
     * @return void
     */
    public function index()
    {
        $param = array();
        $param['where']['inner_banner_name'] = 'Partners';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $data['partner_images'] = array_chunk($this->model_partner_image->find_all($param), 6);

        //
        $this->layout_data['title'] = 'Partners | ' . $this->layout_data['title'];
        //
        $this->load_view('index', $data);
    }
}
