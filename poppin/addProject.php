<form id="newProject" action="requests/addProject.php" method="post">
	
	<h2>Ajouter une annonce</h2>
	
	<div class="desc">
		<h3>Informations générales</h3>
		<div class="field">
			<input class="required" type="text" autocomplete="off" name="title" id="title" maxlength="60" placeholder="Titre de votre annonce" />
			<em><span data-length="60">60</span> caractères restants</em>
		</div>
		<div class="text">
			<textarea class="required" id='normal' name="desc" placeholder="Description de votre annonce..."></textarea>
		</div>
		<div id="block_place" class="clearfix">
			<div class="date field">
				<label for="">Date</label>
				<input type="text" autocomplete="off" class="required datepicker" name="date_tournage" placeholder="Date du tournage" />
			</div>
			<div class="field">
				<label for="place">Lieu</label>
				<input class="required autocomplete location" autocomplete="off" type="text" name="place" placeholder="Ville, département ou code postal" />
				<input type="hidden" name="id_place" class="id_place" />
				<input type="hidden" name="type_place" class="type_place" />
			</div>
		</div>
	</div>
	
	<div class="profiles">
		<h3>Ajouter un (des) poste(s)</h3>
		<ul>
			<li class="clearfix field">
				<input type="text" autocomplete="off" placeholder="Métier recherché" class="job required autocomplete plp" name="name[]" />
				<input type="hidden" name="id_job[]" class="idjob" />
				<input type="text" autocomplete="off" name="profile[]" class="entitled" placeholder="Intitulé du poste recherché" />
				<div class="quantity">
					<a href="#" class="less_quantity number_control">-</a>
					<input type="text" autocomplete="off" value="1" class="number" name="occurence[]"/>
					<a href="#" class="more_quantity number_control">+</a>
				</div>
				<div class="line_control">
					<a href="#" class="delete">-</a>
				</div>
			</li>
			<li class="clearfix field">
				<input type="text" autocomplete="off" placeholder="Métier recherché" class="job autocomplete plp" name="name[]" />
				<input type="hidden" name="id_job[]" class="idjob" />
				<input type="text" autocomplete="off" name="profile[]" class="entitled plp" placeholder="Intitulé du poste recherché" />
				<div>
					<div class="quantity">
						<a href="#" class="less_quantity number_control">-</a>
						<input type="text" autocomplete="off" value="1" class="number" name="occurence[]"/>
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
		<input type="submit" id="add-project" value="Publier l'annonce" onClick="_gaq.push(['_trackEvent', 'Ajouter-Annonce' 'Click', 'publier']);" />
	</div>
	<div class="message error">
		<p><span>Une erreur est survenue, veuillez réessayer</span></p>
	</div>
	
</form>