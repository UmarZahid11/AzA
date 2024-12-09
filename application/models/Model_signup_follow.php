<?php

/**
 * Model_signup_follow
 */
class Model_signup_follow extends MY_Model
{
    protected $_table = 'signup_follow';
    protected $_field_prefix = 'signup_follow_';
    protected $_pk = 'signup_follow_id';
    protected $_status_field = 'signup_follow_status';
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
        $this->pagination_params['fields'] = "signup_follow_id, signup_follow_status";
        parent::__construct();
    }

    /**
     * Method isFollowing
     *
     * @param int $signup_id
     * @param int $follower_id
     * @param string $reference
     *
     * @return array
     */
    public function isFollowing(int $signup_id = 0, int $follower_id = 0, string $reference = FOLLOW_REFERENCE_SIGNUP): ?array
    {
        $userid = $userid ?? $this->userid;
        return $this->find_one_active(
            array(
                'where' => array(
                    'signup_follow_reference_id' => $signup_id,
                    'signup_follow_follower_id' => $follower_id,
                    'signup_follow_reference_type' => $reference
                )
            )
        );
    }

    /**
     * Method getFolloweeCount
     *
     * @param ?int $userid
     * @param string $reference
     *
     * @return int
     */
    public function getFolloweeCount(int $userid = NULL, string $reference = FOLLOW_REFERENCE_SIGNUP): int
    {
        $userid = $userid ?? $this->userid;
        return $this->find_count_active(
            array(
                'where' => array(
                    'signup_follow_follower_id' => $userid,
                    'signup_follow_reference_type' => $reference
                )
            )
        );
    }

    /**
     * Method getFollowerCount
     *
     * @param ?int $userid
     * @param string $reference
     *
     * @return int
     */
    public function getFollowerCount(int $userid = NULL, string $reference = FOLLOW_REFERENCE_SIGNUP): int
    {
        $userid = $userid ?? $this->userid;
        return $this->find_count_active(
            array(
                'where' => array(
                    'signup_follow_reference_id' => $userid,
                    'signup_follow_reference_type' => $reference
                )
            )
        );
    }

    /**
     * Method getFollowee - get users which this userid is following
     *
     * @param ?int $userid
     * @param string $reference
     * @return int
     */
    public function getFollowee(int $userid = NULL, $offset, $limit, string $reference = FOLLOW_REFERENCE_SIGNUP): array
    {
        $userid = $userid ?? $this->userid;

        $where_param = array();
        $where_param['signup_follow_follower_id'] = $userid;
        $where_param['signup_follow_reference_type'] = $reference;

        return $this->find_all_active(
            array(
                'offset' => $offset,
                'limit' => $limit,
                'where' => $where_param,
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = signup_follow.signup_follow_reference_id',
                        'type'  => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type'  => 'both'
                    )
                )
            )
        );
    }

    /**
     * Method getFollower - get followers of this userid
     *
     * @param ?int $userid
     * @param string $reference
     *
     * @return int
     */
    public function getFollower(int $userid = NULL, $offset, $limit, string $reference = FOLLOW_REFERENCE_SIGNUP): array
    {
        $userid = $userid ?? $this->userid;

        $where_param = array();
        $where_param['signup_follow_reference_id'] = $userid;
        $where_param['signup_follow_reference_type'] = $reference;

        return $this->find_all_active(
            array(
                'offset' => $offset,
                'limit' => $limit,
                'where' => $where_param,
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = signup_follow.signup_follow_follower_id',
                        'type'  => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type'  => 'both'
                    )
                ),
            )
        );
    }

    /**
     * Method connectionLevel
     *
     * @param int $signup_id
     * @param int $connectionTo
     * @param string $reference
     *
     * @return void
     */
    public function connectionLevel(int $signup_id, int $connectionTo, string $reference = FOLLOW_REFERENCE_SIGNUP): int
    {
        $connectionLevel = 0;
        if ($this->model_signup->find_by_pk($signup_id) && $this->model_signup->find_by_pk($connectionTo)) {
            if ($this->isFollowing($signup_id, $connectionTo)) {
                $connectionLevel = 1;
            } else {
                $followDetail = $this->model_signup_follow->find_all_active(
                    array(
                        'where' => array(
                            'signup_follow_reference_id' => $signup_id,
                            'signup_follow_reference_type' => $reference
                        )
                    )
                );

                if (!empty($followDetail)) {
                    foreach ($followDetail as $value) {
                        if ($this->model_signup_follow->find_one_active(
                            array(
                                'where' => array(
                                    'signup_follow_reference_id' => $value['signup_follow_follower_id'],
                                    'signup_follow_follower_id !=' => $signup_id,
                                    'signup_follow_follower_id' => $connectionTo,
                                    'signup_follow_reference_type' => $reference
                                )
                            )
                        )) {
                            $connectionLevel = 2;
                            $connectionFound = TRUE;
                            break;
                        } else {
                            $connectionFound = FALSE;
                        }
                    }

                    if (!$connectionFound) {
                        foreach ($followDetail as $key => $value) {
                            $subFollowDetail = $this->model_signup_follow->find_all_active(
                                array(
                                    'where' => array(
                                        'signup_follow_reference_id' => $value['signup_follow_follower_id'],
                                        'signup_follow_reference_type' => $reference
                                    )
                                )
                            );

                            foreach ($subFollowDetail as $subValue) {
                                if ($this->model_signup_follow->find_one_active(
                                    array(
                                        'where' => array(
                                            'signup_follow_reference_id' => $subValue['signup_follow_follower_id'],
                                            'signup_follow_follower_id !=' => $signup_id,
                                            'signup_follow_follower_id' => $connectionTo,
                                            'signup_follow_reference_type' => $reference
                                        )
                                    )
                                )) {
                                    $connectionLevel = 3;
                                    $connectionFound = TRUE;
                                    break;
                                } else {
                                    $connectionFound = FALSE;
                                }
                            }
                        }
                    }
                } else {
                    $connectionLevel = 0;
                }
            }
        }

        return $connectionLevel;
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

            'signup_follow_id' => array(
                'table' => $this->_table,
                'name' => 'signup_follow_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'signup_follow_reference_id' => array(
                'table' => $this->_table,
                'name' => 'signup_follow_reference_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_follow_follower_id' => array(
                'table' => $this->_table,
                'name' => 'signup_follow_follower_id',
                'label' => 'Follower ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim|required'
            ),

            'signup_follow_status' => array(
                'table' => $this->_table,
                'name' => 'signup_follow_status',
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
