<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php // PUT YOUR settings_fields name and your input // ?>
<?php settings_fields('ns_social_login_options_group'); ?>

<div class="wrap ns-settings-container">
	<div class="icon32" id="icon-options-general"><br /></div>
	<div>
	Add shortcode <b>[ns-social-login]</b> to insert login button in your page
	</div>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th class="titledesc ns-social-login-th" scope="row">
						<label for="ns_social_login_app_id">Facebook App ID</label>
					</th>
					<td class="forminp">
						<?php //wp_editor( get_option('ns_social_login_fb_code'), 'ns_social_login_fb_code', $settings = array('textarea_name'=>'ns_social_login_fb_code') ); ?>
						<textarea id="ns_social_login_app_id" name="ns_social_login_app_id"><?php echo get_option('ns_social_login_app_id'); ?></textarea>
					</td>
				</tr>
				<tr>
					<th class="titledesc ns-social-login-th" scope="row">
						<label for="ns_social_login_app_secret">Facebook Secret</label>
					</th>
					<td class="forminp">
						<?php //wp_editor( get_option('ns_social_login_fb_code'), 'ns_social_login_fb_code', $settings = array('textarea_name'=>'ns_social_login_fb_code') ); ?>
						<textarea id="ns_social_login_app_secret" name="ns_social_login_app_secret"><?php echo get_option('ns_social_login_app_secret'); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
</div>