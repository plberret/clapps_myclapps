
<form id="newProject" action="requests/addProject.php" method="post">
	
	<h2>Ajouter une annonce</h2>
	
	<div class="desc">
		<h3>Informations générales</h3>
		<p>
			<input type="text" name="title" id="title" value="Titre de votre annonce" />
			<em>80 caractères restants</em>
		</p>
		<p>
<<<<<<< HEAD
			<textarea id="desc" name="desc" >Description de votre annonce...</textarea>
		</p>
	</div>
	
	<div class="profiles">
		<h3>Ajouter un(des) poste(s)</h3>
		<p class="clearfix">
			<input type="text" name="profile[]" class="entitled" value="Intitulé du poste recherché" />
=======
			<label for="">Poste recherché : </label>
			<input type="text" name="profile[]" class="name" />
>>>>>>> cef67b7f3c66bb15ceb7de54ba8de82b6242df8c
			<select name="domain[]">
				<option value="0">Acteur</option>
				<option value="1">Technicien</option>
			</select>
			<a href="#" class="less number_control">-</a>
			<input type="text" value="7" class="number" />
			<a href="#" class="more number_control">+</a>
			<a href="#" class="delete">-</a>
		</p>
<<<<<<< HEAD
		<p class="clearfix">
			<input type="text" name="profile[]" class="entitled" value="Intitulé du poste recherché" />
=======
		<p>
			<label for="">Poste recherché : </label>
			<input type="text" name="profile[]" class="name" />
>>>>>>> cef67b7f3c66bb15ceb7de54ba8de82b6242df8c
			<select name="domain[]">
				<option value="0">Acteur</option>
				<option value="1">Technicien</option>
			</select>
			<a href="#" class="less number_control">-</a>
			<input type="text" value="7" class="number" />
			<a href="#" class="more number_control">+</a>
			<a href="#" id="add-post">+</a>
		</p>
	</div>
	
	<p class="clearfix">
		<input type="submit" id="add-project" value="Publier l'annonce" />
	</p>

</form>