<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * News
 */
class News extends MY_Controller
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
        $data = array();

        $param = array();
        $param['where']['inner_banner_name'] = 'News';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['page'] = $page;
        $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : PER_PAGE;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $param = array();
        $param['where']['news_approved'] = 1;
        $data['news_count'] = $all_news = $this->model_news->find_all($param);

        $allRecrods = count($all_news);

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $param = array();
        $param['offset'] = $paginationStart;
        $param['limit'] = $limit;
        $param['order'] = 'news_id DESC';
        $param['where']['news_approved'] = 1;
        $data['news'] = $this->model_news->find_all_active($param);

        //
        $this->layout_data['title'] = 'News  | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * detail
     *
     * @return void
     */
    public function detail($slug = "undefined")
    {
        if (!$slug) {
            error_404();
        }

        global $config;

        $data = array();

            $param = array();
            $param['where']['inner_banner_name'] = 'News Detail';
            $data['banner'] = $this->model_inner_banner->find_one_active($param);

            $param = array();
            $param['where']['news_slug'] = $slug;
            $data['news'] = $this->model_news->find_one_active($param);

            if (empty($data['news'])) {
                error_404();
            }

            $news_id = $data['news']['news_id'];

            //
            $data['comment'] = $this->model_comment->find_all_active(
                array(
                    'order' => 'comment_id DESC',
                    'where' => array(
                        'comment_parent_id' => 0,
                        'comment_reference_id' => $news_id,
                        'comment_reference_type' => REFERENCE_TYPE_NEWS
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
            $data['type'] = REFERENCE_TYPE_NEWS;
            $data['reference_id'] = $news_id;

            $data['tags'] = $this->model_tag->find_all_active(
                array(
                    'where' => array(
                        'tag_reference_type' => REFERENCE_TYPE_NEWS,
                        'tag_reference_id' => $news_id
                    )
                )
            );

            //
            $this->layout_data['title'] = $data['news']['news_title'] . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);

    }
}
