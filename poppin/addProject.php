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
			<div class="select">
				<label for="">Date</label>
				<div class="selector">
					<div>
						<span class="value" id="date_filter_selected">Dés que possible</span>
						<span class="button">Modifier</span>
					</div>
					<ul>
						<li>Dès que possible</li>
						<li>Cette semaine</li>
						<li>Ce mois-ci</li>
						<li>Ce trimestre</li>
					</ul>
					<input type="hidden" name="date_filter" value="">
				</div>
			</div>
			<div class="field">
				<label for="">Lieu</label>
				<input type="text" placeholder="Ville, département ou code postal" />
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
</form>