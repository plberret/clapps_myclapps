<?php

	require_once 'connect.php';
	require_once 'init.php';

	function getValideDate($date){
		$tsstart = DateTime::createFromFormat('Y-m-j',$date);
		$start = $tsstart->getTimestamp();
		$now = time();
		$nbjours = ($now-$start)/(60*60*24);
		$tt = strtotime('+2 week',$date);
		$tt = 15*(60*60*24);
		// var_dump($start + $tt);
		// echo date('j/m/Y',$start + $tt);
		return intval(-($now - ($start + $tt))/(60*60*24));
		// return date('j', strtotime('+2 week',$date));
		// return $nbjours;
	}

	function dateFormat($format, $date){
		$Date = DateTime::createFromFormat('Y-m-j',$date);
		$DateTs = $Date->getTimestamp();
		return date($format,$DateTs);
	}

	function dateUstoFr($date){
		$parsedDate = date_parse($date);
		switch ($parsedDate['month']) {
			case '01':
				$parsedDate['month'] = 'janvier';
				break;
			case '02':
				$parsedDate['month'] = 'février';
				break;
			case '03':
				$parsedDate['month'] = 'mars';
				break;
			case '04':
				$parsedDate['month'] = 'avril';
				break;
			case '05':
				$parsedDate['month'] = 'mai';
				break;
			case '06':
				$parsedDate['month'] = 'juin';
				break;
			case '07':
				$parsedDate['month'] = 'juillet';
				break;
			case '08':
				$parsedDate['month'] = 'aout';
				break;
			case '09':
				$parsedDate['month'] = 'septembre';
				break;
			case '10':
				$parsedDate['month'] = 'octobre';
				break;
			case '11':
				$parsedDate['month'] = 'novembre';
				break;
			case '12':
				$parsedDate['month'] = 'décembre';
				break;
			default:
				# code...
				break;
		}
		return $parsedDate['day']." ".$parsedDate['month']." ".$parsedDate['year'];
	}

	function arrayToUrl($array){
		$url ="";
		foreach ($array as $key => $value) {
			$url.='&'.urlencode($key).'='.urlencode($value);
		}
		return $url;
	}

	function changeFavoriteFilter($data){
		global $baseDD;
		
		$user = getIdFromFb();
		$R1=$baseDD->prepare('UPDATE mc_users SET filter = :filter WHERE id_creator = :id_user');
		$R1->bindParam(':filter',$data);
		if ($R1->execute()) {
			echo json_encode(array(success => true ));
		}
	}

	function addProject($data){
		 
		global $baseDD;
		
		// print_r($data);
		$user = getIdFromFb();
		$data['id_creator'] = $user['id_user'];
		$dt = date_create_from_format( 'd/m/Y', $data['date_tournage'] );
		$dateTournage =  $dt->format( 'Y/m/d' );
		$R1=$baseDD->prepare("INSERT INTO `mc_project` (title, description, id_creator, create_date, place, date_filter, type_place) VALUES ( :title, :description, :id_creator, NOW(), :place, :date_filter, :type_place)");
		$R1->bindParam(':title',$data['title']);
		$R1->bindParam(':description',$data['desc']);
		$R1->bindParam(':id_creator',$data['id_creator']);
		$R1->bindParam(':place',$data['id_place']);
		$R1->bindParam(':type_place',$data['type_place']);
		$R1->bindParam(':date_filter',$dateTournage);
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
		// $R1=$baseDD->prepare('DELETE FROM mc_project WHERE id_project = :id_project AND id_creator = :id_user');
		$R1=$baseDD->prepare('UPDATE mc_project SET current_state = 0 WHERE id_project = :id_project AND id_creator = :id_user');
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
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project IN (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
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
		$sql = "SELECT id_project, title, description, id_creator, create_date, date_filter, (SELECT nom FROM villes WHERE id = place) AS place, (SELECT cp FROM villes WHERE id = place) AS zip_code, (SELECT img_url FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project`";
		
		if (!empty($user_fb)) {
			$sql .= ' WHERE id_creator = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb) OR id_project IN (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
			$array = array(':user_fb' => $user_fb);
		}

		$sql .= " ORDER BY id_project DESC";
		$sql .= ' LIMIT '.(POST_PER_PAGE*($page-1)).','.POST_PER_PAGE;

// echo $sql;
		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		if($R1->execute($array)){
			$projects=$R1->fetchAll();
		}
		return $projects;
	 }
	
	 function getProject($id_project){

		global $baseDD;
		$sql = "SELECT id_project, title, description, id_creator, create_date, date_filter, (SELECT nom FROM villes WHERE id = place) AS place, (SELECT cp FROM villes WHERE id = place) AS zip_code, (SELECT img_url FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project` WHERE id_project=:id_project";
		$array = array('id_project' => $id_project);
		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);

		if($R1->execute($array)){
			$projects=$R1->fetchAll();
		}
		 
		return $projects;
	 }

	 function getAutocompletionJsonJobs($job){

	 	global $baseDD;

	 	$sql = 'SELECT id_job, name, domain FROM mc_jobs WHERE name LIKE :job GROUP BY name ORDER BY name ASC';
		$array = array(':job' => $job.'%');
		$R1 = $baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		if($R1->execute($array)){
			$result=$R1->fetchAll();
		}
		echo json_encode($result);
	}

	 function getAutocompletionJsonCities($ville,$restricted = false){

	 	global $baseDD;

	 	if (is_numeric($ville)){

	 		$sql2 = 'SELECT cp, nom, id_ville FROM villes WHERE cp LIKE :dpt GROUP BY nom ORDER BY nom ASC';
			$array2 = array(':dpt' => $ville.'%');
			$R2 = $baseDD->prepare($sql2);
			$R2->setFetchMode(PDO::FETCH_ASSOC);
			if($R2->execute($array2)){
				$dept=$R2->fetchAll();
			}
			echo json_encode($dept);

	 	} else {

			$sql2 = 'SELECT nom_departement AS nom, code AS cp, id_departement AS id_ville, "departements" AS `type`  FROM departements WHERE nom_departement LIKE :dpt OR code LIKE :dpt OR nom_departement LIKE :dpt2 ORDER BY nom_departement ASC';
			$array2 = array(':dpt' => '%'.$ville.'%',':dpt2' => '%'.str_replace('-',' ',$ville).'%');
			$R2 = $baseDD->prepare($sql2);
			$R2->setFetchMode(PDO::FETCH_ASSOC);
			if($R2->execute($array2)){
				$result=$R2->fetchAll();
			}

	 		$sql = 'SELECT cp, nom, id_ville, indice FROM villes WHERE';
			if ($restricted) {
				$sql.=' restricted != 1';
			} else {
				$sql.=' restricted != 2';
			}
	 		$sql.=' AND (nom LIKE :ville OR nom LIKE :ville2) ORDER BY indice DESC, nom ASC, CHAR_LENGTH(nom)';
	 		// echo $sql;
		 	//$sql = 'SELECT v.cp, v.nom, v.id_ville, d.nom_departement, r.nom_region FROM villes AS v, departements AS d, regions AS r WHERE v.nom LIKE :ville OR v.nom LIKE :ville2 OR d.nom_departement LIKE :dpt OR r.nom_region LIKE :region GROUP BY nom ORDER BY nom ASC';
			// $array = array('ville' => $ville.'%','ville2' => str_replace(' ','-',$ville).'%', 'dpt' => $ville.'%', 'region' => $ville.'%');
			$array = array(':ville' => $ville.'%',':ville2' => str_replace(' ','-',$ville).'%');
			$R1 = $baseDD->prepare($sql);
			$R1->setFetchMode(PDO::FETCH_ASSOC);
			if($R1->execute($array)){
				$villes=$R1->fetchAll();
				foreach ($villes as $ville) {
					$ville['type']='villes';
					array_push($result,$ville);
				}
			}

			$sql3 = 'SELECT nom_region AS nom, id_region AS id_ville FROM regions WHERE nom_region LIKE :region ORDER BY nom_region ASC';
			$array3 = array(':region' => '%'.$ville.'%');
			$R3 = $baseDD->prepare($sql3);
			$R3->setFetchMode(PDO::FETCH_ASSOC);
			if($R3->execute($array3)){
				$regions=$R3->fetchAll();
				foreach ($regions as $reg) {
					$reg['type']='regions';
					array_push($result,$reg);
				}
			}

			echo json_encode($result);
	 	}	 	
	 }

	 function getProjectsByFilters($page,$filters){
	 	global $baseDD;
	 	// SELECT id_project, title, description, id_creator, create_date, date_filter, (SELECT nom FROM villes WHERE id_ville = place) AS place, (SELECT cp FROM villes WHERE id_ville = place) AS zip_code, (SELECT img_url FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project`
		$sql =  "SELECT pj.id_project, pj.title, pj.description, pj.id_creator, pj.create_date, pj.date_filter, (SELECT nom FROM villes WHERE id_ville = pj.place) AS place, (SELECT cp FROM villes WHERE id_ville = pj.place) AS zip_code, (SELECT img_url FROM mc_users WHERE mc_users.id_user = pj.id_creator) AS img_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = pj.id_creator) AS name_creator FROM mc_project AS pj, mc_profile AS pf WHERE pj.id_project = pf.id_project";

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