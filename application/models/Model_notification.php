<?php

/**
 * Model_notification
 */
class Model_notification extends MY_Model
{
    protected $_table = 'notification';
    protected $_field_prefix = 'notification_';
    protected $_pk = 'notification_id';
    protected $_status_field = 'notification_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
    public $_per_page = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->pagination_params['fields'] = "notification_id, notification_status";
        parent::__construct();
    }

    /**
     * Method sendNotification
     *
     * @param int $signup_id
     * @param int $from
     * @param int $type
     * @param int $reference_id
     * @param string $comment
     * @param string $comment2
     *
     * @return bool
     */
    public function sendNotification($signup_id, $from, $type, $reference_id = 0, $comment = "", $comment2 = "", $reference_id2 = 0): bool
    {
        $insert = array();
        $insert['notification_signup_id'] = $signup_id ?? 0;
        $insert['notification_from'] = $from ?? 0;
        $insert['notification_type'] = $type ?? '';
        if ($reference_id) {
            $insert['notification_reference_id'] = $reference_id ?? 0;
        }
        if ($reference_id2) {
            $insert['notification_reference_id2'] = $reference_id2 ?? 0;
        }
        if ($comment) {
            $insert['notification_comment'] = $comment ?? "";
        }
        if ($comment2) {
            $insert['notification_comment2'] = $comment2 ?? "";
        }

        return $this->insert_record($insert);
    }

    /**
     * Method seenNotification
     *
     * @return void
     */
    public function seenNotification()
    {
        $update_notifications = $this->model_notification->update_model(
            array(
                'where' => array(
                    'notification_signup_id' => $this->userid
                )
            ),
            array(
                'notification_seen' => STATUS_ACTIVE,
                'notification_alert_seen' => STATUS_ACTIVE
            )
        );
    }

    /**
     * Method notificationRedirection
     *
     * @param array $notification_detail
     *
     * @return void
     */
    function notificationRedirection(array $notification_detail = array()): string
    {
        $url = 'javascript:;';

        switch ($notification_detail['notification_type']) {
            case NOTIFICATION_MESSAGE:
                $url = l('dashboard/message/index/') . JWT::encode($notification_detail['notification_reference_id'], CI_ENCRYPTION_SECRET);
                break;
            case NOTIFICATION_EMAIL:
                $url = l('dashboard/home/message/details/') . ($notification_detail['notification_reference_id']);
                break;
            case NOTIFICATION_JOB_POSTED:
                $url = l('dashboard/job/detail/') . (isset($this->model_job->find_by_pk($notification_detail['notification_reference_id'])['job_slug']) ? $this->model_job->find_by_pk($notification_detail['notification_reference_id'])['job_slug'] : '');
                break;
            case NOTIFICATION_FOLLOW:
                $url = l('dashboard/profile/detail/') . JWT::encode($notification_detail['notification_from'], CI_ENCRYPTION_SECRET) . '/' . $notification_detail['signup_type'];
                break;
            case NOTIFICATION_WEBINAR:
            case NOTIFICATION_WEBINAR_SCHEDULED:
            case NOTIFICATION_WEBINAR_UPDATED:
                $url = l('dashboard/webinar/detail/') . JWT::encode($notification_detail['notification_reference_id'], CI_ENCRYPTION_SECRET);
                break;
            case NOTIFICATION_FOLLOW_PRODUCT:
            case NOTIFICATION_FOLLOW_TECHNOLOGY:
            case NOTIFICATION_FOLLOW_SERVICE:
                $url = l('dashboard/product/detail/') . $this->model_product->find_by_pk($notification_detail['notification_reference_id'])['product_slug'];
                break;
            case NOTIFICATION_PRODUCT_REQUEST:
            case NOTIFICATION_PRODUCT_RESPONSE:
            case NOTIFICATION_MEETING_REQUEST:
            case NOTIFICATION_MEETING_RESPONSE:
                $product = $this->model_product->find_by_pk($notification_detail['notification_reference_id']);
                if ($product) {
                    switch ($product['product_reference_type']) {
                        case PRODUCT_REFERENCE_SERVICE:
                            $url = l('dashboard/product/handle/') . ($this->model_product_request->find_by_pk($notification_detail['notification_reference_id2']) ? JWT::encode($this->model_product_request->find_by_pk($notification_detail['notification_reference_id2'])['product_request_id']) : '');
                            break;
                        case PRODUCT_REFERENCE_TECHNOLOGY:
                            $url = l('dashboard/product/request/') . $product['product_reference_type'];
                            break;
                    }
                }
                break;
            case NOTIFICATION_MEETING:
                $url = l('dashboard/meeting/detail/') . JWT::encode($notification_detail['notification_reference_id']);
                break;
            case NOTIFICATION_JOB_APPLICATION:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_UPDATE:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_APPROVED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_DECLINED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_STARTED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_SUBMITTED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_ACTION:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_MILESTONE_DELETED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_JOB_APPLICATION_APPROVED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_JOB_APPLICATION_DECLINED:
                $url = l('dashboard/application/detail/') . JWT::encode($notification_detail['notification_reference_id']) . '/' . $notification_detail['notification_reference_id2'];
                break;
            case NOTIFICATION_NEW_PROMOTION:
                $url = l('dashboard/profile/promotions');
                break;
            case NOTIFICATION_COACHING_REQUEST_SENT:
            case NOTIFICATION_COACHING_REQUEST_COMPLETED:
                $url = l('dashboard/coaching/detail/') . JWT::encode($notification_detail['notification_reference_id']);
                break;
        }
        return $url;
    }

    /**
     * Method get_fields
     *
     * @param string $specific_field
     *
     * @return void
     */
    public function get_fields($specific_field = "")
    {
        $data =  array(

            'notification_id' => array(
                'table' => $this->_table,
                'name' => 'notification_id',
                'label' => 'ID',
                'primary' => 'primary',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'notification_signup_id' => array(
                'table' => $this->_table,
                'name' => 'notification_signup_id',
                'label' => 'Signup ID',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'notification_from' => array(
                'table' => $this->_table,
                'name' => 'notification_from',
                'label' => 'From',
                'type' => 'hidden',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'notification_type' => array(
                'table' => $this->_table,
                'name' => 'notification_type',
                'label' => 'Type',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'notification_comment' => array(
                'table' => $this->_table,
                'name' => 'notification_comment',
                'label' => 'Comment',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'notification_comment2' => array(
                'table' => $this->_table,
                'name' => 'notification_comment2',
                'label' => 'Comment 2',
                'type' => 'text',
                'type_dt' => 'text',
                'attributes' => array(),
                'js_rules' => '',
                'rules' => 'trim'
            ),

            'notification_status' => array(
                'table' => $this->_table,
                'name' => 'notification_status',
                'label' => 'Status',
                'type' => 'switch',
                'type_dt' => 'switch',
                'type_filter_dt' => 'dropdown',
                'list_data' => array(
                    0 => "<span class='label label-danger'>Inactive</span>",
                    1 =>  "<span class='label label-primary'>Active</span>"
                ),
                'default' => '1',
                'attributes' => array(),
                'dt_attributes' => array("width" => "7%"),
                'rules' => 'trim'
            ),

        );

        if ($specific_field)
            return $data[$specific_field];
        else
            return $data;
    }
}
