<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Logout
 */
class Logout extends MY_Controller
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
	 * Method index
	 *
	 * @return void
	 */
	public function index()
	{
		$this->session->unset_userdata('logged_in');
		redirect('/admin/login');
	}
}


