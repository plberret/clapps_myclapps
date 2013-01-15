<?php 
	// $user_fb="BBBBBBBB";
	//$user_fb="AAAAAAAA";
	require_once 'settings.php';

	$auth_url = "https://www.facebook.com/dialog/oauth?client_id=" . APP_ID . "&redirect_uri=" . urlencode(APP_URL);
$auth_url = "https://www.facebook.com/dialog/oauth?client_id=" .APP_ID. "&redirect_uri=" . urlencode("https://www.facebook.com/pages/null/" .APP_PAGE_ID. "/?sk=app_".APP_ID);
	
	$signed_request = $_REQUEST["signed_request"];

	list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

	$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

	if (empty($data["user_id"])){
		if(empty($user_fb)) {
		// if(empty($_SESSION['data']['user_id'])) {
		//	echo("<script> top.location.href='" . $auth_url . "'</script>");
		$user_fb=$data['user_id'];
		}
	} else {
		$user_fb=$data['user_id'];
	}
?>