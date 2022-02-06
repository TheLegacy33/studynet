<?php
	/**
	 * @var Promotion $promo
	 */
?>
<nav class="navinterne">
	<a href="index.php?p=promotions&a=listepromotions&idecole=<?php print((isset($idEcole)) ? $idEcole : 0); ?>" title="Retour à la liste des promotions"><< Retour</a>
</nav>
<section id="content_body" class="container">
	<form action="" method="post" id="frmSaisie" enctype="multipart/form-data">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Informations de la promotion</div>
			<div class="card-body">
				<div class="form-group">
					<label for="ttNom">Nom : </label>
					<input class="form-control" type="text" aria-describedby="helpIdNom" name="ttNom" id="ttNom" value="<?php print($promo->getLibelle()); ?>" />
					<small id="helpIdNom" class="form-text text-muted">Le nom de la promotion</small>
				</div>
			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-3">Enregistrer<span class="fa fa-save"></span></button>
				<button type="reset" class="btn btn-secondary">Annuler<span class="fa fa-ban"></span></button>
			</div>
		</div>
	</form>
</section>