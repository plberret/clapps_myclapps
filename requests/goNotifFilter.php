<?php
	header('Content-Type: application/json'); 
	require_once('../inc/functions.php');
	$users = getNotifUserFilter(812);
	sendNotif('Notification',$users);
?>