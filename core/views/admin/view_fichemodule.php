<?php
	/**
	 * @var $listePersonnes
	 * @var Personne $personne
	 * @var Module $module
	 * @var ContenuModule $contenuModule
	 * @var Periodeformation $pf
	 * @var Etudiant $etudiant
	 * @var Personne $user
	 * @var UniteEnseignement $uniteenseignement
	 */
?>
<nav class="navinterne">
	<a href="<?php print(ROOTHTML); ?>/index.php?p=periodesformation&a=listemodules&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des modules"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie">
	<div class="card card-default text-justify">
		<div class="card-header text-uppercase">Informations du module</div>
		<div class="card-body">
			<div class="form-group">
				<label for="ttLibelle">Libelle : </label>
				<input type="text" class="form-control" aria-describedby="helpLibelle" name="ttLibelle" id="ttLibelle" value="<?php print($module->getLibelle()); ?>" />
				<small id="helpLibelle" class="form-text text-muted">Nom du module</small>
			</div>
			<div class="form-group">
				<label for="ttCode">Code : </label>
				<input type="text" class="form-control" aria-describedby="helpCode" name="ttCode" id="ttCode" value="<?php print($module->getCode()); ?>" />
				<small id="helpCode" class="form-text text-muted">Code du module</small>
			</div>
			<div class="form-group">
				<label for="cbIntervenant">Intervenant : </label>
				<select id="cbIntervenant" class="form-control" aria-describedby="helpIntervenant" name="cbIntervenant">
					<?php
						$script = '<option value="0"> --- </option>';
						if (isset($listePersonnes)){
							foreach ($listePersonnes as $personne){
								$selected = '';
								if (!is_null($module->getIntervenant())){
									if ($personne->equals($module->getIntervenant())){
										$selected = ' selected';
									}
								}
								$script .= '<option value="'.$personne->getPersId().'" '.$selected.'>'.$personne.'</option>';
							}
						}
						print($script);
					?>
				</select>
				<small id="helpIntervenant" class="form-text text-muted">Intervenant associé au module</small>
			</div>
			<div class="form-group">
				<label for="cbUniteEnseignement">Unite d'enseignement : </label>
				<select id="cbUniteEnseignement" class="form-control" aria-describedby="helpUnite" name="cbUniteEnseignement">
					<?php
						$script = '';
						if (isset($listeUnitesEnseignement)){
							foreach ($listeUnitesEnseignement as $uniteenseignement){
								$selected = '';
								if (!is_null($module->getUniteEnseignement())){
									if ($uniteenseignement->equals($module->getUniteEnseignement())){
										$selected = ' selected';
									}
								}
								$script .= '<option value="'.$uniteenseignement->getId().'" '.$selected.'>'.$uniteenseignement.'</option>';
							}
						}
						print($script);
					?>
				</select>
				<small id="helpUnite" class="form-text text-muted">Unité d'enseignement à laquelle est rattachée le module</small>
			</div>
			<div class="form-group">
				<label for="ttDuree">Durée :</label>
				<input type="number" min="0" step="1" pattern="\d+" class="form-control" aria-describedby="helpDuree" name="ttDuree" id="ttDuree" value="<?php print($module->getDuree()); ?>"/>
				<small id="helpDuree" class="form-text text-muted">Durée totale en heures du module</small>
			</div>
			<div class="form-group">
				<label for="ttResume">Résumé : </label>
				<textarea class="form-control" aria-describedby="helpResume" name="ttResume" id="ttResume"><?php print($module->getDetails()); ?></textarea>
				<small id="helpResume" class="form-text text-muted">Résumé du module</small>
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