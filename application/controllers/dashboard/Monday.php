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
    }

    /**
     * listing function
     *
     * @return void
     */
    function listing()
    {
        $data = [];

        $data['boards'] = $this->get('{ boards { id name } }');

        $this->load_view('listing', $data);
    }

    /**
     * board function
     *
     * @param integer $board_id
     * @return void
     */
    function board(int $board_id = 0)
    {
        $data = [];

        $data['boardDetail'] = $this->get('query { boards (ids: ' . $board_id . ') { id name } }');

        if (!$data['boardDetail']) {
            error_404();
        }

        // $data['boardColumns'] = $this->get('query {boards(ids: ' . $board_id . ') {columns {id title}}}');

        $data['boardGroups'] = $this->get('query {boards (ids: ' . $board_id . ') {groups {title id}}}');
        $data['boardItems'] = $this->get('query { boards (ids: ' . $board_id . '){ items_page { cursor items { id  name  } } } }');
        
        $this->load_view('board', $data);
    }

    /**
     * group function
     *
     * @param strng $group_id
     * @return void
     */
    function group(int $board_id = 0, string $group_id = '')
    {
        $data = [];

        // $data['groupDetail'] = $this->get('query { boards (ids: ' . $board_id . ') { groups (ids: ' . $group_id .') { id title } } }');
        // $data['groupDetail'] = $this->get('{items(ids:[' . $group_id . ']) { name column_values { column { title id text } } } }');
        // $data['boardItems'] = $this->get('query { items { name column_values { column { title id text } } } }');
        $data['boardItems'] = $this->get('query { boards (id: ' . $board_id . ') { items { id name } } }');

        debug($data['boardItems'], 1);

        if (!$data['boardDetail']) {
            error_404();
        }

        $data['boardColumns'] = $this->get('query {boards(ids: ' . $board_id . ') {columns {id title}}}');
        $data['boardGroups'] = $this->get('query {boards (ids: ' . $board_id . ') {groups {title id}}}');
        $data['boardItems'] = $this->get('query {boards (ids: ' . $board_id . ') {items {title id}}}');

        // debug($data['boardGroups'], 1);

        $this->load_view('board', $data);
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

        // // $query = '{items(ids:[1234567890]) { name column_values { column { title id text } } } }';
        // $query =  'query { boards (ids: 8243368831) { groups { title id } } }';
        $data = @file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => json_encode(['query' => $query]),
            ]
        ]));

        if ($data) {
            return json_decode($data, true);
        }

        return $data;
    }
}
