<?php
function ns_social_login_mail_template($ns_titolo, $ns_testo){
	return '
<!DOCTYPE html>
<html dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>NoStudio</title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
	<div id="wrapper" dir="ltr" style="background-color: #f5f5f5; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
		<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
			<tr>
				<td align="center" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #fdfdfd; border: 1px solid #dcdcdc; border-radius: 3px !important;">
						<tr>
							<td align="center" valign="top">
								<!-- Header -->
								<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style=\'background-color: #6baa01; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;\'>
									<tr>
										<td id="header_wrapper" style="padding: 36px 48px; display: block;">
											<h1 style=\'color: #ffffff; font-family: "Varela Round", sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #3da3e5; -webkit-font-smoothing: antialiased;\'>Registration to '.$ns_titolo.'</h1>
										</td>
									</tr>
								</table>
								<!-- End Header -->
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<!-- Body -->
								<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
									<tr>
										<td valign="top" id="body_content" style="background-color: #fdfdfd;">
											<!-- Content -->
											<table border="0" cellpadding="20" cellspacing="0" width="100%">
												<tr>
													<td valign="top" style="padding: 48px;">
														<div id="body_content_inner" style=\'color: #000; font-family: "Montserrat", sans-serif; font-size: 14px; line-height: 150%; text-align: left;\'>

															<p style="margin: 0 0 16px;">'.$ns_testo.'<br><br><br></p>

															<hr>
															<p style="margin: 0 0 16px; color: #6baa01;">'.$ns_titolo.'</p>

														</div>
													</td>
												</tr>
											</table>
											<!-- End Content -->
										</td>
								</tr>
								</table>
								<!-- End Body -->
							</td>
						</tr>

					</table>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>
';
}

function ns_social_login_send_email($fb_user_email, $fb_user_name, $pssw){
	
	$title = str_replace('https://', '', get_home_url());
	$title = str_replace('http://', '', $title);

	$text = '<p>Thanks for your registration to <a href="'.get_home_url().'" style="color: #6baa01;">'.$title.'</a>.</p>
	<p>
		Now you can log in using the data below or directly using your Facebook account.
		<br><br>
		User Name: <b>'.$fb_user_name.'</b>
		<br><br>
		Email: <b>'.$fb_user_email.'</b>
		<br><br>
		User Login: <b>'.$fb_user_name.'</b>
		<br><br>
		Password: <b>'.$pssw.'</b>
	</p>
	';

	$text_template = ns_social_login_mail_template($title, $text);


	wp_mail( $fb_user_email, 'Registration to '.$title , $text_template);
    
}
?>