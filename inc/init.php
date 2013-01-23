<?php 

require_once 'settings.php';
require_once 'api/fbsdk/facebook.php';
require_once './inc/functions.php';


$facebook = new Facebook(array(
	'appId' => APP_ID,
	'secret' => APP_SECRET,
	));

// Get User ID
$auth_url = "https://www.facebook.com/dialog/oauth?client_id=" .APP_ID. "&scope=publish_actions,manage_notifications&redirect_uri=" . urlencode("https://www.facebook.com/pages/null/" .APP_PAGE_ID. "/?sk=app_".APP_ID);

$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

if ($data['app_data']) {
	$auth_url .=  urlencode("&app_data=".$data['app_data']);
}
// var_dump($data['app_data']);

if($_GET['n']=='app'){
	echo("<script> top.location.href='" . $auth_url . "'</script>"); 
}

if(empty($data["user_id"])){
	if(empty($user_fb)) {
		echo("<script> top.location.href='" . $auth_url . "'</script>"); 
	}
}else{
	if(!getIdFromFb()){
		$data =$facebook->api('/me');
		createUser($data);
	}
	if($data['app_data']){
		echo("<script> window.location.href='?id_project=".$data['app_data']."'</script>");
	}
}
/*
$notification_message = 'à postuler à l un de vos proje ';
$notification_app_link = '?n=app'; // The link the notification will go through to, this will be specific to your in Facebook App
$user = $facebook->getUser();
if ($user) {
	/*
	* Facebook user retrieved
	* $user : Holds the Facebook Users unique ID - Required for posting a notification to them
	* */ /*
	try {
		// Try send this user a notification
		$fb_response = $facebook->api('/' . $user . '/notifications', 'POST',
		array(
			'access_token' => $facebook->getAppId() . '|' . $facebook->getApiSecret(), // access_token is a combination of the AppID & AppSecret combined
			'href' => $notification_app_link, // Link within your Facebook App to be displayed when a user click on the notification
			'template' => $notification_message, // Message to be displayed within the notification
		));
		if (!$fb_response['success']) {
			// Notification failed to send
			echo '<p><strong>Failed to send notification</strong></p>'."\n";
			echo '<p><pre>' . print_r($fb_response, true) . '</pre></p>'."\n";
		} else {
			// Success!
			echo '<p>Your notification was sent successfully</p>'."\n";
		}

	} catch (FacebookApiException $e) {
		// Notification failed to send
		echo '<p><pre>' . print_r($e, true) . '</pre></p>';
		$user = NULL;
	}
} else {
	// No Facebook user fetched, show FB login button - Requires Facebook JavaScript SDK (Below)
	echo '<fb:login-button></fb:login-button>'."\n";
}
*/
?>
