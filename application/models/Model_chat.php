<?php

/**
 * Model_chat
 */
class Model_chat extends MY_Model
{
    protected $_table    = 'chat';
    protected $_field_prefix    = 'chat_';
    protected $_pk    = 'chat_id';
    protected $_status_field    = 'chat_status';
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
        $this->pagination_params['fields'] = "chat_id, chat_status";
        parent::__construct();
    }

    /**
     * Method getChatById
     *
     * @param int $paginationStart
     * @param int $limit
     * @param int $userid
     * @param string $reference
     *
     * @return mixed
     */
    function getChatById(int $paginationStart, int $limit, array $userdata, string $reference, $count = FALSE)
    {
        $result_param = array();
        if (!$count) {
            $result_param['offset'] = $paginationStart;
            $result_param['limit'] = $limit;
        }

        $result_param['order'] = 'chat_updatedon DESC';

        if (isset($userdata['chat_signup1'])) {
            $result_param['where'] = array(
                'chat_signup1' => $userdata['chat_signup1'],
                'chat_reference_type' => $reference
            );
            $result_param['joins'] = array(
                0 => array(
                    'table' => 'signup',
                    'joint' => 'signup.signup_id = chat.chat_signup2',
                    'type'  => 'both'
                ),
                1 => array(
                    'table' => 'signup_info',
                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                    'type' => 'both'
                )
            );
        } elseif (isset($userdata['chat_signup2'])) {
            $result_param['where'] = array(
                'chat_signup2' => $userdata['chat_signup2'],
                'chat_reference_type' => $reference
            );
            $result_param['joins'] = array(
                0 => array(
                    'table' => 'signup',
                    'joint' => 'signup.signup_id = chat.chat_signup1',
                    'type'  => 'both'
                ),
                1 => array(
                    'table' => 'signup_info',
                    'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                    'type' => 'both'
                )
            );
        }

        $function = $count ? 'find_count_active' : 'find_all_active';

        return $this->model_chat->{$function}(
            $result_param
        );
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
            'chat_id' => array(
                'table'   => $this->_table,
                'name'   => 'chat_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'chat_status' => array(
                'table'   => $this->_table,
                'name'   => 'chat_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "chat_status",
                'list_data' => array(
                    0 => "<span class='label label-danger'>InActive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
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
