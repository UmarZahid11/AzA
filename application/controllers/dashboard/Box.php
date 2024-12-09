<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Box
 */
class Box extends MY_Controller
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
     * Method index - check route.php for this function url naming
     *
     * @param int $offset
     * @param string $folder_id
     * @param int $limit
     *
     * @return void
     */
    public function index(int $offset = 1, string $folder_id = '0', int $limit = 100)
    {
        // if ($this->user_data['signup_box_id']) {
        //     $response = $this->listUser($this->user_data['signup_box_id']);

        //     if ($response) {
        //         $user_detail = json_decode($response);
        //     } else {
        //         $user_detail = NULL;
        //     }
        //     $access_token = NULL;

        //     if ($user_detail && multiple_property_exists($user_detail, ['type', 'message']) && $user_detail->type == 'error') {
        //         $this->session->set_flashdata('box_recreate', $user_detail->message);
        //         redirect(l('dashboard'));
        //         // allow administrator to bypass so that config value can be updated.
        //     } else if ($user_detail || $this->model_signup->hasRole(ROLE_0)) {
                // if no session variable, then redirect to box for authorization
                // if ($this->session->has_userdata('box') && $this->session->userdata['box']['expiry_time'] && $this->session->userdata['box']['access_token']) {
                if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && $this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)) {
                    // if expiry of the access token greater than current time
                    // then show index page
                    if ((strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {
                        // $access_token = $this->session->userdata['box']['access_token'];
                        $access_token = $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN);
                    } else {
                        // token error - refresh the access token
                        $this->session->set_userdata('box_intended', l('dashboard/box/index/' . $offset . '/' . $folder_id));
                        redirect(l('box'));
                    }
                // } else if($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && $this->model_signup->hasPremiumPermission()) {
                //     $access_token = $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN);
                } else {
                    // token error - create the access token
                    $this->session->set_userdata('box_intended', l('dashboard/box/index/' . $offset . '/' . $folder_id));
                    redirect(l('box'));
                }

                $data = array();
                $data['items_error'] = FALSE;

                //
                $data['offset'] = $offset;
                $paginationStart = ($offset > 0) ? ($offset - 1) * $limit : 0;

                $data['limit'] = $limit;

                $data['prev'] = $offset - 1;
                $data['next'] = $offset + 1;

                $data['folder_id'] = $folder_id;

                $folder_items = $this->folderItems($folder_id, TRUE, $paginationStart, $limit, $access_token);
                $data['folder_items'] = $folder_items ? json_decode($folder_items) : '';

                if ($data['folder_items'] && multiple_property_exists($data['folder_items'], ['type', 'message']) && $data['folder_items']->type == 'error') {
                    $data['items_error'] = TRUE;
                    $data['items_error_message'][] = $data['folder_items']->message;
                    $data['folder_items'] = json_decode($this->folderItems($folder_id, FALSE, $paginationStart, $limit, $access_token));
                }

                $folder_information = $this->folderInformation($folder_id);
                $data['folder_information'] = $folder_information ? json_decode($folder_information) : '';
                if ($data['folder_information'] && multiple_property_exists($data['folder_information'], ['type', 'message']) && $data['folder_information']->type == 'error') {
                    $data['items_error'] = TRUE;
                    $data['items_error_message'][] = $data['folder_information']->message;
                }

                //
                $this->layout_data['title'] = 'Box | ' . $this->layout_data['title'];
                //
                $this->load_view("index", $data);
        //     } else {
        //         // $this->model_signup->update_by_pk($this->userid, array('signup_box_id' => ''));
        //         // create user action
        //         $this->createUser();
        //     }
        // } else {
        //     // create user action
        //     $this->createUser();
        // }
    }

    /**
     * Method preview
     *
     * @return void
     */
    function preview(string $folder_id = '0', string $file_id = ''): void
    {
        // if no session variable, then redirect to box for authorization
        // if ($this->session->has_userdata('box') && $this->session->userdata['box']['expiry_time'] && $this->session->userdata['box']['access_token']) {
        if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && $this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)) {
            // if expiry of the access token greater than current time
            // then show index page
            // if ((strtotime($this->session->userdata['box']['expiry_time'])) > (strtotime(date('Y-m-d H:i:s')))) {
            if ((strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {

                if (!$file_id)
                    error_404();

                $data = array();
                $data['items_error'] = FALSE;
                $data['items_error_message'] = ERROR_MESSAGE;

                $data['file_information'] = json_decode($this->fileInformation($file_id));
                if ($data['file_information'] && multiple_property_exists($data['file_information'], ['type', 'message']) && $data['file_information']->type == 'error') {
                    $data['items_error'] = TRUE;
                    $data['items_error_message'] = $data['file_information']->message;
                    $data['file_information'] = json_decode($this->folderItems($file_id, FALSE));
                }

                $data['folder_information'] = json_decode($this->folderInformation($folder_id));
                //
                $this->layout_data['title'] = 'Box preview | ' . $this->layout_data['title'];
                //
                $this->load_view("preview", $data);
            } else {
                // token error - refresh the access token
                $this->session->set_userdata('box_intended', l('dashboard/box/preview/' . $file_id));
                redirect(l('box'));
            }
        } else {
            // token error - create the access token
            $this->session->set_userdata('box_intended', l('dashboard/box/preview/' . $file_id));
            redirect(l('box'));
        }
    }

    /**
     * Method createUser
     *
     * @return void
     */
    function createUser(): void
    {
        if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)))) {

            if (!$this->user_data['signup_box_id']) {

                //
                $headers = array(
                    'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
                );
                //
                $post_fields = array(
                    'login' => $this->user_data['signup_email'],
                    'name' => $this->model_signup->profileName($this->user_data, FALSE)
                );

                $url = BOX_USER_URL;
                $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                $decoded_response = json_decode($response);

                if ($decoded_response && multiple_property_exists($decoded_response, ['type', 'message']) && $decoded_response->type == 'error') {
                    if (property_exists($decoded_response, 'context_info') && property_exists($decoded_response->context_info, 'errors') && property_exists($decoded_response->context_info->errors[0], 'message')) {
                        $this->session->set_flashdata('error', $decoded_response->context_info->errors[0]->message);
                        redirect(l('dashboard'));
                    } else {
                        $this->session->set_flashdata('error', $decoded_response->message);
                        redirect(l('dashboard'));
                    }
                } else if (property_exists($decoded_response, 'id')) {
                    $updated = $this->model_signup->update_by_pk($this->userid, array('signup_box_id' => $decoded_response->id));
                    if ($updated) {
                        $this->session->set_flashdata('success', 'A box account for reqeusted user has been created successfully. Check your email for password creation process.');
                        redirect(l('dashboard'));
                    } else {
                        $this->session->set_flashdata('error', ERROR_MESSAGE);
                        redirect(l('dashboard'));
                    }
                } else {
                    $this->session->set_flashdata('error', ERROR_MESSAGE);
                    redirect(l('dashboard'));
                }
            } else {
                $this->session->set_flashdata('error', 'A box account for reqeusted user already exists.');
                redirect(l('dashboard'));
            }
        } else {
            $this->session->set_flashdata('error', ERROR_MESSAGE_BOX_UNAVAILABLE);
            redirect(l('dashboard'));
        }
    }

    /**
     * Method reCreateUser
     *
     * @return void
     */
    function reCreateUser(): void
    {
        $json_param['status'] = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

            if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN)) {
                //
                $headers = array(
                    'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
                );
                //
                $post_fields = array(
                    'login' => $this->user_data['signup_email'],
                    'name' => $this->model_signup->profileName($this->user_data, FALSE)
                );

                $url = BOX_USER_URL;
                $response = $this->curlRequest($url, $headers, $post_fields, TRUE);
                $decoded_response = json_decode($response);

                if ($decoded_response && multiple_property_exists($decoded_response, ['type', 'message']) && $decoded_response->type == 'error') {
                    if (property_exists($decoded_response, 'context_info') && property_exists($decoded_response->context_info, 'errors') && property_exists($decoded_response->context_info->errors[0], 'message')) {
                        $json_param['txt'] = $decoded_response->context_info->errors[0]->message;
                    } else {
                        $json_param['txt'] = $decoded_response->message;
                    }
                } else if ($decoded_response && property_exists($decoded_response, 'id')) {
                    $updated = $this->model_signup->update_by_pk($this->userid, array('signup_box_id' => $decoded_response->id));
                    if ($updated) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = 'A box account for reqeusted user has been created successfully. Check your email for password creation process.';
                    } else {
                        $json_param['txt'] = ERROR_MESSAGE;
                    }
                } else {
                    $json_param['txt'] = ERROR_MESSAGE;
                }
            } else {
                $json_param['txt'] = ERROR_MESSAGE_BOX_UNAVAILABLE;
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);
    }

    /**
     * Method getUser
     *
     * @param string $folder_id
     *
     * @return string
     */
    public function getUser(string $user_id = ''): ?string
    {
        $response = NULL;
        // if (isset($this->session->userdata['box']['access_token'])) {
        if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN)) {
            $headers = array(
                'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
            );
            $url = BOX_USER_URL;
            if ($user_id) {
                $url .= '/' . $user_id;
            }
            $response = $this->curlRequest($url, $headers);
        }
        return ($response);
    }

    /**
     * Method listUser
     *
     * @param string $user_id
     *
     * @return ?string
     */
    function listUser(string $user_id = ''): ?string
    {
        $response = NULL;
        if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)))) {
            $headers = array(
                'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
            );
            $url = BOX_USER_URL;
            if ($user_id) {
                $url .= '/' . $user_id;
            }
            $response = $this->curlRequest($url, $headers);
            if($this->session->userdata('last_http_status') >= 400) {
                $refresh_token = $this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN);
                $response = $this->refreshBoxAccessToken($refresh_token);
                $decoded_response = json_decode($response);
                if (multiple_property_exists($decoded_response, ['error', 'error_description'])) {
                } else {
                    if ($this->setBoxSession($decoded_response, FALSE)) {
                        $this->listUser($this->user_data['signup_box_id']);
                    }
                }
            }
        } else {
            $refresh_token = $this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN);
            $response = $this->refreshBoxAccessToken($refresh_token);
            $decoded_response = json_decode($response);
            if (multiple_property_exists($decoded_response, ['error', 'error_description'])) {
            } else {
                if ($this->setBoxSession($decoded_response, FALSE)) {
                    $this->listUser($this->user_data['signup_box_id']);
                }
            }
        }
        return ($response);
    }

    /**
     * Method upload
     *
     * @return void
     */
    public function upload(): void
    {
        $json_param = array();
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $json_param['refresh'] = STATUS_FALSE;
        $authentication_failure = STATUS_FALSE;
        $response = '';

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $file_size = $_FILES['file']['size'];
                // 50 mb
                if ($file_size <= 52428800) {
                    //
                    $file_name = $_FILES['file']['name'];
                    $tmp = $_FILES['file']['tmp_name'];
                    $upload_path = 'box_downloads/';

                    // $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    // $file_name = mt_rand() . '.' . $ext;

                    //
                    // if ($this->session->has_userdata('box') && $this->session->userdata['box']['expiry_time'] && $this->session->userdata['box']['access_token']) {
                    if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && $this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)) {
                        // box access token expiry time is greater than current time
                        // if ((strtotime($this->session->userdata['box']['expiry_time'])) > (strtotime(date('Y-m-d H:i:s')))) {
                        if ((strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {
                            
                            //                            
                            $file_name = cleanString($file_name);

                            if (move_uploaded_file($tmp, $upload_path . $file_name)) {

                                $file_path = l('box_downloads/' . $file_name);
                                // action function
                                $result = $this->uploadAction($file_name, $file_path, $_POST['folder_id'], new CURLFILE($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']));

                                $json_param['response'] = $result['response'];
                                $json_param['last_http_status'] = $result['last_http_status'];
                            } else {
                                $json_param['last_http_status'] = CODE_BAD_REQUEST;
                            }
                        } else {
                            $authentication_failure = STATUS_TRUE;
                            $json_param['refresh'] = STATUS_TRUE;
                        }
                    } else {
                        $authentication_failure = STATUS_TRUE;
                        $json_param['refresh'] = STATUS_TRUE;
                    }

                    if (!$authentication_failure) {
                        $decoded_response = $response ? json_decode($response) : NULL;

                        $json_param['filename'] = $file_name;
                        $json_param['response'] = $decoded_response;

                        if ($decoded_response && property_exists($decoded_response, 'type') && $decoded_response->type == 'error') {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = ($decoded_response && property_exists($decoded_response, 'message')) ? $decoded_response->message : ERROR_MESSAGE;
                        } elseif (!$decoded_response && !in_array($json_param['last_http_status'], [200, 201])) {
                            $json_param['status'] = STATUS_FALSE;
                            if ($json_param['last_http_status'] == 409) {
                                $json_param['txt'] = 'A file already exists, or the account has run out of disk space.';
                            } else {
                                $json_param['txt'] = ERROR_MESSAGE;
                            }
                        } else {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = SUCCESS_MESSAGE;
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE_BOX_AUTHORIZATION_REQUIRED);
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE_FILE_EXCEED_LIMIT);
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method uploadAction
     *
     * @param string $file_name
     * @param string $file_path
     * @param CURLFile $object
     *
     * @return array
     */
    public function uploadAction(string $file_name, string $file_path, string $folder_id, CURLFile $object): array
    {
        $response = '';
        $error = FALSE;
        $message = '';
        $last_http_status = 200;

        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => BOX_UPLOAD_FILE_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                // CURLOPT_POSTFIELDS => array('attributes' => '{"name":" ' . $file_name . '", "parent": {"id": "' . $folder_id . '"}}', 'file' => new CURLFILE($file_path)),
                CURLOPT_POSTFIELDS => array(
                    'attributes' => json_encode(
                        array(
                            'name' => $file_name,
                            'parent' => array(
                                'id' => $folder_id
                            ),
                        )
                    ),
                    'file' => new CURLFILE($file_path)
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
                ),
            ));

            $response = curl_exec($curl);

            $last_http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            log_message('error', 'URL: ' . BOX_UPLOAD_FILE_URL . ' - last_http_status: ' . $last_http_status);
            if (curl_errno($curl)) {
                log_message('error', 'Error:' . curl_error($curl));
            }
            curl_close($curl);
        } catch (\Exception $e) {
            log_message('error', 'Error:' . $e->getMessage());
            //
            $this->_log_message(
                LOG_TYPE_GENERAL,
                LOG_SOURCE_SERVER,
                LOG_LEVEL_ERROR,
                $e->getMessage(),
                ''
            );
            $last_http_status = CODE_BAD_REQUEST;
            $error = TRUE;
            $message = $e->getMessage();
        }

        return array(
            'error' => $error,
            'last_http_status' => $last_http_status,
            'message' => $message,
            'response' => $response,
        );
    }

    /**
     * Method createFolder
     *
     * @return void
     */
    public function createFolder(): void
    {
        $json_param = array();
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {

            $response = '';
            $authentication_failure = STATUS_FALSE;

            //
            // if ($this->session->has_userdata('box') && $this->session->userdata['box']['expiry_time'] && $this->session->userdata['box']['access_token']) {
            if ($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN) && $this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)) {
                // box access token expiry time is greater than current time
                // if ((strtotime($this->session->userdata['box']['expiry_time'])) > (strtotime(date('Y-m-d H:i:s')))) {
                if ((strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {

                    $headers = [
                        'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
                        'content-type: application/json'
                    ];

                    $post_fields = array(
                        'name' => $_POST['folder_name'],
                        'parent' => array(
                            'id' => $_POST['folder_id']
                        ),
                    );

                    $response = $this->curlRequest(BOX_FOLDER_URL, $headers, $post_fields, TRUE);
                } else {
                    $authentication_failure = STATUS_TRUE;
                    $json_param['refresh'] = STATUS_TRUE;
                }
            } else {
                $authentication_failure = STATUS_TRUE;
                $json_param['refresh'] = STATUS_TRUE;
            }

            if (!$authentication_failure) {
                $decoded_response = json_decode($response);

                $json_param['post_fields'] = $post_fields;
                $json_param['response'] = $decoded_response;
                $json_param['last_http_status'] = $this->session->userdata('last_http_status');
                
                if ($decoded_response && property_exists($decoded_response, 'type') && $decoded_response->type == 'error') {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = ($decoded_response && property_exists($decoded_response, 'message')) ? $decoded_response->message : ERROR_MESSAGE;
                } elseif (!$decoded_response) {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = ERROR_MESSAGE;
                } else {
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = SUCCESS_MESSAGE;
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __('Box authorization required.');
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
    }

    /**
     * Method folderItems
     *
     * @param string $folder_id
     * @param bool $has_query_param
     *
     * @return string
     */
    public function folderItems(string $folder_id = '0', bool $has_query_param = TRUE, $offset = 0, $limit = 100, string $access_token = NULL): ?string
    {
        $response = NULL;
        if (($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN))) {
            $headers = array(
                'Authorization: Bearer ' . ($access_token ?? $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN)),
            );
            $url = str_replace('{folder_id}', $folder_id, BOX_FOLDER_GET_ITEMS_URL);
            if ($has_query_param) {
                $url .= '?fields=id,type,name,shared_link,shared_link_permission_options,created_at,expiring_embed_link,owned_by';
            } else {
                $url .= '?fields=id,type,name,created_at,owned_by';
            }
            $url .= '&limit=' . $limit . '&offset=' . $offset;
            $response = $this->curlRequest($url, $headers);
        }
        return ($response);
    }

    /**
     * Method folderInformation
     *
     * @param string $folder_id
     *
     * @return string
     */
    public function folderInformation(string $folder_id = '0'): ?string
    {
        $response = NULL;
        if (($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN))) {
            $headers = array(
                'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
            );
            $url = str_replace('{folder_id}', $folder_id, BOX_FOLDER_GET_URL);
            $response = $this->curlRequest($url, $headers);
        }
        return ($response);
    }

    /**
     * Method folderInformation
     *
     * @param string $file_id
     * @param bool $has_query_param
     *
     * @return string
     */
    public function fileInformation(string $file_id = '', $has_query_param = TRUE): ?string
    {
        $response = NULL;
        if (($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN))) {
            $headers = array(
                'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
            );
            $url = str_replace('{file_id}', $file_id, BOX_FILE_GET_URL);
            if ($has_query_param) {
                $url .= '?fields=name,expiring_embed_link';
            }
            $response = $this->curlRequest($url, $headers);
        }
        return ($response);
    }

    /**
     * Method affect - update and delete action
     *
     * @return void
     */
    function affect(): void
    {
        $json_param = array();
        $json_param['status'] = FALSE;
        $json_param['refresh'] = FALSE;
        $json_param['response'] = NULL;
        $error = FALSE;
        $decoded_response = NULL;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (($this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN))) {
                    if ((strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {
                        if ((isset($_POST['id']) && $_POST['id']) && isset($_POST['type']) && $_POST['type']) {

                            $id = $_POST['id'];
                            $type = $_POST['type'];
                            $post_fields = array();

                            if (isset($_POST['method']) && in_array($_POST['method'], ['put', 'delete'])) {
                                $method = $_POST['method'];

                                if ($method == 'put') {
                                    $post_fields['name'] = isset($_POST['name']) ? $_POST['name'] : '';
                                }

                                $attachment_information = json_decode($this->{$type . 'Information'}($id));
                                
                                //
                                $headers = array(
                                    'Authorization: Bearer ' . $this->getConfigValue(BOX_CONFIG_ACCESS_TOKEN),
                                );

                                switch ($type) {
                                    case 'folder':

                                        if (property_exists($attachment_information, 'id') && $attachment_information->id == $id) {
                                            $url = str_replace('{folder_id}', $id, BOX_FOLDER_GET_URL);
                                            $decoded_response = json_decode($this->curlRequest($url, $headers, $post_fields, FALSE, TRUE, strtoupper($method)));
                                        } else {
                                            $error = TRUE;
                                            $json_param['txt'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                                        }
                                        break;
                                    case 'file':

                                        if (property_exists($attachment_information, 'id') && $attachment_information->id == $id) {
                                            $url = str_replace('{file_id}', $id, BOX_FILE_GET_URL);
                                            $decoded_response = json_decode($this->curlRequest($url, $headers, $post_fields, FALSE, TRUE, strtoupper($method)));
                                        } else {
                                            $error = TRUE;
                                            $json_param['txt'] = ERROR_MESSAGE_RESOURCE_NOT_FOUND;
                                        }
                                        break;
                                    default:
                                        $error = TRUE;
                                        $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                                }

                                //
                                if (!$error) {
                                    $json_param['last_http_status'] = $this->session->userdata('last_http_status');
                                    $json_param['response'] = $decoded_response;
                                    if ($decoded_response && property_exists($decoded_response, 'type') && $decoded_response->type == 'error') {
                                        $json_param['txt'] = $decoded_response->message;
                                    } else {
                                        $json_param['status'] = TRUE;
                                        $json_param['txt'] = SUCCESS_MESSAGE;
                                    }
                                }
                            } else {
                                $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                            }
                        } else {
                            $json_param['txt'] = ERROR_MESSAGE_INVALID_PAYLOAD;
                        }
                    } else {
                        $json_param['refresh'] = TRUE;
                        $json_param['txt'] = ERROR_MESSAGE_BOX_AUTHORIZATION;
                    }
                } else {
                    $json_param['refresh'] = TRUE;
                    $json_param['txt'] = ERROR_MESSAGE_BOX_AUTHORIZATION;
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
