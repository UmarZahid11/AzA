<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH . '/libraries/JWT.php');

/**
 * ZOOM Oauth2 - action class
 */
class Oauth2 extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method updateZoomConfigValue - cron function
     *
     * @return bool
     */
    public function updateZoomConfigValue(): bool
    {
        $response = $this->refreshZoomAccessToken($this->getZoomBasicHeader());
        $decoded_response = json_decode($response);

        if ($decoded_response) {
            try {
                $configArray = array(
                    ZOOM_CONFIG_ACCESS_TOKEN => $decoded_response->access_token,
                    ZOOM_CONFIG_REFRESH_TOKEN => $decoded_response->refresh_token,
                    ZOOM_CONFIG_TOKEN_EXPIRY => ''
                );

                if (property_exists($decoded_response, 'expires_in') && $decoded_response->expires_in) {
                    $configArray[ZOOM_CONFIG_TOKEN_EXPIRY] = date('Y-m-d H:i:s', strtotime('+' . $decoded_response->expires_in . ' seconds'));
                }
            } catch (\Exception $e) {
                log_message('ERROR', $e->getMessage());
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_ZOOM_CRON,
                    LOG_LEVEL_ERROR,
                    $e->getMessage(),
                    ''
                );
            }

            // update config value
            $updated = $this->model_config->update_config($configArray);
            if ($updated) {
                log_message('ERROR', 'Zoom config has been updated');
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_ZOOM_CRON,
                    LOG_LEVEL_INFO,
                    'Zoom config has been updated',
                    ''
                );
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Method index - ZOOM
     *
     * @param string $redirectUrl
     *
     * @return void
     */
    public function index(string $redirectUrl = ''): void
    {
        // add more exclusive conditions
        if ($this->userid > 0) {
            if ($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY)) {
                if ((strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {
                    // nothing to do
                    $this->session->set_flashdata('success', __('Zoom login
                     active!'));
                    $redirectUrl ? redirect($redirectUrl) : redirect(l(''));
                }
                redirect(ZOOM_OAUTH_AUTHORIZE_URL);
            }
            redirect(ZOOM_OAUTH_AUTHORIZE_URL);
        } else {
            error_404();
        }
    }

    /**
     * Method redirect - redirection url to be added in ZOOM dashboard
     *
     * @return void
     */
    public function redirect()
    {
        $headers = [
            'Authorization: Basic ' . JWT::urlsafeB64Encode(ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET),
            'content-type: application/x-www-form-urlencoded',
        ];

        $decoded_response = NULL;
        $response = '';

        if ((strtotime($this->getConfigValue(ZOOM_CONFIG_TOKEN_EXPIRY))) > (strtotime(date('Y-m-d H:i:s')))) {
            // nothing to do
            $this->session->has_userdata('zoom_intended') ? redirect($this->session->userdata('zoom_intended')) : redirect(l('dashboard/home'));
        } else {
            if (!isset($_REQUEST['code'])) {
                // refresh access token
                $response = $this->refresh_zoom_access_token($headers);
            }
        }

        if (!$response) {
            $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

            // create access token
            $response = $this->create_zoom_access_token($code, $headers);
            $decoded_response = json_decode($response);
        }

        $decoded_response = json_decode($response);

        if ($decoded_response && isset($decoded_response->error) && NULL !== $decoded_response->error) {
            $this->model_config->update_by_pk(ZOOM_CONFIG_TOKEN_EXPIRY, '');
            redirect(l('oauth2'));
        } else {
            if (isset($decoded_response->access_token)) {
                //
                $this->set_zoom_session($decoded_response);
                $this->session->set_flashdata('success', __('Authentication successfull!'));
            }
        }
        $this->session->has_userdata('zoom_intended') ? redirect($this->session->userdata('zoom_intended')) : redirect(l('dashboard/home'));
    }

    /**
     * Method create_zoom_access_token
     *
     * @param string $code
     * @param array $headers
     *
     * @return ?string
     */
    public function create_zoom_access_token(string $code, array $headers): ?string
    {
        $post_fields = array();
        if (isset($code) && $code) {
            $post_fields = array(
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => ZOOM_OAUTH_REDIRECT_URL,
            );

            // create access token
            return $this->curlRequest(ZOOM_OAUTH_TOKEN_URL . '?' . http_build_query($post_fields), $headers, [], TRUE);
        }
        return NULL;
    }

    /**
     * Method calling_refresh - zoom - cron - not used
     *
     * @return void
     */
    public function calling_refresh()
    {
        $headers = [
            'Authorization: Basic ' . JWT::urlsafeB64Encode(ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET),
            'content-type: application/x-www-form-urlencoded',
        ];
        $decoded_response = json_decode($this->refresh_zoom_access_token($headers));
        if ($decoded_response && isset($decoded_response->error) && NULL !== $decoded_response->error) {
            return false;
        } else {
            $this->set_zoom_session($decoded_response);
            return true;
        }
    }

    /**
     * Method refresh_zoom_access_token
     *
     * @param array $headers
     *
     * @return ?string
     */
    public function refresh_zoom_access_token(array $headers): ?string
    {
        $post_fields = array();
        if ($this->getConfigValue(ZOOM_CONFIG_REFRESH_TOKEN)) {

            $post_fields = array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->getConfigValue(ZOOM_CONFIG_REFRESH_TOKEN),
            );

            // refresh access token
            return $this->curlRequest(ZOOM_OAUTH_TOKEN_URL . '?' . http_build_query($post_fields), $headers, [], TRUE);
        }
        return NULL;
    }

    /**
     * Method set_zoom_session - ZOOM
     *
     * @param object $decoded_response
     *
     * @return void
     */
    public function set_zoom_session(object $decoded_response): void
    {
        // not used - removeable
        $this->session->set_userdata(
            'zoom',
            array(
                'access_token' => $decoded_response->access_token,
                'token_type' => $decoded_response->token_type,
                'refresh_token' => $decoded_response->refresh_token,
                'expires_in' => $decoded_response->expires_in,
                'scope' => $decoded_response->scope,
            )
        );

        // used - mandatory
        // update config value
        $this->model_config->update_by_pk(ZOOM_CONFIG_ACCESS_TOKEN, array('config_value' => $decoded_response->access_token));
        $this->model_config->update_by_pk(ZOOM_CONFIG_REFRESH_TOKEN, array('config_value' => $decoded_response->refresh_token));
        if ($decoded_response->expires_in) {
            $this->model_config->update_by_pk(ZOOM_CONFIG_TOKEN_EXPIRY, array('config_value' => date('Y-m-d H:i:s', strtotime('+' . $decoded_response->expires_in . ' seconds'))));
        }
    }

    /**
     * Method webhook_endpoint - meeting webhook
     *
     * @return void
     */
    public function webhook_endpoint()
    {
        $reponseData = array();
        $zoomData = file_get_contents("php://input");

        $decoded_json = json_decode($zoomData);
        log_message('ERROR', serialize($decoded_json));
        //
        $this->_log_message(
            LOG_TYPE_API,
            LOG_SOURCE_ZOOM,
            LOG_LEVEL_INFO,
            'Webhook payload',
            serialize($decoded_json)
        );

        $zoomPlainToken = '';
        $sig = '';
        if (property_exists($decoded_json, 'payload') && property_exists($decoded_json->payload, 'plainToken') && null !== $decoded_json->payload->plainToken) {
            $zoomPlainToken = $decoded_json->payload->plainToken;
            $sig = hash_hmac('sha256', $zoomPlainToken, ZOOM_OAUTH_SECRET_TOKEN);
        }

        $message = 'v0:' . $_SERVER['HTTP_X_ZM_REQUEST_TIMESTAMP'] . ':' . $zoomData;
        $hash = hash_hmac('sha256', $message, ZOOM_OAUTH_SECRET_TOKEN);
        $signature = "v0={$hash}";
        $verified = hash_equals($_SERVER['HTTP_X_ZM_SIGNATURE'], $signature);

        // if ($_SERVER['HTTP_X_ZM_SIGNATURE'] == $sig) {
        if ($verified) {
            log_message('ERROR', 'url endpoint verified');

            if ($decoded_json->event == 'endpoint.url_validation') {
                $reponseData['plainToken'] = $zoomPlainToken;
                $reponseData['encryptedToken'] = $sig;
                log_message('ERROR', serialize($reponseData));
            }
        }
        if (isset($decoded_json->event) && $decoded_json->event != 'endpoint.url_validation') {

            //
            $where_coaching = array();
            $update_coaching = array();
            //
            $where_meeting = array();
            $update_meeting = array();
            //
            $where_availability = array();
            $update_availability = array();
            //
            $where_webinar = array();
            $update_webinar = array();

            //
            $where_coaching['where']['coaching_fetchid'] =
            $where_meeting['where']['meeting_fetchid'] =
            $where_availability['where']['signup_availability_meeting_fetchid'] =
            $where_webinar['where']['webinar_fetchid'] =
                isset($decoded_json->payload->object->id) ? $decoded_json->payload->object->id : 0;

            //
            $update_coaching['coaching_response2'] =
            $update_meeting['meeting_response2'] =
            $update_availability['signup_availability_meeting_response2'] =
            $update_webinar['webinar_response2'] =
                $zoomData;

            //
            log_message('ERROR', 'Event: ' . $decoded_json->event);
            //
            $this->_log_message(
                LOG_TYPE_API,
                LOG_SOURCE_ZOOM,
                LOG_LEVEL_INFO,
                $decoded_json->event,
                serialize($decoded_json)
            );

            switch ($decoded_json->event) {
                    //
                case 'meeting.started':
                    $update_coaching['coaching_current_status'] = COACHING_STARTED;
                    $update_meeting['meeting_current_status'] = ZOOM_MEETING_STARTED;
                    $update_availability['signup_availability_meeting_current_status'] = ZOOM_MEETING_STARTED;
                    break;
                case 'meeting.ended':
                    $update_coaching['coaching_current_status'] = COACHING_ENDED;
                    $update_meeting['meeting_current_status'] = ZOOM_MEETING_ENDED;
                    $update_availability['signup_availability_meeting_current_status'] = ZOOM_MEETING_ENDED;
                    break;
                case 'webinar.started':
                    $update_webinar['webinar_current_status'] = ZOOM_WEBINAR_STARTED;
                    break;
                case 'webinar.ended':
                    $update_webinar['webinar_current_status'] = ZOOM_WEBINAR_ENDED;
                    break;
            }

            //
            $updated = '';
            //
            if (!empty($this->model_coaching->find_one_active($where_coaching))) {
                $updated = $this->model_coaching->update_model($where_coaching, $update_coaching);
            } elseif (!empty($this->model_meeting->find_one_active($where_meeting))) {
                $updated = $this->model_meeting->update_model($where_meeting, $update_meeting);
            } elseif (!empty($this->model_signup_availability->find_one_active($where_availability))) {
                $updated = $this->model_signup_availability->update_model($where_availability, $update_availability);
            } elseif (!empty($this->model_webinar->find_one_active($where_webinar))) {
                $updated = $this->model_webinar->update_model($where_webinar, $update_webinar);
            }
            if (!$updated) {
                log_message('ERROR', 'Error in updating meeting');
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_ZOOM,
                    LOG_LEVEL_INFO,
                    'Error in updating meeting',
                    ''
                );
            }
        }
        echo json_encode($reponseData);
    }

    /**
     * Method deauth
     *
     * @return void
     */
    public function deauth()
    {
        log_message('ERROR', serialize($_REQUEST));
    }

    /**
     * Method jwt - ZOOM - not used
     *
     * @return void
     */
    public function jwt(): void
    {
        $iat = (strtotime(date('Y-m-d H:i:s')));
        $exp = (strtotime(date('Y-m-d H:i:s', strtotime('+1 day'))));

        $payLoad = (array(
            "aud" => null,
            "iss" => ZOOM_API_KEY,
            "exp" => $exp,
            "iat" => $iat
        ));

        $JWT = JWT::encode($payLoad, ZOOM_API_SECRET);

        $headers = [
            'Authorization: Bearer ' . $JWT,
            'content-type: application/json'
        ];

        $response = $this->curlRequest(ZOOM_API_URL . '/users', $headers);
        if ($response) {
            echo '<pre>';
            print_r(json_decode($response));
        }
    }
}
