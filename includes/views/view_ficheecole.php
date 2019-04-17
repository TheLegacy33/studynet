<nav class="navinterne">
	<a href="index.php?p=ecoles&a=listeecoles" title="Retour à la liste des écoles"><< Retour</a>
</nav>
<section id="content_body" class="container">
	<form action="" method="post" id="frmSaisie" enctype="multipart/form-data">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Informations de l'école</div>
			<div class="card-body">
				<div class="form-group">
					<label for="ttNom">Nom : </label>
					<input type="text" class="form-control" aria-describedby="helpNom" name="ttNom" id="ttNom" value="<?php print($ecole->getNom()); ?>" />
					<small id="helpNom" class="form-text text-muted">Nom de l'école</small>
				</div>
			</div>
			<div class="card-body mt-1">
				<label for="ttFichier">Logo :</label><input type="file" name="ttFichier" id="ttFichier" />
				<p class="text-muted">La taille maximum autorisée est de <?php print(number_format(getMaximumFileUploadSize(), 0, ',', ' ')); ?> octets</p>
			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-3">Enregistrer<span class="fa fa-save"></span></button>
				<button type="reset" class="btn btn-secondary">Annuler<span class="fa fa-ban"></span></button>
			</div>
		</div>
	</form>
</section>