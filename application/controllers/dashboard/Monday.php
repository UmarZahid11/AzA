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
    function boards() {
        $data = [];

        $data['boards'] = $this->get('{ boards { id name } }');

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
    function groups(int $board_id = 0) {
        $data = [];

        $data['boardDetail'] = $this->get('query { boards (ids: ' . $board_id . ') { id name } }');

        if(!$data['boardDetail']) {
            error_404();
        }

        $data['boardGroups'] = $this->get('query {boards (ids: ' . $board_id . ') {groups {title id}}}');
        $data['boardColumns'] = $this->get('query {boards(ids: ' . $board_id . ') {columns {id title}}}');

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
    function items(int $board_id = 0, string $group_id = '') {
        $data = [];

        $data['group_id'] = $group_id;

        $data['boardDetail'] = $this->get('query { boards (ids: ' . $board_id . ') { id name } }');

        if(!$data['boardDetail']) {
            error_404();
        }

        $data['groupDetail'] = [];
        $boardGroups = $this->get('query {boards (ids: ' . $board_id . ') {groups {title id}}}');

        foreach($boardGroups['data']['boards'][0]['groups'] as $group) {
            if(!$data['groupDetail']) {
                $data['groupDetail'] = ($group_id == $group['id'] ? $group : []);
            } else {
                break;
            }
        }

        $data['boardColumns'] = $this->get('query {boards(ids: ' . $board_id . ') {columns {id title}}}');
        $data['boardItems'] = $this->get('{ boards (ids:  ' . $board_id . ') {  items_page { cursor items { id name state url column_values { id text } group  { id title } } } } }');

        //
        $this->layout_data['title'] = 'Monday items | ' . $this->layout_data['title'];
        //
        $this->load_view('items', $data);       
    }

    /**
     * get function
     *
     * @param string $query
     * @return ?array
     */
    function get(string $query)
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
            ) === false) 
        {
            $error = error_get_last();
            if(isset($error['message'])) {
                $this->session->set_userdata('monday_error', $error['message']);
            }
        }

        if ($data) {
            $responseContent = json_decode($data, true);
        }

        return $responseContent;
    }
}
