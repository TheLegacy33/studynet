<nav class="navinterne">
	<a href="index.php?p=periodesformation&a=listeetudiants&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des étudiants"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie">
	<div class="card card-default text-justify">
		<div class="card-header text-uppercase">Informations de l'étudiant</div>
		<div class="card-body">
			<div><label for="ttNom">Nom : </label><input type="text" name="ttNom" id="ttNom" value="<?php print($etudiant->getNom()); ?>" /></div>
			<div><label for="ttPrenom">Prénom : </label><input type="text" name="ttPrenom" id="ttPrenom" value="<?php print($etudiant->getPrenom()); ?>" /></div>
			<div><label for="ttEmail">Email : </label><input type="email" name="ttEmail" id="ttEmail" value="<?php print($etudiant->getEmail()); ?>" /></div>
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