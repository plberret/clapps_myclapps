<?php 
	require_once './inc/init.php';
	//require_once './inc/initFb.php';
	require_once './inc/settings.php';
	require_once './inc/functions.php';
	$page=$_GET['page'];
	if(!isset($page)){$page=1;}
	$userFilter = getUserFilter();
	parse_str($userFilter['filter'], $userFilterArray);

	// get projects
	if (isset($_GET['id_project'])) : // one project
		$getProjects=getProject($_GET['id_project']);
		$nbProject = getProject($_GET['id_project'],true);
	elseif ($_GET['filter']): // filtre on
		$getProjects=getProjectsByFilters($page,$_GET);
		$nbProject = getProjectsByFilters($page,$_GET,true);
	elseif ($userFilter['filter'] && !$_GET['user_fb'] && !$_GET['favorite']): // user got default filter but not in his projects
		$getProjects=getProjectsByFilters($page,$userFilterArray);
		$nbProject = getProjectsByFilters($page,$userFilterArray,true);
	elseif ($_GET['favorite']):
		$mine = true;
		$fav = true;
		$getProjects=getProjects($page,$_GET['user_fb'],true);
		$nbProject = getProjects($page,$_GET['user_fb'],true,true);
	else:
		if ($_GET['user_fb']){
			$mine = true;
		}
		$getProjects=getProjects($page,$_GET['user_fb']);
		$nbProject = getProjects($page,$_GET['user_fb'],false,true);
	endif;
	$nbProject = intval($nbProject[0]['count']);
?>

<!doctype html>
<html lang="fr">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# myclapps: http://ogp.me/ns/fb/myclapps#">
	<meta charset="utf-8">
	<?php if ($_GET['id_project']||$_GET['app_data']): ?>
		<meta property="fb:app_id" content="112197008935023" />
		<?php if ($_GET['person']==true): ?>
			<meta property="og:type" content="myclapps:person" /> 
		<?php else: ?>
			<meta property="og:type" content="myclapps:announce" /> 
		<?php endif ?>
		<meta property="og:url" content="http://www.my.clapps.fr/project/<?php $_GET['id_project']; ?>" /> 
	<!--<meta property="og:title" content="<?php echo $getProjects[0]['title']; ?>" />
		<meta property="og:description" content="<?php echo $getProjects[0]['description']; ?>" /> -->
		<meta property="og:title" content="test title" />
		<meta property="og:description" content="test desc" />
		<meta property="og:image" content="http://backup.clapps.fr/img/logo_clapps.png" />
	<?php else: ?>
		<meta property="og:title" content="My Clapps" />
		<meta property="og:description" content="L'application des professionnels du cinema" />
		<meta property="og:url" content="http://www.my.clapps.fr" /> 
		<meta property="og:image" content="http://backup.clapps.fr/img/logo_clapps.png" />
	<?php endif; ?>
	<title>My clapps</title>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
	<script src="http://vjs.zencdn.net/c/video.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="./css/style.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" title="no title">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
</head>

<body>
	<div id="page" style="display:none;" class="display_tuto">
		<header>
			<div id="bar_top" class="clearfix">
				<div id="logo">
					<h1>My clapps</h1>
				</div>
				<div id="searchButton" class="bar_top_button">
					<a href="javascript:void(0)" onClick="_gaq.push(['_trackEvent', 'home', 'Click', 'filtres-accès']);">Recherche</a>
				</div>
				<div id="infoButton" class="bar_top_button">
					<a href="javascript:void(0)" onClick="_gaq.push(['_trackEvent', 'home', 'Click', 'Tuto']);">Tutoriel</a>
				</div>
				<nav>
					<ul class="clearfix">
						<li class="addProject">
							<a class="fancybox.ajax" href="poppin/addProject.php" onClick="_gaq.push(['_trackEvent', 'home', 'Click', 'Ajouter une annonce']);" >Ajouter une annonce</a>
						</li>
						<li class="myProject">
							<?php if ($_GET['id_project']): ?>
								<a href="?user_fb=false" id="see-all">Voir toutes les annonces</a>
							<?php else: ?>
								<a href="?user_fb=<?php echo $user_fb ?>" id="see-mine" onClick="_gaq.push(['_trackEvent', 'home', 'Click', 'Mes annonces']);">
									<span class="text">Mes annonces</span>
									<span class="number"><?php echo getNbProjetUser() ?></span>
								</a>
							<?php endif; ?>
						</li>
					</ul>
				</nav>
			</div>
			<div id="block_current_filter" class="clearfix">
				<div id="current_filter">
					<p<?php if (empty($userFilter['filter'])): ?> class="hide" <?php endif; ?>>Vous recherchez un poste<span class="work"> <?php echo $userFilterArray['profile'] ?></span><span class="time"> dès que possible</span><span class="opt<?php if (empty($userFilterArray['location'])): ?> hide<?php endif; ?>"> dans la commune de <span class="location"><?php echo $userFilterArray['location'] ?></span> et <span class="distance"><?php echo $userFilterArray['distance'] ?>km</span> aux alentours</span>.</p>
					<p class="none<?php if (!empty($userFilter['filter'])): ?> hide<?php endif; ?>">Plus de facilité dans vos recherches ?<br/>Filtrez / Sauvegardez / et recevez par notification toutes les annonces qui vous correspondent grâce à vos <span class="open_filtre">filtres</span> !</p>
				</div>
				<div id="notif_email">
					<p>Être tenu au courant des nouveautés de Clapps</p>
					<form action="requests/addListSubscribe.php" id="addSubscribe" class="clearfix">
						<input type="text" autocomplete="off" name="email" placeholder="votreadresse@email.com" />
						<input type="submit" value="Je reste informé" />
					</form>
					<div class="msg success"></div>
				</div>
			</div>
			<form<?php if (!empty($userFilter)): ?> class="less"<?php endif; ?> id="block_filters" action="">
				<div id="filter" class="clearfix">
					<div id="col1" class="col">
						<h2>Filtrer la recherche</h2>
						<p>Utiliser les différents filtres ci-contre
						pour affiner votre recherche.</p>
						<a href="?filter=false" id="refresh_button" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'reinitialiser']);">Réinitialiser la recherche</a>
					</div>
					<div id="col2" class="col">
						<div class="field">
							<label for="profile">Métier</label>
							<input type="text" autocomplete="off" name="profile" id="profile" class="job autocomplete" placeholder="Entrez le métier recherché ..." onBlur="if (this.value != '') _gaq.push(['_trackEvent', 'Filtre', 'Blur', 'metier']);" />
						</div>
						<div class="field select">
							<label for="selector_date">Date</label>
							<div class="selector" id="selector_date">
								<div>
									<span class="value" id="date_filter_selected">Indifférent</span>
									<span class="button">Modifier</span>
								</div>
								<ul>
									<li class="all">Indifférent</li>
									<li class="now">Dès que possible</li>
									<li class="week">Cette semaine</li>
									<li class="month">Ce mois-ci</li>
									<li class="trimestre">Ce trimestre</li>
								</ul>
								<input type="hidden" name="date_filter" id="date_filter" value="all" onBlur="if (this.value != '') _gaq.push(['_trackEvent', 'Filtre', 'Blur', 'date']);">
							</div>
						</div>
					</div>
					<div id="col3" class="col">
						<div class="field">
							<label for="location">Lieux</label>
							<input type="text" autocomplete="off" name="location" id="location" class="location autocomplete" data-restricted="true" autocomplete="off" placeholder="Ville, département ou code postal" onBlur="if (this.value != '') _gaq.push(['_trackEvent', 'Filtre', 'Blur', 'lieu']);" />
							<input type="hidden" name="distance" value="100" id="distance" />
							<input type="hidden" name="id_place" class="id_place" id="id_place"/>
							<input type="hidden" name="type_place" class="type_place" id="type_place" />
						</div>
						<ul id="distances" class="clearfix">
							<li><a href="javascript:void(0);" class="50" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'distance-50']);" >
								<span class="number">50 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="javascript:void(0);" class="100 current" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'distance-100']);" >
								<span class="number">100 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="javascript:void(0);" class="200" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'distance-200']);" >
								<span class="number">200 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="javascript:void(0);" class="500" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'distance-500']);" >
								<span class="number">500 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="javascript:void(0);" class="1000" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'distance-1000']);" >
								<span class="number">1000 </span>
								<span class="unite">KM</span>
							</a></li>
						</ul>
					</div>
				</div>
				<div id="ctn">
					<div id="filter_advanced" class="clearfix">
						<ul class="nav">
							<li class="save">
								<a href="#tab1" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'sauvegarder']);"><span>Sauvegarder les filtres</span></a>
							</li>
							<li class="load">
								<a href="#tab2" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'charger']);"><span>Charger mes filtres</span></a>
							</li>
							<li class="delete">
								<a href="#tab3" onClick="_gaq.push(['_trackEvent', 'Filtre', 'Click', 'supprimer']);"><span>Supprimer mes filtres</span></a>
							</li>
						</ul>
						<div id="tabs">
							<div id="tab1" class="tab">
								<p><strong>Êtes-vous sûr de vouloir sauvegarder cette recherche ?</strong></p>
								<p>Si une sauvegarde antérieur existe, elle sera écrasée.</p>
								<div class="choice clearfix">
									<input type="submit" value="Oui" class="valid_button" onClick="_gaq.push(['_trackEvent', 'Filtre-sauvegarder', 'Click', 'oui']);"/>
									<a href="javascript:void(0);" class="close" onClick="_gaq.push(['_trackEvent', 'Filtre-sauvegarder', 'Click', 'annuler']);">Annuler</a>
								</div>
								<div class="message">
									<p class="alert success">Votre filtre a bien été sauvegardé !</p>
									<p class="alert error">Une erreur est survenue, veuillez réessayer !</p>
								</div>
								<div class="notifs clearfix">
									<p><span>Activation</span> des notifications Facebook</p>
									<a href="javascript:void" class="switch">
										<span class="on state <?php if(getNotifFilter()==1):?> current <?php endif; ?>">ON</span>
										<span class="off state <?php  if(getNotifFilter()==0):?> current <?php endif; ?> ">OFF</span>
										<span class="switch_button">Change</span>
									</a>
								</div>
							</div>
							<div id="tab2" class="tab">
								<p><strong>Êtes-vous sûr de vouloir charger votre filtre ?</strong></p>
								<div class="choice clearfix">
									<a href="javascript:void(0);" class="valid_button load" onClick="_gaq.push(['_trackEvent', 'Filtre-charger', 'Click', 'oui']);">Oui</a>
									<a href="javascript:void(0);" class="close" onClick="_gaq.push(['_trackEvent', 'Filtre-charger', 'Click', 'annuler']);">Annuler</a>
								</div>
								<div class="message">
									<p class="alert success">Votre filtre a bien été chargé !</p>
									<p class="alert empty">Vous n'avez pas de filtre enregistré !</p>
									<p class="alert error">Une erreur est survenue, veuillez réessayer !</p>
								</div>
							</div>
							<div id="tab3" class="tab">
								<p><strong>Êtes-vous sûr de vouloir supprimer votre filtre ?</strong></p>
								<div class="choice clearfix">
									<a href="#" class="valid_button delete" onClick="_gaq.push(['_trackEvent', 'Filtre-supprimer', 'Click', 'oui']);">Oui</a>
									<a href="#" class="close" onClick="_gaq.push(['_trackEvent', 'Filtre-supprimer', 'Click', 'annuler']);">Annuler</a>
								</div>
								<div class="message">
									<p class="alert success">Votre filtre a bien été supprimé !</p>
									<p class="alert empty">Vous n'avez pas de filtre enregistré !</p>
									<p class="alert error">Une erreur est survenue, veuillez réessayer !</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<ul class="help_info">
					<li class="save">Sauvegarder les filtres</li>
					<li class="load">Charger mes filtres</li>
					<li class="delete">Supprimer mes filtres</li>
				</ul>
			</form>
		</header>
	
		<section id="projects"<?php if (!empty($userFilter)): ?> class="margedless"<?php endif; ?>>
			
			<div id="successAddProject" class="message success" style="display:none">
				<p><span>Votre annonce est publiée.</span> Elle sera visible durant 15 jours,<br/> vous pourrez la réactiver pour <span>7 jours supplémentaires</span> à <span>2 jours</span> de sa fin de validité.</p>
			</div>
			<div class="clearfix<?php if (!$mine):?> hide<?php endif; ?>" id="my_project_choice">
				<ul class="submenu_seemine">
					<li class="mine_button<?php if (!$fav):?> current<?php endif; ?>"><a href="?user_fb=true" class="see-mine current">Mes annonces</a></li>
					<li class="favorite_button<?php if ($fav):?> current<?php endif; ?>"><a href="?user_fb=true&favorite=true" class="see-my">Annonces en favoris</a></li>
				</ul>
			</div>
			
			<?php if($getProjects) : ?>
				
				<?php
				foreach ($getProjects as $project): 
					$valideDate = getValideDate($project['create_date'],$project['loop']); // check if project was revalidate, (don't use create_date but update_date)
				?>
					<article style="opacity:0" class="project read <?php if (isFavorite($project,$user_fb)): ?> favorite<?php endif ?>">
						<form action="">
							<div class="preview">
								<div class="block_top clearfix">
									<div class="bloc_img">
										<img src="https://graph.facebook.com/<?php echo $project['id_creator'] ?>/picture" alt="photo profil <?php echo $project['name_creator'] ?>" />
										<span class="reflection"></span>
									</div>
									<div class="title_block">
										<div class="title">
											<h2>
												<span><?php echo $project['title']; ?></span>
												<input type="text" autocomplete="off" class="hide required" name="title" value="<?php echo $project['title']; ?>" maxlength="60" >
											</h2>
											<input type="hidden" name="id_project" value="<?php echo $project['id_project'] ?>">
											<div>Postée par <span><?php echo $project['name_creator']; ?></span> le <?php echo dateFormat('j.m.y',$project['create_date']); ?></div>
										</div>
										<div class="available clearfix">
											<?php $activeActors=getActiveActors($project['id_project']); ?>
											<?php $activeTechnicians=getActiveTechnicians($project['id_project']); ?>
											<div class="actors profile">
												<span class="occurLeft"><?php echo getOccurences($activeActors); ?></span>
												<p>Il reste <span><?php echo getOccurences($activeActors); ?></span> poste(s) de comédien(nes) disponible(s)</p>
											</div> 
											<div class="technicians profile">
												<span class="occurLeft"><?php echo getOccurences($activeTechnicians); ?></span>
												<p>Il reste <span><?php echo getOccurences($activeTechnicians); ?></span> poste(s) de technicien(nes) disponible(s)</p>
											</div>
										</div>
									</div>
								</div>

								<div class="share clearfix">
									<a href="javascript:void(0);" class="share_link" data-id="<?php echo $project['id_project'] ?>" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project'] ?>', 'Click', 'partage']);">Partager l'annonce</a>
									<?php if (!isAdmin($project)): ?>
										<?php if (isFavorite($project,$user_fb)): ?>
											<a href="javascript:void(0);" data-id="<?php echo $project['id_project'] ?>" class="unfavorite_link" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project']; ?>', 'Click', 'supprimer-favoris']);">Retirer des favoris</a>
										<?php else: ?>
											<a href="javascript:void(0);" data-id="<?php echo $project['id_project'] ?>" class="favorite_link" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project']; ?>', 'Click', 'ajouter-favoris']);">Ajouter aux favoris</a>
										<?php endif ?>
									<?php endif ?>
								</div>
								<div class="desc">
									<h3>Détails de l'annonce :</h3>
									<textarea name="desc" class="hide required" id='normal'><?php echo $project['description']; ?></textarea>
									<p class="elips"><?php echo nl2br($project['description']); ?></p>
								</div>
								<div class="bloc_see_more clearfix">

									<div class="project_id">#<?php echo $project['id_project']; ?></div>
									<div class="date">
										<p><?php echo dateUstoFr($project['date_filter']); ?></p>
										<input type="text" autocomplete="off" disabled="disabled" class="datepicker hide" value="<?php echo dateUstoFr($project['date_filter']); ?>">
										<input type="hidden" name="date_filter" class="date_filter">
									</div>
									<div class="place field">
										<p><?php echo $project['place']; ?> (<?php echo $project['zip_code']; ?>)</p>
										<input type="text" autocomplete="off" disabled="disabled" id="location" name="location" class="location autocomplete hide" autocomplete="off" value="<?php echo $project['place']; ?> (<?php echo $project['zip_code']; ?>)">
										<input type="hidden" name="id_place" class="id_place" />
										<input type="hidden" name="type_place" class="type_place" />
									</div>
								</div>
							</div><!-- fin preview -->
							<div class="more">
								<div class="profiles">
									<ul>
										<?php $getProfiles=getProfiles($project['id_project']); ?>
										<?php $getProfilesFound=getProfilesFound($project['id_project']); ?>
										<?php foreach ($getProfiles as $profile) { ?>
											<li class="clearfix profile">
												<?php 
													if($profile['domain']==1){
														$profileDomain="iconActor"; 
													}elseif($profile['domain']==2){
														$profileDomain="iconTechnician"; 
													} 
												?>
												<div class="block_read">
													<div class="icon <?php echo $profileDomain; ?>">
														<span><?php echo $profile['occurence']; ?></span>
													</div>
													<div class="desc"><p><span><?php echo $profile['name']; ?> : </span><?php echo $profile['person']; ?></p></div>
													<div class="apply">
														<?php if (!isAdmin($project,$user_fb)): ?>
															<a href="javascript:void(0);" class="apply_button" data-id="<?php echo $project['id_project'] ?>"  data-idprofile="<?php echo $profile['id_profile'] ?>" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project']; ?>', 'Click', 'postuler']);">Postuler</a>
														<?php else: ?>
															<a href="javascript:void(0);" class="profile_found" data-id="<?php echo $project['id_project'] ?>"  data-idprofile="<?php echo $profile['id_profile'] ?>" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project']; ?>', 'Click', 'jai-trouve']);">J'ai trouvé</a>
														<?php endif; ?>
													</div>
												</div>
												<div class="block_edition hide">
													<div class="add_job add_field field">
														<input type="text" autocomplete="off" class="required job autocomplete plp" placeholder="Métier recherché"  value="<?php echo $profile['name']; ?>" name="name[]" />
														<input type="hidden" class="idjob" name="id_job[]" value="<?php echo $profile['id_job']; ?>" />
													</div>
													<div class="add_desc add_field"><input type="text" autocomplete="off" class="plp required" placeholder="Description du poste recherché" name="profile[]" value="<?php echo $profile['person']; ?>" /></div>
													<div class="edit">
														<div class="deleteButton">
															<a href="javascript:void(0);" class="button_delete_profile" onClick="_gaq.push(['_trackEvent', 'Annonce-edition-#<?php echo $project['id_project']; ?>', 'Click', 'suppression-poste']);">Supprimer</a>
															<div class="confirm">
																<p>êtes-vous sûr de vouloir supprimer ?</p>
																<div>
																	<a href="javascript:void(0);" class="confirm_delete_profile">Oui</a>
																	<a href="javascript:void(0);" class="cancel_delete_profile">Annuler</a>
																</div>
															</div>
														</div>
														<div class="quantity">
															<a href="javascript:void(0);" class="less_quantity number_control">-</a>
															<input type="text" autocomplete="off" value="<?php echo $profile['occurence']; ?>" class="required number" name="occurence[]"/>
															<a href="javascript:void(0);" class="more_quantity number_control">+</a>
														</div>
													</div>
												</div>
											</li>
										<?php } ?>
										<?php foreach ($getProfilesFound as $profile) { ?>
											<?php 
												if($profile['domain']==1){
													$profileDomain="iconActor"; 
												}elseif($profile['domain']==2){
													$profileDomain="iconTechnician"; 
												} 
											?>
											<li class="clearfix profile profileFound">
												<div class="icon <?php echo $profileDomain; ?>">
												</div>
												<div class="desc"><p><span><?php echo $profile['name']; ?> : </span><?php echo $profile['person']; ?></p></div>
											<div class="apply applyFound">Candidat trouvé</div>
											</li>
										<?php } ?>
										<li class="add-line hide profile clearfix">
											<div class="add_job add_field field">
												<input type="text" autocomplete="off" class="job autocomplete plp" placeholder="Métier recherché" name="name[]" />
												<input type="hidden" class="idjob" name="id_job[]" />
											</div>
											<div class="add_desc add_field"><input type="text" autocomplete="off" class="plp" placeholder="Description du poste recherché" name="profile[]"/></div>
											<div class="edit">
												<div class="line_control">
													<a href="javascript:void(0);" class="add-post">+</a>
												</div>
												<div class="quantity">
													<a href="javascript:void(0);" class="less_quantity number_control">-</a>
													<input type="text" autocomplete="off" value="1" class="number" name="occurence[]"/>
													<a href="javascript:void(0);" class="more_quantity number_control">+</a>
												</div>
											</div>
										</li>
									</ul>
								</div><!-- fin profile -->
								<?php if (isAdmin($project,$user_fb)): ?>
									<div class='message erreur'></div>
									<div class="manage manage-read clearfix">
										<p>Validité de l'annonce :
											<?php if ($valideDate>0): ?>
												<span class="valid"><?php echo $valideDate; if ($valideDate>1): ?> jours restants<?php else: ?> jour restant<?php endif ?></span>
											<?php else: ?>
												<span class="finish">Désactivée</span>
											<?php endif ?>
										</p>
										<?php if ($valideDate<0): ?><a href="javascript:void(0);" class="extendProject big_button" data-id="<?php echo $project['id_project'] ?>">Réactiver l'annonce</a><?php endif; ?>
										<?php if (($valideDate>0)&&($valideDate<=DAY_UNTIL_REACTIVATE)): ?><a href="javascript:void(0);" class="extendProject big_button">Prolonger l'annonce</a><?php endif; ?>
										<a href="javascript:void(0);" class='editProject big_button' data-id="<?php echo $project['id_project'] ?>" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project']; ?>', 'Click', 'éditer']);"><span>Editer</span> l'annonce</a>
									</div>
									<div class="manage manage-edition hide clearfix">
										<div class="block_delete_project">
											<a href="javascript:void(0)" class="button_delete_project big_button" onClick="_gaq.push(['_trackEvent', 'Annonce-edition-#<?php echo $project['id_project']; ?>', 'Click', 'supprimer']);">Supprimer l'annonce</a>
											<div class="confirm">
												<p>êtes-vous sûr de vouloir supprimer ?</p>
												<div>
													<a href="poppin/deleteProject.php" data-id="<?php echo $project['id_project'] ?>" class="fancybox.ajax valid_delete_project">Oui</a>
													<a href="javascript:void(0)" class="cancel_delete_project">Annuler</a>
												</div>
											</div>
										</div>
										<input type="submit" value="Valider" onClick="_gaq.push(['_trackEvent', 'Annonce-edition-#<?php echo $project['id_project']; ?>', 'Click', 'valider']);" />
										<a href="javascript:void(0);" class="cancelEditProject big_button" onClick="_gaq.push(['_trackEvent', 'Annonce-edition-#<?php echo $project['id_project']; ?>', 'Click', 'annuler']);">Annuler</a>
									</div>
								<?php endif ?>
							</div><!-- fin more -->
							<div id="block_see_button">
								<a href="javascript:void(0);" class="see-more see-button" onClick="_gaq.push(['_trackEvent', 'Annonce-#<?php echo $project['id_project']; ?>', 'Click', 'voir-plus-moins']);"><span>Voir</span> plus</a>
							</div>
						</form>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="no_result project">
					<?php if ($fav): ?>
						<h2>Vous n'avez pas d'annonces actuellement valides en favoris</h2>
					<?php elseif($mine): ?>
						<h2>Vous n'avez pas encore posté de projet</h2>
						<br/>
						<a class="fancybox.ajax" href="poppin/addProject.php">Ajouter une annonce</a>
					<?php else: ?>
							<h2>Aucun résultat pour cette recherche</h2>
							<p>Merci de modifier vos filtres de recherches</p>
							<a href="?filter=false" class="display_all_projects">Afficher toutes les annonces</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if (ceil($nbProject/POST_PER_PAGE)>$page && !$_GET['id_project']): ?>
				<div class="btn-more-projects">
					<a href="?page=<?php echo $page+1; ?>&<?php echo http_build_query($_GET, '=') ?>" data-nav="<?php echo $page ?>" onClick="_gaq.push(['_trackEvent', 'home', 'Click', 'voir plus']);">Voir plus ...</a>
				</div>
			<?php endif; ?>
			
		</section>
		
		<div id="tuto" class="intro">
			<div id="light_header_tuto"></div>
			<div id="content_tuto">
				<div id="block_logo_tuto">
					<p>My clapps</p>
				</div>
				<div id="block_button_tuto" class="clearfix">
					<div class="desc">
						<h2>Trouver un tournage n’aura jamais été aussi simple !</h2>
						<p>En attendant la sortie du site Mai 2013, <br/>Clapps vous présente son application Facebook</p>
					</div>
					<a href="javascript:void(0);" class="close_tuto">J'accède à l'application</a>
				</div>
				<div id="block_help_tuto" class="clearfix">
					<div class="desc">
						<h2>Besoin d'aide ?</h2>
						<p>Pour toutes questions et/ou pour toutes suggestions,<br/> n'hésitez pas à nous contacter via notre adresse <a href="mailto:contact@clapps.fr">contact@clapps.fr</a></p>
					</div>
					<a href="javascript:void(0);" class="close_tuto">Fermer l'aide</a>
				</div>
				<div id="block_display_tuto">
					<div class="title">
						<h3>Myclapps en 4 étapes</h3>
					</div>
					<div class="display">
						<div class="mask">
							<!-- width="681" height="288" autobuffer loop-->
							<div >
								<video id="vid" width="684" height="290" class="video-js vjs-default-skin"  autoplay preload="auto" loop>
									<source src="./assets/tuto_clapps.mp4" type="video/mp4">
									<source src="./assets/tuto_clapps.ogv" type="video/ogg">
									<source src="./assets/tuto_clapps.webm" type="video/webm">
									Vous ne possédez pas un navigateur adapté pour visionner le tutoriel !
								</video>
							</div>	
						</div>
					</div>
					<div id="display_bottom"></div>
				</div>
				<div id="block_nav_tuto">
					<ul class="clearfix">
						<a class="done current search" href="javascript:void(0);"><li>
							<span></span>
							<em>Rechercher/Filtrer</em>
						</li></a>
						<a class="done next filters" href="javascript:void(0);"><li>
							<span></span>
							<em>Gestion des filtres</em>
						</li></a>
						<a class="done next fav" href="javascript:void(0);"><li>
							<span></span>
							<em>Mise en favoris <br/>Partager une annonce</em>
						</li></a>
						<a class="done next create" href="javascript:void(0);"><li>
							<span></span>
							<em>Création d'une annonce <br/> Édition d'une annonce</em>
						</li></a>
					</ul>
				</div>
			</div>
			<div id="curtain_tuto"></div>
			<div id="shadow_curtain"></div>
		</div><!-- fin tuto-->
		
	</div> <!-- fin page-->
	
	<div id="fb-root"></div>
	
	<script src="js/libs/jquery-1.8.1.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link rel="stylesheet" href="css/jquery.ui.theme.css" />
	<script src="js/libs/jquery-ui.datepicker.js"></script>
	<script src="js/libs/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.autosize.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.autoellipsis-1.0.10.min.js" type="text/javascript"></script> <!-- à supprimer -->
	<script src="js/libs/jquery.dotdotdot-1.5.4-packed.js" type="text/javascript"></script>
	<script src="js/libs/jquery.placeholder.js" type="text/javascript"></script>
	<script src="./js/main.js"></script>
	<script class="maxPages" type="text/javascript"> zf.maxPages = <?php echo getMaxPages($_GET['user_fb']) ?></script>
	<script type="text/javascript" charset="utf-8">
		var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-36398282-2']); _gaq.push(['_trackPageview']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();
	</script>
	
	<?php
		echo '<script>
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId  : '.APP_ID.',
			      status : true, // check login status
			      cookie : true, // enable cookies to allow the server to access the session
			      xfbml  : true  // parse XFBML
			    });
				FB.Canvas.setAutoGrow();
			  };

			  (function() {
			    var e = document.createElement("script");
			    e.src = document.location.protocol + "//connect.facebook.net/fr_FR/all.js";
			    e.async = true;
			    document.getElementById("fb-root").appendChild(e);
			  }());
		</script>';
 	?>

</body>
</html>