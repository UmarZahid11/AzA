<?php

/**
 * Model_job_milestone
 */
class Model_job_milestone extends MY_Model
{
    protected $_table    = 'job_milestone';
    protected $_field_prefix    = 'job_milestone_';
    protected $_pk    = 'job_milestone_id';
    protected $_status_field    = 'job_milestone_status';
    public $relations = array();
    public $pagination_params = array();
    public $dt_params = array();
    public $_per_page    = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "job_milestone_id, job_milestone_job_id, job_milestone_application_id, job_milestone_status";
        $this->pagination_params['joins'][] = array(
            "table" => "job_application",
            "joint" => "job_application.job_application_id = job_milestone.job_milestone_application_id",
        );
        $this->pagination_params['joins'][] = array(
            "table" => "job",
            "joint" => "job.job_id = job_milestone.job_milestone_job_id",
        );

        parent::__construct();
    }

    /**
     * Method locked_milestone_exists
     *
     * @param int $job_id
     * @param int $job_application_id
     *
     * @return bool
     */
    public function locked_milestone_exists(int $job_id = 0, int $job_application_id = 0): bool
    {
        $milestone = $this->find_all_active(
            array(
                'where' => array(
                    'job_milestone_job_id' => $job_id,
                    'job_milestone_application_id' => $job_application_id
                )
            )
        );
        foreach ($milestone as $key => $value) {
            // one milestone is already locked and job status is incomplete
            if ($value['job_milestone_lock_status'] && !$value['job_milestone_completion_status']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method all_milestone_complete
     *
     * @param int $job_id
     * @param int $job_application_id
     *
     * @return bool
     */
    public function all_milestone_complete(int $job_id = 0, int $job_application_id = 0): bool
    {
        $counter = 0;
        $milestone = $this->find_all_active(
            array(
                'where' => array(
                    'job_milestone_job_id' => $job_id,
                    'job_milestone_application_id' => $job_application_id
                )
            )
        );
        foreach ($milestone as $key => $value) {
            // milestone is locked and completed
            if ($value['job_milestone_lock_status'] && $value['job_milestone_completion_status']) {
                $counter++;
            }
        }
        if ($counter == count($milestone) && count($milestone) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Method is_milestone_allowed
     *
     * @param int $job_id
     * @param int $job_application_id
     * @param int $milestone_id
     *
     * @return bool
     */
    public function allowed_milestone_to_start(int $job_id = 0, int $job_application_id = 0, int $milestone_id = 0): bool
    {
        $milestone_details = $this->model_job_milestone->find_one_active(
            array(
                'where' => array(
                    'job_milestone_id' => $milestone_id,
                    'job_milestone_job_id' => $job_id,
                    'job_milestone_application_id' => $job_application_id,
                    'job_milestone_lock_status' => 0,
                    'job_milestone_request_status' => 1
                ),
                'joins' => array(
                    0 => array(
                        "table" => "job",
                        "joint" => "job.job_id = job_milestone.job_milestone_job_id",
                        "type" => "both"
                    ),
                    1 => array(
                        "table" => "job_application",
                        "joint" => "job_application.job_application_id = job_milestone.job_milestone_application_id",
                        "type" => "both"
                    )
                )
            )
        );
        if (!empty($milestone_details)) {
            $previous_milestone = $this->model_job_milestone->find_one_active(
                array(
                    'where' => array(
                        'job_milestone_id < ' => $milestone_details['job_milestone_id'],
                        'job_milestone_job_id' => $job_id,
                        'job_milestone_application_id' => $job_application_id,
                        'job_milestone_completion_status != ' => 1
                    )
                )
            );
            if (empty($previous_milestone)) {
                return true;
            }
        }
        return false;
    }

    /*
    * table             Table Name
    * Name              FIeld Name
    * label             Field Label / Textual Representation in form and DT headings
    * type              Field type : hidden, text, textarea, editor, etc etc.
    *                                 Implementation in form_generator.php
    * type_dt           Type used by prepare_datatables method in controller to prepare DT value
    *                                 If left blank, prepare_datatable Will opt to use 'type'
    * type_filter_dt    Used by DT FILTER PREPRATION IN datatables.php
    * attributes        HTML Field Attributes
    * js_rules          Rules to be aplied in JS (form validation)
    * rules             Server side Validation. Supports CI Native rules

    * list_data         For dropdown etc, data in key-value pair that will populate dropdown
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
    *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    */
    public function get_fields($specific_field = "")
    {
        $fields = array(
            'job_milestone_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_milestone_job_id' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_job_id',
                'label'   => 'Job',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "job_milestone_job_id",
                'list_data' => "job_milestone_job_id",
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'job_milestone_status' => array(
                'table'   => $this->_table,
                'name'   => 'job_milestone_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "job_milestone_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>Read</span>",
                    1 =>  "<span class='label label-primary'>Unread</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
