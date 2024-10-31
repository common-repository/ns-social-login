<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ns_social_login_activate_set_options()
{
    add_option('ns_social_login_app_id', '');
    add_option('ns_social_login_app_secret', '');
	
}

register_activation_hook( __FILE__, 'ns_social_login_activate_set_options');



function ns_social_login_register_options_group()
{
    register_setting('ns_social_login_options_group', 'ns_social_login_app_id'); 
    register_setting('ns_social_login_options_group', 'ns_social_login_app_secret'); 
		
}
 
add_action ('admin_init', 'ns_social_login_register_options_group');

?>