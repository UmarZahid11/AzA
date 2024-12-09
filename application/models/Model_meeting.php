<?php

class Model_meeting extends MY_Model
{
    protected $_table    = 'meeting';
    protected $_field_prefix    = 'meeting_';
    protected $_pk    = 'meeting_id';
    protected $_status_field    = 'meeting_status';
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
        $this->pagination_params['fields'] = "meeting_id, meeting_signup_id, meeting_topic, meeting_status";
        parent::__construct();
    }

    /**
     * Method meetings
     *
     * @param bool $my_created_meeting
     *
     * @return array
     */
    function meetings($my_created_meeting = FALSE, $reference = MEETING_REFERENCE_APPLICATION): array
    {
        $meeting_time = array();
        $where_param = array();
        $join_param = array();

        if (!$my_created_meeting) {
            if($reference == MEETING_REFERENCE_APPLICATION) {
                $where_param = array(
                    'job_application_signup_id' => $this->userid,
                    'meeting_reference_type' => $reference,
                );
                $join_param[0] = array(
                    'table' => 'job_application',
                    'joint' => 'job_application.job_application_id = meeting.meeting_reference_id',
                    'type' => 'both'
                );
                $join_param[1] = array(
                    'table' => 'job',
                    'joint' => 'job.job_id = job_application.job_application_job_id',
                    'type' => 'both'
                );
            } else if($reference == MEETING_REFERENCE_PRODUCT) {
                $where_param = array(
                    'product_request_signup_id' => $this->userid,
                    'meeting_reference_type' => $reference,
                );
                $join_param[0] = array(
                    'table' => 'product_request',
                    'joint' => 'product_request.product_request_id = meeting.meeting_reference_id',
                    'type' => 'both'
                );
                $join_param[1] = array(
                    'table' => 'product',
                    'joint' => 'product.product_id = product_request.product_request_product_id',
                    'type' => 'both'
                );
            }
        } else if ($my_created_meeting) {
            $where_param = array(
                'meeting_signup_id' => $this->userid,
                // 'meeting_reference_type' => MEETING_REFERENCE_APPLICATION,
            );
        }
        
        $join_param[2] = array(
            'table' => 'signup',
            'joint' => 'signup.signup_id = meeting.meeting_signup_id',
            'type' => 'both'
        );
        $join_param[3] = array(
            'table' => 'signup_info',
            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
            'type' => 'both'
        );
        $join_param[4] = array(
            'table' => 'signup_company',
            'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
            'type' => 'left'
        );

        $meetings = $this->find_all_active(
            array(
                'where' => $where_param,
                'joins' => $join_param 
            )
        );

        if (!empty($meetings)) {
            foreach ($meetings as $key => $value) {
                $meeting_time[] = array(
                    'id' => $value['meeting_id'],
                    'email' => $value['signup_email'],
                    'title' => ($value['meeting_topic'] ?? $value['meeting_topic']) . ($value['signup_email'] ? ' - Organizer: ' . $value['signup_email'] : ''),
                    'start' => $value['meeting_start_time'],
                    'end' => date('Y-m-d H:i:s', strtotime('+' . (int) $value['meeting_duration'] . 'minutes', strtotime($value['meeting_start_time']))),
                    'type' => CALENDAR_TYPE_MEETING,
                    'start_url' => $value['meeting_start_url'],
                    'join_url' => $value['meeting_join_url'],
                    'current_status' => $value['meeting_current_status'],
                    'meeting_url' => l('dashboard/meeting/detail/' . JWT::encode($value['meeting_id']))
                );
            }
        }
        return $meeting_time;
    }

    /**
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
     *
     * list_data         For dropdown etc, data in key-value pair that will populate dropdown
     *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
     * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
     *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
     *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
     */
    public function get_fields($specific_field = "")
    {
        $fields = array(
            'meeting_id' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'meeting_signup_id' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_signup_id',
                'label'   => 'Creator',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "meeting_signup_id",
                'list_data' => $this->model_signup->find_all_list_active(array(), 'signup_email'),
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'meeting_uuid' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_uuid',
                'label'   => 'UUID',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'meeting_fetchid' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_fetchid',
                'label'   => 'Meeting id',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'meeting_host_id' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_host_id',
                'label'   => 'Host id',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            // 'meeting_host_email' => array(
            //     'table'   => $this->_table,
            //     'name'   => 'meeting_host_email',
            //     'label'   => 'Host email',
            //     'type'   => 'text',
            //     'type_dt'   => 'text',
            //     'attributes'   => array(),
            //     'dt_attributes'   => array("width" => "5%"),
            //     'js_rules'   => '',
            //     'rules'   => 'trim'
            // ),

            'meeting_agenda' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_agenda',
                'label'   => 'Agenda',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_topic' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_topic',
                'label'   => 'Topic',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_duration' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_duration',
                'label'   => 'Agenda',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_password' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_password',
                'label'   => 'Password',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_contact_email' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_contact_email',
                'label'   => 'Contact Email',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_contact_name' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_contact_name',
                'label'   => 'Contact Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_start_time' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_start_time',
                'label'   => 'Start time',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_response' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_response',
                'label'   => 'Response',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_timezone' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_timezone',
                'label'   => 'Timezone',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'meeting_current_status' => array(
                'table' => $this->_table,
                'name' => 'meeting_current_status',
                'label' => 'Meeting Status',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    ZOOM_MEETING_PENDING => "<span class='label label-danger'>Pending</span>",
                    ZOOM_MEETING_STARTED => "<span class='label label-primary'>Started</span>",
                    ZOOM_MEETING_ENDED => "<span class='label label-primary'>Ended</span>"
                ),
                'default'   => '0',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'meeting_status' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "meeting_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'meeting_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'meeting_createdon',
                'label'   => 'Createdon',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),
        );

        if ($specific_field)
            return $fields[$specific_field];
        else
            return $fields;
    }
}
