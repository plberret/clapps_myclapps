<?php 

$host="localhost";
$user="root";
$pass="";
$base="appliMyClapps";

$baseDD = connect($host, $user, $pass, $base);

function connect($host,$user,$pass, $base) {
  try{
    $bd=new PDO('mysql:host='.$host.';dbname='.$base ,$user,$pass);
  }catch (Exception $e){
    die('BDD CONNEXION ERROR!');
  }
  return $bd;
}
		
?>