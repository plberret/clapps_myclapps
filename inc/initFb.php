<?php 

require_once 'settings.php';
require_once 'api/fbsdk/facebook.php';

$facebook = new Facebook(array(
	'appId' => APP_ID,
	'secret' => APP_SECRET,
));

$user_fb = $facebook->getUser();
//	$loginUrl = $facebook->getLoginUrl();

?>