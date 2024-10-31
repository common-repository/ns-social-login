<?php
/** Template Name: Template Social Login CallBack
*/

    $option_app_id = get_option('ns_social_login_app_id');
    $option_app_secret = get_option('ns_social_login_app_secret');

    $fb = new Facebook\Facebook([
        'app_id' => $option_app_id, // Replace {app-id} with your app id
        'app_secret' => $option_app_secret,
        'default_graph_version' => 'v2.10',
        ]);
    
    $helper = $fb->getRedirectLoginHelper();
    if (isset($_GET['state'])) { $helper->getPersistentDataHandler()->set('state', $_GET['state']); }
    
    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    
    if (! isset($accessToken)) {
        if ($helper->getError()) {
        //header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
        }
        exit;
    }
    
    // Logged in
    //echo '<h3>Access Token</h3>';
    //var_dump($accessToken->getValue());
    
    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();
    
    // Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    //echo '<h3>Metadata</h3>';
    //var_dump($tokenMetadata);
    
    // Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId($option_app_id); // Replace {app-id} with your app id
    // If you know the user ID this access token belongs to, you can validate it here
    //$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();
    
    if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
        exit;
        }
    
        //echo '<h3>Long-lived</h3>';
    // var_dump($accessToken->getValue());
    }
    
    $_SESSION['fb_access_token'] = (string) $accessToken;
    
    // User is logged in with a long-lived access token.
    // You can redirect them to a members-only page.

    /*USER DATA*/
    $user = ns_social_login_retrieve_fb_user_data( $_SESSION['fb_access_token'], $option_app_id, $option_app_secret );

    /*UTENTE NON ANCORA REGISTRATO?*/
    if(!ns_fb_check_if_user_on_db($user)){
        /*SAVING ON DB*/
        //echo 'FALSE';
        ns_social_login_save_user_on_db($user);
    }
    else{
        //echo 'TRUE';
        //LOGIN

        $ns_social_login_user = get_user_by( 'email', $user['email'] );

        $c_user = wp_set_current_user( $ns_social_login_user->ID, $ns_social_login_user->user_login );
        if($c_user ->ID != 0){
            wp_set_auth_cookie( $ns_social_login_user->ID );

            do_action( 'wp_login', $ns_social_login_user->user_login, $ns_social_login_user );
        }
        else{
            echo 'Login Error.';
        }

        
    }

    header('Location: '.get_home_url());




  /*THIS FUNCTION RETRIEVING USER DATA */
  function ns_social_login_retrieve_fb_user_data($token, $option_app_id, $option_app_secret){
    $fb = new Facebook\Facebook([
        'app_id' => $option_app_id,
        'app_secret' => $option_app_secret,
        'default_graph_version' => 'v2.10',
        ]);
      
      try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,email,name,locale,first_name,last_name,picture', $token);
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
      
      $user = $response->getGraphUser();
      
      //echo 'VALORI: '; print_r($user);
      return $user;
      // OR
      // echo 'Name: ' . $user->getName();
  }


/*CHECK IF THE USER IS ALREADY REGISTERED INTO THE SYSTEM*/
function ns_fb_check_if_user_on_db($fb_user){
    if(!email_exists($fb_user['email'])){    
        if(!username_exists($fb_user['first_name'])){
            echo $fb_user['first_name'];
            return false;
        }
        
        if(!username_exists(strtolower($fb_user['first_name']))){
            echo strtolower($fb_user['first_name']);
            return false;
        }
    }

    return true;
}

/*THIS FUNCTION SAVES FB USER DATA ON SYSTEM DB*/
function ns_social_login_save_user_on_db($fb_user){
    //Dati utente FB
    $fb_user_name = $fb_user['first_name'];
    $fb_user_last_name = $fb_user['last_name'];
    $fb_user_email = $fb_user['email'];
    $fb_user_locale = $fb_user['locale'];   
    $pssw = wp_generate_password();

    $userdata = array(
        'user_login'  =>  $fb_user_name,
        'user_email'    =>  $fb_user_email,
        'first_name'    =>  $fb_user_name,
        'last_name'    =>  $fb_user_last_name,
        'user_pass'   =>  $pssw  // When creating an user, `user_pass` is expected.
    );

    $user_id = wp_insert_user($userdata);

    
    /*SEND MAIL TO NEW USER*/
    ns_social_login_send_email($fb_user_email, $fb_user_name, $pssw);
}


?>