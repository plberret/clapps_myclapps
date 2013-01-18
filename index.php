<?php 
	require_once './inc/init.php';
	require_once './inc/settings.php';
	require_once './inc/functions.php';
	$page=$_GET['page'];
	if(!isset($page)){$page=1;}
	$nbProject = getNbProject($_GET['user_fb']);
	// $userFilter = getUserFilter();
	$userFilter = 'profile=Figurant&date_filter=week&location=&distance=100';
	$userFilterArray = array();
	parse_str($userFilter, $values);

?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>My clapps</title>
	<link rel="stylesheet" type="text/css" media="all" href="./css/style.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" title="no title">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
	<div id="page">
		<header>
			<div id="bar_top" class="clearfix">
				<div id="logo">
					<h1>My clapps</h1>
				</div>
				<div id="searchButton" class="bar_top_button">
					<a href="javascript:void(0)" >Recherche</a>
				</div>
				<div id="infoButton" class="bar_top_button">
					<a href="javascript:void(0)" >Tutoriel</a>
				</div>
				<nav>
					<ul class="clearfix">
						<li class="addProject">
							<a class="fancybox.ajax" href="poppin/addProject.php">Ajouter une annonce</a>
						</li>
						<li class="myProject">
							<?php if ($_GET['id_project']): ?>
								<a href="#" id="see-all">Voir toutes les annonces</a>
							<?php else: ?>
								<a href="?user_fb=<?php echo $user_fb ?>" id="see-mine">
									<span class="text">Mes annonces</span>
									<span class="number"><?php echo getNbProject($user_fb) ?></span>
								</a>
							<?php endif; ?>
						</li>
					</ul>
				</nav>
			</div>
			<div id="block_current_filter" class="clearfix">
				<div id="current_filter">
					<p>Vous recherchez <span class="time">dès que possible</span> un poste<span class="work"> d'un ingénieur du son</span><span class="opt"> dans la commune de <span class="location">Paris</span> et <span class="distance">100km</span> aux alentours</span>.</p>
					<p class="none">Plus de facilité dans vos recherches ?<br/>Filtrez / Sauvegardez / et recevez par notification et/ou par mail toutes les annonces qui vous correspondent grâce à vos <span class="open_filtre">filtres</span> !</p>
				</div>
				<div id="notif_email">
					<p>Être tenu au courant des nouveautés de Clapps</p>
					<form action="requests/addListSubscribe.php" id="addSubscribe" class="clearfix">
						<input type="text" name="email" placeholder="votreadresse@email.com" />
						<input type="submit" value="Je reste informé" />
					</form>
				</div>
			</div>
			<form id="block_filters" action="">
				<div id="filter" class="clearfix">
					<div id="col1" class="col">
						<h2>Filtrer la recherche</h2>
						<p>Utiliser les différents filtres ci-contre
						pour affiner votre recherche.</p>
						<a href="?filter=false" id="refresh_button">Réinitialiser la recherche</a>
					</div>
					<div id="col2" class="col">
						<div class="field">
							<label for="profile">Métier</label>
							<input type="text" name="profile" id="profile" class="job autocomplete" placeholder="Entrez le métier recherché ..." />
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
								<input type="hidden" name="date_filter" id="date_filter" value="all">
							</div>
						</div>
					</div>
					<div id="col3" class="col">
						<div class="field">
							<label for="location">Lieux</label>
							<input type="text" name="location" id="location" class="location autocomplete" data-restricted="true" autocomplete="off" placeholder="Ville, département ou code postal" />
							<input type="hidden" name="distance" value="100" id="distance" />
						</div>
						<ul id="distances" class="clearfix">
							<li><a href="#" class="50">
								<span class="number">50 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#" class="current 100">
								<span class="number">100 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#" class="200">
								<span class="number">200 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#" class="500">
								<span class="number">500 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#" class="1000">
								<span class="number">1000 </span>
								<span class="unite">KM</span>
							</a></li>
						</ul>
					</div>
				</div>
				<div id="filter_advanced" class="clearfix">
					<ul class="nav">
						<li class="save">
							<a href="#tab1"><span>Sauvegarder les filtres</span></a>
						</li>
						<li class="load">
							<a href="#tab2" ><span>Charger mes filtres</span></a>
						</li>
						<li class="delete">
							<a href="#tab3" ><span>Supprimer mes filtres</span></a>
						</li>
					</ul>
					<div id="tabs">
						<div id="tab1" class="tab">
							<p><strong>Êtes-vous sûr de vouloir sauvegarder cette recherche ?</strong></p>
							<p>Si une sauvegarde antérieur existe, elle sera écrasée.</p>
							<div class="choice clearfix">
								<input type="submit" value="Oui" class="valid_button" />
								<a href="#" class="close">Annuler</a>
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
								<a href="#" class="valid_button load">Oui</a>
								<a href="#" class="close">Annuler</a>
							</div>
						</div>
						<div id="tab3" class="tab">
							<p><strong>Êtes-vous sûr de vouloir supprimer votre filtre ?</strong></p>
							<div class="choice clearfix">
								<a href="#" class="valid_button delete">Oui</a>
								<a href="#" class="close">Annuler</a>
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
	
		<section id="projects">
			<div id="successAddProject" class="message success">
				<p><span>Votre annonce est publiée.</span> Elle sera visible durant 15 jours,<br/> vous pourrez la réactiver pour <span>7 jours supplémentaires</span> à <span>2 jours</span> de sa fin de validité.</p>
			</div>
			<?php
				if (isset($_GET['id_project'])) : // one project
					$getProjects=getProject($_GET['id_project']);
				elseif ($_GET['filter']): // filtre on
					$getProjects=getProjectsByFilters($page,$_GET);
				elseif ($userFilter && !$_GET['user_fb']): // user got default filter but not in his projects
					$getProjects=getProjectsByFilters($page,$userFilterArray);
				else:
					$getProjects=getProjects($page,$_GET['user_fb']);
				endif;
				foreach ($getProjects as $project): 
					$valideDate = getValideDate($project['create_date'],$project['loop']); // check if project was revalidate, (don't use create_date but update_date)
				?>
					<article class="project read <?php if (isFavorite($project,$user_fb)): ?> favorite<?php endif ?>">
						<form action="">
							<div class="preview">
								<div class="block_top clearfix">
									<img src="https://graph.facebook.com/<?php echo $project['id_creator'] ?>/picture" alt="photo profil <?php echo $project['name_creator'] ?>" />
									<div class="title_block">
										<div class="title">
											<h2><input type="text" disabled="disabled" name="title" value="<?php echo $project['title']; ?>"></h2>
											<input type="hidden" name="id_project" value="<?php echo $project['id_project'] ?>">
											<div>Ajouté par <span><?php echo $project['name_creator']; ?></span> le <?php echo dateFormat('j.m.y',$project['create_date']); ?></div>
										</div>
										<div class="available clearfix">
											<?php $activeActors=getActiveActors($project['id_project']); ?>
											<?php $activeTechnicians=getActiveTechnicians($project['id_project']); ?>
											<div class="actors profile">
												<span><?php echo getOccurences($activeActors); ?></span>
												<p>Il reste <?php echo getOccurences($activeActors); ?> poste(s) de comédien(nes) disponible(s)</p>
											</div> 
											<div class="technicians profile">
												<span><?php echo getOccurences($activeTechnicians); ?></span>
												<p>Il reste <?php echo getOccurences($activeTechnicians); ?> poste(s) de technicien(nes) disponible(s)</p>
											</div>
										</div>
									</div>
								</div>
							
								<div class="share clearfix">
									<a href="#" class="share_link">Partager l'annonce</a>
									<?php if (!isAdmin($project)): ?>
										<?php if (isFavorite($project,$user_fb)): ?>
											<a href="#" data-id="<?php echo $project['id_project'] ?>" class="unfavorite_link">Retirer des favoris</a>
										<?php else: ?>
											<a href="#" data-id="<?php echo $project['id_project'] ?>" class="favorite_link">Ajouter aux favoris</a>
										<?php endif ?>
									<?php endif ?>
								</div>
								<div class="desc">
									<h3>Détails de l'annonce :</h3>
									<textarea class="hide" id='animated'><?php echo $project['description']; ?></textarea>
									<p><?php echo $project['description']; ?></p>
								</div>
								<div class="bloc_see_more clearfix">
		
									<div class="project_id">#<?php echo $project['id_project']; ?></div>
									<div class="date"><input type="text" disabled="disabled" class="datepicker" value="<?php echo dateUstoFr($project['date_filter']); ?>"></div>
									<div class="place field">
										<input type="text" disabled="disabled" id="location" name="location" class="location autocomplete" autocomplete="off" value="<?php echo $project['place']; ?> (<?php echo $project['zip_code']; ?>)">
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
															<a href="#">Postuler</a>
														<?php else: ?>
															<a href="#" class="profile_found" data-id="<?php echo $project['id_project'] ?>"  data-idprofile="<?php echo $profile['id_profile'] ?>" >J'ai trouvé</a>
														<?php endif; ?>
													</div>
												</div>
												<div class="block_edition hide">
													<div class="add_job add_field field">
														<input type="text" class="job autocomplete entitled" placeholder="Métier recherché"  value="<?php echo $profile['name']; ?>" name="name[]" />
														<input type="hidden" class="idjob" name="id_job[]" value="<?php echo $profile['id_job']; ?>" />
													</div>
													<div class="add_desc add_field"><input type="text" placeholder="Description du poste recherché" name="profile[]" value="<?php echo $profile['person']; ?>" /></div>
													<div class="edit">
														<div class="deleteButton">
															<a href="#" class="button_delete_profile">Supprimer</a>
															<div class="confirm">
																<p>êtes-vous sûr de vouloir supprimer ?</p>
																<div>
																	<a href="#" class="confirm_delete_profile">Oui</a>
																	<a href="#" class="cancel_delete_profile">Annuler</a>
																</div>
															</div>
														</div>
														<div class="quantity">
															<a href="#" class="less_quantity number_control">-</a>
															<input type="text" value="1" class="number" name="occurence[]"/>
															<a href="#" class="more_quantity number_control">+</a>
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
											<div class="add_job add_field field"><input type="text" class="job autocomplete entitled" placeholder="Métier recherché" name="name[]" /></div>
											<div class="add_desc add_field"><input type="text" placeholder="Description du poste recherché" name="profile[]"/></div>
											<input type="hidden" class="idjob" name="id_job[]" />
											<div class="edit">
												<div class="line_control">
													<a href="#" class="add-post">+</a>
												</div>
												<div class="quantity">
													<a href="#" class="less_quantity number_control">-</a>
													<input type="text" value="1" class="number" name="occurence[]"/>
													<a href="#" class="more_quantity number_control">+</a>
												</div>
											</div>
										</li>
									</ul>
								</div><!-- fin profile -->
								<?php if (isAdmin($project,$user_fb)): ?>
									<div class="manage manage-read clearfix">
										<p>Validité de l'annonce :
											<?php if ($valideDate>0): ?>
												<span class="valid"><?php echo $valideDate; if ($valideDate>1): ?> jours restants<?php else: ?> jour restant<?php endif ?></span>
											<?php else: ?>
												<span class="finish">Désactivée</span>
											<?php endif ?>
										</p>
										<?php if ($valideDate<0): ?><a href="#" class="extendProject big_button" data-id="<?php echo $project['id_project'] ?>">Réactiver l'annonce</a><?php endif; ?>
										<?php if (($valideDate>0)&&($valideDate<=DAY_UNTIL_REACTIVATE)): ?><a href="#" class="extendProject big_button">Prolonger l'annonce</a><?php endif; ?>
										<a href="#" class='editProject big_button' data-id="<?php echo $project['id_project'] ?>"><span>Editer</span> l'annonce</a>
									</div>
									<div class="manage manage-edition hide clearfix">
										<div class="block_delete_project">
											<a href="javascript:void(0)" class="button_delete_project big_button">Supprimer l'annonce</a>
											<div class="confirm">
												<p>êtes-vous sûr de vouloir supprimer ?</p>
												<div>
													<a href="poppin/deleteProject.php" data-id="<?php echo $project['id_project'] ?>" class="fancybox.ajax valid_delete_project">Oui</a>
													<a href="javascript:void(0)" class="cancel_delete_project">Annuler</a>
												</div>
											</div>
										</div>
										<input type="submit" value="Valider" />
										<a href="#" class="cancelEditProject big_button">Annuler</a>
									</div>
								<?php endif ?>
							</div><!-- fin more -->
							<div id="see_button">
								<a href="#" class="see-more"><span>Voir</span> plus</a>
							</div>
						</form>
					</article>
				<?php endforeach; ?>
				
			<?php if ($nbProject>POST_PER_PAGE && !$_GET['id_project']): ?>
				<div class="btn-more-projects">
					<a href="?page=<?php echo $page+1; ?>&<?php echo http_build_query($_GET, '=') ?>" data-nav="<?php echo $page ?>">Voir plus ...</a>
				</div>
			<?php endif; ?>
			
		</section>
		
		<div id="tuto">
			<div id="light_header_tuto"></div>
			<div id="content_tuto">
				<div id="block_logo_tuto">
					<p>My clapps</p>
				</div>
				<div id="block_button_tuto" class="clearfix">
					<div class="desc">
						<h2>Trouver un tournage n’aura jamais été aussi simple !</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br/> Pellentesque rhoncus tortor non ...</p>
					</div>
					<a href="#">J'accède à l'application</a>
				</div>
				<div id="block_display_tuto">
					<div class="title">
						<h3>Myclapps en 4 étapes</h3>
					</div>
					<div class="display">
						<div class="mask">
							<img src="#" alt="Image tuto" />
						</div>
					</div>
					<div id="display_bottom"></div>
				</div>
				<div id="block_nav_tuto">
					<ul class="clearfix">
						<li class="done"><a href="#">
							<span></span>
							<em>Rechercher/Filtrer</em>
						</a></li>
						<li class="done"><a href="#">
							<span></span>
							<em>Gestion des filtres</em>
						</a></li>
						<li class="current"><a href="#">
							<span></span>
							<em>Mise en favoris <br/>Partager une annonce</em>
						</a></li>
						<li class="next"><a href="#">
							<span></span>
							<em>Création d'une annonce <br/> Édition d'une annonce</em>
						</a></li>
					</ul>
				</div>
			</div>
			<div id="curtain_tuto"></div>
		</div><!-- fin tuto-->
		
	</div> <!-- fin page-->
	
	<div id="fb-root"></div>
	
	<script src="js/libs/jquery-1.8.0.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link rel="stylesheet" href="css/jquery.ui.theme.css" />
	<script src="js/libs/jquery-ui.datepicker.js"></script>
	<script src="js/libs/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.autosize.js" type="text/javascript" charset="utf-8"></script>
	<script src="./js/main.js"></script>
	<script type="text/javascript"> zf.maxPages = <?php echo getMaxPages($_GET['user_fb']) ?></script>
	
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
			    e.src = document.location.protocol + "//connect.facebook.net/en_US/all.js";
			    e.async = true;
			    document.getElementById("fb-root").appendChild(e);
			  }());
		</script>';
 	?>

</body>
</html>