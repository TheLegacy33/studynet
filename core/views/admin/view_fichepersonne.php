<nav class="navinterne">
	<a href="index.php?p=personnes&a=listepersonnes" title="Retour à la liste des modules"><< Retour</a>
</nav>
<section class="container">
	<form action="" method="post" id="frmProfile" enctype="multipart/form-data">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Profil utilisateur</div>
			<div class="card-body">
				<div class="card text-justify">
					<div class="card-header text-uppercase">Informations personnelles</div>
					<div class="card-body">
						<div>
							<label for="ttNom">Nom : </label>
							<input type="text" class="form-control" aria-describedby="helpNom" name="ttNom" id="ttNom" value="<?php print($personne->getNom()); ?>" />
							<small id="helpNom" class="form-text text-muted">Nom de la personne</small>
						</div>
						<div>
							<label for="ttPrenom">Prénom : </label>
							<input type="text" class="form-control" aria-describedby="helpPrenom" name="ttPrenom" id="ttPrenom" value="<?php print($personne->getPrenom()); ?>" />
							<small id="helpPrenom" class="form-text text-muted">Prénom de la personne</small>
						</div>
						<div>
							<label for="ttEmail">Email : </label>
							<input type="email" class="form-control" aria-describedby="helpEmail" name="ttEmail" id="ttEmail" value="<?php print($personne->getEmail()); ?>" />
							<small id="helpEmail" class="form-text text-muted">Email de la personne</small>
						</div>
						<?php
							if ($personne->estEtudiant()){
							print('<div class="form-group"><label for="ttPhoto">Photo :</label><input class="form-control" type="file" name="ttPhoto" id="ttPhoto" value="'.$personne->getPhoto().'" /><small id="helpNom" class="form-text text-muted">Photo de la personne</small></div>');
							}
						?>
					</div>
				</div>
				<div class="card text-justify mt-3" id="infosconn">
					<div class="card-header text-uppercase">Informations de connexion</div>
					<div class="card-body">
						<div>
							<label for="ttLogin">Login : </label>
							<input type="text" class="form-control" aria-describedby="helpLogin" name="ttLogin" id="ttLogin" value="<?php print($personne->getUserAuth()->getLogin()); ?>" />
							<small id="helpLogin" class="form-text text-muted">Identifiant de connexion de la personne</small>
						</div>
						<div>
							<label for="ttPassword">Password : </label>
							<input type="password" class="form-control" aria-describedby="helpPassword" name="ttPassword" id="ttPassword" value="<?php print($personne->getUserAuth()->getPassword()); ?>" />
							<small id="helpPassword" class="form-text text-muted">Mot de passe de la personne</small>
						</div>
						<div>
							<label for="ttPasswordVerif">Vérification : </label>
							<input type="password" class="form-control" aria-describedby="helpVerifPassword" id="ttPasswordVerif" />
							<small id="helpVerifPassword" class="form-text text-muted">Vérification du mot de passe de la personne</small>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-1">Enregistrer<span class="fa fa-save"></span></button>
				<button type="reset" class="btn btn-secondary mr-1">Annuler<span class="fa fa-ban"></span></button>
				<button type="button" class="btn btn-secondary" title="Informations de connexion">Authentification<span class="fa fa-user-lock"></span></button>
			</div>
	</form>
	<script type="text/javascript">
		var loginOrigine = "<?php print($personne->getUserAuth()->getLogin()); ?>";
		var passOrigine = "<?php print($personne->getUserAuth()->getPassword()); ?>";
	</script>
</section>