<?php 
	require_once './inc/init.php';
	require_once './inc/settings.php';
	require_once './inc/functions.php';
	$page=$_GET['page'];
	if(!isset($page)){$page=1;}
	$nbProject = getNbProject($_GET['user_fb']);
?>
<pre>
	<?php array_slice($_GET, 0) ?>// I NEED URL AS ARRAY
</pre>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>My clapps</title>
	<link rel="stylesheet" type="text/css" media="all" href="./css/style.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<div id="page">
		<header>
			<h1>My clapps</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque rhoncus tortor non lorem viverra tincidunt euismod nisi adipiscing. Nunc imperdiet aliquam est quis sollicitudin. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut massa magna, lobortis feugiat hendrerit nec, tincidunt eget urna.</p>
			<nav>
				<ul class="clearfix">
					<li class="addProject">
						<a class="fancybox.ajax" href="poppin/addProject.php">Ajouter une annonce</a>
					</li>
					<li class="myProject">
						<?php if ($_GET['id_project']): ?>
							<a href="#" id="see-all">Voir toutes les annonces</a>
						<?php else: ?>
							<a href="?user_fb=<?php echo $user_fb ?>" id="see-mine">Mes annonces</a>
						<?php endif; ?>
					</li>
				</ul>
			</nav>
		</header>
		<section id="projects">
			<?php
				if (isset($_GET['id_project'])) :
					$getProjects=getProject($_GET['id_project']);
				elseif ($_GET['domain']):
					$getProjects=getProjectsByFilters($page,$_GET);
				else:
					$getProjects=getProjects($page,$_GET['user_fb']);
				endif;
				foreach ($getProjects as $project): ?>
					<article class="project<?php if (isFavorite($project,$user_fb)): ?> favorite<?php endif ?>">
						<div class="preview">
							<div class="block_top clearfix">
								<img src="<?php echo $project['img_creator'] ?>" alt="photo profil" />
								<div class="title_block">
									<div class="title">
										<h2><?php echo $project['title']; ?></h2>
										<div>Ajouté par <span><?php echo $project['name_creator']; ?></span> le 07.10.12</div>
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
								<?php if (isFavorite($project,$user_fb)): ?>
									<a href="#" data-id="<?php echo $project['id_project'] ?>" class="unfavorite_link">Retirer des favoris</a>
								<?php else: ?>
									<a href="#" data-id="<?php echo $project['id_project'] ?>" class="favorite_link">Ajouter aux favoris</a>
								<?php endif ?>
							</div>
							<div class="desc">
								<h3>Détails de l'annonce :</h3>
								<p><?php echo $project['description']; ?></p>
							</div>
							<div class="clearfix">
								<a href="#" class="see-more">Voir plus</a>
								<div class="project_id">#<?php echo $project['id_project']; ?></div>
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
											<p><?php echo $profile['person']; ?></p>
											<div class="apply"><a href="#">Postuler</a></div> 
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
										<div class="applyFound">Candidat trouvé</div>
										</li>
									<?php } ?>
								</ul>
							</div><!-- fin profile -->
						</div><!-- fin more -->
						<?php if (isAdmin($project,$user_fb)): ?>
							<div class="manage">
								<a href="#">Cloturer l'annonce</a>
								<a href="#">Supprimer l'annonce</a>
							</div>
						<?php endif ?>
					</article>
				<?php endforeach; ?>
			<?php if ($nbProject>POST_PER_PAGE && !$_GET['id_project']): ?>
				<div class="btn-more-projects">
					<a href="?page=<?php echo $page+1; ?>&<?php echo implode($_GET, '=') ?>" data-nav="<?php echo $page ?>">charger plus de projets</a>
				</div>
			<?php endif; ?>
		</section>
	</div> <!-- fin page-->
	<div id="fb-root"></div>
	<script src="js/libs/jquery-1.8.0.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
	<script src="./js/main.js"></script>
	<script type="text/javascript">
		zf.maxPages = <?php echo getMaxPages($_GET['user_fb']) ?>
	</script>
	<?php
		echo '<script type="text/javascript">
		FB.init({
			appId : '.APP_ID.',
			status : true, // check login status
			cookie : true, // enable cookies to allow the server to access the session
			xfbml : true // parse XFBML
		});
	</script>
';?>
</body>
</html>