<nav class="navinterne">
	<a href="index.php?p=periodesformation&a=listemodules&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des modules"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie">
	<div class="card card-default text-justify">
		<div class="card-header text-uppercase">Informations du module</div>
		<div class="card-body">
			<div><label for="ttLibelle">Libelle : </label><input type="text" name="ttLibelle" id="ttLibelle" value="<?php print($module->getLibelle()); ?>" /></div>
			<div><label for="cbIntervenant">Intervenant : </label>
				<select id="cbIntervenant" name="cbIntervenant">
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
								$script .= '<option value="'.$personne->getId().'"'.$selected.'>'.$personne.'</option>';
							}
						}
						print($script);
					?>
				</select>
			</div>
			<div><label for="cbUniteEnseignement">Unite d'enseignement : </label>
				<select id="cbUniteEnseignement" name="cbUniteEnseignement">
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
								$script .= '<option value="'.$uniteenseignement->getId().'"'.$selected.'>'.$uniteenseignement.'</option>';
							}
						}
						print($script);
					?>
				</select>
			</div>
			<div><label for="ttResume">Résumé : </label><textarea name="ttResume" id="ttResume"><?php print($module->getDetails()); ?></textarea></div>
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