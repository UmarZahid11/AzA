<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * job_testimonial_request
 */
class job_testimonial_request extends MY_Controller
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

        $this->dt_params['dt_headings'] = "job_testimonial_request_id, job_testimonial_request_signup_id, job_testimonial_request_status";
        $this->dt_params['searchable'] = array("job_testimonial_request_id", "job_testimonial_request_signup_id", "job_testimonial_request_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => true,
            "hide_save_edit" => true,
            "hide_save_new" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['job_testimonial_request_signup_id'] = $this->model_signup->find_all_list_active(array(), 'signup_email');
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
        $this->model_job_testimonial_request->update_by_pk($id, array('job_testimonial_request_seen' => STATUS_ACTIVE));
        //
        $this->add_script(array("jquery.validate.js", "form-validation-script.js"), "js");
        $this->register_plugins("jquery-file-upload");
        parent::add($id, $data = array());
    }
}
