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

if(empty($data["user_id"])){
	if(empty($user_fb)) {
		echo("<script> top.location.href='" . $auth_url . "'</script>"); 
	}
}else{
	if(!getIdFromFb()){
		$data =$facebook->api('/me');
		createUser($data);
	}
}
/*
$app_token_request = "https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . APP_ID
        . "&client_secret=" . APP_SECRET 
        . "&grant_type=client_credentials";

$response = file_get_contents($app_token_request);

$params = null;
parse_str($response, $params);
echo("This app's access token is: " . $params['access_token']);

try {
	$facebook->api('/me/notifications','POST', array(
		'access_token' => $params['access_token'],
		'template' => 'Hello World!',
		'href' => 'www.my.clapps.fr'
	));
	echo 'pulbié';
} catch(FacebookApiException $e) {
	$result = $e->getResult();
	?><pre><?php
	print_r($result);
	?></pre><?php
}
//$notif =$facebook->api('/me/notifications');
*/

?>
