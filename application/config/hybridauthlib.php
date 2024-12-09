<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =
    array(
        // set on "base_url" the relative url that point to HybridAuth Endpoint
        'base_url' => '/hauth/endpoint',

        "providers" => array(
            // openid providers
            "OpenID" => array(
                "enabled" => true
            ),

            "Yahoo" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => ""),
            ),

            "AOL" => array(
                "enabled" => true
            ),

            "Google" => array(
                "enabled" => true,
                "keys" => array("id" => "444316386441-qhfnonrvgmhchbb8kcksn22u1hb2nvv3.apps.googleusercontent.com", "secret" => "7kECrqYoBDnDPA16G1nKZyYx"),
            ),

            "Facebook" => array(
                "enabled" => true,
                "keys" => array("id" => "332324010442053", "secret" => "27fe53e1bf1be50a6d49fb813f8d5941"),
            ),

            "Twitter" => array(
                "enabled" => true,
                "keys" => array("key" => "nelH30hOX315p1SBKcfd0iybw", "secret" => "hclbUEKTrevoOdCVRy0dszWVkNd0VbhU3WkVk3dySJcKjNUyTP")
            ),

            // windows live
            "Live" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => "")
            ),

            "MySpace" => array(
                "enabled" => true,
                "keys" => array("key" => "", "secret" => "")
            ),

            "LinkedIn" => array(
                "enabled" => true,
                "keys" => array("key" => "", "secret" => "")
            ),

            "Foursquare" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => "")
            ),
        ),

        // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
        "debug_mode" => (ENVIRONMENT == 'development'),

        "debug_file" => APPPATH . '/logs/hybridauth.log',
    );


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */