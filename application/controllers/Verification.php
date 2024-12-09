<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Verification
 */
class Verification extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if($this->userid == 0) {
            error_404();
        }
    }

    function index() {
        error_404();
    }

    /**
     * Method profile
     *
     * @return void
     */
    public function profile()
    {
        $data = array();
        //
        $this->layout_data['title'] = 'Profile verification | ' . $this->layout_data['title'];
        //
        $this->load_view('profile', $data);
    }

}