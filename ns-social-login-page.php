<?php
/*
Plugin Name: NS Social login
Plugin URI: http://www.nsthemes.com/
Description: Use your facebook login to create a account on your site
Version: 1.3.3
Author: NsThemes
Author URI: http://nsthemes.com
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 
 * @author        PluginEye
 * @copyright     Copyright (c) 2019, PluginEye.
 * @version         1.0.0
 * @license       https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * PLUGINEYE SDK
*/

require_once('plugineye/plugineye-class.php');
$plugineye = array(
    'main_directory_name'       => 'ns-social-login',
    'main_file_name'            => 'ns-social-login-page.php',
    'redirect_after_confirm'    => 'admin.php?page=ns-social-login%2Fns-admin-options%2Fns_admin_option_dashboard.php',
    'plugin_id'                 => '247',
    'plugin_token'              => 'NWNmZmJlODUwN2E2NzMzZTE0YzI3MjQ3ZGFlNmNhMmFjNTY1Y2Y3ZDVmYTQyMDBkZWZhNDg3OTE4YzUyYTJkZmNjY2I2ZDA5YjQzMjk=',
    'plugin_dir_url'            => plugin_dir_url(__FILE__),
    'plugin_dir_path'           => plugin_dir_path(__FILE__)
);

$plugineyeobj247 = new pluginEye($plugineye);
$plugineyeobj247->pluginEyeStart();      


if ( ! defined( 'FB_TEST_NS_PLUGIN_DIR' ) )
    define( 'FB_TEST_NS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'FB_TEST_NS_PLUGIN_DIR_URL' ) )
    define( 'FB_TEST_NS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );



/* *** plugin options *** */
require_once( FB_TEST_NS_PLUGIN_DIR.'/ns-social-login-options.php');


require_once( plugin_dir_path( __FILE__ ).'ns-admin-options/ns-admin-options-setup.php');

require_once( plugin_dir_path( __FILE__ ).'inc/Facebook/autoload.php');

//require_once( plugin_dir_path( __FILE__ ).'inc/ns-social-login-callback.php');

require_once( plugin_dir_path( __FILE__ ).'inc/ns-social-login-send-registration-email.php');

function ns_show_fb_login( $atts ) {
    $option_app_id = get_option('ns_social_login_app_id');
    $option_app_secret = get_option('ns_social_login_app_secret');

    $fb = new Facebook\Facebook([
        'app_id' => $option_app_id, // Replace {app-id} with your app id
        'app_secret' => $option_app_secret,
        'default_graph_version' => 'v2.10',
        ]);
    
    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ['email']; // Optional permissions
    
    $loginUrl = $helper->getLoginUrl(get_home_url().'/callback/', $permissions);
    
    return '<div id="fb-share-button">
                <svg viewBox="0 0 12 12" preserveAspectRatio="xMidYMid meet">
                    <path class="svg-icon-path" d="M9.1,0.1V2H8C7.6,2,7.3,2.1,7.1,2.3C7,2.4,6.9,2.7,6.9,3v1.4H9L8.8,6.5H6.9V12H4.7V6.5H2.9V4.4h1.8V2.8 c0-0.9,0.3-1.6,0.7-2.1C6,0.2,6.6,0,7.5,0C8.2,0,8.7,0,9.1,0.1z"></path>
                </svg>
                <a style="color: #fff; padding: 8px;" href="' . htmlspecialchars($loginUrl) . '">Register or Log in with Facebook!
                </a>
            </div>';
}
add_shortcode('ns-social-login', 'ns_show_fb_login');


/*ADDING THE FB LOGIN BUTTON TO WP-LOGIN FORM*/
add_action( 'login_form', 'ns_add_fb_login_wp_login_body' );

function ns_add_fb_login_wp_login_body() {
    $option_app_id = get_option('ns_social_login_app_id');
    $option_app_secret = get_option('ns_social_login_app_secret');

    $fb = new Facebook\Facebook([
        'app_id' => $option_app_id, // Replace {app-id} with your app id
        'app_secret' => $option_app_secret,
        'default_graph_version' => 'v2.10',
        ]);
    
    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ['email']; // Optional permissions
    
    $loginUrl = $helper->getLoginUrl(get_home_url().'/callback/', $permissions);
    
    echo '  <div id="fb-share-button" style="margin-bottom: 20px; background: #3b5998; border-radius: 3px; font-weight: 600; padding: 5px 8px; display: inline-block; position: static;">
                <svg viewBox="0 0 12 12" preserveAspectRatio="xMidYMid meet" style="width: 18px; fill: white; vertical-align: middle; border-radius: 2px">
                    <path class="svg-icon-path" d="M9.1,0.1V2H8C7.6,2,7.3,2.1,7.1,2.3C7,2.4,6.9,2.7,6.9,3v1.4H9L8.8,6.5H6.9V12H4.7V6.5H2.9V4.4h1.8V2.8 c0-0.9,0.3-1.6,0.7-2.1C6,0.2,6.6,0,7.5,0C8.2,0,8.7,0,9.1,0.1z"></path>
                </svg>
                <a style="color: #fff; padding: 8px; text-decoration: none;" href="' . htmlspecialchars($loginUrl) . '">Register or Log in with Facebook!
                </a>
            </div>';
}
/********************************************/

/**CREATE DEFAULT PAGE ON INIT*/
add_action( 'init', 'ns_social_login_create_default_page' );
function ns_social_login_create_default_page(){
	if(!get_page_by_title('callback', 'OBJECT', 'page')){
		$args = array('post_title' => 'callback', 'post_status' => 'publish', 'post_type' => 'page');
		$page_id = wp_insert_post($args);
		update_post_meta( $page_id, '_wp_page_template', 'ns-fb-template-page.php' );
	}
}


add_filter( 'page_template', 'ns_social_login_page_template' );
function ns_social_login_page_template( $page_template )
{
   if ( is_page( 'callback' ) ) {
       $page_template =  plugin_dir_path( __FILE__ ).'/ns-fb-template-page.php';
   }
   return $page_template;
}

/******************************/

/* *** add link premium *** */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'nssociallogin_add_action_links' );

function nssociallogin_add_action_links ( $links ) {	
 $mylinks = array('<a id="nsslpremiumlinkpremium" href="https://www.nsthemes.com/?ref-ns=2&campaign=SL-linkpremium" target="_blank">'.__( 'Join NS Club', 'ns-social-login' ).'</a>');
return array_merge( $links, $mylinks );
}

?>