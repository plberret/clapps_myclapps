<?php

	require_once('connect.php');
	 
	 function getProjects(){
		 
		global $baseDD;
		 
		 $R1=$baseDD->prepare("SELECT * FROM `mc_project`");
		 $R1->setFetchMode(PDO::FETCH_ASSOC);
		 if($R1->execute()){
			$projects=$R1->fetchAll();
		 }
		 
		 echo '<pre>';
			print_r($projects);
			echo '</pre>';
		 
		 return $projects;
	
	 }
	 
   ?>