<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Appointment
 */
class Appointment extends MY_Controller
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
    
    
    public function schedule()
    {
        $data = [];

        $param = array();
        $param['where']['inner_banner_name'] = 'Membership';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        //
        $this->layout_data['title'] = 'Appointment Schedule  | ' . $this->layout_data['title'];
        //
        $this->load_view("schedule", $data);
    }

    public function quote()
    {
        $data = [];

        $param = array();
        $param['where']['inner_banner_name'] = 'Membership';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        //
        $this->layout_data['title'] = 'Appointment Quote  | ' . $this->layout_data['title'];
        //
        $this->load_view("quote", $data);
    }
    
}