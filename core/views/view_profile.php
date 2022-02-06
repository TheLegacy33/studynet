<?php
	/**
	 * @var Personne $user
	 */
?>
<section class="container">
	<form action="" method="post" id="frmProfile" enctype="multipart/form-data">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Profil utilisateur</div>
			<div class="card-body">
				<div class="card text-justify">
					<div class="card-header text-uppercase">Informations de connexion</div>
					<div class="card-body">
						<div class="form-group">
							<label for="ttLogin">Identifiant</label>
							<input type="text" class="form-control" aria-describedby="helpLogin" name="ttLogin" id="ttLogin" value="<?php print($user->getUserAuth()->getLogin()); ?>" />
							<small id="helpLogin" class="form-text text-muted">Identifiant de connexion</small>
						</div>
						<div class="form-group">
							<label for="ttPassword">Mot de passe</label>
							<input type="password" class="form-control" aria-describedby="helpPassword" name="ttPassword" id="ttPassword" value="<?php print($user->getUserAuth()->getPassword()); ?>" />
							<small id="helpPassword" class="form-text text-muted">Mot de passe de connexion</small>
						</div>
						<div class="form-group">
							<label for="ttPasswordVerif">Vérification</label>
							<input type="password" class="form-control" aria-describedby="helpVerifPassword" id="ttPasswordVerif" />
							<small id="helpVerifPassword" class="form-text text-muted">Vérification du mot de passe</small>
						</div>
					</div>
				</div>

				<div class="card text-justify mt-3">
					<div class="card-header text-uppercase">Informations personnelles</div>
					<div class="card-body">
						<div class="form-group">
							<label for="ttNom">Nom : </label>
							<input type="text" class="form-control" aria-describedby="helpNom" name="ttNom" id="ttNom" value="<?php print($user->getNom()); ?>" />
							<small id="helpNom" class="form-text text-muted">Votre nom</small>
						</div>
						<div class="form-group">
							<label for="ttPrenom">Prénom : </label>
							<input type="text" class="form-control" aria-describedby="helpPrenom" name="ttPrenom" id="ttPrenom" value="<?php print($user->getPrenom()); ?>" />
							<small id="helpPrenom" class="form-text text-muted">Votre prénom</small>
						</div>
						<div class="form-group">
							<label for="ttEmail">Email : </label>
							<input type="email" class="form-control" aria-describedby="helpEmail" name="ttEmail" id="ttEmail" value="<?php print($user->getEmail()); ?>" />
							<small id="helpEmail" class="form-text text-muted">Votre email</small>
						</div>
						<?php
							if ($user->estEtudiant()){
								$etudiant = Etudiant::getById(Etudiant::getIdByIdPers($user->getPersId()));
								print('<div class="form-group"><label for="ttPhoto">Photo :</label><input class="form-control" type="file" name="ttPhoto" id="ttPhoto" value="'.$etudiant->getPhoto().'" /></div>');
							}
						?>
					</div>
				</div>
			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-3">Enregistrer<span class="fa fa-save"></span></button>
				<button type="reset" class="btn btn-secondary">Annuler<span class="fa fa-ban"></span></button>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		var loginOrigine = "<?php print($user->getUserAuth()->getLogin()); ?>";
		var passOrigine = "<?php print($user->getUserAuth()->getPassword()); ?>";
	</script>
</section>
