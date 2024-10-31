<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php // PUT YOUR settings_fields name and your input // ?>
<?php settings_fields('ns_social_login_options_group'); ?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="ns_social_login_app_id">Facebook code</label>
				</th>
				<td class="forminp">
					<?php //wp_editor( get_option('ns_social_login_fb_code'), 'ns_social_login_fb_code', $settings = array('textarea_name'=>'ns_social_login_fb_code') ); ?>
					<textarea id="ns_social_login_app_id" name="ns_social_login_app_id"><?php echo get_option('ns_social_login_app_id'); ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>

</div>