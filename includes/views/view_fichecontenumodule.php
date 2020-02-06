<?php
	/**
	 * @var Module $module
	 * @var ContenuModule $contenuModule
	 * @var Periodeformation $pf
	 */
?>
<nav class="navinterne">
	<a href="<?php print(ROOTHTML); ?>/index.php?p=periodesformation&a=editcontenumodule&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($module->getId()); ?>" title="Retour au contenu du module"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie">
	<div class="card card-default text-justify">
		<div class="card-header text-uppercase">Informations du contenu</div>
		<div class="card-body">
			<div class="form-group">
				<label for="ttLibelle">Libelle : </label>
				<input type="text" class="form-control" aria-describedby="helpLibelle" name="ttLibelle" id="ttLibelle" value="<?php print($contenuModule->getLibelle()); ?>" />
				<small id="helpLibelle" class="form-text text-muted">Libellé de contenu</small>
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