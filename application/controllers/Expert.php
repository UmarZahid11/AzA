<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Expert
 */
class Expert extends MY_Controller
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
        $param = array();
        $param['where']['job_userid'] = 0;
        $param['limit'] = 6;
        $param['order'] = 'job_id DESC';
        $data['job'] = $this->model_job->find_all_active($param);

        // to be replaced by ajax on view
        $param = array();
        $param['where']['job_userid'] = 0;
        $param['offset'] = 6;
        $param['limit'] = 6;
        $param['order'] = 'job_id DESC';
        $data['additional_job'] = $this->model_job->find_all_active($param);
        
        $param = array();
        $param['where']['inner_banner_name'] = 'Experts';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        //
        $this->layout_data['title'] = 'Experts | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}