<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Product - handling product, technology, service
 */
class Product extends MY_Controller
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
     * Method index
     *
     * @return void
     */
    function index() : void {
        error_404();
    }

    /**
     * Method listing
     *
     * @param $userid $userid
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function listing($reference = PRODUCT_REFERENCE_PRODUCT, $userid = '', int $page = 1, int $limit = PER_PAGE, $search = ''): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            if (in_array($reference, [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_SERVICE, PRODUCT_REFERENCE_TECHNOLOGY])) {

                $data = array();

                $data['search'] = $search;

                $data['page'] = $page;
                $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

                $data['limit'] = $limit;

                // Prev + Next
                $data['prev'] = $page - 1;
                $data['next'] = $page + 1;

                $query = 'Select * from `fb_product`' . ' ';

                //
                if ($userid) {
                    //
                    try {
                        $userid = JWT::decode($userid, CI_ENCRYPTION_SECRET);
                    } catch (\Exception $e) {
                        log_message('ERROR', $e->getMessage());
                        //
                        $this->_log_message(
                            LOG_TYPE_GENERAL,
                            LOG_SOURCE_SERVER,
                            LOG_LEVEL_ERROR,
                            $e->getMessage(),
                            ''
                        );
                        error_404();
                    }
                }

                // $count_param['joins'] = $param['joins'] = array(
                //     0 => array(
                //         'table' => 'signup',
                //         'joint' => 'signup.signup_id = product.product_signup_id',
                //         'type'  => 'both'
                //     ),
                //     1 => array(
                //         'table' => 'signup_info',
                //         'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                //         'type' => 'both'
                //     ),
                //     2 => array(
                //         'table' => 'fb_signup_company',
                //         'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                //         'type' => 'left'
                //     )
                // );
                
                $query .= 'JOIN `fb_signup` ON `fb_signup`.`signup_id` = `fb_product`.`product_signup_id`
                JOIN `fb_signup_info` ON `fb_signup_info`.`signup_info_signup_id` = `fb_signup`.`signup_id`
                LEFT JOIN `fb_signup_company` ON `fb_signup_company`.`signup_company_signup_id` = `fb_signup`.`signup_id`' . ' ';

                $query .= 'WHERE `product_reference_type` = "' . $reference . '" ';
                
                if($userid != $this->userid) {
                    $query .= ' AND `fb_product`.`product_status` = ' . STATUS_ACTIVE . ' ';
                }

                if ($userid) {
                    // $count_param['where']['product_signup_id'] = $param['where']['product_signup_id'] = $userid;
                    $query .= 'AND product_signup_id = "' . $userid . '"' . ' ';
                }

                if ($search) {
                    $query .= "AND (`product_name` LIKE '%" . $search . "%' OR `product_number` LIKE '%" . $search . "%')" . ' ';
                }
                
                $count_query = $query;
                $query .= 'ORDER BY `product_id` DESC LIMIT ' . $limit . ' offset ' . $paginationStart;

                // $count_param['where']['product_reference_type'] = $param['where']['product_reference_type'] = $reference;

                // $param['order'] = 'product_id DESC';
                // $param['offset'] = $paginationStart;
                // $param['limit'] = $limit;

                // if ($search) {
                //     $count_param['where_like'][] = $param['where_like'][] = array(
                //         'column' => 'product_name',
                //         'value' => $search,
                //         'type' => 'both',
                //     );
                //     $count_param['or_where_like'][] = $param['where_like'][] = array(
                //         'column' => 'product_number',
                //         'value' => $search,
                //         'type' => 'both',
                //     );
                // }

                $data['products'] = $this->db->query($query)->result_array();
                $data['products_count'] = $allRecrods = count($this->db->query($count_query)->result_array());
                
                //
                // $data['products'] = $this->model_product->find_all_active(
                //     $param
                // );
                //
                // $data['products_count'] = $allRecrods = $this->model_product->find_count(
                //     $count_param
                // );

                $data['totalPages'] = ceil($allRecrods / $limit);

                $data['reference'] = $reference;
                $data['userid'] = JWT::encode($userid);

                switch ($reference) {
                    case PRODUCT_REFERENCE_PRODUCT:
                        $data['reference_plural'] = 'products';
                        break;
                    case PRODUCT_REFERENCE_SERVICE:
                        $data['reference_plural'] = 'services';
                        break;
                    case PRODUCT_REFERENCE_TECHNOLOGY:
                        $data['reference_plural'] = 'technologies';
                        break;
                }

                //
                $this->layout_data['title'] = ucfirst($reference) . ' | ' . $this->layout_data['title'];
                //
                $this->load_view("listing", $data);
            } else {
                error_404();
            }
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method order
     *
     * @return void
     */
    public function orders(string $reference = PRODUCT_REFERENCE_PRODUCT, int $page = 1, int $limit = PER_PAGE, string $userid = '') {
        if ($this->model_signup->hasPremiumPermission()) {
            if(!$userid) {
                $userid = $this->userid;
            } else {
                //
                try {
                    $userid = JWT::decode($userid, CI_ENCRYPTION_SECRET);
                } catch (\Exception $e) {
                    log_message('ERROR', $e->getMessage());
                    //
                    $this->_log_message(
                        LOG_TYPE_GENERAL,
                        LOG_SOURCE_SERVER,
                        LOG_LEVEL_ERROR,
                        $e->getMessage(),
                        ''
                    );
                    error_404();
                }
            }

            if (!in_array($reference, [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_SERVICE, PRODUCT_REFERENCE_TECHNOLOGY]))
                error_404();

            $data['order_reference'] = $reference;
            $data['page'] = $page;
            $data['offset'] = $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            $my_products = $this->model_product->find_all_active(
                array(
                    'where' => array(
                        'product_signup_id' => $userid,
                        'product_reference_type' => $reference
                    )
                )
            );

            $data['orders'] = array();
            $order_items = array();

            foreach($my_products as $my_product) {
                $order_items[] =
                    $this->model_order_item->find_all_active(
                        array(
                            'where' => array(
                                'order_item_product_id' => $my_product['product_id']
                            ),
                        )
                    );
            }

            $orders_array = array();
            foreach($order_items as $key => $order_item_parent) {
                foreach($order_item_parent as $key => $order_item) {
                    if(!in_array($order_item['order_item_order_id'], $orders_array)) {
                        array_push($orders_array, $order_item['order_item_order_id']);
                    }
                }
            }

            arsort($orders_array);
            foreach($orders_array as $order_id) {
                $order = $this->model_order->find_one_active(
                    array(
                        'where' => array(
                            'order_id' => $order_id
                        ),
                    )
                );

                if($order) {
                    $data['orders'][] = $order;
                }
            }

            //
            $this->layout_data['title'] = ucfirst($reference) . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("orders", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method save
     *
     * @param string $type
     * @param string $slug
     * @param string $reference
     * @param string $slug
     *
     * @return void
     */
    public function save(string $type = CREATE, string $reference = PRODUCT_REFERENCE_PRODUCT, string $slug = ''): void
    {
        $this->register_plugins("select2");

        $data = array();

        if ($this->userid == 0 || !(($this->model_signup->hasPremiumPermission())))
            error_404();

        if (!in_array($reference, [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_SERVICE, PRODUCT_REFERENCE_TECHNOLOGY]))
            error_404();

        if (!in_array($type, [CREATE, UPDATE]))
            error_404();

        if ($type == UPDATE && !$slug)
            error_404();

        $data['type'] = $type;

        $data['product'] = array();

        if ($type == UPDATE) {
            $param = array();
            $param['where']['product_slug'] = $slug;
            $param['where']['product_signup_id'] = $this->userid;
            $data['product'] = $this->model_product->find_one($param);
            if (empty($data['product'])) {
                error_404();
            }
        }

        //
        $data['product_category'] = $this->model_job_category->find_all_active();
        //
        $data['product_job_type'] = $this->model_job_type->find_all_active();

        $data['reference'] = $reference;

        $data['company_contact_exists'] = $this->user_data['signup_company_phone'] ? TRUE : FALSE;

        //
        $this->layout_data['title'] = ucfirst($type) . ' ' . ucfirst($reference) . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("save", $data);
    }

    /**
     * Method detail
     *
     * @param string $slug
     *
     * @return void
     */
    public function detail(string $slug = ''): void
    {
        if (!$slug)
            error_404();

        // if (!($this->model_signup->hasPremiumPermission()))
        //     error_404();
        //

        $data = array();

        $data['product'] = $this->model_product->find_one(
            array(
                'where' => array(
                    'product_slug' => $slug,
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = product.product_signup_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'fb_signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    )

                )
            )
        );

        if (empty($data['product']))
            error_404();


        $product_id = $data['product']['product_id'];
        $data['type'] = $data['product']['product_reference_type'];
        //
        $data['comment'] = $this->model_comment->find_all_active(
            array(
                'order' => 'comment_id DESC',
                'where' => array(
                    'comment_parent_id' => 0,
                    'comment_reference_id' => $product_id,
                    'comment_reference_type' => $data['type']
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = comment.comment_userid',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'signup_info',
                        'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                        'type' => 'both'
                    )
                )
            )
        );

        // commenting purpose
        $data['reference_id'] = $product_id;
        $data['follower_count'] = $this->model_signup_follow->getFollowerCount((int) $data['product']['product_id'], $data['product']['product_reference_type']);
        $data['followee_count'] = $this->model_signup_follow->getFolloweeCount((int) $data['product']['product_id'], $data['product']['product_reference_type']);

        //
        $this->layout_data['title'] = $data['product']['product_name'] . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("detail", $data);
    }

    /**
     * Method request
     *
     * @param string $reference
     * @param int $page
     * @param int $limit
     * @param string $userid
     *
     * @return void
     */
    function request(string $reference = '', int $page = 1, int $limit = PER_PAGE, string $userid = ''): void
    {
        if ($this->userid == 0 || !($this->model_signup->hasPremiumPermission()))
            error_404();

        if (!in_array($reference, [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_SERVICE, PRODUCT_REFERENCE_TECHNOLOGY]))
            error_404();

        $data = array();

        $data['reference'] = $reference;

        if ($userid) {
            try {
                $userid = JWT::decode($userid, CI_ENCRYPTION_SECRET);
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
                //
                $this->_log_message(
                    LOG_TYPE_GENERAL,
                    LOG_SOURCE_SERVER,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
                error_404();
            }
        }

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $where_param = $count_param = $where_like = $or_like = array();
        $where_string = '';

        $where_param['product_reference_type'] = $reference;

        if ($userid) {
            // requests sent from specific user
            $count_param['product_request_signup_id'] = $where_param['product_request_signup_id'] = $userid;
            $data['title'] = 'My ' . $reference . ' requests';
            $data['btn_title'] = 'Manage ' . $reference . ' requests';
            $data['tooltip_title'] = 'All sent requests to ' . $reference;
        } else {
            // request sent to current user
            $count_param['product_signup_id'] = $where_param['product_signup_id'] = $this->userid;
            $data['title'] = 'Manage ' . $reference . ' requests';
            $data['btn_title'] = 'My ' . $reference . ' requests';
            $data['tooltip_title'] = 'All received requests on my posted ' . $reference;
        }

        $data['userid'] = JWT::encode($userid);

        if (isset($_POST)) {
            if (isset($_POST['product_name']) && $_POST['product_name']) {
                $data['product_name'] = $_POST['product_name'];
                $where_like = array(
                    0 => array(
                        'column' => 'fb_product.product_name',
                        'value' => $_POST['product_name'],
                        'type' => 'both',
                    )
                );
            }
            if (isset($_POST['product_request_signup']) && $_POST['product_request_signup']) {
                $data['product_request_signup'] = $_POST['product_request_signup'];
                $where_string = '(fb_signup.signup_firstname LIKE "%' . $_POST['product_request_signup'] . '%" OR fb_signup.signup_lastname LIKE "%' . $_POST['product_request_signup'] . '%" OR fb_signup_company.signup_company_name LIKE "%' . $_POST['product_request_signup'] . '%")';
            }
            if (isset($_POST['product_request_current_status']) && $_POST['product_request_current_status']) {
                $data['product_request_current_status'] = $_POST['product_request_current_status'];
                $where_param['product_request_current_status'] = $count_param['product_request_current_status'] = $_POST['product_request_current_status'];
            }
        }

        $data['requests'] = $this->model_product_request->find_all_active(
            array(
                'order' => 'product_request_id DESC',
                'offset' => $paginationStart,
                'limit' => $limit,
                'where' => $where_param,
                'where_like' => $where_like,
                'or_like' => $or_like,
                'where_string' => $where_string,
                'joins' => array(
                    0 => array(
                        'table' => 'fb_product',
                        'joint' => 'product.product_id = product_request.product_request_product_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'fb_signup',
                        'joint' => 'signup.signup_id = product_request.product_request_signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'fb_signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    )
                )
            )
        );

        $data['requests_count'] = $allRecrods = $this->model_product_request->find_count_active(
            array(
                'where' => $count_param,
                'where_like' => $where_like,
                'or_like' => $or_like,
                'where_string' => $where_string,
                'joins' => array(
                    0 => array(
                        'table' => 'fb_product',
                        'joint' => 'product.product_id = product_request.product_request_product_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'fb_signup',
                        'joint' => 'signup.signup_id = product_request.product_request_signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'fb_signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    )
                )
            )
        );

        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = $data['title'] . ' | ' . $this->layout_data['title'];
        //
        $this->load_view("request", $data);
    }

    /**
     * Method handle
     *
     * @param int $product_request_id - service handling page
     *
     * @return void
     */
    function handle(string $product_request_id = ''): void
    {
        if ($this->userid == 0 || !($this->model_signup->hasPremiumPermission()))
            error_404();

        if (!$product_request_id)
            error_404();

        if ($product_request_id) {
            try {
                $product_request_id = JWT::decode($product_request_id, CI_ENCRYPTION_SECRET);
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
                //
                $this->_log_message(
                    LOG_TYPE_GENERAL,
                    LOG_SOURCE_SERVER,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
                error_404();
            }
        }

        $data = array();

        $data['product_request_detail'] = $this->model_product_request->find_one_active(
            array(
                'where' => array(
                    'product_request_id' => $product_request_id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'fb_product',
                        'joint' => 'product.product_id = product_request.product_request_product_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'fb_signup',
                        'joint' => 'signup.signup_id = product_request.product_request_signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'fb_signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    )
                )
            )
        );

        $data['product_owner_detail'] = $this->model_product_request->find_one_active(
            array(
                'where' => array(
                    'product_request_id' => $product_request_id
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'fb_product',
                        'joint' => 'product.product_id = product_request.product_request_product_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'fb_signup',
                        'joint' => 'signup.signup_id = product.product_signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'fb_signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    )
                )
            )
        );

        if (empty($data['product_request_detail']))
            error_404();

        if ($data['product_request_detail']['product_reference_type'] != PRODUCT_REFERENCE_SERVICE)
            error_404();

        $data['order_item'] = $this->model_order_item->find_one_active(
            array(
                'where' => array(
                    'order_item_id' => $data['product_request_detail']['product_request_order_item_id'],
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'fb_order',
                        'joint' => 'order.order_id = order_item.order_item_order_id',
                        'type' => 'both'
                    )
                )
            )
        );
        $data['order'] = [];
        if($data['order_item']) {
            $data['order'] = $this->model_order->find_by_pk($data['order_item']['order_item_order_id']);
        }

        $data['has_sent_request'] = $this->model_meeting_request->find_one_active(
            array(
                'where' => array(
                    'meeting_request_reference' => MEETING_REQUEST_REFERENCE_PRODUCT,
                    'meeting_request_reference_id' => $data['product_request_detail']['product_request_product_id'],
                    'meeting_request_reference_request_id' => $data['product_request_detail']['product_request_id'],
                    'meeting_request_signup_id' => $data['product_request_detail']['product_request_signup_id']
                ),
                'joins' => array(
                    0 => array(
                        'table' => 'fb_product',
                        'joint' => 'product.product_id = meeting_request.meeting_request_reference_id',
                        'type' => 'both'
                    ),
                    1 => array(
                        'table' => 'fb_signup',
                        'joint' => 'signup.signup_id = meeting_request.meeting_request_signup_id',
                        'type' => 'both'
                    ),
                    2 => array(
                        'table' => 'fb_signup_company',
                        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                        'type' => 'left'
                    )
                )
            )
        );

        $data['meeting'] = $this->model_meeting->find_one_active(
            array(
                'where' => array(
                    'meeting_reference_type' => MEETING_REFERENCE_PRODUCT,
                    'meeting_reference_id' => $data['product_request_detail']['product_request_id']
                )
            )
        );

        $data['timezones'] = $this->model_timezones->find_all_active();

        //
        $this->layout_data['title'] = ucfirst($data['product_request_detail']['product_reference_type']) . ' request detail | ' . $this->layout_data['title'];
        //
        $this->load_view("handle", $data);
    }

    /**
     * Method saveData
     *
     * @return void
     */
    public function saveData(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $subscribed = FALSE;
        $subscription_error = FALSE;
        $subscription = '';

        // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if ((isset($this->user_data['signup_company_phone']) && $this->user_data['signup_company_phone'])) {
                    if (isset($_POST) && isset($_POST['product'])) {
                        $type = 'insert';
                        $error = false;
                        $errorMessage = __(ERROR_MESSAGE);
                        $successMessage = "Success";
                        $productId = 0;
                        $affectProduct = $_POST['product'];
                        $product_reference_type = (isset($_POST['product_reference_type']) && in_array($_POST['product_reference_type'], [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_SERVICE, PRODUCT_REFERENCE_TECHNOLOGY])) ? $_POST['product_reference_type'] : 'product';

                        // check if product exists with the requested id
                        if (isset($_POST['product_id']) && intVal($_POST['product_id']) > 0) {
                            $param = array();
                            $param['where']['product_id'] = $_POST['product_id'];
                            $param['where']['product_signup_id'] = $this->userid;
                            $productDetail = $this->model_product->find_one($param);
                            if (empty($productDetail)) {
                                $error = true;
                                $errorMessage = "Requested " . ($product_reference_type) . " doesn't exists";
                            } else {
                                $productId = $productDetail['product_id'];
                            }
                        }

                        if (!$error) {
                            $somError = FALSE;
                            $param = array();
                            // exclude currently updating product for updation
                            if ($productId) {
                                $param['where']['product_id !='] = $_POST['product_id'];
                            }
                            $param['where']['product_slug'] = $affectProduct['product_slug'];
                            $productDetail = $this->model_product->find_one($param);

                            if (empty($productDetail)) {
                                $affectProduct['product_category'] = isset($affectProduct['product_category']) ? serialize($affectProduct['product_category']) : '{}';

                                if (isset($_FILES['product_attachment']['error']) && $_FILES['product_attachment']['error'] == 0) {
                                    $tmp = $_FILES['product_attachment']['tmp_name'];
                                    $ext = pathinfo($_FILES['product_attachment']['name'], PATHINFO_EXTENSION);
                                    $name = mt_rand() . '.' . $ext;
                                    $upload_path = 'assets/uploads/product/';

                                    if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                        $somError = TRUE;
                                    } else {
                                        $affectProduct['product_attachment'] = $name;
                                        $affectProduct['product_attachment_path'] = $upload_path;
                                    }
                                }

                                // charge for technology
                                if (isset($_POST['stripeToken']) && g('db.admin.enable_technology_listing_subscription') && g('db.admin.technology_listing_subscription_fee')) {
                                    $customer = $this->createStripeResource('customers', [
                                        'source' => $_POST['stripeToken'],
                                    ]);
                                    $product = $this->createStripeResource('products', [
                                        'name' => $affectProduct['product_name'],
                                    ]);
                                    $interval = (isset($affectProduct['product_subscription_interval']) && $affectProduct['product_subscription_interval'] ? $affectProduct['product_subscription_interval'] : 1);
                                    $cancel_at = strtotime(date('Y-m-d H:i:s', strtotime('+' . (int) $interval . $affectProduct['product_subscription_interval_type'])));
                                    switch($affectProduct['product_subscription_interval_type']) {
                                        case 'day':
                                            $technology_listing_subscription_fee = g('db.admin.technology_listing_subscription_fee') * $interval;
                                            break;
                                        case 'week':
                                            $technology_listing_subscription_fee = g('db.admin.technology_listing_subscription_fee') * 7 * $interval;
                                            break;
                                        case 'month':
                                            $technology_listing_subscription_fee = g('db.admin.technology_listing_subscription_fee') * 28 * $interval;
                                            break;
                                        default:
                                            $technology_listing_subscription_fee = g('db.admin.technology_listing_subscription_fee') * $interval;
                                    }

                                    //
                                    $price = $this->createStripeResource('prices', [
                                        'unit_amount' => $technology_listing_subscription_fee * 100,
                                        'currency' => DEFAULT_CURRENCY_CODE,
                                        'product' => $product->id,
                                        'recurring' => array(
                                            'interval' => $affectProduct['product_subscription_interval_type'],
                                            'interval_count' => 1,
                                        ),
                                    ]);

                                    $subscription = $this->createStripeResource('subscriptions', [
                                        'customer' => $customer->id,
                                        'cancel_at' => $cancel_at,
                                        'items' => [
                                            ['price' => $price->id],
                                        ],
                                    ]);

                                    if ($subscription) {
                                        $affectProduct['product_subscription_id'] = $subscription->id;
                                        $affectProduct['product_subscription_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscription);
                                        $affectProduct['product_subscription_status'] = $this->model_membership->subscriptionStatus($subscription->status);
                                        $affectProduct['product_subscription_current_period_start'] = date('Y-m-d H:i:s', $subscription->current_period_start);
                                        $affectProduct['product_subscription_current_period_end'] = date('Y-m-d H:i:s', $subscription->current_period_end);
                                        $affectProduct['product_subscription_expiry'] = date('Y-m-d H:i:s', $cancel_at);
                                        //
                                        $subscribed = TRUE;
                                    } else {
                                        $subscription_error = TRUE;
                                        $errorMessage = 'An error occurred while trying to process subscription.';
                                    }
                                }

                                if (!$subscription_error) {
                                    // if action => update else, action => insert
                                    if ($productId) {
                                        $inserted = $this->model_product->update_by_pk($productId, $affectProduct);

                                        $type = UPDATE;
                                        $successMessage = __("Changes have been saved.");

                                        // if error
                                        if (!$inserted) {
                                            $error = true;
                                            $errorMessage = __(ERROR_MESSAGE_UPTODATE);
                                        }
                                    } else {

                                        if (!$error) {
                                            $inserted = $this->model_product->insert_record($affectProduct);

                                            $type = INSERT;
                                            $successMessage = SUCCESS_MESSAGE;

                                            // if error
                                            if (!$inserted) {
                                                $error = true;
                                                $errorMessage = __(ERROR_MESSAGE);
                                            } else {
                                                $productId = $inserted;
                                            }
                                        }
                                    }

                                    // if product is only inserted and fee is paid
                                    if ($subscribed) {
                                        // save order
                                        $affect_param = array();
                                        $affect_param['order_user_id'] = $this->userid;
                                        $affect_param['order_email'] = $this->user_data['signup_email'];
                                        $affect_param['order_firstname'] = $this->user_data['signup_firstname'];
                                        $affect_param['order_lastname'] = $this->user_data['signup_lastname'];
                                        $affect_param['order_phone'] = $this->user_data['signup_phone'];
                                        $affect_param['order_address1'] = $this->user_data['signup_address'];
                                        $affect_param['order_city'] = $this->user_data['signup_city'];
                                        $affect_param['order_zip'] = $this->user_data['signup_zip'];
                                        $affect_param['order_reference_id'] = $productId;
                                        $affect_param['order_reference_type'] = ORDER_REFERENCE_TECHNOLOGY_LISTING;
                                        $affect_param['order_quantity'] = $interval;
                                        $affect_param['order_quantity_interval'] = $affectProduct['product_subscription_interval_type'];
                                        $affect_param['order_total'] = $technology_listing_subscription_fee;
                                        $affect_param['order_shipping'] = 0;
                                        $affect_param['order_amount'] = $affect_param['order_total'] + $affect_param['order_shipping'];
                                        $affect_param['order_status'] = STATUS_ACTIVE;
                                        $affect_param['order_payment_status'] = STATUS_ACTIVE;
                                        $affect_param['order_status_message'] = PAYMENT_STATUS[PAYMENT_STATUS_COMPLETED];
                                        $affect_param['order_shipment_price'] = price($affect_param['order_shipping']);
                                        $affect_param['order_merchant'] = STRIPE;
                                        $affect_param['order_currency'] = DEFAULT_CURRENCY_CODE;
                                        $affect_param['order_stripe_transaction_id'] = $subscription ? $subscription->id : '';
                                        $affect_param['order_stripe_response'] = str_replace('Stripe\Subscription JSON:', '', (string) $subscription);
                                        //
                                        $affected_order = $this->model_order->insert_record($affect_param);

                                        if ($affected_order) {
                                            $affect_param = array();
                                            $affect_param['order_item_status'] = STATUS_ACTIVE;
                                            $affect_param['order_item_order_id'] = $affected_order;
                                            $affect_param['order_item_product_id'] = $productId;
                                            $affect_param['order_item_user_id'] = $this->userid;
                                            $affect_param['order_item_price'] = g('db.admin.technology_listing_subscription_fee');
                                            $affect_param['order_item_subtotal'] = g('db.admin.technology_listing_subscription_fee');
                                            $affect_param['order_item_qty'] = 1;
                                            $affect_param['order_item_qty'] = $interval;
                                            $affect_param['order_item_qty_interval'] = $affectProduct['product_subscription_interval_type'];
                                            //
                                            $this->model_order_item->insert_record($affect_param);
                                        }
                                        // saving to log for webhook differentiaition
                                        if (!$this->saveStripeLog($this->userid, STRIPE_LOG_REFERENCE_TECHNOLOGY, (int) $productId, STRIPE_LOG_RESOURCE_TYPE['subscriptions'], $subscription->id, str_replace('Stripe\Subscription JSON:', '', (string) $subscription))) {
                                            log_message('ERROR', 'Unable to generate log');
                                        }
                                    }

                                    if (!$error) {

                                        try {
                                            if ($type == UPDATE) {
                                                // shoot notification to all followers of the product
                                                $follower = $this->model_signup_follow->getFollower((int) $productId, 0, 9999, FOLLOW_REFERENCE_PRODUCT);
                                                foreach ($follower as $argv) {
                                                    $this->model_notification->sendNotification($argv['signup_id'], $this->userid, NOTIFICATION_FOLLOW_PRODUCT_UPDATE, $productId, NOTIFICATION_FOLLOW_PRODUCT_UPDATE_COMMENT, '', '');
                                                }
                                                $notification_comment = str_replace('{item}', $product_reference_type, NOTIFICATION_PRODUCT_UPDATED_COMMENT);
                                                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PRODUCT_UPDATED, 0, $notification_comment);
                                            } else if ($type == INSERT) {
                                                $notification_comment = str_replace('{item}', $product_reference_type, NOTIFICATION_PRODUCT_ADDED_COMMENT);
                                                $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_PRODUCT_ADDED, 0, $notification_comment);
                                            }
                                        } catch (\Exception $e) {
                                            log_message('ERROR', $e->getMessage());
                                            //
                                            $this->_log_message(
                                                LOG_TYPE_GENERAL,
                                                LOG_SOURCE_SERVER,
                                                LOG_LEVEL_ERROR,
                                                $e->getMessage(),
                                                ''
                                            );
                                        }

                                        $json_param['status'] = STATUS_TRUE;
                                        $json_param['type'] = $type;
                                        $json_param['slug'] = $affectProduct['product_slug'];
                                        $json_param['txt'] = $successMessage . ($somError ? ' with an error.' : ' successfully.');
                                    } else {
                                        $json_param['status'] = STATUS_FALSE;
                                        $json_param['txt'] = $errorMessage;
                                    }
                                } else {
                                    $json_param['txt'] = $errorMessage;
                                }
                            } else {
                                $json_param['txt'] = __("Enter a unique " . ($product_reference_type) . " name in the name field.");
                            }
                        } else {
                            $json_param['txt'] = $errorMessage;
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['txt'] = __('A valid company contact number is required before adding new product.');
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        // } else {
            // $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        // }
        echo json_encode($json_param);
    }

    /**
     * Method deleteAttachment - delete product attachment
     *
     * @return void
     */
    public function deleteAttachment(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $productId = $_POST['id'];

                $param = array();
                $param['where']['product_id'] = $productId;
                $param['where']['product_signup_id'] = $this->userid;
                $productDetail = $this->model_product->find_one($param);

                if (!empty($productDetail)) {
                    $affect_param = array();
                    $affect_param['product_attachment'] = '';
                    $affect_param['product_attachment_path'] = '';
                    $affected = $this->model_product->update_by_pk($productId, $affect_param);

                    if ($affected) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete
     *
     * @return void
     */
    public function delete(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['id'])) {
                    $product_details = $this->model_product->find_by_pk((int) $_POST['id']);
                    if (!empty($product_details)) {
                        if ($this->userid == $product_details['product_signup_id']) {

                            $updateParam = array();
                            $whereParam = array();
                            $updateParam['product_status'] = STATUS_DELETE;
                            $updateParam['product_isdeleted'] = STATUS_ACTIVE;
                            $updateParam['product_deletedon'] = date("Y-m-d H:i:s");
                            $whereParam['where']['product_id'] = $product_details['product_id'];
                            $updated_product = $this->model_product->update_model($whereParam, $updateParam);

                            if ($updated_product) {
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['txt'] = __('Product deleted successfully!');
                            } else {
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['txt'] = __('Error in deleting requested product.');
                            }
                        } else {
                            $json_param['txt'] = ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE;
                        }
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                    }
                } else {
                    $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                }
            } else {
                $json_param['txt'] = ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE;
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);
    }

    /**
     * Method saveRequest - save service request details
     *
     * @return void
     */
    function saveRequest(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['message'] = ERROR_MESSAGE;
        $file_size_error = FALSE;
        $affected = 0;
        $updated = FALSE;

        $stripe_error = FALSE;
        $connect_error = FALSE;
        $payment_failed = FALSE;
        $resoruce_error = FALSE;
        $request_updated = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST) && isset($_POST['product_request']['product_request_product_id'])) {

                    $product = $this->model_product->find_one_active(
                        array(
                            'where' => array(
                                'product_id' => (int) $_POST['product_request']['product_request_product_id']
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'fb_signup',
                                    'joint' => 'signup.signup_id = product.product_signup_id',
                                    'type' => 'both'
                                )
                            )
                        )
                    );

                    if (!empty($product)) {
                        $affect_param = $_POST['product_request'];

                        $request_exists = array();
                        // can be updated
                        if ($this->userid != $product['product_signup_id'] && !isset($_POST['product_request_id'])) {
                            $request_exists = $this->model_product_request->find_one_active(
                                array(
                                    'where' => array(
                                        'product_request_signup_id' => $affect_param['product_request_signup_id'],
                                        'product_request_product_id' => $affect_param['product_request_product_id'],
                                        'product_request_current_status != ' => REQUEST_COMPLETE
                                    )
                                )
                            );
                        }

                        if (empty($request_exists)) {
                            if (isset($_FILES['product_request_attachment']) && $_FILES['product_request_attachment']['error'] == 0) {
                                if ($_FILES['product_request_attachment']['size'] <= MAX_FILE_SIZE) {
                                    $tmp = $_FILES['product_request_attachment']['tmp_name'];
                                    $ext = pathinfo($_FILES['product_request_attachment']['name'], PATHINFO_EXTENSION);
                                    $name = mt_rand() . '.' . $ext;
                                    $upload_path = 'assets/uploads/product_request/';

                                    if (move_uploaded_file($tmp, $upload_path . $name)) {
                                        $affect_param['product_request_attachment'] = $name;
                                        $affect_param['product_request_attachment_path'] = $upload_path;
                                    }
                                } else {
                                    $file_size_error = TRUE;
                                }
                            }

                            if (!$file_size_error) {
                                if (isset($_POST['product_request_id']) && $_POST['product_request_id']) {
                                    $product_request_detail = $this->model_product_request->find_by_pk($_POST['product_request_id']);
                                    if (!empty($product_request_detail)) {

                                        // only for service where payment is sent to escrow(azaverze) before completion of the service
                                        // charging now, the amount will be transfered when marked completed
                                        if (
                                            $product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE &&
                                            $this->userid == $product_request_detail['product_request_signup_id'] &&
                                            $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_PENDING
                                        ) {
                                            if (isset($_POST['stripeToken']) && $_POST['stripeToken']) {
                                                if($product['signup_is_stripe_connected']) {

                                                    $order_affect_param = array();
                                                    $order_affect_param['order_user_id'] = $this->userid;
                                                    $order_affect_param['order_firstname'] = $this->user_data['signup_firstname'];
                                                    $order_affect_param['order_lastname'] = $this->user_data['signup_lastname'];
                                                    $order_affect_param['order_email'] = $this->user_data['signup_email'];
                                                    $order_affect_param['order_phone'] = $this->user_data['signup_phone'];
                                                    $order_affect_param['order_address1'] = $this->user_data['signup_address'];
                                                    $order_affect_param['order_country'] = $this->user_data['signup_country'];
                                                    $order_affect_param['order_state'] = $this->user_data['signup_state'];
                                                    $order_affect_param['order_city'] = $this->user_data['signup_city'];
                                                    $order_affect_param['order_zip'] = $this->user_data['signup_zip'];

                                                    $order_affect_param['order_is_shipment_address'] = STATUS_ACTIVE;
                                                    $order_affect_param['order_quantity'] = 1;
                                                    $order_affect_param['order_status'] = STATUS_INACTIVE;
                                                    $order_affect_param['order_payment_status'] = STATUS_INACTIVE;
                                                    $order_affect_param['order_status_message'] = "Pending";
                                                    $order_affect_param['order_payment_comments'] = "Unpaid";
                                                    $order_affect_param['order_shipment_price'] = 0;
                                                    $order_affect_param['order_currency'] = DEFAULT_CURRENCY_CODE;
                                                    $order_affect_param['order_reference_id'] = $product_request_detail['product_request_product_id'];
                                                    $order_affect_param['order_reference_type'] = ORDER_REFERENCE_SERVICE;

                                                    // amount consented by both sides
                                                    $order_affect_param['order_amount'] = $product_request_detail['product_request_proposed_fee'];
                                                    $order_affect_param['order_fee'] = ($product_request_detail['product_request_proposed_fee'] * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0));
                                                    $order_affect_param['order_shipping'] = 0;
                                                    $order_affect_param['order_tax'] = 0;
                                                    $order_affect_param['order_total'] = $product_request_detail['product_request_proposed_fee'] + ($product_request_detail['product_request_proposed_fee'] * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0));
                                                    $order_id = $this->model_order->insert_record($order_affect_param);

                                                    $insert_item = array();
                                                    $insert_item['order_item_status'] = STATUS_ACTIVE;
                                                    $insert_item['order_item_order_id'] = $order_id;
                                                    $insert_item['order_item_user_id'] = $this->userid;
                                                    $insert_item['order_item_product_id'] = $product['product_id'];
                                                    $insert_item['order_item_product_request_id'] = $product_request_detail['product_request_id'];

                                                    $insert_item['order_item_price'] = $product_request_detail['product_request_proposed_fee'];
                                                    $insert_item['order_item_subtotal'] = $product_request_detail['product_request_proposed_fee'];
                                                    $insert_item['order_item_qty'] = 1;
                                                    $insert_item['order_item_option'] = serialize([
                                                        'url' => l("dashboard/product/detail/" . $product['product_slug']),
                                                        'number' => $product['product_number'],
                                                        'attachment' => Links::img($product['product_attachment_path'], $product['product_attachment']),
                                                        'description' => $product['product_function'],
                                                        'industry' => $product['product_industry'],
                                                        'category' => $product['product_category'],
                                                        'type' => $product['product_reference_type'],
                                                        'request_id' => $product_request_detail['product_request_id'],
                                                    ]);
                                                    $insert_item['order_item_payment_due'] = $insert_item['order_item_subtotal'];
                                                    $order_item_id = $this->model_order_item->insert_record($insert_item);

                                                    $token = $_POST['stripeToken'];
                                                    $total = intval($order_affect_param['order_total']) * 100;

                                                    $charge = '';
                                                    $stripe_error = FALSE;

                                                    try {
                                                        // charge now transfer on completion of the request
                                                        $charge = $this->createStripeResource(
                                                            'charges',
                                                            [
                                                                "amount" => $total,
                                                                "currency" => DEFAULT_CURRENCY_CODE,
                                                                "card" => $token,
                                                                "description" => "Stripe charge for order id: " . $order_id
                                                            ]
                                                        );
                                                    } catch (\Exception $e) {
                                                        $json_param['message'] = $e->getMessage();
                                                        $stripe_error = TRUE;
                                                    }

                                                    if(!$stripe_error) {
                                                        $charge_id = $charge->id;
                                                        $receipt_url = $charge->receipt_url;
                                                        $status = $charge->status;
                                                        $charge = str_replace('Stripe\Charge JSON:', '', (string) $charge);
                                                        // $response = json_decode($charge, true);
                                                        if ($status == 'succeeded') {
                                                            $order_payment_status = TRUE;
                                                        }
                                                    }

                                                    if ($order_payment_status) {

                                                        // update order
                                                        $order_affect_param = array();
                                                        $order_affect_param['order_payment_status'] = STATUS_TRUE;
                                                        $order_affect_param['order_status'] = STATUS_TRUE;
                                                        $order_affect_param['order_payment_comments'] = 'Completed';
                                                        $order_affect_param['order_merchant'] = STRIPE;
                                                        $order_affect_param['order_stripe_response'] = $charge;
                                                        $order_affect_param['order_stripe_charge_id'] = $charge_id;
                                                        $affected = $this->model_order->update_by_pk($order_id, $order_affect_param);

                                                        // set payment status to in escrow
                                                        $affect_param['product_request_payment_status'] = PAYMENT_STATUS_ESCROW;
                                                        $affect_param['product_request_order_item_id'] = $order_item_id;

                                                        // mailing charge receipt
                                                        if ($receipt_url && $this->user_data['signup_email'] && ENVIRONMENT != 'development') {
                                                            try {
                                                                // Generate Body of Email
                                                                $stripeReceipt_url = file_get_contents($receipt_url);
                                                                $matches = array();
                                                                if($stripeReceipt_url) {
                                                                    preg_match("/<body[^>]*>(.*?)<\/body>/is", $stripeReceipt_url, $matches);
                                                                }
                                                                $to = $this->user_data['signup_email'];
                                                                if(!empty($matches)) {
                                                                    $this->model_email->notification_order_charge_receipt($to, $matches[1], $config['site_name'] . ' Order Payment Receipt');
                                                                }
                                                            } catch (\Exception $e) {
                                                                log_message('ERROR', $e->getMessage());
                                                                //
                                                                $this->_log_message(
                                                                    LOG_TYPE_GENERAL,
                                                                    LOG_SOURCE_SERVER,
                                                                    LOG_LEVEL_ERROR,
                                                                    $e->getMessage(),
                                                                    ''
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        $payment_failed = TRUE;
                                                    }
                                                } else {
                                                    $connect_error = TRUE;
                                                }
                                            } else {
                                                $stripe_error = TRUE;
                                            }
                                        }

                                        // when in escrow and the requestor mark the service complete
                                        if (
                                            $product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE &&
                                            $this->userid == $product_request_detail['product_request_signup_id'] &&
                                            $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_ESCROW &&
                                            $affect_param['product_request_current_status'] == REQUEST_COMPLETE
                                        ) {
                                            // transfer
                                            $order_items = $this->model_order_item->find_all_active(
                                                array(
                                                    'where' => array(
                                                        'order_item_id' => $product_request_detail['product_request_order_item_id'],
                                                        'order_item_user_id' => $this->userid,
                                                        'order_item_product_id' => $product['product_id'],
                                                        'order_item_product_request_id' => $product_request_detail['product_request_id'],
                                                        'order_item_stripe_transfer_status' => TRANSFER_UNPAID,
                                                    ),
                                                    'joins' => array(
                                                        0 => array(
                                                            'table' => 'product',
                                                            'joint' => 'product.product_id = order_item.order_item_product_id',
                                                            'type' => 'both'
                                                        ),
                                                        1 => array(
                                                            'table' => 'signup',
                                                            'joint' => 'signup.signup_id = product.product_signup_id',
                                                            'type' => 'both'
                                                        ),
                                                        2 => array(
                                                            'table' => 'order',
                                                            'joint' => 'order.order_id = order_item.order_item_order_id',
                                                            'type' => 'both'
                                                        )
                                                    )
                                                )
                                            );

                                            if($order_items) {

                                                foreach ($order_items as $order_item) {
                                                    try {
                                                        $transfer_data = $this->createStripeResource('transfers', [
                                                            "amount" => $order_item['order_item_payment_due'] * 100,
                                                            "currency" => DEFAULT_CURRENCY_CODE,
                                                            "destination" => $order_item['signup_account_id'],
                                                            "source_transaction" => $order_item['order_stripe_charge_id'],
                                                        ]);

                                                        $updated = $this->model_order_item->update_by_pk(
                                                            $order_item['order_item_id'],
                                                            array(
                                                                'order_item_stripe_transfer_status' => STATUS_ACTIVE,
                                                                'order_item_stripe_transfer_response' => str_replace('Stripe\Transfer JSON:', '', (string) $transfer_data)
                                                            )
                                                        );

                                                        if ($updated) {
                                                            log_message('ERROR', 'items updated');

                                                            $request_updated = $this->model_product_request->update_model(
                                                                array(
                                                                    'where' => array(
                                                                        'product_request_id' => $product_request_detail['product_request_id'],
                                                                        'product_request_product_id' => $order_item['order_item_product_id'],
                                                                        'product_request_signup_id' => $this->userid
                                                                    )
                                                                ),
                                                                array(
                                                                    'product_request_current_status' => REQUEST_COMPLETE,
                                                                    'product_request_payment_status' => PAYMENT_STATUS_COMPLETED
                                                                )
                                                            );
                                                            if ($request_updated) {
                                                                log_message('ERROR', 'request updated');
                                                            }
                                                        }
                                                    } catch (\Exception $e) {
                                                        $error = TRUE;
                                                        $json_param['message'] = $e->getMessage();
                                                    }
                                                }
                                            } else {
                                                $resoruce_error = TRUE;
                                            }
                                        }

                                        if(!$connect_error && !$stripe_error) {
                                            if(!$request_updated) {
                                                $affected = $this->model_product_request->update_by_pk(
                                                    $_POST['product_request_id'],
                                                    $affect_param
                                                );
                                            } else {
                                                $affected = 1;
                                            }
                                            $updated = TRUE;
                                            $requestor_id = $product_request_detail['product_request_signup_id'];
                                            $product_request_id = $product_request_detail['product_request_id'];
                                        }
                                    } else {
                                        // product request not found
                                        $resoruce_error = TRUE;
                                    }
                                } else {
                                    $affected = $this->model_product_request->insert_record($affect_param);
                                    $requestor_id = $affect_param['product_request_signup_id'];
                                    $product_request_id = $affected;
                                }

                                if ($affected) {

                                    if ($updated) {
                                        $comment = str_replace('{item}', $product['product_reference_type'], NOTIFICATION_PRODUCT_RESPONSE_COMMENT);
                                        if ($requestor_id != $this->userid) {
                                            $this->model_notification->sendNotification($requestor_id, $product['signup_id'], NOTIFICATION_PRODUCT_RESPONSE, $product['product_id'], $comment, '', $product_request_id);
                                        } else {
                                            $this->model_notification->sendNotification($product['signup_id'], $requestor_id, NOTIFICATION_PRODUCT_RESPONSE, $product['product_id'], $comment, '', $product_request_id);
                                        }
                                    } else {
                                        $comment = str_replace('{item}', $product['product_reference_type'], NOTIFICATION_PRODUCT_REQUEST_COMMENT);
                                        $this->model_notification->sendNotification($product['signup_id'], $requestor_id, NOTIFICATION_PRODUCT_REQUEST, $product['product_id'], $comment, '', $product_request_id);
                                    }

                                    $json_param['status'] = STATUS_TRUE;
                                    $json_param['message'] = SUCCESS_MESSAGE;
                                } else {
                                    switch(true) {
                                        case $connect_error:
                                            $json_param['message'] = ERROR_MESSAGE_STRIPE_CONNECT_OWNER_ERROR;
                                            break;
                                        case $stripe_error:
                                            $json_param['message'] = ERROR_MESSAGE_STRIPE_ERROR;
                                            break;
                                        case $resoruce_error:
                                            $json_param['message'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                                            break;
                                        default:
                                            $json_param['message'] = ERROR_MESSAGE;
                                            break;
                                    }
                                }
                            } else {
                                $json_param['message'] = ERROR_MESSAGE_FILE_EXCEED_LIMIT;
                            }
                        } else {
                            $json_param['message'] = 'A request has already been sent.';
                        }
                    } else {
                        $json_param['message'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                    }
                } else {
                    $json_param['message'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                }
            } else {
                $json_param['message'] = ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE;
            }
        } else {
            $json_param['message'] = ERROR_MESSAGE_LINK_EXPIRED;
        }

        echo json_encode($json_param);
    }

    /**
     * Method createOrder - PayPal
     *
     * @return void
     */
    function createOrder() : void
    {
        $json_param['status'] = FALSE;
        $json_param['order_id'] = 0;
        $json_param['response'] = '';
        $json_param['message'] = 'An error occurred while trying to process your request.';
        $response = NULL;
        $error = FALSE;
        $order = array();
        $purchase_units = array();
        $affected = 0;
        $updated = FALSE;

        $postArray = $_POST; //json_decode(file_get_contents('php://input'), true);

        if (isset($postArray['_token']) && $this->verify_csrf_token($postArray['_token'])) {
            if (isset($postArray['product_request_id']) && $postArray['product_request_id']) {
                //
                $product_request_id = $postArray['product_request_id'];
                //
                $product_request_detail = $this->model_product_request->find_one(
                    array(
                        'where' => array(
                            'product_request_id' => (int) $product_request_id,
                            'product_request_signup_id' => $this->userid,
                        )
                    )
                );

                //
                $requestor_id = $product_request_detail['product_request_signup_id'];

                if (!empty($product_request_detail)) {

                    $product = $this->model_product->find_one_active(
                        array(
                            'where' => array(
                                'product_id' => $product_request_detail['product_request_product_id']
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'fb_signup',
                                    'joint' => 'signup.signup_id = product.product_signup_id',
                                    'type' => 'both'
                                ),
                                1 => array(
                                    'table' => 'fb_signup_company',
                                    'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
                                    'type' => 'left'
                                )
                            )
                        )
                    );

                    if (!empty($product)) {

                        $affect_param = isset($_POST['product_request']) ? $_POST['product_request'] : [];

                        if($product['signup_paypal_email']) {

                            if (
                                $product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE &&
                                $this->userid == $product_request_detail['product_request_signup_id'] &&
                                $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_PENDING
                            ) {

                                $total = $product_request_detail['product_request_proposed_fee'] + ($product_request_detail['product_request_proposed_fee'] * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0));

                                $purchase_units[] = array(
                                    "amount" => array(
                                        "currency_code" => DEFAULT_CURRENCY_CODE,
                                        "value" => $total
                                    ),
                                    "payee" => array(
                                        'email_address' => $product['signup_paypal_email']
                                    ),
                                );

                                try {
                                    // authorize only

                                    $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL;
                                    $headers = array();
                                    $headers[] = 'Content-Type: application/json';
                                    $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                                    $body = array(
                                        "intent" => "AUTHORIZE",
                                        "purchase_units" => $purchase_units
                                    );

                                    //
                                    $response = $this->curlRequest($url, $headers, $body, TRUE);
                                    $decoded_response = json_decode($response);

                                    if(property_exists($decoded_response, 'message')) {
                                        $json_param['message'] = $decoded_response->message;
                                    } else {

                                        $order_affect_param = array();
                                        $order_affect_param['order_user_id'] = $this->userid;
                                        $order_affect_param['order_firstname'] = $this->user_data['signup_firstname'];
                                        $order_affect_param['order_lastname'] = $this->user_data['signup_lastname'];
                                        $order_affect_param['order_email'] = $this->user_data['signup_email'];
                                        $order_affect_param['order_phone'] = $this->user_data['signup_phone'];
                                        $order_affect_param['order_address1'] = $this->user_data['signup_address'];
                                        $order_affect_param['order_country'] = $this->user_data['signup_country'];
                                        $order_affect_param['order_state'] = $this->user_data['signup_state'];
                                        $order_affect_param['order_city'] = $this->user_data['signup_city'];
                                        $order_affect_param['order_zip'] = $this->user_data['signup_zip'];

                                        $order_affect_param['order_is_shipment_address'] = STATUS_ACTIVE;
                                        $order_affect_param['order_quantity'] = 1;
                                        $order_affect_param['order_status'] = STATUS_INACTIVE;
                                        $order_affect_param['order_payment_status'] = STATUS_INACTIVE;
                                        $order_affect_param['order_status_message'] = "Pending";
                                        $order_affect_param['order_payment_comments'] = "Unpaid";
                                        $order_affect_param['order_shipment_price'] = 0;
                                        $order_affect_param['order_currency'] = DEFAULT_CURRENCY_CODE;
                                        $order_affect_param['order_reference_type'] = ORDER_REFERENCE_PRODUCT;

                                        // amount consented by both sides
                                        $order_affect_param['order_amount'] = $product_request_detail['product_request_proposed_fee'];
                                        $order_affect_param['order_fee'] = ($product_request_detail['product_request_proposed_fee'] * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0));
                                        $order_affect_param['order_shipping'] = 0;
                                        $order_affect_param['order_tax'] = 0;
                                        $order_affect_param['order_total'] = $total;
                                        $order_affect_param['order_merchant'] = PAYPAL;
                                        $order_id = $this->model_order->insert_record($order_affect_param);

                                        $insert_item = array();
                                        $insert_item['order_item_status'] = STATUS_ACTIVE;
                                        $insert_item['order_item_order_id'] = $order_id;
                                        $insert_item['order_item_user_id'] = $this->userid;
                                        $insert_item['order_item_product_id'] = $product['product_id'];
                                        $insert_item['order_item_product_request_id'] = $product_request_detail['product_request_id'];

                                        $insert_item['order_item_price'] = $product_request_detail['product_request_proposed_fee'];
                                        $insert_item['order_item_subtotal'] = $product_request_detail['product_request_proposed_fee'];
                                        $insert_item['order_item_qty'] = 1;
                                        $insert_item['order_item_option'] = serialize([
                                            'url' => l("dashboard/product/detail/" . $product['product_slug']),
                                            'number' => $product['product_number'],
                                            'attachment' => Links::img($product['product_attachment_path'], $product['product_attachment']),
                                            'description' => $product['product_function'],
                                            'industry' => $product['product_industry'],
                                            'category' => $product['product_category'],
                                            'type' => $product['product_reference_type'],
                                            'request_id' => $product_request_detail['product_request_id'],
                                        ]);
                                        $insert_item['order_item_payment_due'] = $insert_item['order_item_subtotal'];
                                        $order_item_id = $this->model_order_item->insert_record($insert_item);

                                        $affect_param['product_request_order_item_id'] = $order_item_id;

                                        $json_param['order_id'] = $order_id;
                                        $json_param['response'] = $decoded_response;
                                    }
                                } catch(\Exception $e) {
                                    $json_param['message'] = $e->getMessage();
                                    log_message('ERROR', $e->getMessage());
                                    $error = TRUE;
                                }

                                if(!$error) {
                                    $affected = $this->model_product_request->update_by_pk(
                                        $_POST['product_request_id'],
                                        $affect_param
                                    );
                                    $updated = TRUE;
                                    $product_request_id = $product_request_detail['product_request_id'];
                                }
                            }

                            if ($affected) {
                                if ($updated) {
                                    $comment = str_replace('{item}', $product['product_reference_type'], NOTIFICATION_PRODUCT_RESPONSE_COMMENT);
                                    if ($requestor_id != $this->userid) {
                                        $this->model_notification->sendNotification($requestor_id, $product['signup_id'], NOTIFICATION_PRODUCT_RESPONSE, $product['product_id'], $comment, '', $product_request_id);
                                    } else {
                                        $this->model_notification->sendNotification($product['signup_id'], $requestor_id, NOTIFICATION_PRODUCT_RESPONSE, $product['product_id'], $comment, '', $product_request_id);
                                    }
                                } else {
                                    $comment = str_replace('{item}', $product['product_reference_type'], NOTIFICATION_PRODUCT_REQUEST_COMMENT);
                                    $this->model_notification->sendNotification($product['signup_id'], $requestor_id, NOTIFICATION_PRODUCT_REQUEST, $product['product_id'], $comment, '', $product_request_id);
                                }

                                $json_param['status'] = STATUS_TRUE;
                                $json_param['message'] = SUCCESS_MESSAGE;
                            }
                        } else {
                            $json_param['message'] = ERROR_MESSAGE_PAYPAL_CONNECT_OWNER_ERROR;
                        }
                    } else {
                        $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['message'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method saveOrder - PayPal
     *
     * @return void
     */
    function saveOrder() {
        $json_param['status'] = FALSE;
        $json_param['message'] = 'An error occurred while trying to process your request.';

        $error = FALSE;
        $order = array();
        $order_payment_status = FALSE;
        $status = 'PENDING';

        // paypal order id
        $orderID = '';

        // if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['order_id']) && $_POST['order_id']) {
                $order_id = $_POST['order_id'];

                $order = $this->model_order->find_one(
                    array(
                        'where' => array(
                            'order_id' => (int) $order_id,
                            'order_user_id' => $this->userid,
                            'order_merchant' => PAYPAL
                        )
                    )
                );

                if (!empty($order)) {
                    try {
                        $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL . '/' . $_POST['orderID'];
                        $headers = array();
                        $headers[] = 'Content-Type: application/json';
                        $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                        $response = $this->curlRequest($url, $headers);
                        $decoded_response = json_decode($response);
                        if(property_exists($decoded_response, 'message')) {
                            $json_param['message'] = $decoded_response->message;
                        } else {
                            $orderID = $decoded_response->id;
                        }

                        if($orderID) {
                            $affect_param = array();
                            $affect_param['order_payment_status'] = PAYMENT_STATUS_ESCROW;
                            $affect_param['order_paypal_response'] = serialize($_POST);
                            $affect_param['order_paypal_order_id'] = $orderID;
                            $affected = $this->model_order->update_by_pk($order_id, $affect_param);

                            if($affected) {

                                $order_item = $this->model_order_item->find_one_active(
                                    array(
                                        'where'=> array(
                                            'order_item_order_id' => $order_id
                                        )
                                    )
                                );

                                // set payment status to in escrow
                                $updated = $this->model_product_request->update_model(
                                    array(
                                        'where' => array(
                                            'product_request_id' => $order_item['order_item_product_request_id'],
                                            'product_request_signup_id' => $this->userid
                                        )
                                    ),
                                    array(
                                        'product_request_payment_status' => PAYMENT_STATUS_ESCROW
                                    )
                                );

                                if($updated) {
                                    //
                                    $json_param['status'] = STATUS_TRUE;
                                    $json_param['message'] = SUCCESS_MESSAGE;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $json_param['message'] = $e->getMessage();
                        $error = TRUE;
                    }
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        // } else {
        //     $json_param['message'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        // }

        echo json_encode($json_param);
    }

    /**
     * Method authorizeOrder - PayPal
     *
     * @return void
     */
    function authorizeOrder() {
        $json_param['status'] = FALSE;
        $json_param['message'] = 'An error occurred while trying to process your request.';

        $error = FALSE;
        $order = array();
        $order_payment_status = FALSE;
        $status = 'PENDING';
        $affected = 0;

        // paypal order id
        $orderID = '';

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_POST['order_id']) && $_POST['order_id']) {

                $product_request_detail = $this->model_product_request->find_by_pk($_POST['product_request_id']);

                if(!empty($product_request_detail)) {

                    $order_id = $_POST['order_id'];

                    $order = $this->model_order->find_one(
                        array(
                            'where' => array(
                                'order_id' => (int) $order_id,
                                'order_user_id' => $this->userid,
                                'order_merchant' => PAYPAL
                            )
                        )
                    );

                    if(!empty($order)) {
                        $product = $this->model_product->find_one_active(
                            array(
                                'where' => array(
                                    'product_id' => (int) $_POST['product_request']['product_request_product_id']
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'fb_signup',
                                        'joint' => 'signup.signup_id = product.product_signup_id',
                                        'type' => 'both'
                                    )
                                )
                            )
                        );

                        if (!empty($order)) {
                            $affect_param = $_POST['product_request'];

                            try {
                                $orderID = $order['order_paypal_order_id'];

                                if (
                                    $product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE &&
                                    $this->userid == $product_request_detail['product_request_signup_id'] &&
                                    $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_ESCROW &&
                                    $affect_param['product_request_current_status'] == REQUEST_COMPLETE
                                ) {

                                    // https://developer.paypal.com/docs/checkout/standard/customize/authorization/

                                    // fetch
                                    // curl -v -X GET https://api-m.sandbox.paypal.com/v2/checkout/orders/48S239579N169645 \
                                    //   -H "Content-Type: application/json" \
                                    //   -H "Authorization: Bearer ACCESS-TOKEN" \

                                    $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL . '/' . $orderID;
                                    $headers = array();
                                    $headers[] = 'Content-Type: application/json';
                                    $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                                    $response = $this->curlRequest($url, $headers);
                                    $decoded_response = json_decode($response);
                                    if(property_exists($decoded_response, 'message')) {
                                        $json_param['message'] = $decoded_response->message;
                                    } else {
                                        $orderID = $decoded_response->id;
                                    }

                                    if($orderID) {
                                        // capture
                                        // curl -v -X POST https://api-m.sandbox.paypal.com/v2/payments/authorizations/66P728836U784324A/capture \
                                        //   -H "Content-Type: application/json" \
                                        //   -H "Authorization: Bearer ACCESS-TOKEN" \
                                        //   -H "PayPal-Request-Id: PAYPAL-REQUEST-ID" \

                                        $captureUrl = str_replace('{orderId}', $orderID, PAYPAL_AUTHORIZATION_CAPTURE_URL);
                                        $url = PAYPAL_URL . $captureUrl;

                                        $headers = array();
                                        $headers[] = 'Content-Type: application/json';
                                        $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

                                        $purchase_units[] = array(
                                            "payment_instruction" => array(
                                                "platform_fees" => array(
                                                    0 => array(
                                                        "amount" => array(
                                                            "currency_code" => DEFAULT_CURRENCY_CODE,
                                                            "value" => (intval($product_request_detail['product_request_proposed_fee'] * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0)))
                                                        )
                                                    )
                                                )
                                            )
                                        );

                                        $body = array(
                                            "purchase_units" => $purchase_units
                                        );

                                        $response = $this->curlRequest($url, $headers, $body, TRUE);

                                        $decoded_response = json_decode($response);
                                        if(property_exists($decoded_response, 'message')) {
                                            $json_param['message'] = $decoded_response->message;
                                        } else {
                                            $orderID = $decoded_response->id;
                                            $status = $decoded_response->status;

                                            if ($status == 'COMPLETED') {
                                                $affect_param = array();
                                                $affect_param['order_payment_status'] = STATUS_TRUE;
                                                $affect_param['order_status'] = STATUS_TRUE;
                                                $affect_param['order_payment_comments'] = 'Completed';
                                                $affected = $this->model_order->update_by_pk($order_id, $affect_param);
                                            }
                                        }
                                    } else {
                                        $error = TRUE;
                                    }
                                } else {
                                    $affected = 1;
                                }
                            } catch (\Exception $e) {
                                $json_param['message'] = $e->getMessage();
                                $error = TRUE;
                            }

                            if (!$error && $affected) {
                                // '_token': $('meta[name=csrf-token]').attr("content"),
                                // 'order_id': $('input[name=order_id]').val(),
                                // 'orderID': data.orderID,
                                // 'payerID': data.payerID,
                                // 'paymentID': data.paymentID,
                                // 'facilitatorAccessToken': data.facilitatorAccessToken,

                                // fire invoice email

                                $affect_param = $_POST['product_request'];
                                $updated = $this->model_product_request->update_by_pk(
                                    $_POST['product_request_id'],
                                    $affect_param
                                );
                                $requestor_id = $product_request_detail['product_request_signup_id'];
                                $product_request_id = $product_request_detail['product_request_id'];

                                if ($updated) {
                                    $comment = str_replace('{item}', $product['product_reference_type'], NOTIFICATION_PRODUCT_RESPONSE_COMMENT);
                                    if ($requestor_id != $this->userid) {
                                        $this->model_notification->sendNotification($requestor_id, $product['signup_id'], NOTIFICATION_PRODUCT_RESPONSE, $product['product_id'], $comment, '', $product_request_id);
                                    } else {
                                        $this->model_notification->sendNotification($product['signup_id'], $requestor_id, NOTIFICATION_PRODUCT_RESPONSE, $product['product_id'], $comment, '', $product_request_id);
                                    }
                                } else {
                                    $comment = str_replace('{item}', $product['product_reference_type'], NOTIFICATION_PRODUCT_REQUEST_COMMENT);
                                    $this->model_notification->sendNotification($product['signup_id'], $requestor_id, NOTIFICATION_PRODUCT_REQUEST, $product['product_id'], $comment, '', $product_request_id);
                                }

                                //
                                $json_param['status'] = STATUS_TRUE;
                                $json_param['message'] = SUCCESS_MESSAGE;

                                // captured on response save order and redirect to success page
                                // $json_param['redirect_url'] = l('dashboard/order/result/' . JWT::encode($order_id));
                            }
                        } else {
                            $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                        }
                    } else {
                        $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['message'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['message'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method addCart - for technology and product only
     *
     * @return void
     */
    public function addCart()
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $request_error = FALSE;
        $product_exists_error = FALSE;
        $owner_error = FALSE;
        $inserted = 0;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['product_id'])) {

                    $product = $this->model_product->find_by_pk((int) $_POST['product_id']);

                    if (!empty($product)) {

                        if ($product['product_quantity'] >= $_POST['product_quantity']) {

                            $product_cost = $product['product_cost'];
                            $product_request = array();
                            $product_request_id = 0;

                            // not used
                            if (isset($product['product_reference_type']) && $product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE) {
                                $product_request = $this->model_product_request->find_one_active(
                                    array(
                                        'where' => array(
                                            'product_request_product_id' => $product['product_id'],
                                            'product_request_signup_id' => $this->userid,
                                        ),
                                        'where_not_in' => array(
                                            'product_request_current_status' => [REQUEST_PENDING, REQUEST_REJECTED, REQUEST_INCOMPLETE, REQUEST_COMPLETE]
                                        )
                                    )
                                );

                                if (empty($product_request)) {
                                    $request_error = TRUE;
                                } else {
                                    if (!$this->model_product->isProductInCart((int) $product['product_id'])) {
                                        // for product type service
                                        $product_cost = $product_request['product_request_proposed_fee'];
                                        $product_request_id = $product_request['product_request_id'];
                                    } else {
                                        $product_exists_error = TRUE;
                                    }
                                }
                            }

                            if (!$request_error && !$product_exists_error) {

                                $sameProductOwnerInCart = $this->model_product->sameProductOwnerInCart((int) $product['product_id']);
                                // dd($this->cart->contents());
                                // dd($sameProductOwnerInCart);
                                if (!$sameProductOwnerInCart) {
                                    $owner_error = TRUE;
                                    $json_param['txt'] = __(ERROR_MESSAGE_PRODUCT_DIFFERNT_OWNER);
                                }

                                if(!$owner_error) {

                                    $cartFirstContent = cartFirstContent($this->cart->contents());

                                    $cart_reference_type =  $cartFirstContent ? $cartFirstContent['options']['type'] : $product['product_reference_type'];

                                    if($cart_reference_type == $product['product_reference_type']) {

                                        $data = array(
                                            'id'      => $product['product_id'],
                                            'qty'     => (isset($_POST['product_quantity']) && !empty($_POST['product_quantity']) ? $_POST['product_quantity'] : 1),
                                            'price'   => $product_cost,
                                            'name'    => htmlentities($product['product_name']),
                                            'options' => array(
                                                'owner' => $product['product_signup_id'],
                                                'url' => l("dashboard/product/detail/" . $product['product_slug']),
                                                'number' => $product['product_number'],
                                                'attachment' => Links::img($product['product_attachment_path'], $product['product_attachment']),
                                                'description' => $product['product_function'],
                                                'industry' => $product['product_industry'],
                                                'category' => $product['product_category'],
                                                'type' => $product['product_reference_type'],
                                                'request_id' => $product_request_id,
                                            )
                                        );
                                        
                                        $this->cart->product_name_rules = '[:print:]';
                                        $inserted = $this->cart->insert($data);

                                        if ($inserted) {
                                            if ($product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE) {
                                                $json_param['redirect'] = STATUS_TRUE;
                                            } else {
                                                $json_param['redirect'] = STATUS_FALSE;
                                            }
                                            $json_param['status'] = STATUS_TRUE;
                                            $json_param['txt'] = __(SUCCESS_MESSAGE);
                                        } else {
                                            $json_param['txt'] = __(ERROR_MESSAGE_CART_INSERT);
                                        }
                                    } else {
                                        $json_param['txt'] = __(ERROR_MESSAGE_CART_INSERT_TYPE);
                                    }
                                }
                            } else {
                                switch (TRUE) {
                                    case $request_error:
                                        $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                                        break;

                                    case $product_exists_error:
                                        $json_param['txt'] = __(ERROR_MESSAGE_PRODUCT_EXISTS);
                                        break;
                                }
                            }
                        } else {
                            $json_param['txt'] = __('The requested quantity is unavailable.');
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method updateCartItems
     *
     * @return void
     */
    public function updateCartItems()
    {
        $input = $_POST;
        $json_param['status'] = STATUS_FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (count($input) > 0) {
                $updated = FALSE;
                $with_quantity_error = FALSE;

                if (array_filled($input)) {
                    $data = array();
                    if (is_array($input['rowid'])) {
                        for ($i = 0; $i < count($input['rowid']); $i++) {
                            $product_detail = $this->model_product->find_by_pk($input['id'][$i]);
                            if ($product_detail['product_quantity'] >= $input['qty'][$i]) {
                                $data['rowid'] = $input['rowid'][$i];
                                $data['qty'] = $input['qty'][$i];
                                $updated = $this->cart->update($data);
                            } else {
                                $with_quantity_error = TRUE;
                            }
                        }
                    } else {
                        $product_detail = $this->model_product->find_by_pk($input['id']);
                        if ($product_detail['product_quantity'] >= $input['qty']) {
                            $data['rowid'] = $input['rowid'];
                            $data['qty'] = $input['qty'];
                            $updated = $this->cart->update($data);
                        } else {
                            $with_quantity_error = TRUE;
                        }
                    }

                    if ($updated || $with_quantity_error) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = $with_quantity_error ? 'Cart updated with quantity error on one or more product' : SUCCESS_MESSAGE;
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE_UPTODATE;
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method deleteCartItem
     *
     * @return void
     */
    public function deleteCartItem(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['rowid']) && $_POST['rowid']) {

                    $data['rowid'] = $_POST['rowid'];
                    $data['qty'] = 0;
                    $updated = $this->cart->update($data);

                    if ($updated) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = SUCCESS_MESSAGE;
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method clearCart
     *
     * @return void
     */
    public function clearCart()
    {
        $this->cart->destroy();
    }
}
