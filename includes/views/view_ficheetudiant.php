<nav class="navinterne">
	<a href="index.php?p=periodesformation&a=listeetudiants&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des étudiants"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie">
	<div class="panel panel-default text-justify">
		<div class="panel-heading">Informations de l'étudiant</div>
		<div class="panel-body">
			<div><label for="ttNom">Nom : </label><input type="text" name="ttNom" id="ttNom" value="<?php print($etudiant->getNom()); ?>" /></div>
			<div><label for="ttPrenom">Prénom : </label><input type="text" name="ttPrenom" id="ttPrenom" value="<?php print($etudiant->getPrenom()); ?>" /></div>
			<div><label for="ttEmail">Email : </label><input type="email" name="ttEmail" id="ttEmail" value="<?php print($etudiant->getEmail()); ?>" /></div>
			<?php
			if (isset($message)){
				print('<p class="text-danger">'.$message.'</p>');
			}
			?>
		</div>
		<div class="panel-footer formbtn">
			<button type="submit" class="btn btn-success">Enregistrer<span class="glyphicon glyphicon-floppy-save"></span></button>
			<button type="reset" class="btn btn-default">Annuler<span class="glyphicon glyphicon-floppy-remove"></span></button>
		</div>
	</div>
</form>