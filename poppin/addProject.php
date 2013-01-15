<form id="newProject" action="requests/addProject.php" method="post">
	
	<h2>Ajouter une annonce</h2>
	
	<div class="desc">
		<h3>Informations générales</h3>
		<div class="field">
			<input class="required" type="text" name="title" id="title" maxlength="80" placeholder="Titre de votre annonce" />
			<em><span data-length="80">80</span> caractères restants</em>
		</div>
		<div class="text">
			<textarea class="required" id="desc" name="desc" placeholder="Description de votre annonce..."></textarea>
		</div>
		<div id="block_place" class="clearfix">
			<div class="date field">
				<label for="">Date</label>
				<input type="text" class="required datepicker" name="date_tournage" placeholder="Date du tournage" />
			</div>
			<div class="field">
				<label for="place">Lieu</label>
				<input class="required autocomplete location" type="text" name="place" placeholder="Ville, département ou code postal" />
				<input type="hidden" name="id_place" class="id_place" />
				<input type="hidden" name="type_place" class="type_place" />
			</div>
		</div>
	</div>
	
	<div class="profiles">
		<h3>Ajouter un(des) poste(s)</h3>
		<ul>
			<li class="clearfix field">
				<input type="text" placeholder="Métier recherché" class="job required autocomplete" />
				<input type="hidden" name="domain[]" value="3" />
				<input type="text" name="profile[]" class="required entitled" placeholder="Intitulé du poste recherché" />
				<div class="quantity">
					<a href="#" class="less_quantity number_control">-</a>
					<input type="text" value="1" class="number" name="occurence[]"/>
					<a href="#" class="more_quantity number_control">+</a>
				</div>
				<div class="line_control">
					<a href="#" class="delete">-</a>
				</div>
			</li>
			<li class="clearfix">
				<input type="text" placeholder="Métier recherché" class="job" />
				<input type="hidden" name="domain[]" value="3" />
				<input type="text" name="profile[]" class="entitled" placeholder="Intitulé du poste recherché" />
				<div>
					<div class="quantity">
						<a href="#" class="less_quantity number_control">-</a>
						<input type="text" value="1" class="number" name="occurence[]"/>
						<a href="#" class="more_quantity number_control">+</a>
					</div>
					<div class="line_control">
						<a href="#" id="add-post">+</a>
					</div>
				</div>
			</li>
		</ul>
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