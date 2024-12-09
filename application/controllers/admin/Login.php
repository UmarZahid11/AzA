<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Login
 */
class Login extends MY_Controller
{
	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$session = $this->session->userdata('logged_in');

		if ($session && $session['is_admin']) {
			if ($_GET['redirect_url'])
				redirect(urldecode($_GET['redirect_url']));
			else
				redirect("/admin");
		}
		$this->admin_dir = "admin/" . $this->router->class;
	}

	/**
	 * Method index
	 *
	 * @return void
	 */
	public function index()
	{
		global $config;

		$data['logo'] = $this->model_logo->find_all_active();
		$this->layout = "admin/admin_plain";
		$user_data['login'] = array();
		$user_data['login'] = $this->input->post(NULL, true);
		$this->layout_data['css_files'] = array(
			"pages/login.css",
			"plugins.css",
			"components.css",
			"layout.css",
			"themes/default.css",
			"custom.css",
			"plugins.css",
		);

		$this->layout_data['js_files'] = array(
			"metronic.js",
			"layout.js",
			"demo.js",
			"login.js",
			"tkd_script.js",
		);

		if (isset($_POST) && $_POST) {

			$captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
			$secretKey = defined('CAPTCHA_SECRET_KEY') ? CAPTCHA_SECRET_KEY : '';

			if($secretKey) {
				// post request to server
				$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
				$response = file_get_contents($url);
				$responseKeys = json_decode($response, true);
			} else {
				$responseKeys["success"] = TRUE;
			}

			// should return JSON with success as true
			if ($responseKeys["success"]) {
				if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

					$redirect = urldecode($_POST['redirect_url']);
					if (!$redirect)
						$redirect = $config['base_url'] . 'admin';

					if ($this->model_user->login())
						redirect($redirect);
					else
						$data['error'] = "Invalid credentials.";
				} else {
					$data['error'] = ERROR_MESSAGE_LINK_EXPIRED;
				}
			} else {
				$data['error'] = ERROR_MESSAGE_CAPTCHA_FAILED;
			}
		}

		$data['user_input'] = $user_data['login'];
		//
		$this->load_view("_form", $data);
	}
}
