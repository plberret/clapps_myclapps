<?php require_once('./inc/functions.php'); ?>

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
						<a href="#">Mes annonces</a>
					</li>
				</ul>
			</nav>
		</header>
		<section>
			<?php
			
				$getProjects=getProjects();
			
				foreach ($getProjects as $project) :
			?>
			<pre><?php //print_r($getProjects); ?></pre>
			
					<article class="project">
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
								<a href="#" class="favorite_link">Ajouter aux favoris</a>
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
									<li class="clearfix profileFound">
										<div class="icon iconTechnician">
											<span><?php echo $profile['occurence']; ?></span>
										</div>
										<p>Exemple de profil trouvé</p>
										<div class="applyFound">Candidat trouvé</div>
									</li>
								</ul>
							</div><!-- fin profile -->
							<!--<div class="manage">
								<a href="#">Cloturer l'annonce</a>
								<a href="#">Supprimer l'annonce</a>
							</div>-->
						</div><!-- fin more -->
					</article>
			<?php endforeach; ?>
		</section>
	</div> <!-- fin page-->
	<script src="js/libs/jquery-1.8.0.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
	<script src="./js/main.js"></script>
</body>
</html>