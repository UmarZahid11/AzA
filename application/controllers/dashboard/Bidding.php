<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bidding
 */
class Bidding extends MY_Controller
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
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $data = array();

        //
        $this->layout_data['title'] = 'Bidding | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}