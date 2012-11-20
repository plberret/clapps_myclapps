<form id="newProject" action="requests/addProject.php" method="post">
	
	<h2>Ajouter une annonce</h2>
	
	<div class="desc">
		<h3>Informations générales</h3>
		<p>
			<input type="text" name="title" id="title" maxlength="80" placeholder="Titre de votre annonce" />
			<em><span data-length="80">80</span> caractères restants</em>
		</p>
		<p>
			<textarea id="desc" name="desc" placeholder="Description de votre annonce..."></textarea>
		</p>
	</div>
	
	<div class="profiles">
		<h3>Ajouter un(des) poste(s)</h3>
		<p class="clearfix">
			<input type="text" name="profile[]" class="entitled" placeholder="Intitulé du poste recherché" />
			<select name="domain[]">
				<option value="1">Acteur</option>
				<option value="2">Technicien</option>
			</select>
			<a href="#" class="less number_control">-</a>
			<input type="text" value="1" class="number" name="occurence[]"/>
			<a href="#" class="more number_control">+</a>
			<a href="#" class="delete">-</a>
		</p>
		<p class="clearfix">
			<input type="text" name="profile[]" class="entitled" placeholder="Intitulé du poste recherché" />
			<select name="domain[]">
				<option value="1">Acteur</option>
				<option value="2">Technicien</option>
			</select>
			<a href="#" class="less number_control">-</a>
			<input type="text" value="1" class="number" name="occurence[]"/>
			<a href="#" class="more number_control">+</a>
			<a href="#" id="add-post">+</a>
		</p>
	</div>
	
	<p class="clearfix">
		<input type="submit" id="add-project" value="Publier l'annonce" />
	</p>
</form>