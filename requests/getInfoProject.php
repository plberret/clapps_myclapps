<?php
	header('Content-Type: application/json'); 
	require_once('../inc/functions.php');
	getInfoProject($_POST['project'], $_POST['profile']);
?>