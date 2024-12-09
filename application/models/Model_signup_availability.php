<?php

/**
 * Model_signup_availability
 */
class Model_signup_availability extends MY_Model
{
    protected $_table = 'signup_availability';
    protected $_field_prefix = 'signup_availability_';
    protected $_pk = 'signup_availability_id';
    protected $_status_field = 'signup_availability_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
    public $_per_page = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "signup_availability_id, signup_availability_status";
        parent::__construct();
    }

    /**
     * Method userAvailabilitySlots - specific fields due to json encoding of calender data
     *
     * @param int $userid
     *
     * @return void
     */
    public function userAvailabilitySlots($userid)
    {
        return $this->find_all_active(
            array(
                'where' => array(
                    'signup_availability_signup_id' => $userid,
                ),
                'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup_availability.signup_availability_requester_id = signup.signup_id',
                        'type'  => 'left'
                    )
                )
            )
        );
    }

    /**
     * Method userAvailabilitySlots
     *
     * @param int $userid
     *
     * @return void
     */
    public function userAvailabilitySlotsCalendar($userid)
    {
        $slots = $this->find_all_active(
            array(
                'where' => array(
                    'signup_availability_signup_id' => $userid,
                ),
                // 'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup_availability.signup_availability_signup_id = signup.signup_id',
                        'type'  => 'left'
                    )
                )
            )
        );
        
        $availabilty_time = [];
        $requester_email = '';
        foreach($slots as $key => $value) {
        
            //
            $requester = $this->model_signup->find_by_pk($value['signup_availability_requester_id']);
            if($requester) {
                $requester_email = $requester['signup_email'];
            }
            
            //
            $meeting_recording = array();
                
            //
            $availabilty_time[] = array(
                'id' => $value['signup_availability_id'],
                'email' => $value['signup_email'],
                'requester' => $requester_email,
                // 'title' => ($value['signup_availability_title']),
                'title' => ($value['signup_availability_purpose'] ?? $value['signup_availability_title']) . ($value['signup_email'] ? (' (' . $value['signup_email'] . ')') : ''),

                'purpose' => $value['signup_availability_purpose'],
                'color' => ($value['signup_availability_type'] == SLOT_LOCKED) ? SLOT_LOCKED_COLOR : SLOT_AVAILABLE_COLOR,
                'type' => CALENDAR_TYPE_SLOT,
                'start' => $value['signup_availability_start'],
                'end' => $value['signup_availability_end'],
                'start_url' => $value['signup_availability_meeting_start_url'],
                'join_url' => $value['signup_availability_meeting_join_url'],
                'current_status' => $value['signup_availability_meeting_current_status'],
                'meeting_url' => (($value['signup_availability_type'] == SLOT_LOCKED) ? l('dashboard/meeting/detail/' . JWT::encode($value['signup_availability_id'])) : ''),
            );
        }
        return $availabilty_time;
    }
    
    /**
     * Method userAvailabilitySlots
     *
     * @param int $userid
     *
     * @return void
     */
    public function userRequestorSlotsCalendar($userid)
    {
        $slots = $this->find_all_active(
            array(
                'where' => array(
                    'signup_availability_requester_id' => $userid,
                ),
                // 'fields' => 'signup_availability_id as id, signup_email as email, signup_availability_title as title, signup_availability_purpose as purpose, IF(signup_availability_type = "' . SLOT_LOCKED . '", "' . SLOT_LOCKED_COLOR . '", "' . SLOT_AVAILABLE_COLOR . '") as color, signup_availability_type as type, signup_availability_start as start, signup_availability_end as end, signup_availability_meeting_start_url as start_url, signup_availability_meeting_join_url as join_url, signup_availability_meeting_current_status as current_status',
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup_availability.signup_availability_signup_id = signup.signup_id',
                        'type'  => 'left'
                    )
                )
            )
        );
        
        $availabilty_time = [];
        foreach($slots as $key => $value) {
            //
            $requester = $this->model_signup->find_by_pk($value['signup_availability_requester_id']);
            if($requester) {
                $requester_email = $requester['signup_email'];
            }
            
            //
            $meeting_recording = array();

            //
            $availabilty_time[] = array(
                'id' => $value['signup_availability_id'],
                'email' => $value['signup_email'],
                'requester' => $requester_email,
                // 'title' => ($value['signup_availability_title']),
                'title' => ($value['signup_availability_purpose'] ?? $value['signup_availability_title']) . ($value['signup_email'] ? (' - Requester: ' . $value['signup_email']) : ''),

                'purpose' => $value['signup_availability_purpose'],
                'color' => ($value['signup_availability_type'] == SLOT_LOCKED) ? SLOT_LOCKED_COLOR : SLOT_AVAILABLE_COLOR,
                'type' => CALENDAR_TYPE_SLOT,
                'start' => $value['signup_availability_start'],
                'end' => $value['signup_availability_end'],
                'start_url' => $value['signup_availability_meeting_start_url'],
                'join_url' => $value['signup_availability_meeting_join_url'],
                'current_status' => $value['signup_availability_meeting_current_status'],
                'meeting_url' => (($value['signup_availability_type'] == SLOT_LOCKED) ? l('dashboard/meeting/detail/' . JWT::encode($value['signup_availability_id'])) : ''),
            );
        }
        return $availabilty_time;
    }
    
    /**
     * Method userAvailabilitySlot - specific fields due to json encoding of calender data
     *
     * @param $id $id
     * @param $userid $userid
     *
     * @return void
     */
    public function userAvailabilitySlot($id, $userid)
    {
        return $this->find_one_active(
            array(
                'where' => array(
                    'signup_availability_id' => $id,
                    'signup_availability_signup_id' => $userid,
                ),
                'fields' => 'signup_availability_id as id, signup_availability_title as title, signup_availability_start as start, signup_availability_end as end',
            )
        );
    }

    /**
     * Method get_fields
     *
     * @param string $specific_field
     *
     * @return array
     */
    public function get_fields($specific_field = "")
    {
        $data =  array(

            'signup_availability_id' => array(
                'table' => $this->_table,
                'name' => 'signup_availability_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_availability_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_availability_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_availability_status' => array(
                'table' => $this->_table,
                'name' => 'signup_availability_status',
                'label' => 'Status',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>Inactive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),

        );

        if ($specific_field)
            return $data[$specific_field];
        else
            return $data;
    }
}
