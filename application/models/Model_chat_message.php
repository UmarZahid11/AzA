<?php

/**
 * Model_chat_message
 */
class Model_chat_message extends MY_Model
{
    protected $_table    = 'chat_message';
    protected $_field_prefix    = 'chat_message_';
    protected $_pk    = 'chat_message_id';
    protected $_status_field    = 'chat_message_status';
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
        $this->pagination_params['fields'] = "chat_message_id, chat_message_status";
        parent::__construct();
    }

    /**
     * Method lastMessage
     *
     * @param int $chat_id
     *
     * @return string
     */
    function lastMessage(int $chat_id) : string {
        $chat_message = $this->find_one_active(
            array(
                'order' => 'chat_message_id desc',
                'where' => array(
                    'chat_message_chat_id' => $chat_id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = chat_message.chat_message_sender',
                        'type' => 'both'
                    )
                )
            )
        );

        if(!empty($chat_message)) {
            return $this->model_signup->profileName($chat_message, FALSE) . ': ' . strip_string($chat_message['chat_message_text'], 10);
        }
        return '...';
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
            'chat_message_id' => array(
                'table'   => $this->_table,
                'name'   => 'chat_message_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'chat_message_media' => array(
                'table' => $this->_table,
                'name' => 'chat_message_media',
                'label' => 'Media',
                'name_path' => 'chat_message_media_path',
                'upload_config' => 'site_upload_chat_message',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes'   => array(
                    'allow_ext' => 'png|jpeg|jpg',
                    'image_size_recommended' => '635px × 800px',
                ),
                'dt_attributes' => array("width" => "10%"),
                'rules' => 'trim|htmlentities',
            ),

            'chat_message_status' => array(
                'table'   => $this->_table,
                'name'   => 'chat_message_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "chat_message_status",
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
