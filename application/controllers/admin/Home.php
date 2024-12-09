<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Home
 */
class Home extends MY_Controller
{
	/**
	 * Method index
	 *
	 * @return void
	 */
	public function index()
	{
		$this->layout_data['page_title'] = "Dashboard";
		$this->layout_data['bread_crumbs'] = array(array("home/" => "Home"));
		$this->layout_data['additional_tools'] = array(
			"jquery-ui",
			"bootstrap",
			"bootstrap-hover-dropdown",
			"jquery-slimscroll",
			"boots",
			"font-awesome",
			"simple-line-icons",
		);

		$this->add_script(array("pages/tasks.css", "agent_analytics.css"));
		$this->add_script(array("tasks.js", "index.js", "real-time.js", "canvasjs.min.js"), "js");
		$this->register_plugins(array('fullcalendar2'));

		$data['unread_inquiry'] = $this->model_inquiry->get_unread_inquiry();
		$data['signup_approval'] = $this->model_signup->find_count_active(array('where' => array('signup_is_approved' => 0)));
		$data['blog_approval_request'] = $this->model_blog->find_count_active(array('where' => array('blog_approved' => 0)));
		$data['inquiry_request'] = $this->model_inquiry->find_count_active(array('where' => array('inquiry_status' => 1)));
		$data['testimonial_request'] = $this->model_job_testimonial_request->find_count_active(array('where' => array('job_testimonial_request_seen' => 0)));

		$data['agent'] = $this->model_agent->get_records();
		$data['inquiries'] = $this->model_inquiry->get_records();
		$data['config'] = $this->config->config;
		$data['logo'] = $this->model_logo->find_all_active();
		
		$data['inquiriesDataset'] = '';
		$inquiriesDataset = array();

		foreach($this->model_signup->find_all_active(array('where' => array('signup_for_future' => 1))) as $inquiriesData) {
	        $location = $inquiriesData['signup_location_country'];
	        if(array_key_exists($location, $inquiriesDataset)) {
	            $inquiriesDataset[$location] += 1;
	        } else {
	            $inquiriesDataset[$location] = 1;
	        }
		}
		
		foreach($inquiriesDataset as $key => $inquiriesData) {
		    $data['inquiriesDataset'] .= (',["' . $key . '",' . $inquiriesData. ']');
		}
		
		//
		$this->load_view("dashboard", $data);
	}
}
