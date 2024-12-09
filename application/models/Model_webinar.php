<?php

/**
 * Model_webinar
 */
class Model_webinar extends MY_Model
{
    protected $_table    = 'webinar';
    protected $_field_prefix    = 'webinar_';
    protected $_pk    = 'webinar_id';
    protected $_status_field    = 'webinar_status';
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
        $this->pagination_params['fields'] = "webinar_id, webinar_topic, webinar_status";
        parent::__construct();
    }

    /**
     * Method webinars
     *
     * @param bool $only_follower - get all webinars of users who are being followed by current user
     *
     * @return array
     */
    function webinars($only_follower = TRUE): array
    {
        $webinars = array();
        $webinar_time = array();
        $following = array();

        if ($only_follower) {
            // current user following
            $following = $this->model_signup_follow->find_all_list_active(
                array(
                    'where' => array(
                        'signup_follow_follower_id' => $this->userid,
                        'signup_follow_reference_type' => FOLLOW_REFERENCE_SIGNUP
                    )
                ),
                'signup_follow_reference_id'
            );
        }

        //
        $where_param = array();
        $where_in_param = array();

        // show current user own webinars too.
        if ($this->model_signup->hasPremiumPermission()) {
            if (!in_array($this->userid, $following)) {
                array_push($following, $this->userid);
            }
        }

        if (!empty($following)) {
            $where_in_param['signup_id'] = $following;
        }

        if (!empty($following) || !$only_follower) {
            $webinars = $this->model_webinar->find_all_active(
                array(
                    'where' => $where_param,
                    'where_in' => $where_in_param,
                    'joins' => array(
                        0 =>  array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = webinar.webinar_userid',
                            'type' => 'both'
                        )
                    )
                )
            );
        }

        //
        if(!empty($webinars)) {
            foreach ($webinars as $key => $value) {
                $webinar_time[] = array(
                    'id' => $value['webinar_id'],
                    'email' => $value['signup_email'],
                    'title' => ($value['webinar_topic'] ?? $value['webinar_topic']) . ($value['signup_email'] ? ' - Organizer: ' . $value['signup_email'] : ''),
                    'start' => $value['webinar_start_time'],
                    'end' => date('Y-m-d H:i:s', strtotime('+' . (int) $value['webinar_duration'] . 'minutes', strtotime($value['webinar_start_time']))),
                    'type' => CALENDAR_TYPE_WEBINAR,
                    'start_url' => $value['webinar_start_url'],
                    'join_url' => $value['webinar_join_url'],
                    'current_status' => $value['webinar_current_status'],
                    'webinar_url' => l('dashboard/webinar/detail/' . JWT::encode($value['webinar_id']))
                );
            }
        }
        return $webinar_time;
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
            'webinar_id' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'webinar_userid' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_userid',
                'label'   => 'Creator',
                'type'   => 'dropdown',
                'type_dt'   => 'dropdown',
                'list_data_key' => "webinar_userid",
                'list_data' => $this->model_signup->find_all_list_active(array(), 'signup_email'),
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'webinar_uuid' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_uuid',
                'label'   => 'UUID',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'webinar_fetchid' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_fetchid',
                'label'   => 'webinar id',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'webinar_host_id' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_host_id',
                'label'   => 'Host id',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            // 'webinar_host_email' => array(
            //     'table'   => $this->_table,
            //     'name'   => 'webinar_host_email',
            //     'label'   => 'Host email',
            //     'type'   => 'text',
            //     'type_dt'   => 'text',
            //     'attributes'   => array(),
            //     'dt_attributes'   => array("width" => "5%"),
            //     'js_rules'   => '',
            //     'rules'   => 'trim'
            // ),

            'webinar_agenda' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_agenda',
                'label'   => 'Agenda',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_topic' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_topic',
                'label'   => 'Topic',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_duration' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_duration',
                'label'   => 'Agenda',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_password' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_password',
                'label'   => 'Password',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_contact_email' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_contact_email',
                'label'   => 'Contact Email',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_contact_name' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_contact_name',
                'label'   => 'Contact Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_start_time' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_start_time',
                'label'   => 'Start time',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_response' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_response',
                'label'   => 'Response',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_timezone' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_timezone',
                'label'   => 'Timezone',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'webinar_current_status' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_current_status',
                'label'   => 'webinar Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "webinar_current_status",
                'list_data' => array(
                    ZOOM_WEBINAR_PENDING => "<span class='label label-danger'>Pending</span>",
                    ZOOM_WEBINAR_STARTED => "<span class='label label-primary'>Started</span>",
                    ZOOM_WEBINAR_ENDED => "<span class='label label-primary'>Ended</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'webinar_status' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "webinar_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default'   => '1',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "7%"),
                'rules'   => 'trim'
            ),

            'webinar_createdon' => array(
                'table'   => $this->_table,
                'name'   => 'webinar_createdon',
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
