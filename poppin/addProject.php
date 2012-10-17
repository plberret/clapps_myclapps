<form id="newProject" action="requests/addProject.php" method="post">
	<p>
		<label for="title">Nom du projet </label>
		<input type="text" name="title" id='title'/>
	</p>
	<p>
		<label for="desc">Description</label>
		<textarea id="desc"  name="desc" cols="30" rows="10"></textarea>
	</p>
	<div id="profileList">
		<p>
			<label for="">Poste recherché : </label>
			<input type="text" name="profile[]" class="name" />
			<select name="domain[]">
				<option value="actor">Acteur</option>
				<option value="technicien">Technicien</option>
			</select>
		</p>
		<p>
			<label for="">Poste recherché : </label>
			<input type="text" name="profile[]" class="name" />
			<select name="domain[]">
				<option value="actor">Acteur</option>
				<option value="technicien">Technicien</option>
			</select>
		</p>
		<a href="#" id="add-post">Ajouter un poste</a>
	</div>
	<p>
		<input type="submit" id="add-project" value="envoyer" />
	</p>
</form>