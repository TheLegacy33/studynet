<section id="content_body" class="row formaffiche">
	<header class="text-center text-info" style="font-size: 20px">Liste des modules suivis par
		<?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
		du <?php print($pf->getDateDebut()); ?> au <?php print($pf->getDateFin()); ?>
	</header>
	<?php
		$appreciationG = Evaluation::getAppreciationGenerale($etudiant->getId(), $pf->getId());
		if ($appreciationG == null){
			$appreciationG = 'Pas d\'appréciation générale';
		}
		$script = '';
		$script .= '<section class="row col-xs-12">';
		if ($user->canEdit('appreciation', $pf)) {
			$script .= '<label>Appreciation générale :</label><a href="index.php?p=periodesformation&a=editappgenerale&idetudiant=' . $etudiant->getId() . '&idpf=' . $pf->getId() . '" title="Modifier l\'appréciation générale"><span class="glyphicon glyphicon-edit"></span></a><br />';
		}
		$script .= '<p class="commentaire">' . $appreciationG . '</p>';
		$script .= '</section>';
		print($script);
	?>
	<table>
		<tr>
			<th style="width: 700px;">Libellé</th>
			<th style="width: 100px;" colspan="3">Action</th>
		</tr>
		<?php
			$script = '';
			if (count($listeModules) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeModules as $module){
					$script .= '<tr>';
					$script .= '<td style="font-weight: bold;">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
					$script .= '<td style="width: 30px;"><a href="index.php?p=periodesformation&a=viewdetailsevaluations&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Voir les détails"><span class="glyphicon glyphicon-tasks"></span></a></td>';
                    if ($user->canEdit('module', $pf, $module)) {
                        $script .= '<td style="width: 30px;"><a href="index.php?p=periodesformation&a=editdetailsevaluations&idetudiant=' . $etudiant->getId() . '&idpf=' . $pf->getId() . '&idmodule=' . $module->getId() . '" title="Modifier l\'évaluation"><span class="glyphicon glyphicon-pencil"></span></a></td>';
                    }
                    $script .= '</tr>';
					if ($module->hasContenu()){

						$script .= '<tr>';
						$script .= '<td colspan="3">';
						$script .= '<table style="width: 98%; border: none; margin: 5px auto">';
						foreach ($module->getContenu() as $contenuModule){
							$script .= '<tr class="lignetab">';
							$script .= '<td style="border: none; text-align: left">'.$contenuModule->getLibelle().'</td>';
							$eval = $etudiant->getEvaluationContenuModule($contenuModule->getId());
							$script .= '<td style="border: none;" class="eval_'.$eval.'">'.$eval.'</td>';
							$script .= '</tr>';
						}
						$script .= '</table>';
						$script .= '</td>';
						$script .= '</tr>';
					}
				}
			}
			print($script);
		?>
	</table>
</section>
