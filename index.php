<?php 
	require_once './inc/init.php';
	require_once './inc/settings.php';
	require_once './inc/functions.php';
	$page=$_GET['page'];
	if(!isset($page)){$page=1;}
	$nbProject = getNbProject($_GET['user_fb']);
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
				</div>
				<div id="notif_email">
					<p>M'avertir des nouvelles annonces par email</p>
					<form action="" class="clearfix">
						<input type="text" placeholder="votreadresse@email.com" />
						<ul class="switch">
							<li><a href="#">ON</a></li>
						</ul>
						<input type="hidden" name="notif" value="" />
					</form>
				</div>
			</div>
			<form id="block_filters" action="">
				<div id="filter" class="clearfix">
					<div id="col1" class="col">
						<h2>Filtrer la recherche</h2>
						<p>Utiliser les différents filtres ci-contre
						pour affiner votre recherche.</p>
						<a href="javascript:void(0);" id="refresh_button">Réinitialiser la recherche</a>
					</div>
					<div id="col2" class="col">
						<div class="field">
							<label for="profile">Métier</label>
							<input type="text" name="profile" id="profile" class="metier autocomplete" placeholder="Entrez le métier recherché ..." />
						</div>
						<div class="field select">
							<label for="selector_date">Date</label>
							<div class="selector" id="selector_date">
								<div>
									<span class="value" id="date_filter_selected">Dés que possible</span>
									<span class="button">Modifier</span>
								</div>
								<ul>
									<li class="now">Dès que possible</li>
									<li class="week">Cette semaine</li>
									<li class="month">Ce mois-ci</li>
									<li class="trimestre">Ce trimestre</li>
								</ul>
								<input type="hidden" name="date_filter" id="date_filter" value="now">
							</div>
						</div>
					</div>
					<div id="col3" class="col">
						<div class="field">
							<label for="location">Lieux</label>
							<input type="text" name="location" id="location" class="location autocomplete" autocomplete="off" placeholder="Ville, département ou code postal" />
							<input type="hidden" name="distance" value="100" id="distance" />
						</div>
						<ul id="distances" class="clearfix">
							<li><a href="#">
								<span class="number">50 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#" class="current">
								<span class="number">100 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#">
								<span class="number">200 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#">
								<span class="number">500 </span>
								<span class="unite">KM</span>
							</a></li>
							<li><a href="#">
								<span class="number">1000 </span>
								<span class="unite">KM</span>
							</a></li>
						</ul>
					</div>
				</div>
				<div id="filter_advanced" class="clearfix">
					<ul class="nav">
						<li class="save current">
							<a href="#tab1"><span>Sauvegarder les filtres</span></a>
						<!--	<span class="bubble">Sauvegarder les filtres</span> -->
						</li>
						<li class="load">
							<a href="#tab2"><span>Charger mes filtres</span></a>
						<!--	<span class="bubble">Charger mes filtres</span> -->
						</li>
						<li class="delete">
							<a href="#tab3"><span>Supprimer mes filtres</span></a>
						<!--	<span class="bubble">Supprimer mes filtres</span> -->
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
							<p>Ne manquez aucunes annonces, activez les notifications emails</p>
							<div class="clearfix">
								<input type="text" class="email" placeholder="votreadresse@email.com" />
								<ul class="switch">
									<li class="current">
										<a href="#">ON</a>
									</li>
									<li>
										<a href="#">OFF</a>
									</li>
								</ul>
							</div>
						</div>
						<div id="tab2" class="tab">
							<p>Êtes-vous sûr de vouloir sauvegarder cette recherche ?</p>
							<p>Si une sauvegarde antérieur existe, elle sera écrasée.</p>
							<input type="text" value="Oui" />
							<a href="#">Annuler</a>
							<p>Ne manquez aucunes annonces, activez les notifications emails</p>
							<input type="text" value="votreadresse@email.com" />
						</div>
						<div id="tab3" class="tab">
							<p>Êtes-vous sûr de vouloir sauvegarder cette recherche ?</p>
							<p>Si une sauvegarde antérieur existe, elle sera écrasée.</p>
							<input type="text" value="Oui" />
							<a href="#">Annuler</a>
							<p>Ne manquez aucunes annonces, activez les notifications emails</p>
							<input type="text" value="votreadresse@email.com" />
						</div>
					</div>
				</div>
			</form>
		</header>
	
		<section id="projects">
		
			<article class="project">
				<form action="">
					<div class="preview">
						<div class="block_top clearfix">
							<img src="" alt="photo profil" />
							<div class="title_block">
								<div class="title">
									<input type="text" value="Projet en mode édition" />
									<div>Ajouté par <span>Pierre-loic</span> le 07.10.12</div>
								</div>
								<div class="available clearfix">
									<div class="actors profile">
										<span>22</span>
										<p>Il reste 22 poste(s) de comédien(nes) disponible(s)</p>
									</div> 
									<div class="technicians profile">
										<span>18</span>
										<p>Il reste 18 poste(s) de technicien(nes) disponible(s)</p>
									</div>
								</div>
							</div>
						</div>
						<div class="share clearfix">
							<a href="#" class="share_link">Partager l'annonce</a>
							<a href="#" class="favorite_link">Ajouter aux favoris</a>
						</div>
						<div class="desc">
							<h3>Détails de l'annonce :</h3>
							<textarea name="" id="" cols="30" rows="10">description</textarea>
						</div>
						<div class="bloc_see_more clearfix">
							<div class="project_id">#3000</div>
							<div class="date"><input type="text" value="28 décembre 2012" /></div>
							<div class="place field"><input type="text" value="Paris 11e (75011)" class="autocomplete location"/></div>
							<div id="see_button">
								<a href="#" class="see-more"><span>Voir</span> plus</a>
							</div>
						</div>
					</div><!-- fin preview -->
					<div class="more">
						<div class="profiles">
							<ul>
								<li class="clearfix">
									<div class="icon iconActor"><span>36</span></div>
									<div class="edit_desc"><textarea name="" placeholder="Description du profil">Une actrice blonde, 1m70 à forte poitrine acceptant les scènes de nudité</textarea></div>
									
									<div class="edit clearfix">
										<div class="deleteButton">
											<a href="#">Supprimer</a>
											<div class="confirm">
												<p>êtes-vous sûr de vouloir supprimer ?</p>
												<div>
													<a href="#">Oui</a>
													<a href="#">Annuler</a>
												</div>
											</div>
										</div>
										<div class="foundButton">
											<a href="#"><span>Trouvé</span></a>
										</div>
									</div>
								</li>
								<li class="clearfix">
									<div class="icon iconActor"><span>52</span></div>
									<div class="edit_desc"><textarea name="" placeholder="Description du profil">Un acteur Barbu, 1m85 bien monté acceptant les scènes de nudité. </textarea></div>
									
									<div class="edit">
										<div class="deleteButton">
											<a href="#">Supprimer</a>
											<div class="confirm">
												<p>êtes-vous sûr de vouloir supprimer ?</p>
												<div>
													<a href="#">Oui</a>
													<a href="#">Annuler</a>
												</div>
											</div>
										</div>
										<div class="quantity">
											<a href="#" class="less_quantity number_control">-</a>
											<input type="text" value="1" class="number" name="occurence[]"/>
											<a href="#" class="more_quantity number_control">+</a>
										</div>
									</div>
								</li>
								<li class="clearfix profileFound">
									<div class="icon iconActor"><span>52</span></div>
									<p>Un acteur d'1m80</p>
									<div class="apply applyFound">Candidat trouvé</div>
								</li>
							</ul>
						</div><!-- fin profile -->
						<div class="manage clearfix">
							<a href="poppin/deleteProject.php" class="fancybox.ajax deleteProject">Supprimer l'annonce</a>
							<input type="submit" value="Valider" />
							<a href="#" class="cancelProject">Annuler</a>
						</div>
					</div><!-- fin more -->
				</form>
			</article>
			
			<?php
				if (isset($_GET['id_project'])) :
					$getProjects=getProject($_GET['id_project']);
				elseif ($_GET['filter']):
					$getProjects=getProjectsByFilters($page,$_GET);
				else:
					$getProjects=getProjects($page,$_GET['user_fb']);
				endif;
				foreach ($getProjects as $project): 
					$valideDate = getValideDate($project['create_date']); // check if project was revalidate, (don't use create_date but update_date)
				?>
					<article class="project<?php if (isFavorite($project,$user_fb)): ?> favorite<?php endif ?>">
						<div class="preview">
							<div class="block_top clearfix">
								<img src="<?php echo $project['img_creator'] ?>" alt="photo profil" />
								<div class="title_block">
									<div class="title">
										<h2><input type="text" disabled="disabled" value="<?php echo $project['title']; ?>"></h2>
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
								<p><textarea disabled="disabled"><?php echo $project['description']; ?></textarea></p>
							</div>
							<div class="bloc_see_more clearfix">
								<div class="project_id">#<?php echo $project['id_project']; ?></div>
								<div class="date"><input type="text" disabled="disabled" value="<?php echo dateUstoFr($project['date_filter']); ?>"></div>
								<div class="place"><input type="text" disabled="disabled" value="<?php echo $project['place']; ?> (<?php echo $project['zip_code']; ?>)"></div>
							</div>
						</div><!-- fin preview -->
						<div class="more">
							<div class="profiles">
								<ul>
									<?php $getProfiles=getProfiles($project['id_project']); ?>
									<?php $getProfilesFound=getProfilesFound($project['id_project']); ?>
									<?php foreach ($getProfiles as $profile) { ?>
										<li class="clearfix">
											<?php 
												if($profile['domain']==1){
													$profileDomain="iconActor"; 
												}elseif($profile['domain']==2){
													$profileDomain="iconTechnician"; 
												} 
											?>
											<div class="icon <?php echo $profileDomain; ?>">
												<span><?php echo $profile['occurence']; ?></span>
											</div>
											<p><input type="text" disabled="disabled" value="<?php echo $profile['person']; ?>"></p>
											<div class="apply">
												<?php if (!isAdmin($project,$user_fb)): ?>
													<a href="#">Postuler</a>
												<?php else: ?>
													<a href="#">J'ai trouvé</a>
												<?php endif; ?>
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
										<li class="clearfix profileFound">
											<div class="icon <?php echo $profileDomain; ?>">
											</div>
											<p><?php echo $profile['person']; ?></p>
										<div class="apply applyFound">Candidat trouvé</div>
										</li>
									<?php } ?>
								</ul>
							</div><!-- fin profile -->
							<?php if (isAdmin($project,$user_fb)): ?>
								<div class="manage clearfix">
									<p>Validité de l'annonce :
										<?php if ($valideDate>0): ?>
											<span class="valid"><?php echo $valideDate; if ($valideDate>1): ?> jours restants<?php else: ?> jour restant<?php endif ?></span>
										<?php else: ?>
											<span class="finish">Désactivée</span>
										<?php endif ?>
									</p>
									<?php if ($valideDate<0): ?><a href="#" class="extendProject">Réactiver l'annonce</a><?php endif; ?>
									<?php if (($valideDate>0)&&($valideDate<3)): ?><a href="#" class="extendProject">Prolonger l'annonce</a><?php endif; ?>
									<a href="#" class='editProject' data-id="<?php echo $project['id_project'] ?>"><span>Editer</span> l'annonce</a>
								</div>
							<?php endif ?>
						</div><!-- fin more -->
						<div id="see_button">
							<a href="#" class="see-more"><span>Voir</span> plus</a>
						</div>
					</article>
				<?php endforeach; ?>
				
			<?php if ($nbProject>POST_PER_PAGE && !$_GET['id_project']): ?>
				<div class="btn-more-projects">
					<a href="?page=<?php echo $page+1; ?><?php echo http_build_query($_GET, '=') ?>" data-nav="<?php echo $page ?>">Voir plus ...</a>
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
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
	 <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
	<script src="js/libs/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>
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