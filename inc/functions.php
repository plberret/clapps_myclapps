<?php

	require_once('connect.php');
	require_once('settings.php');
	
	function addProject($data){
		 
		global $baseDD;
		// print_r($data);
		$data['id_creator'] = '1';
		$R1=$baseDD->prepare("INSERT INTO `mc_project` (title, description, id_creator, create_date) VALUES ( :title, :description, :id_creator, NOW())");
		$R1->bindParam(':title',$data['title']);
		$R1->bindParam(':description',$data['desc']);
		$R1->bindParam(':id_creator',$data['id_creator']);
		$R1->setFetchMode(PDO::FETCH_ASSOC);

		if($R1->execute()){
			$ID=$baseDD->lastInsertId('mc_project');
		}
		
		foreach ($data['profile'] as $dat => $key) {
			if (!empty($data['profile'][$dat]) && !empty($data['domain'][$dat])) {
				$R2=$baseDD->prepare("INSERT INTO `mc_profile` (id_project, person, occurence, domain) VALUES ( :id, :person, :occurence, :domain)");
				$R2->bindParam(':id',$ID);
				$R2->bindParam(':person',$data['profile'][$dat]);
				$R2->bindParam(':occurence',$data['occurence'][$dat]);
				$R2->bindParam(':domain',$data['domain'][$dat]);
				if($R2->execute()){}
			}
		}

		echo json_encode(array('id' => $ID));
	 }

	 function getNbProject($user_fb)
	{

		global $baseDD;
		$sql = 'SELECT count(*) AS nb FROM `mc_project`';
		
		if (!empty($user_fb)) {
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project = (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
			$array = array('user_fb' => $user_fb);
		}
		
		$q = $baseDD->prepare($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$q->execute($array);
		return $q->fetchColumn();
	}


	 function getMaxPages($user_fb){

	 	global $baseDD;
	 	$sql = 'SELECT count(*) AS nb FROM `mc_project`';
		
		if (!empty($user_fb)) {
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project = (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
			$array = array('user_fb' => $user_fb);
		}
		$q = $baseDD->prepare($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$q->execute($array);
		$result = $q->fetchAll();
		return ceil($result[0]['nb']/POST_PER_PAGE);
	 }

	 function getProjects($page,$user_fb){

		global $baseDD;
		$sql = "SELECT id_project, title, description, id_creator, create_date, (SELECT img_url FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project`";
		
		if (!empty($user_fb)) {
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project = (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
			$array = array('user_fb' => $user_fb);
		}
		
		$sql .= " ORDER BY id_project DESC";
		$sql .= ' LIMIT '.(POST_PER_PAGE*($page-1)).','.POST_PER_PAGE;
		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($R1->execute($array)){
			$projects=$R1->fetchAll();
		}
		 
		return $projects;
	 }
	
	 function getProject($id_project){

		global $baseDD;
		$sql = "SELECT id_project, title, description, id_creator, create_date, (SELECT img_url FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project` WHERE id_project=:id_project";
		$array = array('id_project' => $id_project);
		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($R1->execute($array)){
			$projects=$R1->fetchAll();
		}
		 
		return $projects;
	 }
/*	function getUserProjects(){ Depreciated by getProjects($id_creator)

		global $baseDD;
		
		// recuperer les projets favoris + les prochains que l'utilisateur à créé

		 $R1=$baseDD->prepare("SELECT * FROM `mc_project`");
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$projects=$R1->fetchAll();
		 }

		 return $projects;
	 }*/
	
	function getProfiles($project){
		
		global $baseDD;
		
		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` WHERE id_project=:project AND current_state=1");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);
		
		 if($R1->execute()){
			$profiles=$R1->fetchAll();
		 }
		
		 return $profiles;
	}
	
	function getOccurences($ppl){
		$occurence=0;
		foreach ($ppl as $occ) {
			$occurence+=$occ['occurence'];
		}
		return $occurence;
	}
	
	function getActiveActors($project){

		global $baseDD;

		 $R1=$baseDD->prepare("SELECT person, occurence FROM `mc_profile` WHERE id_project=:project AND domain=1 AND current_state=1 ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$actors=$R1->fetchAll();
		 }

		 return $actors;

	 }
	
	function getActiveTechnicians($project){

		global $baseDD;

		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` WHERE id_project=:project AND domain=2 AND current_state=1 ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$technicians=$R1->fetchAll();
		 }

		 return $technicians;

	 }
	 
	
   ?>