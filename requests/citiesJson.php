<?php 
	header('Content-Type: application/json'); 
	require_once('../inc/functions.php');
	getAutocompletionJsonCities($_GET['ville'],$_GET['restricted'])
?>