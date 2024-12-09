<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * job_question
 */
class job_question extends MY_Controller
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

        $this->dt_params['dt_headings'] = "job_question_id, job_question_job_id, job_question_title, job_question_status";
        $this->dt_params['searchable'] = array("job_question_id", "job_question_title", "job_question_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => false,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['job_question_job_id'] = $this->model_job->find_all_list_active(array(), 'job_title');

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
}