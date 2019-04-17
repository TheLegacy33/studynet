<nav class="navinterne">
	<a href="index.php?p=periodesformation&a=listeetudiants&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des étudiants"><< Retour</a>
</nav>
<section class="container">
	<form action="" method="post" id="frmSaisie">
		<div class="card card-default text-justify">
			<div class="card-header text-uppercase">Informations de l'étudiant</div>
			<div class="card-body">
				<div class="form-group">
					<label for="ttNom">Nom : </label>
					<input type="text" aria-describedby="helpNom" class="form-control" name="ttNom" id="ttNom" value="<?php print($etudiant->getNom()); ?>" />
					<small id="helpNom" class="form-text text-muted">Nom de l'étudiant</small>
				</div>
				<div class="form-group">
					<label for="ttPrenom">Prénom : </label>
					<input type="text" aria-describedby="helpPrenom" class="form-control" name="ttPrenom" id="ttPrenom" value="<?php print($etudiant->getPrenom()); ?>" />
					<small id="helpPrenom" class="form-text text-muted">Prénom de l'étudiant</small>
				</div>
				<div class="form-group">
					<label for="ttEmail">Email : </label>
					<input type="email" aria-describedby="helpEmail" class="form-control" name="ttEmail" id="ttEmail" value="<?php print($etudiant->getEmail()); ?>" />
					<small id="helpEmail" class="form-text text-muted">Adresse email de l'étudiant</small>
				</div>
				<div class="form-group">
					<label for="ttPhoto">Photo :</label>
					<input class="form-control" type="file" name="ttPhoto" id="ttPhoto" value="<?php print($etudiant->getPhoto()); ?>" />
				</div>
				<?php
				if (isset($message)){
					print('<p class="text-danger">'.$message.'</p>');
				}
				?>
			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-3">Enregistrer<span class="fa fa-save"></span></button>
				<button type="reset" class="btn btn-secondary">Annuler<span class="fa fa-ban"></span></button>
			</div>
		</div>
	</form>
</section>