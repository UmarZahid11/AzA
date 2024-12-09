<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Job_milestone_payment
 */
class Job_milestone_payment extends MY_Controller
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

        $this->dt_params['dt_headings'] = "job_milestone_payment_id, signup_fullname, job_title, job_milestone_payment_amount, job_milestone_payment_due, job_milestone_payment_createdon, job_milestone_payment_updatedon, job_milestone_payment_money_position_status";
        $this->dt_params['searchable'] = array("job_milestone_payment_id", "signup_fullname", "job_title", "job_milestone_payment_money_position_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => false,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(
                '<a title="View" href="' . $config['admin_base_url'] . 'job_milestone_payment/detail/%d/" class="btn-xs btn btn-primary order_details_btn"><i class="icon-picture"></i></a>',
            ),
        );

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
    public function add($id = 0, $data = array())
    {
        parent::add($id, $data = array());
    }

    /**
     * Method detail
     *
     * @param int $job_milestone_payment_id
     *
     * @return void
     */
    public function detail($job_milestone_payment_id = 0)
    {
        $this->layout_data['template_config']['show_toolbar'] = false;
        $this->register_plugins(array(
            "jquery-ui",
            "bootstrap",
            "bootstrap-hover-dropdown",
            "jquery-slimscroll",
            "uniform",
            "boots",
            "font-awesome",
            "simple-line-icons",
            "select2",
            "bootbox",
            "bootstrap-toastr",
            "bootstrap-datetimepicker"
        ));

        $data['job_milestone_payment_detail'] = $this->model_job_milestone_payment->find_one_active(
            array(
                'where' => array(
                    'job_milestone_payment_id' => (int) $job_milestone_payment_id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'job_milestone',
                        'joint' => 'job_milestone.job_milestone_id = job_milestone_payment.job_milestone_payment_milestone_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'job',
                        'joint' => 'job.job_id = job_milestone.job_milestone_job_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'job_application',
                        'joint' => 'job_application.job_application_id = job_milestone.job_milestone_application_id',
                        'type' => 'both'
                    ),
                    3 => array(
                        "table" => "signup",
                        "joint" => "signup.signup_id = job_application.job_application_signup_id",
                        "type"  => "both"
                    )
                )
            )
        );

        if (!array_filled($data['job_milestone_payment_detail']))
            not_found("Invalid resource found.");

        $data['page_title_min'] = "Detail";
        $data['page_title'] = "Job Milestone Payment";
        $data['class_name'] = "job_milestone_payment";
        $data['model_name'] = "model_job_milestone_payment";
        $data['model_obj'] = $this->model_job_milestone_payment;
        $data['dt_params'] = $this->dt_params;
        $data['id'] = (int) $job_milestone_payment_id;
        $data['object'] = $this;

        $this->load_view("detail", $data);
    }
}
