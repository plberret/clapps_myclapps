<?php
	header('Content-Type: application/json'); 
	require_once('../inc/functions.php');
	changeFavoriteFilter($_POST);
	// print_r($_POST);
?>