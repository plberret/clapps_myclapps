<?php

	require_once 'connect.php';
	require_once 'init.php';

	function arrayToUrl($array){
		$url ="";
		foreach ($array as $key => $value) {
			$url.='&'.urlencode($key).'='.urlencode($value);
		}
		return $url;
	}

	function addProject($data){
		 
		global $baseDD;
		
		// print_r($data);
		$user = getIdFromFb();
		$data['id_creator'] = $user['id_user'];
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

	function addFavorite($project){

		global $baseDD;

		$user = getIdFromFb();
		$R1=$baseDD->prepare('INSERT INTO mc_favorite (id_project, id_user) VALUES (:id_project, :id_user)');
		$R1->bindParam(':id_project',$project['id']);
		$R1->bindParam(':id_user',$user['id_user']);
		if ($R1->execute()) {
			echo json_encode(array('success' => true ));
		}
	}

	function deleteFavorite($project){

		global $baseDD;

		$user = getIdFromFb();
		$R1=$baseDD->prepare('DELETE FROM mc_favorite WHERE id_project = :id_project AND id_user = :id_user');
		$R1->bindParam(':id_project',$project['id']);
		$R1->bindParam(':id_user',$user['id_user']);
		if ($R1->execute()) {
			echo json_encode(array(success => true ));
		}
	}

	function deleteProject($project){

		global $baseDD;

		$user = getIdFromFb();
		$R1=$baseDD->prepare('DELETE FROM mc_project WHERE id_project = :id_project AND id_creator = :id_user');
		$R1->bindParam(':id_project',$project['id']);
		$R1->bindParam(':id_user',$user['id_user']);

		if ($R1->execute()) {
			echo json_encode(array(success => true )); // triggered meme si retour vide
		} else {
			echo json_encode(array(success => false ));
		}
	}

	function getNbProject($user_fb){

		global $baseDD;
		$sql = 'SELECT count(*) AS nb FROM `mc_project`';
		
		if (!empty($user_fb)) {
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project = (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
			$array = array(':user_fb' => $user_fb);
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
			$array = array(':user_fb' => $user_fb);
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
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project = (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb2))';
			$array = array(':user_fb' => $user_fb,'user_fb2' => $user_fb);
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

	 function getProjectsByFilters($page,$filters)
	 {
	 	global $baseDD;

		$sql =  "SELECT pj.id_project, pj.title, pj.description, pj.id_creator, pj.create_date, (SELECT img_url FROM mc_users WHERE mc_users.id_user = pj.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = pj.id_creator) AS name_creator FROM mc_project AS pj, mc_profile AS pf WHERE pj.id_project = pf.id_project";

		if ($filters['domain']) {
			$sql .= " AND domain = :domain AND pf.current_state = 1";
		}

		// if ($filters['region']) {
			// $sql .= " AND getDistance((SELECT lat FROM villes WHERE id_ville = :ville),(SELECT lng FROM villes WHERE id_ville = :ville),lat,lng) < 100000";
		// }

		$sql .= " GROUP BY pj.id_project";
		$sql .= " ORDER BY id_project DESC";
		$sql .= ' LIMIT '.(POST_PER_PAGE*($page-1)).','.POST_PER_PAGE;
		
		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);

		if ($filters['domain']) {
			$R1->bindParam('domain',$filters['domain']);
		}
		
		if($R1->execute($array)){
			$projects=$R1->fetchAll();
		}
		 
		return $projects;
	 }
	
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

	function getProfilesFound($project){
		
		global $baseDD;
		
		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` WHERE id_project=:project AND current_state=2");
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
	
	function isFavorite($project){
		global $baseDD, $user_fb;
		
		$R1=$baseDD->prepare('SELECT id_project FROM `mc_favorite` WHERE id_project=:project AND id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb)');
		$R1->bindParam(':project',$project['id_project']);
		$R1->bindParam(':user_fb',$user_fb);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($R1->execute()){
			$profiles=$R1->fetchAll();
		}
		
		return $profiles;
	}

	function isAdmin($project){
		global $baseDD, $user_fb;
		
		$R1=$baseDD->prepare('SELECT id_project FROM `mc_project` WHERE id_project=:project AND id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb)');
		$R1->bindParam(':project',$project['id_project']);
		$R1->bindParam(':user_fb',$user_fb);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($R1->execute()){
			$profiles=$R1->fetchAll();
		}
		
		return $profiles;
	}

	function getIdFromFb(){
		global $baseDD, $user_fb;
		
		$R1=$baseDD->prepare('SELECT id_user FROM `mc_users` WHERE user_fb = :user_fb');
		$R1->bindParam(':user_fb',$user_fb);
		
		 if($R1->execute()){
			$id=$R1->fetch();
		}
		
		return $id;
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