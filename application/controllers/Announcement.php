<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Announcement
 */
class Announcement extends MY_Controller
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

    function index() : void {
        error_404();
    }

    /**
     * listing
     *
     * @return void
     */
    public function listing($page = 1)
    {
        global $config;

        $data = array();

        $data['config'] = $config;

        $param = array();
        $param['where']['inner_banner_name'] = 'Announcement';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $data['page'] = $page;
        $limit = PER_PAGE;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['announcements_count'] = $allRecrods = $this->model_announcement->find_count_active();

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $data['announcements'] = $this->model_announcement->find_all_active(
            array(
                'order' => 'announcement_id desc',
                'offset' => $paginationStart,
                'limit' => $limit
            )
        );

        //
        $this->layout_data['title'] = 'Announcements  | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    function detail($slug = "") : void
    {
        if (!$slug) {
            error_404();
        }

        $data = array();

        $param = array();
        $param['where']['inner_banner_name'] = 'Announcement Detail';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['announcement_slug'] = $slug;
        $data['announcement'] = $this->model_announcement->find_one_active($param);

        if (empty($data['announcement'])) {
            error_404();
        }

        $announcement_id = $data['announcement']['announcement_id'];

        //
        $data['comment'] = $this->model_comment->find_all_active(
            array(
                'order' => 'comment_id DESC',
                'where' => array(
                    'comment_parent_id' => 0,
                    'comment_reference_id' => $announcement_id,
                    'comment_reference_type' => REFERENCE_TYPE_ANNOUNCEMENT
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

        $data['type'] = REFERENCE_TYPE_ANNOUNCEMENT;
        $data['reference_id'] = $announcement_id;

        //
        $this->layout_data['title'] = $data['announcement']['announcement_title'] . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

}