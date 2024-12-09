<?php

/**
 * Model_review
 */
class Model_review extends MY_Model
{
    protected $_table    = 'review';
    protected $_field_prefix    = 'review_';
    protected $_pk    = 'review_id';
    protected $_status_field    = 'review_status';
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
        $this->pagination_params['fields'] = "review_id, review_name, review_status";
        parent::__construct();
    }

    /**
     * Method reviewCount
     *
     * @param int $signupId
     * @param string $type
     *
     * @return int
     */
    public function reviewCount($signupId, $type): int
    {
        return count($this->model_review->find_all_active(
            array(
                'where' => array(
                    'review_type' => $type,
                    'review_reference_id' => $signupId
                )
            )
        ));
    }

    /**
     * Method reviewAvg
     *
     * @param int $signupId
     * @param string $type
     *
     * @return float
     */
    public function reviewAvg($signupId, $type): float
    {
        $reviewCount = $this->reviewCount($signupId, $type);
        $reviews = ($this->model_review->find_all_active(
            array(
                'where' => array(
                    'review_type' => $type,
                    'review_reference_id' => $signupId
                )
            )
        ));
        $reviewRating = 0;
        foreach ($reviews as $value) {
            $reviewRating = $reviewRating + $value['review_rating'];
        }

        if ($reviewRating > 0) {
            return $reviewRating / $reviewCount;
        }
        return $reviewRating;
    }

    /**
     * Method reviewPercentage
     *
     * @param int $signupId
     * @param string $type
     * @param int $rating
     *
     * @return void
     */
    public function reviewPercentage(int $signupId, string $type, int $rating): int
    {
        $reviews = ($this->model_review->find_count_active(
            array(
                'where' => array(
                    'review_type' => $type,
                    'review_reference_id' => $signupId
                )
            )
        ));
        $reviews_rating = ($this->model_review->find_count_active(
            array(
                'where' => array(
                    'review_type' => $type,
                    'review_reference_id' => $signupId,
                    'review_rating' => $rating
                )
            )
        ));

        return ($reviews > 0) ? ((int) (($reviews_rating / $reviews) * 100)) : 0;
    }

    /**
     * Method review_exists
     *
     * @param int $id
     * @param int $userid
     *
     * @return bool
     */
    public function review_exists($id, $userid): bool
    {
        //
        $review_exists = $this->model_review->find_one_active(
            array(
                'where' => array(
                    'review_type' => REVIEW_TYPE_SIGNUP,
                    'review_reference_id' => $id,
                    'review_reviewer_id' => $userid
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = review.review_reference_id',
                        'type' => 'both'
                    )
                )
            )
        );
        return $review_exists ? true : false;
    }

    /**
     * Method user_review
     *
     * @param int $id
     * @param int $userid
     *
     * @return array
     */
    public function user_review($id, $userid) : array
    {
        //
        return $this->model_review->find_one_active(
            array(
                'where' => array(
                    'review_type' => REVIEW_TYPE_SIGNUP,
                    'review_reference_id' => $id,
                    'review_reviewer_id' => $userid
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = review.review_reference_id',
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
            'review_id' => array(
                'table'   => $this->_table,
                'name'   => 'review_id',
                'label'   => 'ID',
                'type'   => 'hidden',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'trim'
            ),

            'review_rating' => array(
                'table'   => $this->_table,
                'name'   => 'review_rating',
                'label'   => 'Rating',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'review_name' => array(
                'table'   => $this->_table,
                'name'   => 'review_name',
                'label'   => 'Reviewer Name',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'review_email' => array(
                'table'   => $this->_table,
                'name'   => 'review_email',
                'label'   => 'Reviewer Email',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'review_description' => array(
                'table'   => $this->_table,
                'name'   => 'review_description',
                'label'   => 'Reviewer Description',
                'type'   => 'text',
                'type_dt'   => 'text',
                'attributes'   => array(),
                'dt_attributes'   => array("width" => "5%"),
                'js_rules'   => '',
                'rules'   => 'required|trim'
            ),

            'review_status' => array(
                'table'   => $this->_table,
                'name'   => 'review_status',
                'label'   => 'Status',
                'type'   => 'switch',
                'type_dt'   => 'dropdown',
                'type_filter_dt' => 'dropdown',
                'list_data_key' => "review_status",
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
