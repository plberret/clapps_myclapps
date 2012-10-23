<?php 

$host="localhost";
$user="root";
$pass="root";
$base="appliMyClapps";

function connect($host,$user,$pass, $base) {
  try{
    $bd=new PDO('mysql:host='.$host.';dbname='.$base, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  }catch (Exception $e){
    die('BDD CONNEXION ERROR!');
  }
  return $bd;
}

$baseDD = connect($host, $user, $pass, $base);
		
?>     
