<?php

/**
 * Model_signup_analytics
 */
class Model_signup_analytics extends MY_Model
{

    protected $_table = 'signup_analytics';
    protected $_field_prefix = 'signup_analytics_';
    protected $_pk = 'signup_analytics_id';
    protected $_status_field = 'signup_analytics_status';
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
        $this->pagination_params['fields'] = "signup_analytics_id, signup_analytics_status";
        parent::__construct();
    }

    /**
     * Method addAnalytic
     *
     * @param int $userId
     * @param int $referId
     * @param string $type
     * @param string $comment
     *
     * @return int
     */
    public function addAnalytic(int $userId = 0, int $refererId = 0, string $type = "", string $comment = ""): int
    {
        $inserted = 0;
        if (empty($this->find_one_active(
            array(
                'where' => array(
                    'signup_analytics_signup_id' => $userId,
                    'signup_analytics_referer_id' => $refererId,
                    'signup_analytics_type' => $type,
                    'signup_analytics_date' => date("Y-m-d"),
                )
            )
        )) && $userId != $refererId) {
            $inserted = $this->insert_record(
                array(
                    'signup_analytics_signup_id' => $userId,
                    'signup_analytics_referer_id' => $refererId,
                    'signup_analytics_type' => $type,
                    'signup_analytics_comment' => $comment,
                    'signup_analytics_date' => date("Y-m-d"),
                )
            );
        }
        return $inserted;
    }


    /**
     * Method getUserAnalytics
     *
     * @param int $userId
     *
     * @return array
     */
    public function getUserAnalytics(int $userId): array
    {
        $availabilitylabel = array();
        $myAvailability = $this->find_all_active(
            array(
                'where' => array(
                    'signup_analytics_signup_id' => $userId,
                    'signup_analytics_type' => ANALYTICS_TYPE_VIEW
                ),
                'order' => 'signup_analytics_createdon ASC'
            )
        );

        foreach($myAvailability as $key => $value) {
            if(validateDate($value['signup_analytics_date'])) {
                $label = date("d M Y", strtotime($value['signup_analytics_date']));
                if(array_key_exists($label, $availabilitylabel)) {
                    $availabilitylabel[$label] = $availabilitylabel[$label]+1;
                } else {
                    $availabilitylabel[$label] = 1;
                }
            }
        }

        return $availabilitylabel;
    }

    /**
     * Method get_fields
     *
     * @param string $specific_field
     *
     * @return void
     */
    public function get_fields($specific_field = "")
    {
        $data =  array(

            'signup_analytics_id' => array(
                'table' => $this->_table,
                'name' => 'signup_analytics_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_analytics_signup_id' => array(
                'table' => $this->_table,
                'name' => 'signup_analytics_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_analytics_type' => array(
                'table' => $this->_table,
                'name' => 'signup_analytics_type',
                'label' => 'Type',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_analytics_comment' => array(
                'table' => $this->_table,
                'name' => 'signup_analytics_comment',
                'label' => 'Comment',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_analytics_status' => array(
                'table' => $this->_table,
                'name' => 'signup_analytics_status',
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
