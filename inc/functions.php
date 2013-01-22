<?php
	require_once 'initFb.php';
	require_once 'settings.php';
	require_once 'connect.php';
	require_once 'api/mailchimp/MCAPI.class.php';
	require_once 'api/mailchimp/config.inc.php';

	function getValideDate($date,$loop){
		$tsstart = DateTime::createFromFormat('Y-m-j',$date);
		$start = $tsstart->getTimestamp();
		$now = time();
		$nbjours = ($now-$start)/(60*60*24);
		$nb = 2+$loop;
		$tt = $nb*7*(60*60*24); // $nb semaines
		return intval(-($now - ($start + $tt))/(60*60*24));
		// return date('j', strtotime('+2 week',$date));
		// return $nbjours;
	}

	function activateProject($data){
		
		global $baseDD;

		$user = getIdFromFb();
		var_dump($data);
		var_dump($user);
		$sql = 'SELECT create_date, `loop` FROM mc_project WHERE id_project = :id_project AND id_creator = :id_user';
		$R1=$baseDD->prepare($sql);
		$R1->bindParam(':id_project',$data['id']);
		$R1->bindParam(':id_user',$user['id_user']);
		$R1->setFetchMode(PDO::FETCH_ASSOC);

		if($R1->execute()){
			$result=$R1->fetch();
			var_dump($result);
			if (getValideDate($result['create_date'],$result['loop'])<=DAY_UNTIL_REACTIVATE) {
				$loop = $result['loop']+1;
				echo $loop;
				$R2=$baseDD->prepare('UPDATE mc_project SET `loop` = :loop WHERE id_project = :id_project');
				$R2->bindParam(':loop',$loop);
				$R2->bindParam(':id_project',$data['id']);
				if ($R2->execute()) {
					echo json_encode(array(success => true ));
				}
			}
		}
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

	function changeFavoriteFilter($filter){
		global $baseDD;
		
		$user = getIdFromFb();
		$R1=$baseDD->prepare('UPDATE mc_users SET filter = :filter WHERE id_user = :id_user');
		$R1->bindParam(':filter',$filter['filter']);
		$R1->bindParam(':id_user',$user['id_user']);
		if ($R1->execute()) {
			$R2=$baseDD->prepare('INSERT INTO filter_regist (id_user,filter) VALUES (:id_user, :filter)');
			$R2->bindParam(':filter',$filter['filter']);
			$R2->bindParam(':id_user',$user['id_user']);
			if ($R2->execute()) {
				echo json_encode(array(success => true ));
			} else {
				echo json_encode(array(success => false ));
			}
		}
	}

	function updateProject($data){
		// var_dump($data);
		global $baseDD;
		
		// print_r($data);
		$user = getIdFromFb();

		// date tournage
		// $dt = date_create_from_format( 'd/m/Y', $data['date_tournage'] );
		// $dateTournage =  $dt->format( 'Y/m/d' );
		// type_place
		$array = array('id_project' => $data['id_project']);
		

		$sql = "UPDATE `mc_project` SET";

		if (!empty($data['title'])) {
			$sql .= " title = :title";
			$array['title'] = $data['title'];
		 }

		if (!empty($data['desc'])) {
			if (!empty($data['title'])) {
				$sql .= ",";
			}
			$sql .= " description = :description";
			$array['description'] = $data['desc'];
		 }

		 if (!empty($data['date_filter'])) {
		 	if (!empty($data['desc']) || !empty($data['title'])) {
		 		$sql .= ",";
			}
			$sql .= " date_filter = :date_filter";
			$array['date_filter'] = $data['date_filter'];
		 }

		if ($data['id_place']) {
			if (!empty($data['desc']) || !empty($data['title']) || empty($data['date_filter']) ) {
				$sql .= ",";
			}
			$array['place_villes'] = 0;
			$array['place_departements'] = 0;
			$array['place_regions'] = 0;
			$array['place_'.$data['type_place']] = $data['id_place'];
			$sql .= " place_villes = :place_villes,  place_departements = :place_departements, place_regions = :place_regions";
		}

		$sql .=" WHERE id_project = :id_project";
		// $sql .= "place_villes = :place_villes, date_filter = :date_filter, place_departements = :place_departements, place_regions = :place_regions");
		// $R1=$baseDD->prepare("INSERT INTO `mc_project` (title, description, id_creator, create_date, place_villes, date_filter, place_departements, place_regions) VALUES ( :title, :description, :id_creator, NOW(), :place_villes, :date_filter, :place_departements, :place_regions)");
		$R1=$baseDD->prepare($sql);
		// $R1->bindParam(':title',$data['title']);
		// $R1->bindParam(':description',$data['desc']);
		// $R1->bindParam(':id_creator',$data['id_creator']);
		// $R1->bindParam(':place_villes',$data['place_villes']);
		// $R1->bindParam(':place_departements',$data['place_departements']);
		// $R1->bindParam(':place_regions',$data['place_regions']);
		// $R1->bindParam(':date_filter',$dateTournage);
		$R1->setFetchMode(PDO::FETCH_ASSOC);

// echo $sql;

		if($R1->execute($array)){
			$R1b=$baseDD->prepare("DELETE FROM mc_profile WHERE id_project = :id_project AND current_state = 1");
			$R1b->bindParam(':id_project',$data['id_project']);
			$R1b->execute();
			if (!empty($data['profile'][0])) {
				foreach ($data['profile'] as $dat => $key) {
					if (!empty($data['profile'][$dat])) {
						if (empty($data['id_job'][$dat])) {
							$R2=$baseDD->prepare("INSERT INTO `mc_jobs` (name, domain) VALUES ( :name, 3)");
							$R2->bindParam(':name',$data['name'][$dat]);
							if($R2->execute()){
								$IDJOB=$baseDD->lastInsertId('mc_jobs');
							}
						} else {
							$IDJOB = $data['id_job'][$dat];
						}
						$R3=$baseDD->prepare("INSERT INTO `mc_profile` (id_project, person, occurence, id_job) VALUES ( :id, :person, :occurence, :id_job)");
						$R3->bindParam(':id',$data['id_project']);
						$R3->bindParam(':person',$data['profile'][$dat]);
						$R3->bindParam(':occurence',$data['occurence'][$dat]);
						$R3->bindParam(':id_job',$IDJOB);
						if($R3->execute()){
							$ok = true;
						} else {
							$ok = false;
						}
					}
				}
				if ($ok) {
					echo json_encode(array('success' => true, 'id' => $data['id_project'] ));
				} else {
					echo json_encode(array('success' => false ));
				}
			} else {
				echo json_encode(array('success' => true, 'id' => $data['id_project'] ));
			}
		} else {
			echo json_encode(array('success' => false ));
		}
	}

	function addProject($data){
		 
		global $baseDD;
		
		// print_r($data);
		$user = getIdFromFb();
		// date tournage
		$data['id_creator'] = $user['id_user'];
		$dt = date_create_from_format( 'd/m/Y', $data['date_tournage'] );
		$dateTournage =  $dt->format( 'Y/m/d' );
		// type_place
		$data['place_'.$data['type_place']] = $data['id_place'];

		// var_dump($data);

		$check = 0;
		foreach ($data as $key => $value) {
			if (empty($value) && $key != 'name' && $key != 'profile') {
				echo json_encode(array('success' => false));
				return false;
			} elseif (empty($value) && $key == 'name' && $key == 'profile'){
				$check ++;
				if ($check <2) {
					echo json_encode(array('success' => false));
				}
			}
		}

		$R1=$baseDD->prepare("INSERT INTO `mc_project` (title, description, id_creator, create_date, place_villes, date_filter, place_departements, place_regions) VALUES ( :title, :description, :id_creator, NOW(), :place_villes, :date_filter, :place_departements, :place_regions)");
		$R1->bindParam(':title',$data['title']);
		$R1->bindParam(':description',$data['desc']);
		$R1->bindParam(':id_creator',$data['id_creator']);
		$R1->bindParam(':place_villes',$data['place_villes']);
		$R1->bindParam(':place_departements',$data['place_departements']);
		$R1->bindParam(':place_regions',$data['place_regions']);
		$R1->bindParam(':date_filter',$dateTournage);
		$R1->setFetchMode(PDO::FETCH_ASSOC);


		if($R1->execute()){
			$ID=$baseDD->lastInsertId('mc_project');
		}
		foreach ($data['profile'] as $dat => $key) {
			if (!empty($data['profile'][$dat])) {
				if (empty($data['id_job'][$dat])) {
					$R2=$baseDD->prepare("INSERT INTO `mc_jobs` (name, domain) VALUES ( :name, 3)");
					$R2->bindParam(':name',$data['name'][$dat]);
					if($R2->execute()){
						$IDJOB=$baseDD->lastInsertId('mc_jobs');
					}
				} else {
					$IDJOB = $data['id_job'][$dat];
				}
				$R3=$baseDD->prepare("INSERT INTO `mc_profile` (id_project, person, occurence, id_job) VALUES ( :id, :person, :occurence, :id_job)");
				$R3->bindParam(':id',$ID);
				$R3->bindParam(':person',$data['profile'][$dat]);
				$R3->bindParam(':occurence',$data['occurence'][$dat]);
				$R3->bindParam(':id_job',$IDJOB);
				if($R3->execute()){
					$ok = true;
				}
			}
		}
		if ($ok){
			echo json_encode(array('success' => true ,'id' => $ID));
		} else {
			echo json_encode(array('success' => false));
		}
	}

	function getUserFilter($json=false){
		global $baseDD;

		$user = getIdFromFb();
		$R1=$baseDD->prepare('SELECT filter FROM mc_users WHERE id_user = :id_user');
		$R1->bindParam(':id_user',$user['id_user']);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		if ($R1->execute()) {
			// echo json_encode(array('success' => true ));
			$result = $R1->fetch();
			if ($json) {
				echo json_encode($result);
			}else{
				return $result;
			}
		}
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

	function deleteProject($idProject){

		global $baseDD;

		$user = getIdFromFb();
		$sql = 'UPDATE mc_project SET current_state = 0 WHERE id_project = :id_project AND id_creator = :id_user';
		$R1=$baseDD->prepare($sql);
		$R1->bindParam(':id_project',$idProject['id']);
		$R1->bindParam(':id_user',$user['id_user']);

		if ($R1->execute()) {
			echo json_encode(array(success => true )); // triggered meme si retour vide
		} else {
			echo json_encode(array(success => false ));
		}
	}

	function deleteProjectWhy($data){

		global $baseDD;

		$user = getIdFromFb();
		$sql = 'UPDATE mc_project SET reason = :reason, reason_desc = :reason_desc WHERE id_project = :id_project AND id_creator = :id_user';
		$R1=$baseDD->prepare($sql);
		$R1->bindParam(':id_project',$data['id']);
		$R1->bindParam(':id_user',$user['id_user']);
		$R1->bindParam(':reason',$data['reason']);
		$R1->bindParam(':reason_desc',$data['desc']);

		$sql2 = 'INSERT INTO mc_remarques (id_user, remarque) VALUES ( :id, :remarque)';
		$R2=$baseDD->prepare($sql2);
		$R2->bindParam(':id',$user['id_user']);
		$R2->bindParam(':remarque',$data['remarque']);

		if ($R1->execute() && $R2->execute()) {
			echo json_encode(array(success => true )); // triggered meme si retour vide
		} else {
			echo json_encode(array(success => false ));
		}
	}

	function getNbProject($data){

		global $baseDD;

		$sql =  "SELECT count(pj.*) FROM mc_project AS pj, mc_profile AS pf WHERE pj.id_project = pf.id_project AND pj.current_state = 1";

		if ($filters['profile']) {
			$sql .= " AND pf.id_job IN (SELECT id_job FROM mc_jobs WHERE name = :profile) AND pf.current_state = 1";
			$array['profile'] = $filters['profile'];
			// echo $filters['profile'];
		}

		if ($filters['id_place']) {
			$filters['place_'.$filters['type_place']] = $filters['id_place'];
			if ($filter['type_place']=='villes') {
				$sql .=" AND (getDistance((SELECT lat FROM villes WHERE id = :place_villes),(SELECT lon FROM villes WHERE id = :place_villes),(SELECT lat FROM villes WHERE id = pj.place_villes),(SELECT lon FROM villes WHERE id = pj.place_villes)) < :maxdist)";
			}
			
			// $sql .= " AND pj.place_villes = :place_villes AND pj.place_departements = :place_departements AND pj.place_regions = :place_regions";
			// $array['place_regions']=($filters['place_regions'])?$filters['place_regions']:0;
			$array['place_villes']=($filters['place_villes'])?$filters['place_villes']:0;
			// $array['place_departements']=($filters['place_departements'])?$filters['place_departements']:0;
			$array['maxdist']=100000;
		}

		$sql .= " GROUP BY pj.id_project";
		$sql .= " ORDER BY `loop` DESC, id_project DESC";

		$q = $baseDD->prepare($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$q->execute($array);
		return $q->fetchColumn();
	}

	function getNbProjetUser(){
		
		global $baseDD; // OR id_project IN (SELECT id_project FROM mc_favorite WHERE id_user = (SELECT id_user FROM mc_users WHERE user_fb = :user_fb))';
		$user = getIdFromFb();
		$sql = 'SELECT count(*) AS nb FROM `mc_project` WHERE current_state != 0 AND id_creator = :id_user';
		$array = array(':id_user' => $user['id_user']);

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

	 function getProjects($page,$user_fb,$favorite = false,$count=false){
		global $baseDD;
		if ($count) {
	 		$sql = "SELECT count(DISTINCT id_project) AS count FROM mc_project";
		} else {
			$sql = "SELECT id_project, `loop`, title, description, id_creator, create_date, date_filter, (SELECT IFNULL((SELECT nom FROM villes WHERE id = place_villes),IFNULL((SELECT nom FROM departements WHERE id = place_departements),(SELECT nom FROM regions WHERE id = place_regions)))) AS place, (SELECT IFNULL((SELECT cp FROM villes WHERE id = place_villes),(SELECT cp FROM departements WHERE id = place_departements))) AS zip_code, (SELECT user_fb FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS id_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project`";
		}
		
		if ($user_fb) {
			$user = getIdFromFb();
			if ($favorite) {
				$sql .= ' WHERE id_project IN (SELECT id_project FROM mc_favorite WHERE id_user = :id_user) AND current_state = 1';
			} else {
				$sql .= ' WHERE current_state != 0 AND id_creator = :id_user';
			}
			$array = array(':id_user' => $user['id_user']);	
		} else {
			$sql .= ' WHERE current_state = 1';
		}

		
		if (!$count) {
			$sql .= " ORDER BY `loop` DESC, id_project DESC";
			$sql .= ' LIMIT '.(POST_PER_PAGE*($page-1)).','.POST_PER_PAGE;
		}

		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		if($R1->execute($array)){
			$projects=$R1->fetchAll();
		}
		return $projects;
	 }
	
	 function getProject($id_project){

		global $baseDD;
		$sql = "SELECT id_project, `loop`, title, description, id_creator, create_date, date_filter, (SELECT IFNULL((SELECT nom FROM villes WHERE id = place_villes),IFNULL((SELECT nom FROM departements WHERE id = place_departements),(SELECT nom FROM regions WHERE id = place_regions)))) AS place, (SELECT IFNULL((SELECT cp FROM villes WHERE id = place_villes),(SELECT cp FROM departements WHERE id = place_departements))) AS zip_code, (SELECT user_fb FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS id_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = mc_project.id_creator) AS name_creator  FROM `mc_project` WHERE id_project=:id_project AND current_state = 1";
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

	 	$sql = 'SELECT id_job, name, domain FROM mc_jobs WHERE name LIKE :job OR name LIKE :job2 GROUP BY name ORDER BY name ASC';
		$R1 = $baseDD->prepare($sql);
		$job1 = $job.'%';
		$job2 = '% '.$job.'%';
		$R1->bindParam(':job',$job1);
		$R1->bindParam(':job2',$job2);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		if($R1->execute()){
			$result=$R1->fetchAll();
		}
		echo json_encode($result);
	}

	 function getAutocompletionJsonCities($ville,$restricted = false){

	 	global $baseDD;

	 	if (is_numeric($ville)){

	 		$sql2 = 'SELECT cp, nom, id FROM villes WHERE cp LIKE :dpt GROUP BY nom ORDER BY nom ASC';
			$array2 = array(':dpt' => $ville.'%');
			$R2 = $baseDD->prepare($sql2);
			$R2->setFetchMode(PDO::FETCH_ASSOC);
			if($R2->execute($array2)){
				$dept=$R2->fetchAll();
			}
			echo json_encode($dept);

	 	} else {

			$sql2 = 'SELECT nom, cp, id, "departements" AS `type`  FROM departements WHERE nom LIKE :dpt OR cp LIKE :dpt OR nom LIKE :dpt2 ORDER BY nom ASC';
			$array2 = array(':dpt' => '%'.$ville.'%',':dpt2' => '%'.str_replace('-',' ',$ville).'%');
			$R2 = $baseDD->prepare($sql2);
			$R2->setFetchMode(PDO::FETCH_ASSOC);
			if($R2->execute($array2)){
				$result=$R2->fetchAll();
			}

	 		$sql = 'SELECT cp, nom, id, indice FROM villes WHERE';
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
				foreach ($villes as $vil) {
					$vil['type']='villes';
					array_push($result,$vil);
				}
			}

			$sql3 = 'SELECT nom , id FROM regions WHERE nom LIKE :region ORDER BY nom ASC';
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

	 function getProjectsByFilters($page,$filters,$count = false){
	 	global $baseDD;
	 	if ($filters['id_place']) {
				$filters['place_'.$filters['type_place']] = $filters['id_place'];
			}
	 	if ($count) {
	 		$sql = "SELECT count(DISTINCT pj.id_project) AS count";
	 	} else {
			$sql = "SELECT pj.id_project, pj.loop, pj.title, pj.description, pj.id_creator, pj.create_date, pj.date_filter, (SELECT IFNULL((SELECT nom FROM villes WHERE id = pj.place_villes),IFNULL((SELECT nom FROM departements WHERE id = pj.place_departements),(SELECT nom FROM regions WHERE id = pj.place_regions)))) AS place, (SELECT IFNULL((SELECT cp FROM villes WHERE id = pj.place_villes),(SELECT cp FROM departements WHERE id = pj.place_departements))) AS zip_code, (SELECT user_fb FROM mc_users WHERE mc_users.id_user = pj.id_creator) AS id_creator, (SELECT name FROM mc_users WHERE mc_users.id_user = pj.id_creator) AS name_creator";
			if ($filters['place_villes']) {
				$sql .= ", (getDistance((SELECT lat FROM villes WHERE id = :place_villes),(SELECT lon FROM villes WHERE id = :place_villes),(SELECT lat FROM villes WHERE id = pj.place_villes),(SELECT lon FROM villes WHERE id = pj.place_villes))) AS distance";
			}
	 	}
		$sql.=" FROM mc_project AS pj, mc_profile AS pf WHERE pj.id_project = pf.id_project AND pj.current_state = 1";

		if ($filters['profile']) {
			$sql .= " AND pf.id_job IN (SELECT id_job FROM mc_jobs WHERE association IN (SELECT association FROM mc_jobs WHERE name = :profile))";
			$array['profile'] = $filters['profile'];
			// echo $filters['profile'];
		}
		if ($filters['place_villes'] || $filters['location']) {
			$sql .=" AND (getDistance((SELECT lat FROM villes WHERE id = :place_villes),(SELECT lon FROM villes WHERE id = :place_villes),(SELECT lat FROM villes WHERE id = pj.place_villes),(SELECT lon FROM villes WHERE id = pj.place_villes))) < :maxdist";
			$array['place_villes']=($filters['place_villes'])?$filters['place_villes']:0;
			$array['maxdist']=$filters['distance'].'000';
		}
		if ($filters['place_departements']) {
			$sql .=" AND pj.place_departements = :place_departements OR pj.place_villes IN (SELECT id FROM villes WHERE id_departement = :place_departements)";
			$array['place_departements']=($filters['place_departements'])?$filters['place_departements']:0;
		}
		if ($filters['place_regions']) {
			$sql .=" AND pj.place_regions = :place_regions OR pj.place_departements IN (SELECT id FROM departements WHERE id_region = :place_regions) OR pj.place_villes IN (SELECT id FROM villes WHERE id_departement IN (SELECT id FROM departements WHERE id_region = :place_regions))";
			$array['place_regions']=($filters['place_regions'])?$filters['place_regions']:0;
		}
		if ($filters['date_filter']) {
			switch ($filters['date_filter']) {
				case 'now':
					$array['date_filter']=3;
					break;
				case 'week':
					$array['date_filter']=7;
					break;
				case 'month':
					$array['date_filter']=30;
					break;
				case 'trimestre':
					$array['date_filter']=60;
					break;
				
				default:
					break;
			}
			if ($array['date_filter']) {
				$sql .= " AND TO_DAYS(NOW()) - TO_DAYS(pj.date_filter) <= :date_filter AND TO_DAYS(NOW()) - TO_DAYS(pj.date_filter) >= 0";
			}
		}

		if (!$count) {
			$sql .= " GROUP BY pj.id_project";
			if ($filters['place_villes']) {
				$sql .= " ORDER BY `loop` DESC, distance ASC, pj.id_project DESC";
			} else {
				$sql .= " ORDER BY `loop` DESC, pj.id_project DESC";
			}
			$sql .= ' LIMIT '.(POST_PER_PAGE*($page-1)).','.POST_PER_PAGE;
		}
		
// var_dump($filters);
// var_dump($count);

		$R1=$baseDD->prepare($sql);
		$R1->setFetchMode(PDO::FETCH_ASSOC);


// var_dump($array);
		if($R1->execute($array)){
			$projects = $R1->fetchAll();
		}
		

		// if ($count) {
			// echo "$sql";
			// foreach ($project[0] as $key => $value) {
				// $project = $value->$key;
			// }
		// }
		 
		return $projects;
	 }
	
	function getProfiles($project){
		
		global $baseDD;
		
		 $R1=$baseDD->prepare("SELECT mcp.* , mcj.* FROM `mc_profile` AS mcp, mc_jobs AS mcj WHERE mcp.id_project=:project AND mcp.id_job=mcj.id_job AND current_state=1");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);
		
		 if($R1->execute()){
			$profiles=$R1->fetchAll();
		 }
		
		 return $profiles;
	}

	function getProfilesFound($project){
		
		global $baseDD;
		
		 $R1=$baseDD->prepare("SELECT mcp.* , mcj.* FROM `mc_profile` AS mcp, mc_jobs AS mcj WHERE mcp.id_project=:project AND mcp.id_job=mcj.id_job AND current_state=2");
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
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($R1->execute()){
			$id=$R1->fetch();
		}
		
		return $id;
	}

	function createUser($data){
		global $baseDD;

		$name = $data['firstname'].' '.$data['lastname'];
		$R1=$baseDD->prepare('INSERT INTO `mc_users` (user_fb, name) VALUES (:user_fb, :name)');
		$R1->bindParam(':user_fb',$data['id']);
		$R1->bindParam(':name',$data['name']);

		if($R1->execute()){
			$ID=$baseDD->lastInsertId('mc_users');
		}
	}

	function profileFound($data){
		global $baseDD;

		$user = getIdFromFb();

		$sql = 'UPDATE mc_profile SET current_state = 2  WHERE id_profile = :id_profile AND id_project = (SELECT id_project FROM mc_project WHERE id_project = :id_project AND id_creator = :id_user)';
		$R1=$baseDD->prepare($sql);
		$R1->bindParam(':id_user',$user['id_user']);
		$R1->bindParam(':id_profile',$data['id_profile']);
		$R1->bindParam(':id_project',$data['id_project']);
		$R1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($R1->execute()){
			echo json_encode(array('success' => true ));
		}
	}

	function getActiveActors($project){

		global $baseDD;

		 $R1=$baseDD->prepare("SELECT person, occurence FROM `mc_profile` AS mcp, mc_jobs AS mcj WHERE id_project=:project AND mcp.id_job=mcj.id_job AND domain=1 AND current_state=1 ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$actors=$R1->fetchAll();
		 }

		 return $actors;

	 }
	
	function getActiveTechnicians($project){

		global $baseDD;

		 $R1=$baseDD->prepare("SELECT * FROM `mc_profile` AS mcp, mc_jobs AS mcj WHERE id_project=:project AND mcp.id_job=mcj.id_job AND domain=2 AND current_state=1 ");
		 $R1->bindParam(':project',$project);
		 $R1->setFetchMode(PDO::FETCH_ASSOC);

		 if($R1->execute()){
			$technicians=$R1->fetchAll();
		 }

		 return $technicians;

	 }
	
	function getNotifFilter(){
		global $baseDD;
		$user = getIdFromFb();
		$R1=$baseDD->prepare('SELECT notif_filter FROM mc_users WHERE id_user = :id_user');
		$R1->bindParam(':id_user',$user['id_user']);
		if ($R1->execute()) {
			$notif=$R1->fetch();
		}
		
		return $notif['notif_filter'];
	}
	
	function enableNotifFilter(){
		global $baseDD;
		$user = getIdFromFb();
		$R1=$baseDD->prepare('UPDATE mc_users SET notif_filter = 1 WHERE id_user = :id_user');
		$R1->bindParam(':id_user',$user['id_user']);
		if ($R1->execute()) {
			echo json_encode(array(success => true ));
		} else {
			echo json_encode(array(success => false ));
		}
	}
	
	function disableNotifFilter(){
		global $baseDD;
		$user = getIdFromFb();
		$R1=$baseDD->prepare('UPDATE mc_users SET notif_filter = 0 WHERE id_user = :id_user');
		$R1->bindParam(':id_user',$user['id_user']);
		if ($R1->execute()) {
			echo json_encode(array(success => true ));
		} else {
			echo json_encode(array(success => false ));
		}
	}
	
	function addListSubscribe($user){
		
		global $baseDD, $apikey, $listId, $merge_vars ;
		
		$api = new MCAPI($apikey);
		$retval = $api->listSubscribe( $listId, $user['email'], $merge_vars, $email_type='html', $double_optin=false, $update_existing=false, $replace_interests=true, $send_welcome=true );

		if($api->errorCode){
			switch ($api->errorCode) {
				case 214:
					// email deja en base 
					$getUser = $api->listMemberInfo( $listId, $user['email']);
					if ($api->errorCode){
						echo json_encode(array(error => "Une erreur est survenue, veuillez réessayer. Merci de nous contacter si le problème persiste." ));
					}else{
						echo json_encode(array(result => $getUser['data']));
					}
					break;
				default:
					echo json_encode(array(error => "Une erreur est survenue, veuillez réessayer. Merci de nous contacter si le problème persiste." ));
			}
		}else{
			echo json_encode(array( success => true ));
		}
	}
	
	function addCity($city){
		global $baseDD;
		
		$R1=$baseDD->prepare("INSERT INTO villes VALUES ('',(SELECT id FROM departements WHERE cp=:zip), :place, :zip, :lat, :lng, 1, 0)");
		
		$R1->bindParam(':place', $city['place']);
		$R1->bindParam(':zip', $city['zip']);
		$R1->bindParam(':lat', $city['latitude']);
		$R1->bindParam(':lng', $city['longitude']);
		
		if ($R1->execute()) {
			$id=$baseDD->lastInsertId('villes');
			echo json_encode(array(id => $id ));
		} else {
			echo json_encode(array(success => false ));
		}
	}

?>
