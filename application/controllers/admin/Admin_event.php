<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Admin_event
 */
class Admin_event extends MY_Controller
{
    /**
     * _list_data
     *
     * @var array
     */
    public $_list_data = array();

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        global $config;

        parent::__construct();

        $this->dt_params['dt_headings'] = "admin_event_id,admin_event_name,admin_event_category,admin_event_created,admin_event_status";

        $this->dt_params['searchable'] = array("admin_event_id", "admin_event_name", "admin_event_status");

        $this->dt_params['action'] = array(
            "hide" => false,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
            "hide_add_button" => false
        );

        $this->_list_data['admin_event_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }

    /**
     * Method add
     *
     * @param int $id
     * @param array $data
     *
     * @return void
     */
    public function add($id = '', $data = array())
    {
        parent::add($id, $data);
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save()
    {
        $json_param = array();
        $post = $this->input->post();

        if (count($post) > 0) {
            $_POST['admin_event']['admin_event_name'] = $post['title'];
            $_POST['admin_event']['admin_event_category'] = $post['category'];
            $_POST['admin_event']['admin_event_created'] = $post['cdate'];

            // Validation success
            if ($this->validate("model_admin_event")) {

                $data = $_POST['admin_event'];
                $data['admin_event_created'] = $_POST['cdate'];
                $data['admin_event_status'] = 1;
                $this->model_admin_event->set_attributes($data);
                $inserted_id = $this->model_admin_event->save();

                if ($inserted_id > 0) {
                    $json_param['status'] = 1;
                    $json_param['txt'] = 'Event added.';
                } else {
                    $json_param['status'] = 0;
                    $json_param['txt'] = 'Something went wrong.Please try later.';
                }
            } else {
                $json_param['status'] = 0;
                $json_param['txt'] = validation_errors();
            }
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = 'No parameters found';
        }

        echo json_encode($json_param);
    }

    /**
     * Method update
     *
     * @param int $id
     *
     * @return void
     */
    public function update($id = 0)
    {
        $json_param = array();

        $post = $this->input->post('admin_event');
        if (count($post) > 0 && ($id > 0) && (!empty($post['admin_event_name']))) {
            $updated = $this->model_admin_event->update_by_pk($id, $post);
            if ($updated) {
                $json_param['status'] = 1;
                $json_param['txt'] = 'Event updated.';
            } else {
                $json_param['status'] = 0;
                $json_param['txt'] = 'Record not updated.';
            }
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = 'Invalid parameter.';
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete
     *
     * @return void
     */
    public function delete()
    {
        $json_param = array();

        $id = $this->input->post('id');
        if ($id > 0) {
            $updated = $this->model_admin_event->delete_by_pk($id);
            if ($updated) {
                $json_param['status'] = 1;
                $json_param['txt'] = 'Event removed.';
            } else {
                $json_param['status'] = 0;
                $json_param['txt'] = 'Failed to delete event.';
            }
        } else {
            $json_param['status'] = 0;
            $json_param['txt'] = 'Invalid parameter.';
        }

        echo json_encode($json_param);
    }
}


