<?php
require_once 'settings.php';

function connect($host,$user,$pass, $base) {
  try{
    $bd=new PDO('mysql:host='.$host.';dbname='.$base, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  }catch (Exception $e){
    die($e->getMessage());
  }
  return $bd;
}

$baseDD = connect(HOST, USER, PASS, BASE);
		
?>     
