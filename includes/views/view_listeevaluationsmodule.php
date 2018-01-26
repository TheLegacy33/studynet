<section id="content_body" class="row">
	<header class="text-center text-info header-section">
		Liste des évaluations pour le module <?php print($module->getLibelle()); ?>.
	</header>
	<?php
		if ($user->isAdmin() OR $pf->getResponsable() == $user){
			?>
			<div class="row btnactions">
				<a href="index.php?p=periodesformation&a=ajoutevaluation&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($module->getId()); ?>" class="btn btn-default" title="Ajout d'une évaluation">Nouvelle évaluation<span class="glyphicon glyphicon-plus"></span></a>
			</div>
			<?php
		}
	?>
	<div class="row">
		<table>
			<tr>
				<th style="width: 120px;">Date</th>
				<th style="width: 120px;">Durée (min)</th>
				<th style="width: 300px;">Sujet</th>
				<th style="width: 150px;">Nb. Documents</th>
				<th style="width: 100px;">Type</th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			<?php
				$script = '';
				if (count($listeEvaluations) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listeEvaluations as $evaluation){
						$script .= '<tr class="lignedata">';
						$script .= '<td>'.$evaluation->getDate().'</td>';
						$script .= '<td>'.$evaluation->getDuree().'</td>';
						$script .= '<td>'.$evaluation->getSujet().'</td>';
						$script .= '<td>'.count($evaluation->getDocuments()).'</td>';
						$script .= '<td>'.$evaluation->getType()->getLibelle().'</td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=editevaluation&idpf='.$pf->getId().'&idevaluation='.$evaluation->getId().'" title="Editer l\'évaluation"><span class="glyphicon glyphicon-edit"></span></a></td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=listedocuments&idpf='.$pf->getId().'&idevaluation='.$evaluation->getId().'" title="Voir les documents pour cette évaluation"><span class="glyphicon glyphicon-tasks"></span></a></td>';
						$script .= '<td><a style="cursor: pointer" data-name="dropevaluation" data-id="'.$evaluation->getId().'" title="Supprimer l\'evaluation"><span class="glyphicon glyphicon-remove"></span></a></td>';
						$script .= '</tr>';
					}
				}
				print($script);
			?>
		</table>
	</div>
</section>
