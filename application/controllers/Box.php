<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Box
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
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        if ($this->userid > 0) {
            // if box session paramter has been set
            // if ($this->session->has_userdata('box') && $this->session->userdata['box']['expiry_time']) {
            if($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY)) {
                // if expiry of the access token less than current time
                // then make call to refresh the access token
                if ((strtotime($this->getConfigValue(BOX_CONFIG_TOKEN_EXPIRY))) < (strtotime(date('Y-m-d H:i:s')))) {
                    $refresh_token = $this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN); //$this->session->userdata['box']['refresh_token'];
                    $response = $this->refreshBoxAccessToken($refresh_token);
                    $decoded_response = json_decode($response);
                    if (multiple_property_exists($decoded_response, ['error', 'error_description'])) {
                        $this->session->set_flashdata('error', $decoded_response->error_description);
                        $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard'));
                    } else {
                        // update sesssion
                        if ($this->setBoxSession($decoded_response, FALSE)) {
                            $this->session->set_flashdata('box_success', SUCCESS_MESSAGE_BOX_AUTHORIZATION);
                            $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard/box'));
                        } else {
                            $this->session->set_flashdata('error', ERROR_MESSAGE_BOX_AUTHORIZATION);
                            $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard'));
                        }
                    }
                } else {
                    // else show active message
                    $this->session->set_flashdata('success', SUCCESS_MESSAGE_BOX_AUTHORIZATION_ACTIVE);
                    $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard'));
                }
            } else if($this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN)) {
                $refresh_token = $this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN);
                $response = $this->refreshBoxAccessToken($refresh_token);
                $decoded_response = json_decode($response);
                if (multiple_property_exists($decoded_response, ['error', 'error_description'])) {
                    $this->session->set_flashdata('error', $decoded_response->error_description);
                    // $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard'));
                    // changes here
                    redirect(BOX_OAUTH_AUTHORIZATION_URL);
                } else {
                    // update sesssion
                    if ($this->setBoxSession($decoded_response, FALSE)) {
                        $this->session->set_flashdata('box_success', SUCCESS_MESSAGE_BOX_AUTHORIZATION);
                        $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard/box'));
                    } else {
                        $this->session->set_flashdata('error', ERROR_MESSAGE_BOX_AUTHORIZATION);
                        $this->session->has_userdata('box_intended') ? redirect($this->session->userdata('box_intended')) : redirect(l('dashboard'));
                    }
                }
            } else {
                // else redirect to oauth url
                redirect(BOX_OAUTH_AUTHORIZATION_URL);
            }
        } else {
            error_404();
        }
    }

    /**
     * Method redirect
     *
     * @return void
     */
    public function redirect()
    {
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';

        $decoded_response = NULL;
        $response = '';

        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        $response = ($this->createBoxAccessToken($code, $headers));
        $decoded_response = json_decode($response);
        if (property_exists($decoded_response, 'error')) {
            $this->session->set_flashdata('error', $decoded_response->error_description);
            redirect(l(''));
        } else {
            if ($this->setBoxSession($decoded_response, FALSE)) {
                $this->session->set_flashdata('box_success', SUCCESS_MESSAGE_BOX_AUTHORIZATION);
                redirect(l('dashboard/box'));
            } else {
                $this->session->set_flashdata('error', ERROR_MESSAGE_BOX_AUTHORIZATION);
                redirect(l(''));
            }
        }
    }

    /**
     * Method createBoxAccessToken
     *
     * @param string $code
     * @param array $headers
     *
     * @return string
     */
    public function createBoxAccessToken(string $code, array $headers): ?string
    {
        $post_fields = array();
        if (isset($code) && $code) {
            $post_fields = array(
                'client_id' => BOX_CLIENT_ID,
                'client_secret' => BOX_CLIENT_SECRET,
                'code' => $code,
                'grant_type' => 'authorization_code',
            );

            // create access token
            return $this->curlRequest(BOX_OAUTH_TOKEN_URL, $headers, $post_fields, TRUE, FALSE, '', TRUE);
        }
        return NULL;
    }

    /**
     * Method refreshBoxAccessToken
     *
     * @param array $headers
     *
     * @return ?string
     */
    public function refreshBoxAccessToken(string $refresh_token): ?string
    {
        $headers = [
            'content-type: application/x-www-form-urlencoded',
        ];

        $post_fields = array();
        if ($refresh_token) {

            $post_fields = array(
                'client_id' => BOX_CLIENT_ID,
                'client_secret' => BOX_CLIENT_SECRET,
                'refresh_token' => $refresh_token,
                'grant_type' => 'refresh_token',
            );

            // refresh access token
            return $this->curlRequest(BOX_OAUTH_TOKEN_URL, $headers, $post_fields, TRUE, FALSE, '', TRUE);
        }
        return NULL;
    }

    /**
     * Method updateBoxConfigValue - cron function
     *
     * @return bool
     */
    function updateBoxConfigValue(): bool
    {
        if ($this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN)) {
            $response = $this->refreshBoxAccessToken($this->getConfigValue(BOX_CONFIG_REFRESH_TOKEN));
            $decoded_response = json_decode($response);
            if (multiple_property_exists($decoded_response, ['error', 'error_description'])) {
                log_message('ERROR', ($decoded_response->error_description ?? ERROR_MESSAGE));
                //
                $this->_log_message(
                    LOG_TYPE_API,
                    LOG_SOURCE_BOX_CRON,
                    LOG_LEVEL_ERROR,
                    $decoded_response->error_description ?? ERROR_MESSAGE,
                    ''
                );
            } else {
                log_message('ERROR', 'Setting box session');
                // update sesssion
                $this->setBoxSession($decoded_response, TRUE);
            }
        }
        return false;
    }

    /**
     * Method revokeBoxAccessToken
     *
     * @param array $headers
     *
     * @return ?string
     */
    public function revokeBoxAccessToken(array $headers, string $token): ?string
    {
        $headers = [
            'content-type: application/x-www-form-urlencoded',
        ];

        $post_fields = array();
        if ($token) {

            $post_fields = array(
                'client_id' => BOX_CLIENT_ID,
                'client_secret' => BOX_CLIENT_SECRET,
                'token' => $token,
            );

            // refresh access token
            return $this->curlRequest(BOX_OAUTH_REVOKE_URL, $headers, $post_fields, TRUE, FALSE, '', TRUE);
        }
        return NULL;
    }

    /**
     * Method setBoxSession
     *
     * @param object $decoded_response
     *
     * @return bool
     */
    public function setBoxSession(object $decoded_response, bool $is_cron = FALSE): bool
    {
        $updated = 0;
        try {
            if(!$is_cron) {
                $this->session->set_userdata(
                    'box',
                    array(
                        'access_token' => $decoded_response->access_token,
                        'expires_in' => $decoded_response->expires_in,
                        'refresh_token' => $decoded_response->refresh_token,
                        'token_type' => $decoded_response->token_type,
                        'expiry_time' => date('Y-m-d H:i:s', strtotime('+' . ((int) (($decoded_response->expires_in))) . ' seconds'))
                    )
                );
            }

            if ($is_cron || ($this->model_signup->hasPremiumPermission() || $this->model_signup->hasRole(ROLE_3))) {
                $configArray = array(
                    BOX_CONFIG_ACCESS_TOKEN => $decoded_response->access_token,
                    BOX_CONFIG_REFRESH_TOKEN => $decoded_response->refresh_token,
                    BOX_CONFIG_TOKEN_EXPIRY => ''
                );
                
                if (property_exists($decoded_response, 'expires_in') && $decoded_response->expires_in) {
                    $configArray[BOX_CONFIG_TOKEN_EXPIRY] = date('Y-m-d H:i:s', strtotime('+' . ((int) (($decoded_response->expires_in))) . ' seconds'));
                }
                // update config value
                $updated = $this->model_config->update_config($configArray);
                log_message('ERROR', 'Updating box config');
            } else {
                $updated = 1;
            }
        } catch (\Exception $e) {
            //
            $this->_log_message(
                LOG_TYPE_API,
                LOG_SOURCE_BOX_CRON,
                LOG_LEVEL_ERROR,
                $e->getMessage(),
                ''
            );
            log_message('ERROR', $e->getMessage());
            return false;
        }

        if ($updated) {
            log_message('ERROR', 'Box config has been updated on: ' . date('Y-m-d H:i:s'));
            //
            $this->_log_message(
                LOG_TYPE_API,
                LOG_SOURCE_BOX_CRON,
                LOG_LEVEL_INFO,
                'Box config has been updated',
                ''
            );
            return true;
        } else {
            return false;
        }
    }
}
