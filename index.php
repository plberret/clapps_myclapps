
<?php require_once('./inc/functions.php'); ?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>My clapps</title>
	<link rel="stylesheet" type="text/css" media="all" href="./css/style.css">
</head>
<body>
	
	<header>
		<h1>My clapps</h1>
		<nav>
			<ul>
				<li>
					<a href="#">Ajouter une annonce</a>
				</li>
				<li>
					<a href="#">Mes annonces</a>
				</li>
			</ul>
		</nav>
	</header>
	
	<section>
		<form id="newProject" style="margin: 30px 0; background: #DDD;">
			<p>
				<label for="">Nom du projet </label>
				<input type="text" />
			</p>
			<p>
				<label for="">Description</label>
				<textarea name="" id="" cols="30" rows="10"></textarea>
			</p>
			<div id="profileList">
				<p>
					<label for="">Poste recherché : </label>
					<input type="text" name="profile1" />
					<select name="domain1" id="domain1">
						<option value="actor">Acteur</option>
						<option value="technicien">Technicien</option>
					</select>
				</p>
				<p>
					<label for="">Poste recherché : </label>
					<input type="text" name="profile1" />
					<select name="domain1" id="domain1">
						<option value="actor">Acteur</option>
						<option value="technicien">Technicien</option>
					</select>
				</p>
				<a href="#">Ajouter un poste</a>
			</div>
			<p>
				<input type="submit" value="envoyer" />
			</p>
		</form>
	
		<?php
			
			$getProjects=getProjects();
			
			echo '<pre>';
		//	print_r($getProjects);
			echo '</pre>';
			
			foreach ($getProjects as $project) {
				
		?>
		 
				<article style="margin: 30px 0; background: #CCC;">
					<div class="preview">
						<h2><?php echo $project['Title']; ?></h2>
						<div>Ajouté par <span>Admin</span> le <span>29 octobre 2012</span></div>
						<div class="available">
							<?php $activeActors=getActiveActors($project['ID_project']); ?>
							<?php $activeTechnicians=getActiveTechnicians($project['ID_project']); ?>
							<a href="#"><?php echo count($activeActors); ?> Acteurs dispo</a>
							<a href="#"><?php echo count($activeTechnicians); ?> techniciens dispo</a>
						</div>
						<div class="share">
							<a href="#">Ajouter aux favoris</a>
							<a href="#">Partager l'annonce</a>
						</div>
						<div class="desc">
							<img src="#" alt="photo profil" />
							<p><?php echo $project['Description']; ?></p>
						</div>
						<a href="#" class="see-more">Voir plus</a>
					</div><!-- fin preview -->
					<div class="more">
						<div class="moreDesc">
							<p>more description</p>
						</div>
						<div class="profiles">
							<ul>
								<?php $getProfiles=getProfiles($project['ID_project']); ?>
								<?php foreach ($getProfiles as $profile) { ?>
									<li>
										<span>Img</span>
										<p><?php echo $profile['Person']; ?></p>
										<a href="#">Candidater</a>
										<a href="#">Candidat trouvé</a>
									</li>
								<?php } ?>
							</ul>
						</div><!-- fin profile -->
						<div class="manage">
							<a href="#">Cloturer l'annonce</a>
							<a href="#">Supprimer l'annonce</a>
						</div>
					</div><!-- fin more -->
				</article>
				
		<?php } ?>
	</section>
	<script src="js/jquery-1.8.0.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="./js/main.js"></script>
</body>
</html>