<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Monday
 */
class Monday extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // if(!$this->session->has_userdata('monday') && $_SERVER['REQUEST_METHOD'] == 'GET') {
        //     $this->session->set_userdata('monday_intended', $_SERVER['REDIRECT_QUERY_STRING']);
        //     redirect(l('monday'));
        // }
    }

    /**
     * boards function
     *
     * @return void
     */
    function boards()
    {
        $data = [];

        $data['boards'] = $this->query('{ boards { id name board_kind } }');

        //
        $this->layout_data['title'] = 'Monday | ' . $this->layout_data['title'];
        //
        $this->load_view('boards', $data);
    }

    /**
     * board function
     *
     * @param integer $board_id
     * @return void
     */
    function groups(int $board_id = 0)
    {
        $data = [];

        $data['boardDetail'] = $this->query('query { boards (ids: ' . $board_id . ') { id name } }');

        if (!$data['boardDetail'] || empty($data['boardDetail']['data']['boards'])) {
            error_404();
        }

        $data['board_id'] = $data['boardDetail']['data']['boards'][0]['id'];
        $data['boardGroups'] = $this->query('query {boards (ids: ' . $board_id . ') {groups {title id}}}');
        $data['boardColumns'] = $this->query('query {boards(ids: ' . $board_id . ') {columns {id title}}}');

        //
        $this->layout_data['title'] = 'Monday boards | ' . $this->layout_data['title'];
        //
        $this->load_view('groups', $data);
    }

    /**
     * group function
     *
     * @param strng $group_id
     * @return void
     */
    function items(int $board_id = 0, string $group_id = '', int $limit = 30, string $cursor = '')
    {
        $data = [];

        $data['board_id'] = $board_id;
        $data['group_id'] = $group_id;
        $data['limit'] = $limit;
        $data['cursor'] = '';

        $data['boardDetail'] = $this->query('query { boards (ids: ' . $board_id . ') { id name } }');

        if (!$data['boardDetail'] || empty($data['boardDetail']['data']['boards'])) {
            error_404();
        }

        $data['groupDetail'] = [];
        $boardGroups = $this->query('query {boards (ids: ' . $board_id . ') {groups {title id}}}');

        foreach ($boardGroups['data']['boards'][0]['groups'] as $group) {
            if (!$data['groupDetail']) {
                $data['groupDetail'] = ($group_id == $group['id'] ? $group : []);
            } else {
                break;
            }
        }

        $data['boardColumns'] = $this->query('query {boards(ids: ' . $board_id . ') {columns {id title}}}');

        $data['items'] = [];
        if (!$cursor) {
            $boardItems = $this->query('{ boards (ids:  ' . $board_id . ') {  items_page(limit: ' . $limit . ') { cursor items { id name state url column_values { id text } group  { id title } } } } }');
            if (isset($boardItems) && isset($boardItems['data']['boards']) && !empty($boardItems['data']['boards'])) {
                $data['items'] = $boardItems['data']['boards'][0]['items_page']['items'];
                $data['cursor'] = $boardItems['data']['boards'][0]['items_page']['cursor'];
            }
        } else {
            $boardItems = $this->query('query { next_items_page(cursor: "' . $cursor . '", limit: ' . $limit . ') { cursor items { id name state url column_values { id text } group  { id title } } } }');
            $data['items'] = $boardItems['data']['next_items_page']['items'];
            $data['cursor'] = $boardItems['data']['next_items_page']['cursor'];
        }

        //
        $this->layout_data['title'] = 'Monday items | ' . $this->layout_data['title'];
        //
        $this->load_view('items', $data);
    }

    /**
     * query function
     *
     * @param string $query
     * @return ?array
     */
    function query(string $query)
    {
        $token = MONDAY_ACCESS_TOKEN;
        $apiUrl = MONDAY_API_URL;
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
        $responseContent = '';

        if (
            (
                $data = @file_get_contents($apiUrl, false, stream_context_create([
                    'http' => [
                        'method' => 'POST',
                        'header' => $headers,
                        'content' => json_encode(['query' => $query]),
                    ]
                ]))
            ) === false
        ) {
            $error = error_get_last();
            if (isset($error['message'])) {
                log_message('ERROR', $error['message']);
                $this->session->set_userdata('monday_error', $error['message']);
            }
        }

        if ($data) {
            $responseContent = json_decode($data, true);
        }

        return $responseContent;
    }

    /**
     * saveData function
     *
     * @return void
     */
    function saveData()
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $referenceDetail = [];

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['type'])) {

                    $reference_id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
                    $board_id = isset($_POST['board_id']) && $_POST['board_id'] ? $_POST['board_id'] : '';
                    $group_id = isset($_POST['group_id']) && $_POST['group_id'] ? $_POST['group_id'] : '';
                    $name = isset($_POST['name']) && $_POST['name'] ? $_POST['name'] : '';

                    switch ($_POST['type']) {
                        case 'board':
                            if ($reference_id) {
                                $referenceDetail = $this->query('query { boards (ids: ' . $reference_id . ') { id name } }');
                                if ($referenceDetail) {
                                    $json_param['id'] = $reference_id;
                                    $referenceDetail = $this->query('mutation { update_board (board_id: ' . $reference_id . ', board_attribute: name, new_value: "' . $name . '") }');
                                }
                            } else {
                                $referenceDetail = $this->query('mutation { create_board (board_name: "' . $name . '", board_kind: ' . (isset($_POST['kind']) && $_POST['kind'] ? $_POST['kind'] : 'public') . ') { id } }');
                                if ($referenceDetail) {
                                    $json_param['id'] = isset($referenceDetail['data']) ? $referenceDetail['data']['create_board']['id'] : '';
                                }
                            }
                            break;
                        case 'group':
                            if ($board_id) {
                                $boardDetail = $this->query('query { boards (ids: ' . $board_id . ') { id name } }');
                                if ($boardDetail) {
                                    if ($reference_id) {
                                        $json_param['id'] = $reference_id;
                                        $referenceDetail = $this->query('mutation {
                                            update_group (board_id: ' . $board_id . ', group_id: "' . $reference_id . '", group_attribute: title, new_value: "' . $name . '") { 
                                              id
                                            } 
                                        }');
                                    } else {
                                        $referenceDetail = $this->query('mutation {
                                            create_group (board_id: ' . $board_id . ', group_name: "' . $name . '") {
                                                id
                                            }
                                        }');
                                        if ($referenceDetail) {
                                            $json_param['id'] = isset($referenceDetail['data']) ? $referenceDetail['data']['create_group']['id'] : '';
                                        }
                                    }
                                }
                            }
                            break;
                        case 'item':
                            if ($board_id) {
                                $boardDetail = $this->query('query { boards (ids: ' . $board_id . ') { id name } }');
                                if ($boardDetail) {
                                    if ($reference_id) {
                                    } else {
                                        $referenceDetail = $this->query('mutation {
                                            create_item (board_id: ' . $board_id . ', group_id: "' . $group_id . '", item_name: "' . $name . '", column_values: "{\"date\":\"' . date('Y-m-d') . '\"}") {
                                                id
                                            }
                                        }');
                                        if ($referenceDetail) {
                                            $json_param['id'] = isset($referenceDetail['data']) ? $referenceDetail['data']['create_item']['id'] : '';
                                        }
                                    }
                                }
                            }
                            break;
                    }

                    if (isset($referenceDetail['error_code'])) {
                        if (isset($referenceDetail['error_message']) && $referenceDetail['error_message']) {
                            $json_param['txt'] = $referenceDetail['error_message'];
                        } else {
                            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                        }
                    } else {
                        if ($json_param['id']) {
                            $json_param['status'] = TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;
                        } else {
                            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                        }
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
     * deleteBoard function
     *
     * @return void
     */
    function deleteBoard()
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $referenceDetail = [];

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if (isset($_POST['id']) && $_POST['id']) {
                    $boardDetail = $this->query('query { boards (ids: ' . $_POST['id'] . ') { id name } }');
                    if ($boardDetail) {
                        $referenceDetail = $this->query('mutation {
                            delete_board (board_id: ' . $_POST['id'] . ') {
                                id
                            }
                        }');

                        if (isset($referenceDetail['error_code'])) {
                            if (isset($referenceDetail['error_message']) && $referenceDetail['error_message']) {
                                $json_param['txt'] = $referenceDetail['error_message'];
                            } else {
                                $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                            }
                        } else {
                            if(isset($referenceDetail['data']['delete_board']['id']) && $referenceDetail['data']['delete_board']['id']) {
                                $json_param['status'] = TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            }
                        }
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
     * deleteGroup function
     *
     * @return void
     */
    function deleteGroup()
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $referenceDetail = [];

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if ((isset($_POST['board_id']) && $_POST['board_id']) && (isset($_POST['id']) && $_POST['id'])) {
                    $boardDetail = $this->query('query { boards (ids: ' . $_POST['board_id'] . ') { id name } }');
                    if ($boardDetail) {
                        $referenceDetail = $this->query('mutation {
                            delete_group (board_id: ' . $_POST['board_id'] . ', group_id: "' . $_POST['id'] . '") {
                                id
                                deleted
                            }
                        }');
                        if (isset($referenceDetail['error_code'])) {
                            if (isset($referenceDetail['error_message']) && $referenceDetail['error_message']) {
                                $json_param['txt'] = $referenceDetail['error_message'];
                            } else {
                                $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                            }
                        } else {
                            if(isset($referenceDetail['data']['delete_group']['id']) && $referenceDetail['data']['delete_group']['id']) {
                                $json_param['status'] = TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            }
                        }
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
     * deleteGroup function
     *
     * @return void
     */
    function deleteItem()
    {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $referenceDetail = [];

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
                if ((isset($_POST['board_id']) && $_POST['board_id']) && (isset($_POST['group_id']) && $_POST['group_id']) && (isset($_POST['id']) && $_POST['id'])) {
                    $boardDetail = $this->query('query { boards (ids: ' . $_POST['board_id'] . ') { id name } }');
                    if ($boardDetail) {
                        $referenceDetail = $this->query('mutation {
                            delete_item (item_id: ' . $_POST['id'] . '") {
                                id name
                            }
                        }');
                        if (isset($referenceDetail['error_code'])) {
                            if (isset($referenceDetail['error_message']) && $referenceDetail['error_message']) {
                                $json_param['txt'] = $referenceDetail['error_message'];
                            } else {
                                $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                            }
                        } else {
                            if(isset($referenceDetail['data']['delete_item']['id']) && $referenceDetail['data']['delete_item']['id']) {
                                $json_param['status'] = TRUE;
                                $json_param['txt'] = SUCCESS_MESSAGE;
                            }
                        }
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
}
