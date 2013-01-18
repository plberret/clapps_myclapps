<div id="blocDelete">
	<div class="title">
		<h2>Votre annonce est supprimée </h2>
		<p>Afin de mieux connaitre les raisons de la suppression votre annonce, pouvez-vous remplir le formulaire ci-dessous ? Merci d’avance pour votre participation</p>
	</div>
	<form action="">
		<div class="select">
			<label for="">Pourquoi avez-vous supprimé votre annonce ?</label>
			<div class="selector">
				<div>
					<span class="value" id="date_filter_selected">Sélectionnez une raison...</span>
					<span class="button">Modifier</span>
				</div>
				<ul>
					<li class="clapps">J'ai composé mon équipe avec Clapps</li>
					<li class="autre_service">J'ai trouvé via un autre service</li>
					<li class="mon_reseau">J'ai trouvé via mon réseau</li>
					<li class="autre">Autre</li>
				</ul>
				<input type="hidden" name="reason" class="reason" value="">
			</div>
		</div>
		<div class="field precise">
			<input type="text" name="desc" placeholder="Précisez" />
		</div>
		<div class="text">
			<textarea name="remarque" placeholder="Vous avez des remarques ? N’hésitez pas ..." ></textarea>
		</div>
		<input type="submit" value="Soumettre" />
	</form>
</div>