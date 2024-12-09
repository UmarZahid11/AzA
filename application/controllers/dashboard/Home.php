<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Home
 */
class Home extends MY_Controller
{
    /**
     * dataService
     *
     * @var mixed
     */
    protected $dataService;

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
        // debug($this->user_data, 1);

        $data = array();

        $this->register_plugins("datetime-picker");

        $data['countries'] = $this->model_country->find_all_active();
        $data['signup'] = $this->model_signup->find_by_pk($this->userid);

        $data['testimonial'] = $this->model_testimonial->find_all_active(
            array(
                'limit' => 10,
                'order' => 'testimonial_id DESC'
            )
        );

        $param = array();
        $param['limit'] = 6;
        $param['order'] = 'story_id DESC';
        $data['story'] = $this->model_story->find_all_active($param);

        $webinar_time = $this->model_webinar->webinars();

        $my_meeting_time = $this->model_meeting->meetings(TRUE);

        $requested_meeting_time = $this->model_meeting->meetings();

        $requested_service_meeting_time = $this->model_meeting->meetings(FALSE, MEETING_REFERENCE_PRODUCT);

        $meeting_time = array_merge($requested_meeting_time, $my_meeting_time, $requested_service_meeting_time);

        $availability_slots = array_merge($this->model_signup_availability->userRequestorSlotsCalendar($this->userid), $this->model_signup_availability->userAvailabilitySlotsCalendar($this->userid));

        $data['calendar_events'] = array_merge($webinar_time, $meeting_time);

        $data['calendar_events'] = array_merge($data['calendar_events'], $availability_slots);

        // $data['calendar_events'] = ($availability_slots);

        //
        $this->layout_data['title'] = 'Dashboard | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $data = array();
        $this->load_view("test", $data);
    }

    /**
     * session
     *
     * @return void
     */
    public function session()
    {
        debug($this->session->userdata);
    }

    // STRIPE START

    /**
     * Method stripe
     *
     * @param string $type
     *
     * @return void
     */
    public function stripe(string $type = '')
    {
        if (!$this->userid)
            error_404();

        if (!$type)
            error_404();

        if (!in_array($type, STRIPE_ENTITY_TYPE))
            error_404();

        $data = array();

        $affect_param = array();
        $data['stripe_account'] = NULL;
        $data['stripe_account_links'] = NULL;
        $data['url'] = '';
        $error = FALSE;
        $already_connected_error = FALSE;

        //
        $accountId = '';
        if ($this->user_data['signup_account_response'] && null !== json_decode($this->user_data['signup_account_response'])->id) {
            $accountId = json_decode($this->user_data['signup_account_response'])->id;
            $data['stripe_account'] = $this->user_data['signup_account_response'];
        }

        try {
            switch ($type) {
                case 'accounts':
                    //
                    if (isset($this->user_data['signup_is_stripe_connected'])) {
                        //
                        if (!$this->user_data['signup_is_stripe_connected']) {
                            if (!$accountId) {
                                $data['stripe_account'] = $this->createStripeResource('accounts', [
                                    'type' => 'custom',
                                    'country' => DEFAULT_COUNTRY_CODE,
                                    'email' => $this->user_data['signup_email'],
                                    'capabilities' => [
                                        'card_payments' => ['requested' => true],
                                        'transfers' => ['requested' => true],
                                    ],
                                ]);
                                $accountId = $data['stripe_account']->id;
                                $data['stripe_account'] = $data['stripe_account'] ? str_replace('Stripe\Account JSON:', '', (string) $data['stripe_account']) : '';
                            }
                            //
                            if ($accountId) {
                                $data['stripe_account_links'] = $this->createStripeResource(
                                    'accountLinks',
                                    [
                                        'account' => $accountId,
                                        'refresh_url' => STRIPE_REFRESH_URL,
                                        'return_url' => STRIPE_RETURN_URL,
                                        'type' => STRIPE_ACCOUNT_ONBOARDING,
                                        'collect' => 'eventually_due',
                                    ]
                                );
                            }
                            //
                            $affect_param['signup_account_id'] = $accountId;
                            $affect_param['signup_account_response'] = $data['stripe_account'];
                        } else {
                            if ($accountId) {
                                $data['stripe_account_links'] = $this->createStripeResource(
                                    'accountLinks',
                                    [
                                        'account' => $accountId,
                                        'refresh_url' => STRIPE_REFRESH_URL,
                                        'return_url' => STRIPE_RETURN_URL,
                                        'type' => STRIPE_ACCOUNT_UPDATE,
                                        'collect' => 'eventually_due',
                                    ]
                                );
                            }
                        }
                    }

                    //
                    $data['url'] = $data['stripe_account_links'] && $data['stripe_account_links']->url ? $data['stripe_account_links']->url : '';
                    if (!$data['url']) {
                        $this->session->set_flashdata('error', ERROR_MESSAGE);
                        redirect(l('dashboard'));
                    }
                    break;

                case 'return':
                    //
                    if (isset($this->user_data['signup_is_stripe_connected'])) {
                        //
                        if (!$this->user_data['signup_is_stripe_connected']) {
                            if ($accountId) {
                                $data['stripe_account'] = $this->resource('accounts', $accountId);
                                // updated account details
                                $affect_param['signup_account_response'] = $data['stripe_account'] ? (str_replace('Stripe\Account JSON:', '', (string) $data['stripe_account'])) : '';
                            }

                            if ($data['stripe_account']->charges_enabled) {
                                $affect_param['signup_is_stripe_connected'] = TRUE;
                            } else {
                                $error = TRUE;
                            }
                        } else {
                            $already_connected_error = TRUE;
                        }
                    } else {
                        error_404();
                    }
                    break;
            }

            if ($error) {
                $this->session->set_flashdata('error', 'Stripe connect incomplete.');
                redirect(l('dashboard/home'));
            }

            if ($already_connected_error) {
                $this->session->set_flashdata('error', 'Stripe connect account is already active.');
                redirect(l('dashboard/home'));
            }

            if (!empty($affect_param)) {
                $this->model_signup->update_by_pk($this->userid, $affect_param);
                if ($this->model_signup->find_by_pk($this->userid)['signup_is_stripe_connected']) {
                    // notification here
                    $this->model_notification->sendNotification($this->userid, $this->userid, NOTIFICATION_STRIPE_CONNECTED, 0, NOTIFICATION_STRIPE_CONNECTED_COMMENT);

                    $this->session->set_flashdata('success', SUCCESS_MESSAGE);
                    redirect(l('dashboard/home'));
                }
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
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(l('dashboard'));
        }

        $this->load_view('stripe/' . $type, $data);
    }

    // STRIPE END

    // QUICKBOOK START

    /**
     * Method quickbook
     *
     * @return void
     */
    public function quickbook()
    {
        if ($this->session->has_userdata('quickbook')) {
            if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {

                $data = array();

                //
                $this->layout_data['title'] = 'Quickbooks | ' . $this->layout_data['title'];
                //
                $this->load_view("quickbook/index", $data);
            } elseif (isset($this->session->userdata['quickbook']['refresh_token_expiry']) && $this->session->userdata['quickbook']['refresh_token_expiry'] > date('Y/m/d H:i:s')) {
                $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook'));
                redirect(l('quickbook'));
            } else {
                $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook'));
                redirect(l('quickbook'));
            }
        } else {
            $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook'));
            redirect(l('quickbook'));
        }
    }

    /**
     * Method quickbook_listing
     *
     * @param string $entity
     * @param int $page
     *
     * @return void
     */
    public function quickbook_listing($entity = '', int $page = 0, $customerId = 0)
    {
        if (!$entity) {
            error_404();
        }

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            redirect(l('dashboard'));
        }

        if ($entity && in_array($entity, QUICKBOOK_ENTITY_TYPE)) {

            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    $data = array();

                    $data['customerId'] = $customerId;

                    $data['page'] = $page;
                    $data['prev'] = $page - 1;
                    $data['next'] = $page + 1;

                    $data[$entity] = array();
                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];

                    if (!$customerId) {
                        $$entity = $this->dataService->FindAll($entity, $page * 10, PER_PAGE);
                    } else {
                        if ($entity == 'invoice') {
                            $$entity = $this->dataService->FindWhere($entity, $page * 10, PER_PAGE, "WHERE CustomerRef = '" . $customerId . "'");
                        } else {
                            $$entity = $this->dataService->FindAll($entity, $page * 10, PER_PAGE);
                        }
                    }

                    $error = $this->dataService->getLastError();
                    if ($error) {
                        log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());

                        //
                        $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                        $json = json_encode($xml);
                        $decoded_error = json_decode($json, TRUE);
                        $this->session->set_flashdata('error', $decoded_error['Fault']['Error']['Detail']);
                        //
                        redirect(l('dashboard/home/quickbook'));
                    } else {
                        //
                        $this->layout_data['title'] = 'Quickbook ' . ucfirst($entity) . ' | ' . $this->layout_data['title'];

                        $data['entityArray'] = $$entity;
                        $data['entity'] = $entity;

                        if ($entity == 'invoice') {
                            //
                            $this->load_view("quickbook/" . $entity . "/listing", $data);
                        } else {
                            //
                            $this->load_view("quickbook/listing", $data);
                        }
                    }
                } elseif (isset($this->session->userdata['quickbook']['refresh_token_expiry']) && $this->session->userdata['quickbook']['refresh_token_expiry'] > date('Y/m/d H:i:s')) {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-listing/' . $entity . '/' . $page . ($customerId ? '/' . $customerId : '')));
                    redirect(l('quickbook'));
                } else {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-listing/' . $entity . '/' . $page . ($customerId ? '/' . $customerId : '')));
                    redirect(l('quickbook'));
                }
            } else {
                $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-listing/' . $entity . '/' . $page . ($customerId ? '/' . $customerId : '')));
                redirect(l('quickbook'));
            }
        } else {
            error_404();
        }
    }

    /**
     * Method quickbook_view
     *
     * @param string $entity
     * @param int $id
     * @param boolean $printPdf - view pdf or load html
     *
     * @return void
     */
    public function quickbook_view(string $entity = '', int $id = 0, bool $printPdf = FALSE): void
    {
        if ((!$id || !is_numeric($id)) && $entity != 'cashflow') {
            error_404();
        }

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            redirect(l('dashboard'));
        }

        if ($entity && in_array($entity, QUICKBOOK_ENTITY_TYPE)) {

            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    $data = array();

                    // additional $entity data
                    $data[$entity] = array();
                    $data['entity'] = $entity;

                    switch ($entity) {
                        case 'cashflow':
                            $data['item'] = $this->getEntity('item', 500);
                            $data['class'] = $this->getEntity('class', 500);
                            $data['vendor'] = $this->getEntity('vendor', 500);
                            $data['customer'] = $this->getEntity('customer', 500);
                            $data['department'] = $this->getEntity('department', 500);
                            break;
                    }

                    if ($entity != 'cashflow') {

                        $this->dataService = $this->session->userdata['quickbook']['service_instance'];
                        $$entity = $this->dataService->FindById($entity, $id);

                        $pdfPath = '';
                        if ($printPdf) {
                            $pdfPath = $this->dataService->DownloadPDF($$entity, QUICKBOOK_DOWNLOAD_PATH, FALSE);
                        }
                        $error = $this->dataService->getLastError();

                        if ($error) {
                            log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());
                            //
                            $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                            $json = json_encode($xml);
                            $decoded_error = json_decode($json, TRUE);
                            $this->session->set_flashdata('error', $decoded_error['Fault']['Error']['Detail']);
                            //
                            redirect(l('dashboard/home/quickbook'));
                        } else {
                            if ($pdfPath) {
                                $pdfPath = str_replace(QUICKBOOK_DOWNLOAD_REPLACE_PATH, '', $pdfPath);
                                redirect(l('') . $pdfPath);
                            }

                            $data[$entity] = $$entity;
                            $data['type'] = $entity;
                        }
                    }

                    //
                    $this->layout_data['title'] = 'Quickbook ' . ucfirst($entity) . ' | ' . $this->layout_data['title'];
                    //
                    $this->load_view("quickbook/" . $entity . "/view", $data);
                } elseif (isset($this->session->userdata['quickbook']['refresh_token_expiry']) && $this->session->userdata['quickbook']['refresh_token_expiry'] > date('Y/m/d H:i:s')) {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-view/' . $entity . '/' . $id . ($printPdf ? '/' . $printPdf : '')));
                    redirect(l('quickbook'));
                } else {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-view/' . $entity . '/' . $id . ($printPdf ? '/' . $printPdf : '')));
                    redirect(l('quickbook'));
                }
            } else {
                $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-view/' . $entity . '/' . $id . ($printPdf ? '/' . $printPdf : '')));
                redirect(l('quickbook'));
            }
        } else {
            error_404();
        }
    }

    /**
     * Method quickbook_save
     *
     * @param string $entity
     * @param int $id
     * @param int $referenceId
     *
     * @return void
     */
    public function quickbook_save(string $entity = '', int $id = 0, int $referenceId = 0): void
    {
        if (!$entity) {
            error_404();
        }

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            redirect(l('dashboard'));
        }

        if ($entity && in_array($entity, QUICKBOOK_ENTITY_TYPE)) {

            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    //
                    $this->layout_data['title'] = 'Quickbook ' . ucfirst($entity) . ' | ' . $this->layout_data['title'];

                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];

                    $data = array();

                    switch ($entity) {
                        case 'account':
                            $data['AccountType'] = $this->model_quickbook_account_repo->find_all_active(
                                array(
                                    'where' => array(
                                        'quickbook_account_repo_is_subtype' => 0
                                    )
                                )
                            );
                            break;
                        case 'invoice':
                        case 'estimate':
                            if (!$referenceId) {
                                $this->session->set_flashdata('error', __('Unable to find requested user profile!'));
                                redirect(l('dashboard/home/quickbook-listing/customer'));
                            }
                            $data['companyInfo'] = $this->getEntity('company', 500)[0];
                            $data['customerInfo'] = $this->getEntityById('customer', (int) $referenceId);
                            if (empty($data['customerInfo'])) {
                                $this->session->set_flashdata('error', __('The requested customer doesn\'t exists!'));
                                redirect(l('dashboard/home/quickbook-listing/customer'));
                            }
                            $data['term'] = $this->getEntity('term', 500);
                            $data['items'] = $this->getEntity('item', 500);
                            $data['taxCode'] = $this->getEntity('taxcode', 500);
                            break;
                        case 'class':
                            $data['parentRef'] = $this->getEntity('class', 500);
                            break;
                        case 'department':
                            $data['parentRef'] = $this->getEntity('department', 500);
                            break;
                        case 'item':
                            $data['IncomeAccountRef'] = $this->dataService->query("select * from account WHERE AccountType = 'Income' AND AccountSubType = 'SalesOfProductIncome' ");
                            $data['AssetAccountRef'] = $this->dataService->query("select * from account WHERE AccountType = 'Other Current Asset' ");
                            $data['ExpenseAccountRef'] = $this->dataService->query("select * from account WHERE AccountType = 'Cost of Goods Sold' ");
                            break;
                        case 'bill':
                            $data['VendorRef'] = $this->getEntity('vendor', 500);
                            $data['CurrencyRef'] = $this->getEntity('companycurrency', 500);
                            $data['AccountRef'] = $this->dataService->query("Select * from account Where AccountType = 'Expense' ");
                            $data['ClassRef'] = $this->getEntity('class', 500);
                            $data['CustomerRef'] = $this->getEntity('customer', 500);
                            $data['taxCodeRef'] = $this->getEntity('taxcode', 500);
                            break;
                        case 'billpayment':
                            $data['VendorRef'] = $this->getEntity('vendor', 500);
                            $data['CurrencyRef'] = $this->getEntity('companycurrency', 500);
                            $data['AccountRef'] = $this->getEntity("account", 500);
                            $data['bill'] = $this->getEntity('bill');
                            break;
                        case 'timeactivity':
                            $data['VendorRef'] = $this->getEntity('vendor', 500);
                            $data['EmployeeRef'] = $this->getEntity('employee', 500);
                            break;
                    }

                    $data['entity'] = $entity;
                    if ($id) {

                        // additional $entity data
                        $data[$entity] = array();
                        $this->dataService = $this->session->userdata['quickbook']['service_instance'];
                        $$entity = $this->dataService->FindbyId($entity, $id);

                        $error = $this->dataService->getLastError();
                        if ($error) {
                            log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());
                            //
                            $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                            $json = json_encode($xml);
                            $decoded_error = json_decode($json, TRUE);
                            $this->session->set_flashdata('error', $decoded_error['Fault']['Error']['Detail']);
                            //
                            redirect(l('dashboard/home/quickbook'));
                        } else {
                            $data['title'] = 'edit';
                            $data[$entity] = $$entity;
                        }
                    } else {
                        $data['title'] = 'create';
                    }

                    //
                    if ($entity == 'invoice') {
                        $this->load_view("quickbook/" . $entity . "/create", $data);
                    } else {
                        $this->load_view("quickbook/save", $data);
                    }
                } elseif (isset($this->session->userdata['quickbook']['refresh_token_expiry']) && $this->session->userdata['quickbook']['refresh_token_expiry'] > date('Y/m/d H:i:s')) {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-save/' . $entity . ($id ? '/' . $id : '') . ($referenceId ? '/' . $referenceId : '')));
                    redirect(l('quickbook'));
                } else {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-save/' . $entity . ($id ? '/' . $id : '') . ($referenceId ? '/' . $referenceId : '')));
                    redirect(l('quickbook'));
                }
            } else {
                $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-save/' . $entity . ($id ? '/' . $id : '') . ($referenceId ? '/' . $referenceId : '')));
                redirect(l('quickbook'));
            }
        } else {
            error_404();
        }
    }

    /**
     * Method quickbook_send_email
     *
     * @param string $entity
     * @param int $id
     *
     * @return void
     */
    public function quickbook_send_email(string $entity = '', int $id = 0): void
    {
        if (!$id || !is_numeric($id)) {
            error_404();
        }

        if ($entity && in_array($entity, QUICKBOOK_ENTITY_TYPE)) {

            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    $data = array();

                    // additional $entity data
                    $data[$entity] = array();
                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];

                    $$entity = $this->dataService->FindById($entity, $id);
                    $sendEmail = $this->dataService->SendEmail($$entity);
                    $error = $this->dataService->getLastError();

                    if ($error) {
                        log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());
                        //
                        $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                        $json = json_encode($xml);
                        $decoded_error = json_decode($json, TRUE);
                        $this->session->set_flashdata('error', $decoded_error['Fault']['Error']['Detail']);
                        //
                        redirect(l('dashboard/home/quickbook'));
                    } else {
                        if (isset($sendEmail->EmailStatus) && $sendEmail->EmailStatus == 'EmailSent') {
                            $this->session->set_flashdata('success', 'Email sent successfully');
                            redirect(l('dashboard/home/quickbook-view/' . $entity . '/' . $id));
                        } else {
                            $this->session->set_flashdata('error', __(ERROR_MESSAGE));
                            redirect(l('dashboard/home/quickbook-view/' . $entity . '/' . $id));
                        }
                    }
                } elseif (isset($this->session->userdata['quickbook']['refresh_token_expiry']) && $this->session->userdata['quickbook']['refresh_token_expiry'] > date('Y/m/d H:i:s')) {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-view/' . $entity . '/' . $id));
                    redirect(l('quickbook'));
                } else {
                    $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-view/' . $entity . '/' . $id));
                    redirect(l('quickbook'));
                }
            } else {
                $this->session->set_userdata('quickbook_intended', l('dashboard/home/quickbook-view/' . $entity . '/' . $id));
                redirect(l('quickbook'));
            }
        } else {
            error_404();
        }
    }

    // QUICKBOOK END

    /**
     * Method compose
     *
     * @return void
     */
    public function compose(): void
    {
        $data = array();

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }

        //
        $this->layout_data['title'] = 'Compose | ' . $this->layout_data['title'];
        //
        $this->load_view("compose", $data);
    }

    /**
     * Method chat
     *
     * @return void
     */
    public function chat(): void
    {
        $data = array();

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }

        //
        $this->layout_data['title'] = 'Chat | ' . $this->layout_data['title'];
        //
        $this->load_view("chat", $data);
    }

    /**
     * Method inbox
     *
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function inbox(int $page = 1, int $limit = PER_PAGE): void
    {
        global $config;

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }

        $data = array();

        $data['page'] = $page;
        // $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 9;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        // user is receiver
        $data['message'] = $this->model_chat->getChatById($paginationStart, $limit, array('chat_signup2' => $this->userid), CHAT_REFERENCE_EMAIL);
        $data['message_count'] = $allRecrods = $this->model_chat->getChatById(0, 0, array('chat_signup2' => $this->userid), CHAT_REFERENCE_EMAIL, TRUE);

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        $data['type'] = 'inbox';

        //
        $this->layout_data['title'] = 'Inbox | ' . $this->layout_data['title'];
        //
        $this->load_view("inbox", $data);
    }

    /**
     * Method sent
     *
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function sent(int $page = 1, int $limit = PER_PAGE): void
    {
        global $config;

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }

        $data = array();

        $data['page'] = $page;
        // $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 9;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        // user is sender
        $data['message'] = $this->model_chat->getChatById($paginationStart, $limit, array('chat_signup1' => $this->userid), CHAT_REFERENCE_EMAIL);
        $data['message_count'] = $allRecrods = $this->model_chat->getChatById(0, 0, array('chat_signup1' => $this->userid), CHAT_REFERENCE_EMAIL, TRUE);

        // Calculate total pages
        $data['totalPages'] = ceil($allRecrods / $limit);

        $data['type'] = 'sent';

        //
        $this->layout_data['title'] = 'Sent | ' . $this->layout_data['title'];
        //
        $this->load_view("sent", $data);
    }

    /**
     * Method sent
     *
     * @param int $id
     *
     * @return void
     */
    public function message_details(int $id = 0): void
    {
        global $config;

        if (!($this->model_signup->hasPremiumPermission())) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }

        if (!$id) {
            $this->session->set_flashdata('error', 'Requested message doesn\'t exists!');
            redirect(l('dashboard/home/inbox'));
        } else {

            $data = array();

            $chat = $this->model_chat->find_one_active(
                array(
                    'where' => array(
                        'chat_id' => $id,
                        'chat_reference_type' => CHAT_REFERENCE_EMAIL
                    )
                )
            );

            if (!empty($chat)) {

                $param = array();
                $param['where']['chat_id'] = $id;
                $param['where']['chat_reference_type'] = CHAT_REFERENCE_EMAIL;

                if ($chat['chat_signup1'] == $this->userid) {
                    $param['joins'][] = array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = chat.chat_signup2',
                        'type'  => 'both'
                    );
                } elseif ($chat['chat_signup2'] == $this->userid) {
                    $param['joins'][] = array(
                        'table' => 'signup',
                        'joint' => 'signup.signup_id = chat.chat_signup1',
                        'type'  => 'both'
                    );
                } else {
                    $this->session->set_flashdata('error', __(ERROR_MESSAGE_RESOURCE_NOT_FOUND));
                    redirect(l('dashboard/home/inbox'));
                }

                $data['chat'] = $this->model_chat->find_one_active($param);

                if (!empty($data['chat'])) {

                    $data['chat']['message'] = array();
                    $data['chat']['count'] = 0;

                    $chat_message = $this->model_chat_message->find_all_active(
                        array(
                            'order' => 'chat_message_parent DESC, chat_message_createdon DESC',
                            'where' => array(
                                'chat_message_chat_id' => $id,
                                'chat_reference_type' => CHAT_REFERENCE_EMAIL
                            ),
                            'joins' => array(
                                0 => array(
                                    'table' => 'chat',
                                    'joint' => 'chat.chat_id = chat_message.chat_message_chat_id',
                                    'type'  => 'both'
                                )
                            )
                        )
                    );

                    if (!empty($chat_message)) {
                        $data['chat']['message'] = $chat_message;
                        $data['chat']['count'] = count($chat_message);
                    }
                } else {
                    $this->session->set_flashdata('error', __('Requested message doesn\'t exists!'));
                    redirect(l('dashboard/home/inbox'));
                }
            } else {
                $this->session->set_flashdata('error', __('Requested message doesn\'t exists!'));
                redirect(l('dashboard/home/inbox'));
            }

            //
            $this->layout_data['title'] = 'Message | ' . $this->layout_data['title'];
            //
            $this->load_view("message_details", $data);
        }
    }

    /**
     * Method switch_language - language switcher
     *
     * @param string $language
     *
     * @return void
     */
    function switch_language(string $language = ""): void
    {
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        $this->session->set_userdata(
            'site_lang_code',
            $this->model_language->find_one_active(
                array(
                    'where' => array(
                        'language_value' => $language,
                    )
                )
            )['language_code']
        );

        redirect($_SERVER['HTTP_REFERER']);
    }
}
