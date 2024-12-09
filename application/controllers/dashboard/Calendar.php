<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Calendar
 */
class Calendar extends MY_Controller
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
     * Method calendar
     *
     * @return void
     */
    function index(): void
    {
        $data = array();

        $webinar_time = $this->model_webinar->webinars();

        $requested_meeting_time = $this->model_meeting->meetings();

        $my_meeting_time = $this->model_meeting->meetings(TRUE);

        $requested_service_meeting_time = $this->model_meeting->meetings(FALSE, MEETING_REFERENCE_PRODUCT);

        $meeting_time = array_merge($requested_meeting_time, $my_meeting_time, $requested_service_meeting_time);

        $availability_slots = array_merge($this->model_signup_availability->userRequestorSlotsCalendar($this->userid), $this->model_signup_availability->userAvailabilitySlotsCalendar($this->userid));

        $data['calendar_events'] = array_merge($webinar_time, $meeting_time);

        $data['calendar_events'] = array_merge($data['calendar_events'], $availability_slots);
        
        //
        $this->layout_data['title'] = 'Calendar | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }
}