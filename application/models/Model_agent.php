<?php

/**
 * Model_agent
 */
class Model_agent extends MY_Model
{
    protected $_table    = 'agent';
    protected $_field_prefix    = 'agt_';
    protected $_pk    = 'agt_id';
    protected $_status_field    = 'agt_status';
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
        $this->pagination_params['fields'] = "agt_id,agt_status";
        parent::__construct();
    }

    /**
     * Method get_records
     *
     * @return array
     */
    public function get_records()
    {
        $params['fields'] = "(select count(agt_id) from " . $this->db->dbprefix . "agent where 1) as user_counts,
        (select count(agt_id) from " . $this->db->dbprefix . "agent where agt_type='desktop') as desk_users,
        (select count(agt_id) from " . $this->db->dbprefix . "agent where agt_type='mobile') as mob_users";
        $result = $this->find_one($params);

        return $result;
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
        $fields = array(
            'agt_id' => array(
                'table'   => $this->_table,
                'name'   => 'agt_id',
                'label'   => 'id #',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'agt_name' => array(
                'table'   => $this->_table,
                'name'   => 'agt_name',
                'label'   => 'Name',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => 'required',
                'rules'   => 'required|trim|htmlentities'
            ),

            'agt_type' => array(
                'table'   => $this->_table,
                'name'   => 'agt_type',
                'label'   => 'Type',
                'type'   => 'text',
                'attributes'   => array(),
                'js_rules'   => '',
                'rules'   => 'required|trim|htmlentities'
            ),

            'agt_status' => array(
                'table'   => $this->_table,
                'name'   => 'agt_status',
                'label'   => 'Status?',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "agt_status",
                'list_data' => array(),
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
