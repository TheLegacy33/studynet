<?php
?>
<nav class="navinterne">
    <?php print('<a href="index.php?p=periodesformation&a=listeetudiants&idpf='.$idpf.'" title="Retour à la liste des modules"><< Retour</a>'); ?>
</nav>
<section id="content_body" class="row">
	<header class="text-center text-info" style="font-size: 20px">Liste des modules suivis par
		<?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
		du <?php print($pf->getDateDebut()); ?> au <?php print($pf->getDateDebut()); ?>
	</header>
	<table>
		<tr>
			<th>Libellé</th>
			<th colspan="3">Action</th>
		</tr>
		<?php
			$script = '';
			if (count($listeModules) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeModules as $module){
					$script .= '<tr>';
					$script .= '<td style="width: 400px; font-weight: bold;">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
					$script .= '<td style="width: 30px;"><a href="index.php?p=evaluations&a=view&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Voir l\'évaluation"><span class="glyphicon glyphicon-tasks"></span></a></td>';
                    if ($canEdit) {
                        $script .= '<td style="width: 30px;"><a href="index.php?p=evaluations&a=edit&idetudiant=' . $etudiant->getId() . '&idpf=' . $pf->getId() . '&idmodule=' . $module->getId() . '" title="Modifier l\'évaluations"><span class="glyphicon glyphicon-pencil"></span></a></td>';
                    }
                    $script .= '</tr>';
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
			print($script);
		?>
	</table>
</section>
