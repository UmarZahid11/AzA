<?php

/**
 * Model_comment
 */
class Model_comment extends MY_Model
{
    protected $_table    = 'comment';
    protected $_field_prefix    = 'comment_';
    protected $_pk    = 'comment_id';
    protected $_status_field    = 'comment_status';
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
        $this->pagination_params['fields'] = "comment_id, comment_text, comment_status";
        parent::__construct();
    }

    /**
     * Method get_comment_replies
     *
     * @param int $reference_id
     * @param int $comment_id
     *
     * @return array
     */
    public function get_comment_replies($reference_id, $comment_id) : ?array
    {
        return $this->model_comment->find_all_active(
            array(
                'order' => 'comment_id DESC',
                'where' => array(
                    'comment_parent_id' => $comment_id,
                    'comment_reference_id' => $reference_id,
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = comment.comment_userid',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    )
                )
            )
        );
    }

    /**
     * Method get_comment_count
     *
     * @param string $reference_type
     * @param int $reference_id
     *
     * @return void
     */
    function get_comment_count($reference_type, $reference_id) {
        return $this->model_comment->find_count_active(
            array(
                'order' => 'comment_id DESC',
                'where' => array(
                    'comment_reference_type' => $reference_type,
                    'comment_reference_id' => $reference_id,
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = comment.comment_userid',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    )
                )
            )
        );
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

     * list_data         For dropdown etc, data in key-value pair that will populate dropdown
     *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
     * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
     *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
     *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
     */
    public function get_fields($specific_field = "")
    {
        $fields = array(
            'comment_id' => array(
                'table'   => $this->_table,
                'name'   => 'comment_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'comment_text' => array(
                'table'   => $this->_table,
                'name'   => 'comment_text',
                'label'   => 'Comment',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'comment_status' => array(
                'table'   => $this->_table,
                'name'   => 'comment_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "comment_status",
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
