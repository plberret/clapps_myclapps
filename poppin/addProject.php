<form id="newProject" action="requests/addProject.php" method="post">
	
	<h2>Ajouter une annonce</h2>
	
	<div class="desc">
		<h3>Informations générales</h3>
		<div class="field">
			<input type="text" name="title" id="title" maxlength="80" placeholder="Titre de votre annonce" />
			<em><span data-length="80">80</span> caractères restants</em>
		</div>
		<div class="text">
			<textarea id="desc" name="desc" placeholder="Description de votre annonce..."></textarea>
		</div>
		<div id="block_place" class="clearfix">
			<div class="date field">
				<label for="">Date</label>
				<input type="text" placeholder="Date du tournage" />
			</div>
			<div class="field">
				<label for="place">Lieu</label>
				<input type="text" name="place" placeholder="Ville, département ou code postal" />
				<ul id="autocompletion">
					<li>Paris</li>
					<li>Marseille</li>
					<li>Lyon</li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="profiles">
		<h3>Ajouter un(des) poste(s)</h3>
		<div class="clearfix">
			<input type="text" placeholder="Métier recherché" class="job" />
			<!-- forcé en attendant l'update de leo -->
			<input type="hidden" name="domain[]" value="1" />
			<input type="text" name="profile[]" class="entitled" placeholder="Intitulé du poste recherché" />
			<div class="quantity">
				<a href="#" class="less number_control">-</a>
				<input type="text" value="1" class="number" name="occurence[]"/>
				<a href="#" class="more number_control">+</a>
			</div>
			<div class="line_control">
				<a href="#" class="delete">-</a>
			</div>
		</div>
		<div class="clearfix">
			<input type="text" placeholder="Métier recherché" class="job" />
			<!-- forcé en attendant l'update de leo -->
			<input type="hidden" name="domain[]" value="1" />
			<input type="text" name="profile[]" class="entitled" placeholder="Intitulé du poste recherché" />
			<div class="quantity">
				<a href="#" class="less number_control">-</a>
				<input type="text" value="1" class="number" name="occurence[]"/>
				<a href="#" class="more number_control">+</a>
			</div>
			<div class="line_control">
				<a href="#" id="add-post">+</a>
			</div>
		</div>
	</div>
	
	<div class="clearfix">
		<input type="submit" id="add-project" value="Publier l'annonce" />
	</div>
	
	<div class="message success">
		<p><span>Votre annonce est publiée.</span> Elle sera visible durant 15 jours,<br/> vous pourrez la réactiver pour <span>7 jours supplémentaires</span> à <span>2 jours</span> de sa fin de validité.</p>
	</div>
	<div class="message error">
		<p><span>Veuillez remplir tous les champs.</span></p>
	</div>
	
</form>