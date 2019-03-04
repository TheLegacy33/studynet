<nav class="navinterne">
	<a href="index.php?p=ecole&a=listeecoles" title="Retour à la liste des écoles"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie" enctype="multipart/form-data">
	<div class="panel panel-default text-justify">
		<div class="panel-heading">Informations de l'école</div>
		<div class="panel-body">
			<div><label for="ttNom">Nom : </label><input type="text" name="ttNom" id="ttNom" value="<?php print($ecole->getNom()); ?>" /></div>

		</div>
		<div class="panel-footer mt-3">
			<label for="ttFichier">Logo :</label><input type="file" name="ttFichier" id="ttFichier" />
			<p class="text-muted">La taille maximum autorisée est de <?php print(number_format(getMaximumFileUploadSize(), 0, ',', ' ')); ?> octets</p>
		</div>
		<div class="formbtn">
			<button type="submit" class="btn btn-success">Enregistrer<span class="fa fa-save"></span></button>
			<button type="reset" class="btn btn-secondary">Annuler<span class="fa fa-ban"></span></button>
		</div>
	</div>
</form>