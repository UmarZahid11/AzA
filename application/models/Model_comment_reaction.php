<?php

/**
 * Model_comment_reaction
 */
class Model_comment_reaction extends MY_Model
{
    protected $_table    = 'comment_reaction';
    protected $_field_prefix    = 'comment_reaction_';
    protected $_pk    = 'comment_reaction_id';
    protected $_status_field    = 'comment_reaction_status';
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
        $this->pagination_params['fields'] = "comment_reaction_id, comment_reaction_text, comment_reaction_status";
        parent::__construct();
    }

    /**
     * Method get_comment_reactions
     *
     * @param int $blog_comment_id
     *
     * @return array
     */
    public function get_comment_reactions(int $blog_comment_id): ?array
    {
        $reaction_details = [];
        $reaction_details['reaction_array'] =
            $this->model_comment_reaction->find_all_active(
                array(
                    'order' => 'comment_reaction_id DESC',
                    'where' => array(
                        'comment_reaction_comment_id' => $blog_comment_id,
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = comment_reaction.comment_reaction_userid',
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

        $reaction_details['reaction_count'] = count($reaction_details['reaction_array']);

        $reaction_details['top_reactions'] = $this->top_reactions($reaction_details['reaction_array']);

        return $reaction_details;
    }

    /**
     * Method top_reactions
     *
     * @param array $comment_reaction_array
     *
     * @return array
     */
    public function top_reactions(array $comment_reaction_array): ?array
    {
        $individual_count = array();
        foreach ($comment_reaction_array as $key => $value) {
            switch($value['comment_reaction_text']) {
                case REACTION_LIKE:
                    $individual_count[REACTION_LIKE] = isset($individual_count[REACTION_LIKE]) ? $individual_count[REACTION_LIKE] + 1 : 1;
                    break;
                case REACTION_LOVE:
                    $individual_count[REACTION_LOVE] = isset($individual_count[REACTION_LOVE]) ? $individual_count[REACTION_LOVE] + 1 : 1;
                    break;
                case REACTION_HAHA:
                    $individual_count[REACTION_HAHA] = isset($individual_count[REACTION_HAHA]) ? $individual_count[REACTION_HAHA] + 1 : 1;
                    break;
                case REACTION_WOW:
                    $individual_count[REACTION_WOW] = isset($individual_count[REACTION_WOW]) ? $individual_count[REACTION_WOW] + 1 : 1;
                    break;
                case REACTION_SAD:
                    $individual_count[REACTION_SAD] = isset($individual_count[REACTION_SAD]) ? $individual_count[REACTION_SAD] + 1 : 1;
                    break;
                case REACTION_ANGRY:
                    $individual_count[REACTION_ANGRY] = isset($individual_count[REACTION_ANGRY]) ? $individual_count[REACTION_ANGRY] + 1 : 1;
                    break;

            }
        }
        return $individual_count;
    }

    /**
     * Method get_comment_reactions
     *
     * @param int $blog_comment_id
     *
     * @return array
     */
    public function get_comment_reaction(int $blog_comment_id, $userid): ?array
    {
        return $this->model_comment_reaction->find_one_active(
            array(
                'order' => 'comment_reaction_id DESC',
                'where' => array(
                    'comment_reaction_comment_id' => $blog_comment_id,
                    'comment_reaction_userid' => $userid,
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = comment_reaction.comment_reaction_userid',
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
            'comment_reaction_id' => array(
                'table'   => $this->_table,
                'name'   => 'comment_reaction_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'comment_reaction_text' => array(
                'table'   => $this->_table,
                'name'   => 'comment_reaction_text',
                'label'   => 'Reaction',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim|required'
            ),

            'comment_reaction_status' => array(
                'table'   => $this->_table,
                'name'   => 'comment_reaction_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "comment_reaction_status",
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
