<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Notification
 */
class Notification extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->userid <= 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l(''));
        }
    }

    /**
     * Method index
     *
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function index(int $page = 1, int $limit = 50): void
    {
        $this->model_notification->seenNotification();

        global $config;

        $data = array();

        $data['page'] = $page;
        $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $data['notification'] = $this->model_notification->find_all_active(
            array(
                'offset' => $paginationStart,
                'limit' => $limit,
                'order' => 'notification_createdon DESC',
                'where' => array(
                    'notification_signup_id' => $this->userid
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = notification.notification_from',
                        'type'  => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type'  => 'left'
                    )
                )
            )
        );

        $data['notification_count'] = $allRecrods = $this->model_notification->find_count_active(
            array(
                'where' => array(
                    'notification_signup_id' => $this->userid
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = notification.notification_from',
                        'type'  => 'both'
                    )
                )
            )
        );

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = 'Notification | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}
