<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Story
 */
class Story extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index
     *
     * @return void
     */
    public function index($page = 1)
    {
        global $config;

        $data = array();

        $param = array();
        $param['where']['inner_banner_name'] = 'Story Detail';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['page'] = $page;
        $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 9;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $param = array();
        $data['story_count'] = $all_story = $this->model_story->find_all($param);

        $allRecrods = count($all_story);

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $param = array();
        $param['offset'] = $paginationStart;
        $param['limit'] = $limit;
        $param['order'] = 'story_id DESC';
        $data['story'] = $this->model_story->find_all_active($param);

        //
        $this->layout_data['title'] = 'Story  | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * detail
     *
     * @return void
     */
    public function detail($slug = "undefined", $type = 0)
    {

        if (!$slug) {
            error_404();
        }

        global $config;

        $data = array();


            $param = array();
            $param['where']['inner_banner_name'] = 'Story Detail';
            $data['banner'] = $this->model_inner_banner->find_one_active($param);

            $param = array();
            $param['where']['story_slug'] = $slug;
            $data['story'] = $this->model_story->find_one_active($param);

            if (empty($data['story'])) {
                error_404();
            }


            $story_id = $data['story']['story_id'];

            //
            $data['comment'] = $this->model_comment->find_all_active(
                array(
                    'order' => 'comment_id DESC',
                    'where' => array(
                        'comment_parent_id' => 0,
                        'comment_reference_id' => $story_id,
                        'comment_reference_type' => REFERENCE_TYPE_STORY
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

            // commenting purpose
            $data['type'] = REFERENCE_TYPE_STORY;
            $data['reference_id'] = $story_id;

            //
            $this->layout_data['title'] = $data['story']['story_title'] . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);
    }
}
