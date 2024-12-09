<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\Facades\Account as FacadesAccount;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\Department;
use QuickBooksOnline\API\Facades\Employee;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\QuickBookClass;
use QuickBooksOnline\API\Facades\TimeActivity;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\ReportService\ReportService;

/**
 * Quickbook - action class (views in dashboard/home)
 */
class Quickbook extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->userid == 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l(''));
        }
        $this->dataService = $this->instantiateQuickbookInstance();
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index(): void
    {
        try {
            // $this->dataService->getServiceContext()->requestValidator->getAccessToken();
            // $this->session->unset_userdata('quickbook');
            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    //
                    $this->session->set_flashdata('success', __('Authentication active!'));
                    $this->session->has_userdata('quickbook_intended') ? redirect($this->session->userdata('quickbook_intended')) : redirect(l('dashboard/home/quickbook'));
                } elseif (isset($this->session->userdata['quickbook']['refresh_token_expiry']) && $this->session->userdata['quickbook']['refresh_token_expiry'] > date('Y/m/d H:i:s')) {
                    //
                    $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
                    $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
                    $error = $OAuth2LoginHelper->getLastError();

                    if ($error) {
                        log_message('ERROR', $error->getResponseBody());
                        $this->session->unset_userdata('quickbook');
                        header('Location: ' . l('quickbook'));
                    } else {
                        // Refresh Token is called successfully
                        $this->dataService->updateOAuth2Token($refreshedAccessTokenObj);
                        $this->set_session($refreshedAccessTokenObj, $this->dataService);

                        // redirect to active page //
                        $this->session->has_userdata('quickbook_intended') ? redirect($this->session->userdata('quickbook_intended')) : redirect(l('dashboard/home/quickbook'));
                    }
                } else {
                    $this->session->unset_userdata('quickbook');
                    header('Location: ' . l('quickbook'));
                }
            } else {
                $this->authorize_quickbook();
            }
        } catch (\Exception $e) {
            // die($e->getMessage());
            //
            $this->_log_message(
                LOG_TYPE_API,
                LOG_SOURCE_QUICKBOOK,
                LOG_LEVEL_ERROR,
                $e->getMessage(),
                ''
            );
            $this->authorize_quickbook();
        }
    }

    /**
     * Method oauth_redirect
     *
     * @return void
     */
    public function authorize_quickbook(): void
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        $error = $OAuth2LoginHelper->getLastError();
        if ($error) {
            log_message('ERROR', $error->getResponseBody());
        } else {
            header('Location: ' . $authorizationCodeUrl);
        }
    }

    /**
     * Method redirect
     *
     * @return void
     */
    public function redirect(): void
    {
        if (isset($_SERVER['QUERY_STRING'])) {
            $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
            $parseUrl = $this->parseAuthRedirectUrl(htmlspecialchars_decode($_SERVER['QUERY_STRING']));

            $code = isset($parseUrl['code']) ? $parseUrl['code'] : '';
            $realmId = isset($parseUrl['realmId']) ? $parseUrl['realmId'] : '';

            // Update the OAuth2Token
            try {
                $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);
                $this->dataService->updateOAuth2Token($accessToken);

                // Setting the accessToken for session variable
                $this->set_session($accessToken, $this->dataService);
    
                // redirect to active page //
                $this->session->set_flashdata('success', __('Authentication successfull!'));
                $this->session->has_userdata('quickbook_intended') ? redirect($this->session->userdata('quickbook_intended')) : redirect(l('dashboard/home/quickbook'));
            } catch (\Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(l('dashboard'));
            }
        }
    }

    /**
     * Method set_session
     *
     * @param OAuth2AccessToken $tokenObj
     *
     * @return void
     */
    public function set_session(OAuth2AccessToken $tokenObj, $service_instance): void
    {
        $this->session->set_userdata(
            'quickbook',
            array(
                'access_token' => $tokenObj->getAccessToken(),
                'access_token_expiry' => $tokenObj->getAccessTokenExpiresAt(),
                'refresh_token' => $tokenObj->getRefreshToken(),
                'refresh_token_expiry' => $tokenObj->getRefreshTokenExpiresAt(),
                'realm_id' => $tokenObj->getRealmID(),
                'object' => $tokenObj,
                'service_instance' => $service_instance
            )
        );
    }

    /**
     * Method saveInvoice
     *
     * @return void
     */
    public function saveInvoice()
    {
        $id = '';
        if (isset($_POST['id']) && $_POST['id']) {
            $id = $_POST['id'];
        }
        if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    if ($id) {
                        $updateArray = array();

                        $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);
                        $invoice = $this->dataService->FindbyId('invoice', $id);

                        switch (true) {
                            case (isset($_POST['CustomerRef']['value'])):
                                $updateArray['CustomerRef']['value'] = $_POST['CustomerRef']['value'] ? $_POST['CustomerRef']['value'] : $invoice->CustomerRef->value;
                            case isset($_POST['DocNumber']):
                                $updateArray['DocNumber'] = $_POST['DocNumber'] ? $_POST['DocNumber'] : $invoice->DocNumber;
                            case (isset($_POST['TxnDate'])):
                                $updateArray['TxnDate'] = $_POST['TxnDate'] ? $_POST['TxnDate'] : $invoice->TxnDate;
                            case isset($_POST['DueDate']):
                                $updateArray['DueDate'] = $_POST['DueDate'] ? $_POST['DueDate'] : $invoice->DueDate;
                            case (isset($_POST['EmailStatus'])):
                                $updateArray['EmailStatus'] = $_POST['EmailStatus'] ? $_POST['EmailStatus'] : $invoice->EmailStatus;
                            case (isset($_POST['BillEmail']['Address'])):
                                $updateArray['BillEmail']['Address'] = $_POST['BillEmail']['Address'] ? $_POST['BillEmail']['Address'] : $invoice->BillEmail->Address;
                            case (isset($_POST['CustomerMemo']['value'])):
                                $updateArray['CustomerMemo']['value'] = $_POST['CustomerMemo']['value'] ? $_POST['CustomerMemo']['value'] : $invoice->CustomerMemo;
                            case (isset($_POST['SalesTermRef']['value'])):
                                $updateArray['SalesTermRef']['value'] = $_POST['SalesTermRef']['value'] ? $_POST['SalesTermRef']['value'] : $invoice->SalesTermRef->value;
                        }

                        for ($i = 0; $i < count($_POST['Line']['SalesItemLineDetail']['ItemRef']['value']); $i++) {
                            if (!isset($updateArray['Line'][$i]['DetailType'])) {
                                $updateArray['Line'][$i]['DetailType'] = 'SalesItemLineDetail';
                            }

                            switch (true) {
                                case (isset($_POST['Line']['SalesItemLineDetail']['TaxCodeRef']['value'][$i])):
                                    $updateArray['Line'][$i]['SalesItemLineDetail']['TaxCodeRef']['value'] = $_POST['Line']['SalesItemLineDetail']['TaxCodeRef']['value'][$i] ? $_POST['Line']['SalesItemLineDetail']['TaxCodeRef']['value'][$i] : $invoice->Line[$i]->SalesItemLineDetail->TaxCodeRef->value;
                                case (isset($_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i])):
                                    $updateArray['Line'][$i]['SalesItemLineDetail']['ItemRef']['value'] = $_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i] ? $_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i] : $invoice->Line[$i]->SalesItemLineDetail->ItemRef->value;
                                case isset($_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i]):
                                    $updateArray['Line'][$i]['SalesItemLineDetail']['ItemRef']['name'] = $this->getEntityById('item', (int) $_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i])->FullyQualifiedName;
                                case isset($_POST['Line']['SalesItemLineDetail']['UnitPrice'][$i]):
                                    $updateArray['Line'][$i]['SalesItemLineDetail']['UnitPrice'] = $_POST['Line']['SalesItemLineDetail']['UnitPrice'][$i] ? $_POST['Line']['SalesItemLineDetail']['UnitPrice'][$i] : $invoice->Line[$i]->SalesItemLineDetail->UnitPrice;
                                case (isset($_POST['Line']['SalesItemLineDetail']['Qty'][$i])):
                                    $updateArray['Line'][$i]['SalesItemLineDetail']['Qty'] = $_POST['Line']['SalesItemLineDetail']['Qty'][$i] ? $_POST['Line']['SalesItemLineDetail']['Qty'][$i] : $invoice->Line[$i]->SalesItemLineDetail->Qty;
                                case (isset($_POST['Line']['Description'][$i])):
                                    $updateArray['Line'][$i]['Description'] = $_POST['Line']['Description'][$i] ? $_POST['Line']['Description'][$i] : '';
                                case (isset($updateArray['Line'][$i]['SalesItemLineDetail']['Qty']) && isset($updateArray['Line'][$i]['SalesItemLineDetail']['UnitPrice'])):
                                    $updateArray['Line'][$i]['Amount'] = $updateArray['Line'][$i]['SalesItemLineDetail']['Qty'] * $updateArray['Line'][$i]['SalesItemLineDetail']['UnitPrice'];
                                    //
                                case (isset($_POST['Line']['Id'][$i])):
                                    $updateArray['Line'][$i]['Id'] = $_POST['Line']['Id'][$i] ? $_POST['Line']['Id'][$i] : '';
                            }
                            $updateArray['Line'][$i]['LineNum'] = $i + 1;
                        }

                        if (!empty($updateArray)) {
                            $theResourceObj = Invoice::update($invoice, $updateArray);
                            $resultingObj = $this->dataService->Update($theResourceObj);
                            $error = $this->dataService->getLastError();
                        } else {
                            $error = true;
                            $errorMessage = __(ERROR_MESSAGE);
                        }
                    } else {
                        $invoiceArray = array();

                        switch (true) {
                            case (isset($_POST['CustomerRef']['value'])):
                                $invoiceArray['CustomerRef']['value'] = $_POST['CustomerRef']['value'];
                            case isset($_POST['DocNumber']):
                                $invoiceArray['DocNumber'] = $_POST['DocNumber'];
                            case (isset($_POST['TxnDate'])):
                                $invoiceArray['TxnDate'] = $_POST['TxnDate'];
                            case isset($_POST['DueDate']):
                                $invoiceArray['DueDate'] = $_POST['DueDate'];
                            case (isset($_POST['EmailStatus'])):
                                $invoiceArray['EmailStatus'] = $_POST['EmailStatus'];
                            case (isset($_POST['BillEmail']['Address'])):
                                $invoiceArray['BillEmail']['Address'] = $_POST['BillEmail']['Address'];
                            case (isset($_POST['CustomerMemo']['value'])):
                                $invoiceArray['CustomerMemo']['value'] = $_POST['CustomerMemo']['value'];
                            case (isset($_POST['SalesTermRef']['value'])):
                                $invoiceArray['SalesTermRef']['value'] = $_POST['SalesTermRef']['value'];
                        }

                        for ($i = 0; $i < count($_POST['Line']['SalesItemLineDetail']['ItemRef']['value']); $i++) {
                            if (!isset($invoiceArray['Line'][$i]['DetailType'])) {
                                $invoiceArray['Line'][$i]['DetailType'] = 'SalesItemLineDetail';
                            }

                            switch (true) {
                                case (isset($_POST['Line']['SalesItemLineDetail']['TaxCodeRef']['value'][$i])):
                                    $invoiceArray['Line'][$i]['SalesItemLineDetail']['TaxCodeRef']['value'] = $_POST['Line']['SalesItemLineDetail']['TaxCodeRef']['value'][$i];
                                case (isset($_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i])):
                                    $invoiceArray['Line'][$i]['SalesItemLineDetail']['ItemRef']['value'] = $_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i];
                                case isset($_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i]):
                                    $invoiceArray['Line'][$i]['SalesItemLineDetail']['ItemRef']['name'] = $this->getEntityById('item', (int) $_POST['Line']['SalesItemLineDetail']['ItemRef']['value'][$i])->FullyQualifiedName;
                                case isset($_POST['Line']['SalesItemLineDetail']['UnitPrice'][$i]):
                                    $invoiceArray['Line'][$i]['SalesItemLineDetail']['UnitPrice'] = $_POST['Line']['SalesItemLineDetail']['UnitPrice'][$i];
                                case (isset($_POST['Line']['SalesItemLineDetail']['Qty'][$i])):
                                    $invoiceArray['Line'][$i]['SalesItemLineDetail']['Qty'] = $_POST['Line']['SalesItemLineDetail']['Qty'][$i];
                                case (isset($_POST['Line']['Description'][$i])):
                                    $invoiceArray['Line'][$i]['Description'] = $_POST['Line']['Description'][$i];
                                case (isset($invoiceArray['Line'][$i]['SalesItemLineDetail']['Qty']) && isset($invoiceArray['Line'][$i]['SalesItemLineDetail']['UnitPrice'])):
                                    $invoiceArray['Line'][$i]['Amount'] = $invoiceArray['Line'][$i]['SalesItemLineDetail']['Qty'] * $invoiceArray['Line'][$i]['SalesItemLineDetail']['UnitPrice'];
                                    //
                            }
                            $invoiceArray['Line'][$i]['LineNum'] = $i + 1;
                        }

                        $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);
                        $theResourceObj = Invoice::create($invoiceArray);
                        $resultingObj = $this->dataService->Add($theResourceObj);
                        $error = $this->dataService->getLastError();
                    }

                    if ($error) {
                        //
                        $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                        $json = json_encode($xml);
                        $decoded_error = json_decode($json, TRUE);
                        $errorMessage = $decoded_error['Fault']['Error']['Detail'];
                        //

                        log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());

                        $json_param['refresh'] = STATUS_FALSE;
                        $json_param['txt'] = $errorMessage;
                        $json_param['status'] = STATUS_FALSE;
                    } else {

                        $where_param = array();
                        $where_param['where']['quickbook_activity_entity_id'] = $id;

                        // LOGS TO VIEW AT ADMIN
                        if ($resultingObj) {

                            $affectArray = array(
                                'quickbook_activity_userid' => $this->userid,
                                'quickbook_activity_entity' => 'invoice',
                                'quickbook_activity_entity_class' => Invoice::class,
                                'quickbook_activity_entity_id' => $resultingObj->Id,
                                'quickbook_activity_entity_data' => serialize($resultingObj)
                            );

                            foreach (QUICKBOOK_ENTITY_TYPE as $entityType) {
                                $ucEntityType = ucfirst($entityType);
                                if (property_exists($resultingObj, ($ucEntityType . 'Ref')) && $resultingObj->{$ucEntityType . 'Ref'}) {
                                    $entityData = $this->dataService->FindbyId($ucEntityType, $resultingObj->{$ucEntityType . 'Ref'});
                                    if ($entityData) {
                                        $entityIndex = 'quickbook_activity_' . $entityType . '_ref';
                                        $entityField = $this->model_quickbook_activity->get_fields($entityIndex);
                                        if ($entityField) {
                                            $affectArray[$entityIndex] = serialize($entityData);
                                        }
                                    }
                                }
                            }
                            if ($id) {
                                //
                                $this->model_quickbook_activity->update_model($where_param, $affectArray);
                            } else {
                                //
                                $this->model_quickbook_activity->insert_record($affectArray);
                            }
                        }

                        $json_param['refresh'] = STATUS_FALSE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['result'] = $resultingObj;
                    }
                } else {
                    $json_param['refresh'] = STATUS_TRUE;
                    $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['result'] = '{}';
                }
            } else {
                $json_param['refresh'] = STATUS_TRUE;
                $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                $json_param['status'] = STATUS_FALSE;
                $json_param['result'] = '{}';
            }
        } else {
            $json_param['refresh'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            $json_param['status'] = STATUS_FALSE;
            $json_param['result'] = '{}';
        }
        echo json_encode($json_param);
    }

    /**
     * Method saveEntity
     *
     * @return void
     */
    public function saveEntity()
    {
        $json_param = array();
        $json_param['quickbook_error'] = TRUE;

        $id = 0;
        if (isset($_POST['id']) && $_POST['id']) {
            $id = $_POST['id'];
        }

        if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    if (isset($_POST['entity'])) {
                        $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);

                        //
                        $resultingObj = ['resultingObj' => NULL, 'message' => NULL];

                        $rawError = FALSE;
                        $error = FALSE;

                        switch ($_POST['entity']) {
                            case 'account':
                                $class = FacadesAccount::class;
                                break;
                            case 'customer':
                                $class = Customer::class;
                                break;
                            case 'class':
                                $class = QuickBookClass::class;
                                break;
                            case 'department':
                                $class = Department::class;
                                break;
                            case 'invoice':
                                $class = Invoice::class;
                                break;
                            case 'vendor':
                                $class = Vendor::class;
                                break;
                            case 'item':
                                $class = Item::class;
                                break;
                            case 'bill':
                                $class = Bill::class;
                                break;
                            case 'billpayment':
                                $class = BillPayment::class;
                                break;
                            case 'employee':
                                $class = Employee::class;
                                break;
                            case 'timeactivity':
                                $class = TimeActivity::class;
                                break;
                            default:
                                $rawError = TRUE;
                        }

                        if (!$rawError) {
                            if ($id) {
                                $entity = $this->dataService->FindbyId($_POST['entity'], $id);
                                $resultingObj = $this->entityAction($_POST, $class, TRUE, $entity);
                            } else {
                                $resultingObj = $this->entityAction($_POST, $class);
                            }

                            $error = $this->dataService->getLastError();
                        }

                        if ($error) {
                            //
                            $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                            $json = json_encode($xml);
                            $decoded_error = json_decode($json, TRUE);
                            $errorMessage = $decoded_error['Fault']['Error']['Detail'];
                            //

                            log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());

                            $json_param['refresh'] = STATUS_FALSE;
                            $json_param['txt'] = $errorMessage;
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['result'] = '{}';
                            $json_param['quickbook_error'] = TRUE;
                        } elseif ($rawError) {
                            $json_param['refresh'] = STATUS_FALSE;
                            $json_param['txt'] = __('An error occurred while trying to find requested entity resource.');
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['result'] = '{}';
                        } else {
                            $json_param['result'] = $resultingObj['resultingObj'];
                            $json_param['refresh'] = STATUS_FALSE;

                            //
                            if ($json_param['result']) {
                                $json_param['txt'] = __(SUCCESS_MESSAGE);
                                $json_param['status'] = STATUS_TRUE;
                            } else {
                                $json_param['txt'] = $resultingObj['message'] ?? __(ERROR_MESSAGE);
                                $json_param['status'] = STATUS_FALSE;
                            }
                        }
                    } else {
                        $json_param['refresh'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['result'] = '{}';
                    }
                } else {
                    $json_param['refresh'] = STATUS_TRUE;
                    $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['result'] = '{}';
                }
            } else {
                $json_param['refresh'] = STATUS_TRUE;
                $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                $json_param['status'] = STATUS_FALSE;
                $json_param['result'] = '{}';
            }
        } else {
            $json_param['refresh'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            $json_param['status'] = STATUS_FALSE;
            $json_param['result'] = '{}';
        }
        echo json_encode($json_param);
    }

    /**
     * Method entityAction
     *
     * @param array $entityArray
     * @param class $entity
     * @param boolean $update
     * @param object $entityFetchedArray
     *
     * @return array
     */
    public function entityAction($entityArray, $entity, $update = FALSE, $entityFetchedArray = NULL): array
    {
        $where_param = array();
        $entityRaw = $where_param['where']['quickbook_activity_entity'] = $entityArray['entity'];
        $where_param['where']['quickbook_activity_entity_id'] = isset($entityArray['id']) ? $entityArray['id'] : 0;

        // non-acceptable param in quickbook api request array
        unset($entityArray['entity']);
        unset($entityArray['id']);

        $resultingObj = NULL;
        $message = NULL;

        if (class_exists($entity)) {
            try {
                if ($update) {
                    //
                    $theResourceObj = $entity::update($entityFetchedArray, $entityArray);
                    $resultingObj = $this->dataService->Update($theResourceObj);
                } else {
                    //
                    $theResourceObj = $entity::create($entityArray);
                    $resultingObj = $this->dataService->Add($theResourceObj);
                }

                // LOGS TO VIEW AT ADMIN
                if ($resultingObj) {

                    $affectArray = array(
                        'quickbook_activity_userid' => $this->userid,
                        'quickbook_activity_entity' => $entityRaw,
                        'quickbook_activity_entity_class' => $entity,
                        'quickbook_activity_entity_id' => $resultingObj->Id,
                        'quickbook_activity_entity_data' => serialize($resultingObj)
                    );

                    foreach (QUICKBOOK_ENTITY_TYPE as $entityType) {
                        $ucEntityType = ucfirst($entityType);
                        if (property_exists($resultingObj, ($ucEntityType . 'Ref')) && $resultingObj->{$ucEntityType . 'Ref'}) {
                            $entityData = $this->dataService->FindbyId($ucEntityType, $resultingObj->{$ucEntityType . 'Ref'});
                            if ($entityData) {
                                $entityIndex = 'quickbook_activity_' . $entityType . '_ref';
                                $entityField = $this->model_quickbook_activity->get_fields($entityIndex);
                                if ($entityField) {
                                    $affectArray[$entityIndex] = serialize($entityData);
                                }
                            }
                        }
                    }
                    if ($where_param['where']['quickbook_activity_entity_id']) {
                        //
                        $this->model_quickbook_activity->update_model($where_param, $affectArray);
                    } else {
                        //
                        $this->model_quickbook_activity->insert_record($affectArray);
                    }
                }
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_SERVER,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
                $message = $e->getMessage();
            }
        }

        return ['resultingObj' => $resultingObj, 'message' => $message];
    }

    /**
     * Method deleteEntity
     *
     * @return void
     */
    public function deleteEntity()
    {
        $json_param = array();

        $class = '';
        $response = '';
        $rawError = FALSE;
        $deleted = FALSE;

        $id = 0;
        if (isset($_POST['id']) && $_POST['id']) {
            $id = $_POST['id'];
        }

        if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {

                    if (isset($_POST['entity']) && isset($_POST['Id'])) {

                        switch ($_POST['entity']) {
                            case 'account':
                                $class = FacadesAccount::class;
                                break;
                            case 'customer':
                                $class = Customer::class;
                                break;
                            case 'class':
                                $class = QuickBookClass::class;
                                break;
                            case 'department':
                                $class = Department::class;
                                break;
                            case 'invoice':
                                $class = Invoice::class;
                                break;
                            case 'vendor':
                                $class = Vendor::class;
                                break;
                            case 'item':
                                $class = Item::class;
                                break;
                            case 'bill':
                                $class = Bill::class;
                                break;
                            case 'billpayment':
                                $class = BillPayment::class;
                                break;
                            case 'employee':
                                $class = Employee::class;
                                break;
                            case 'timeactivity':
                                $class = TimeActivity::class;
                                break;
                            default:
                                $rawError = TRUE;
                        }

                        if (!$rawError) {
                            $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);

                            if (class_exists($class)) {
                                $entity = $this->dataService->FindbyId($_POST['entity'], $id);

                                if ($entity) {
                                    $response = $this->dataService->delete($entity);
                                    if ($response->Id) {
                                        $deleted = TRUE;
                                    }
                                }
                            }

                            if ($deleted) {
                                $json_param['refresh'] = STATUS_FALSE;
                                $json_param['txt'] = __(SUCCESS_MESSAGE);
                                $json_param['status'] = STATUS_TRUE;
                            } else {
                                $json_param['refresh'] = STATUS_FALSE;
                                $json_param['txt'] = __(ERROR_MESSAGE);
                                $json_param['status'] = STATUS_FALSE;
                                $json_param['result'] = '{}';
                            }
                        } else {
                            $json_param['refresh'] = STATUS_FALSE;
                            $json_param['txt'] = __('An error occurred while trying to find requested entity resource.');
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['result'] = '{}';
                        }
                    } else {
                        $json_param['refresh'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['result'] = '{}';
                    }
                } else {
                    $json_param['refresh'] = STATUS_TRUE;
                    $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['result'] = '{}';
                }
            } else {
                $json_param['refresh'] = STATUS_TRUE;
                $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                $json_param['status'] = STATUS_FALSE;
                $json_param['result'] = '{}';
            }
        } else {
            $json_param['refresh'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            $json_param['status'] = STATUS_FALSE;
            $json_param['result'] = '{}';
        }
        echo json_encode($json_param);
    }

    /**
     * Method generateCashFlow
     *
     * @return void
     */
    public function generateCashFlow()
    {
        $json_param = array();
        if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
            if ($this->session->has_userdata('quickbook')) {
                if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                    if (isset($_POST)) {
                        $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);

                        $serviceContext = $this->dataService->getServiceContext();
                        $reportService = new ReportService($serviceContext);

                        //
                        if (isset($_POST['start_date']) && $_POST['start_date']) {
                            $reportService->setStartDate($_POST['start_date']);
                        }
                        if (isset($_POST['end_date']) && $_POST['end_date']) {
                            $reportService->setEndDate($_POST['end_date']);
                        }

                        //
                        if (isset($_POST['customer']['Id']) && $_POST['customer']['Id']) {
                            $reportService->setCustomer($_POST['customer']['Id']);
                        }
                        if (isset($_POST['vendor']['Id']) && $_POST['vendor']['Id']) {
                            $reportService->setVendor($_POST['vendor']['Id']);
                        }
                        if (isset($_POST['class']['Id']) && $_POST['class']['Id']) {
                            $reportService->setClassid($_POST['class']['Id']);
                        }
                        if (isset($_POST['department']['Id']) && $_POST['department']['Id']) {
                            $reportService->setDepartment($_POST['department']['Id']);
                        }
                        if (isset($_POST['item']['Id']) && $_POST['item']['Id']) {
                            $reportService->setItem($_POST['item']['Id']);
                        }

                        //
                        if (isset($_POST['sort_order']) && $_POST['sort_order']) {
                            $reportService->setSortOrder($_POST['sort_order']);
                        }
                        if (isset($_POST['summarize_column_by']) && $_POST['summarize_column_by']) {
                            $reportService->setSummarizeColumnBy($_POST['summarize_column_by']);
                        }

                        $cashFlowReport = $reportService->executeReport("CashFlow");

                        $error = $this->dataService->getLastError();

                        if ($error) {
                            //
                            $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                            $json = json_encode($xml);
                            $decoded_error = json_decode($json, TRUE);
                            $errorMessage = $decoded_error['Fault']['Error']['Detail'];
                            //
                            log_message('ERROR', $error->getHttpStatusCode() . ' ' . $error->getOAuthHelperError() . ' ' . $error->getResponseBody());

                            $json_param['refresh'] = STATUS_FALSE;
                            $json_param['status'] = FALSE;
                            $json_param['txt'] = $errorMessage;
                            $json_param['result'] = '{}';
                        } else {
                            $json_param['refresh'] = STATUS_FALSE;
                            $json_param['status'] = TRUE;
                            $json_param['txt'] = __(SUCCESS_MESSAGE);
                            $json_param['result'] = $cashFlowReport;
                        }
                    } else {
                        $json_param['refresh'] = STATUS_FALSE;
                        $json_param['status'] = FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                        $json_param['result'] = '{}';
                    }
                } else {
                    $json_param['refresh'] = STATUS_TRUE;
                    $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['result'] = '{}';
                }
            } else {
                $json_param['refresh'] = STATUS_TRUE;
                $json_param['txt'] = __(ERROR_MESSAGE_AUTHENTICATION);
                $json_param['status'] = STATUS_FALSE;
                $json_param['result'] = '{}';
            }
        } else {
            $json_param['refresh'] = STATUS_FALSE;
            $json_param['status'] = FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL);
            $json_param['result'] = '{}';
        }
        echo json_encode($json_param);
    }

    /**
     * Method parseAuthRedirectUrl
     *
     * @param string $url
     *
     * @return array
     */
    function parseAuthRedirectUrl(string $url): array
    {
        parse_str($url, $qsArray);

        if (!empty($qsArray)) {
            return array(
                'code' => $qsArray['code'],
                'realmId' => $qsArray['realmId']
            );
        }
        return array();
    }

    /**
     * Method revokeToken
     *
     * @return void
     */
    function revokeToken(): void
    {
        $json_param = array();
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->userid > 0 && $this->model_signup->hasPremiumPermission()) {
                if ($this->session->has_userdata('quickbook')) {
                    if (isset($this->session->userdata['quickbook']['access_token_expiry']) && $this->session->userdata['quickbook']['access_token_expiry'] > date('Y/m/d H:i:s')) {
                        if (isset($_POST)) {
                            try {
                                $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);
                                $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
                                $OAuth2LoginHelper->revokeToken($this->session->userdata['quickbook']['access_token']);
                                $error = $OAuth2LoginHelper->getLastError();

                                if ($error) {
                                    log_message('ERROR', $error->getResponseBody());
                                    //
                                    $xml = simplexml_load_string($error->getResponseBody(), "SimpleXMLElement", LIBXML_NOCDATA);
                                    $json = json_encode($xml);
                                    $decoded_error = json_decode($json, TRUE);
                                    //
                                    $json_param['txt'] = $decoded_error['Fault']['Error']['Detail'];
                                } else {
                                    $this->session->unset_userdata('quickbook');
                                    $json_param['txt'] = SUCCESS_MESSAGE;
                                    $json_param['status'] = TRUE;
                                }
                            } catch (\Exception $e) {
                                $json_param['txt'] = $e->getMessage();
                            }
                        } else {
                            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                        }
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE_AUTHENTICATION;
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
}
