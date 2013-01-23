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

if ($_GET['app_data']) {
	$auth_url .=  urlencode("&app_data=".$_GET['app_data']);
}

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

?>
