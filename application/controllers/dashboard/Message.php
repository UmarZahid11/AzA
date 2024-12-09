<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Message
 */
class Message extends MY_Controller
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
     * @return void
     */
    function index(string $chat_id = '', int $page = 1, int $limit = PER_PAGE, $search = ''): void
    {
        $data = array();

        if ($chat_id) {
            try {
                $chat_id = JWT::decode($chat_id, CI_ENCRYPTION_SECRET);
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
                //
                $this->_log_message(
                    LOG_TYPE_GENERAL,
                    LOG_SOURCE_SERVER,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
                error_404();
            }
        }

        $data['chat_detail'] = array();
        $data['chat_message'] = array();

        if ($chat_id) {
            $chat_detail = $this->model_chat->find_by_pk($chat_id);

            if ($chat_detail) {
                if ($chat_detail['chat_signup1'] == $this->userid) {
                    $join_param = array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = chat.chat_signup2',
                            'type' => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        )
                    );
                } else {
                    $join_param = array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = chat.chat_signup1',
                            'type' => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        )
                    );
                }
            }

            $data['chat_detail'] = $this->model_chat->find_one_active(
                array(
                    'where' => array(
                        'chat_id' => $chat_id,
                        'chat_isdeleted' => STATUS_FALSE
                    ),
                    'joins' => $join_param
                )
            );

            if (!empty($data['chat_detail'])) {
                $data['chat_message'] = $this->model_chat_message->find_all_active(
                    array(
                        'order' => 'chat_message_createdon asc',
                        'where' => array(
                            'chat_message_chat_id' => $chat_id
                        )
                    )
                );
            } else {
                error_404();
            }
        }

        $data['chat_message_count'] = count($data['chat_message']);

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $query = 'SELECT * FROM `fb_chat`
        INNER JOIN fb_signup ON ((fb_chat.chat_signup1 = fb_signup.signup_id AND chat_signup1 != ' . $this->userid . ') OR fb_chat.chat_signup2 = fb_signup.signup_id AND chat_signup2 != ' . $this->userid . ')
        INNER JOIN fb_signup_info ON (fb_signup.signup_id = fb_signup_info.signup_info_signup_id)
        WHERE (`chat_signup1` = ' . $this->userid . ' OR `chat_signup2` = ' . $this->userid . ') AND `chat_reference_type` = "' . CHAT_REFERENCE_MESSAGE . '" AND `fb_chat`.`chat_status` = 1';

        if ($search) {
            $query .= ' AND (signup_firstname LIKE "%' . $search . '%" OR signup_lastname = "%' . $search . '%" )';
        }

        $query .= ' ORDER BY chat_updatedon desc';

        $data['message'] = $this->db->query($query . ' limit ' . $limit . ' offset ' . $paginationStart)->result_array();
        $data['message_count'] = $allRecrods = $this->db->query($query)->num_rows();

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        $query = 'SELECT * FROM `fb_signup_follow`
        INNER JOIN fb_signup ON ((fb_signup_follow.signup_follow_follower_id = fb_signup.signup_id AND signup_follow_follower_id != ' . $this->userid . ')
        OR (fb_signup_follow.signup_follow_reference_id = fb_signup.signup_id AND signup_follow_reference_id != ' . $this->userid . '))
        INNER JOIN fb_signup_info ON (fb_signup.signup_id = fb_signup_info.signup_info_signup_id)
        WHERE (`signup_follow_reference_id` = ' . $this->userid . ' OR `signup_follow_follower_id` = ' . $this->userid . ') AND `signup_follow_reference_type` = "' . FOLLOW_REFERENCE_SIGNUP . '" AND `fb_signup_follow`.`signup_follow_status` = 1';
        $query .= ' ORDER BY signup_firstname asc';

        $signup_follower = ($this->db->query($query)->result_array());

        $data['signup_follower'] = array();
        foreach($signup_follower as $key => $value) {
            if(!array_key_exists($key, $data['signup_follower'])) {
                $data['signup_follower'][$value['signup_id']] = $value;
            }
        }

        $data['chat_id'] = JWT::encode($chat_id);
        $data['search'] = $search;

        //
        $this->layout_data['title'] = 'Message | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * Method save
     *
     * @return void
     */
    function save(): void
    {
        $json_param = array();
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['chat_message'])) {
                    $chat_detail = $this->model_chat->find_one_active(
                        array(
                            'where' => array(
                                'chat_id' => $_POST['chat_message']['chat_message_chat_id'],
                                'chat_isdeleted' => FALSE
                            )
                        )
                    );

                    if (!empty($chat_detail)) {
                        $affect_param = $_POST['chat_message'];
                        $affected = $this->model_chat_message->insert_record($affect_param);
                        if ($affected) {
                            $this->model_chat->update_by_pk(
                                $_POST['chat_message']['chat_message_chat_id'],
                                array(
                                    'chat_seen' => STATUS_FALSE,
                                    'chat_updatedon' => date('Y-m-d H:i:s')
                                )
                            );
                            $json_param['status'] = TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;
                            //
                            $this->model_notification->sendNotification(($chat_detail['chat_signup1'] == $this->userid ? $chat_detail['chat_signup2'] : $chat_detail['chat_signup1']), $this->userid, NOTIFICATION_MESSAGE, $_POST['chat_message']['chat_message_chat_id'], NOTIFICATION_MESSAGE_COMMENT);
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }

        $json_param['timezone_name'] = date_default_timezone_get();
        echo json_encode($json_param);
    }

    /**
     * Method start
     *
     * @return void
     */
    function start(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['signup_id']) && $_POST['signup_id'] != $this->userid) {
                    $signup_details = $this->model_signup->find_by_pk($_POST['signup_id']);
                    if (!empty($signup_details)) {

                        $chat_detail = $this->db->query('SELECT * FROM `fb_chat` WHERE ((`chat_signup1` = ' . $this->userid . ' AND `chat_signup2` = ' . $signup_details['signup_id'] . ') OR (`chat_signup2` = ' . $this->userid . ' AND `chat_signup1` = ' . $signup_details['signup_id'] . ')) AND `chat_reference_type` = "' . CHAT_REFERENCE_MESSAGE . '" AND `fb_chat`.`chat_status` = 1')->row_array();

                        if (empty($chat_detail)) {

                            $affected = $this->model_chat->insert_record(
                                array(
                                    'chat_reference_type' => CHAT_REFERENCE_MESSAGE,
                                    'chat_signup1' => $signup_details['signup_id'],
                                    'chat_signup2' => $this->userid,
                                )
                            );
                        } else {
                            $affected = $chat_detail['chat_id'];
                        }
                        if ($affected) {
                            $this->model_chat->update_by_pk(
                                $affected,
                                array(
                                    'chat_seen' => STATUS_TRUE,
                                )
                            );

                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __(SUCCESS_MESSAGE);
                            $json_param['redirect_url'] = l('dashboard/message/index/' . JWT::encode($affected));
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method messageCountRefresh
     *
     * @return void
     */
    function messageCountRefresh(): void
    {
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST['chat_id']) && isset($_POST['chat_message_count'])) {
            $chat_id = $_POST['chat_id'];
            $chat_message_count = $_POST['chat_message_count'];

            $chat_detail = $this->model_chat->find_by_pk($chat_id);

            if (!empty($chat_detail)) {
                $chat_messages = $this->model_chat_message->find_count_active(
                    array(
                        'where' => array(
                            'chat_message_chat_id' => $chat_id
                        )
                    )
                );
                // new messages in table for this chat_id then true to refresh chat area
                if ($chat_messages > $chat_message_count) {
                    $this->model_chat->update_by_pk(
                        $chat_id,
                        array(
                            'chat_seen' => STATUS_TRUE,
                        )
                    );
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['chat_message_count'] = $chat_messages;
                }
            }
        }
        echo json_encode($json_param);
    }

    /**
     * Method chatListingRefresh
     *
     * @return void
     */
    function chatListingRefresh(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $query = 'SELECT * FROM `fb_chat` WHERE (`chat_signup1` = ' . $this->userid . ' OR `chat_signup2` = ' . $this->userid . ') AND `chat_reference_type` = "' . CHAT_REFERENCE_MESSAGE . '" AND `fb_chat`.`chat_status` = 1 AND `fb_chat`.`chat_seen` = 0 AND chat_updatedon > "' . date('Y-m-d H:i:s', strtotime('-1 minute', strtotime(date('Y-m-d H:i:s')))) . '"';
        if (!empty($this->db->query($query)->result_array())) {
            $json_param['status'] = STATUS_TRUE;
        }
        echo json_encode($json_param);
    }
}
