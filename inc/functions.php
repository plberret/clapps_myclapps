<?php

	require_once('connect.php');
	 
	 function getProjects(){
		 
		global $baseDD;
		 
		 $R1=$baseDD->prepare("SELECT * FROM `mc_project`");
		 $R1->setFetchMode(PDO::FETCH_ASSOC);
		
		 if($R1->execute()){
			$projects=$R1->fetchAll();
		 }
		 
		 return $projects;
	
	 }
	
	function getUserProjects(){

		global $baseDD;
		
		// recuperer les projets favoris + les prochains que l'utilisateur à créé

		 $R1=$baseDD->prepare("SELECT * FROM `mc_project`");
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$projects=$R1->fetchAll();
		 }

		 return $projects;

	 }
	
	function getProfiles($project){
		
		global $baseDD;
		
		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` WHERE ID_project=:project ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);
		
		 if($R1->execute()){
			$profiles=$R1->fetchAll();
		 }
		
		 return $profiles;
		
	 }
	
	function getActiveActors($project){

		global $baseDD;

		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` WHERE ID_project=:project AND Domain=1 AND Current_state=1 ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$actors=$R1->fetchAll();
		 }

		 return $actors;

	 }
	
	function getActiveTechnicians($project){

		global $baseDD;

		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` WHERE ID_project=:project AND Domain=2 AND Current_state=1 ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$technicians=$R1->fetchAll();
		 }

		 return $technicians;

	 }
	 
	
   ?>