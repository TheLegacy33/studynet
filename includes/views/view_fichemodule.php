<nav class="navinterne">
	<a href="index.php?p=periodesformation&a=listemodules&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des modules"><< Retour</a>
</nav>
<form action="" method="post" id="frmSaisie">
	<div class="panel panel-default text-justify">
		<div class="panel-heading">Informations du module</div>
		<div class="panel-body">
			<div><label for="ttLibelle">Libelle : </label><input type="text" name="ttLibelle" id="ttLibelle" value="<?php print($module->getLibelle()); ?>" /></div>
			<div><label for="cbIntervenant">Intervenant : </label>
				<select id="cbIntervenant" name="cbIntervenant">
					<?php
						$script = '<option value="0"> --- </option>';
						foreach ($listeIntervenants as $intervenant){
							$selected = '';
							if (!is_null($module->getIntervenant())){
								if ($intervenant->getId() == $module->getIntervenant()->getId()){
									$selected = ' selected';
								}
							}
							$script .= '<option value="'.$intervenant->getId().'"'.$selected.'>'.$intervenant.'</option>';
						}
						print($script);
					?>
				</select>
			</div>
			<div><label for="cbUniteEnseignement">Unite d'enseignement : </label>
				<select id="cbUniteEnseignement" name="cbUniteEnseignement">
					<?php
						$script = '<option value="0"> --- </option>';
						foreach ($listeUnitesEnseignement as $uniteenseignement){
							$selected = '';
							if (!is_null($module->getIdUniteEnseignement())){
								if ($uniteenseignement->getId() == $module->getIdUniteEnseignement()){
									$selected = ' selected';
								}
							}
							$script .= '<option value="'.$uniteenseignement->getId().'"'.$selected.'>'.$uniteenseignement.'</option>';
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
		<div class="panel-footer formbtn">
			<button type="submit" class="btn btn-success">Enregistrer<span class="glyphicon glyphicon-floppy-save"></span></button>
			<button type="reset" class="btn btn-default">Annuler<span class="glyphicon glyphicon-floppy-remove"></span></button>
		</div>
	</div>
</form>