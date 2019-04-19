<nav class="navinterne">
	<?php print('<a href="index.php?p=periodesformation&a=listemodules&idpf='.$idpf.'" title="Retour à la liste des modules"><< Retour</a>'); ?>
</nav>
<section id="content_body" class="container">
	<header class="col-12 text-center text-info header-section">
		Liste des évaluations pour le module <?php print($module->getLibelle()); ?>.
	</header>
	<?php
		if ($user->isAdmin() OR $pf->getResponsable() == $user OR $module->getIntervenant()->equals($user)){
			?>
			<div class="row btnactions">
				<a href="index.php?p=periodesformation&a=ajoutevaluation&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($module->getId()); ?>" class="btn btn-default" title="Ajout d'une évaluation">Nouvelle évaluation<span class="glyphicon glyphicon-plus"></span></a>
				<a href="index.php?p=periodesformation&a=viewnotes&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($module->getId()); ?>" class="btn btn-default" title="Voir les notes">Voir les notes<span class="glyphicon glyphicon-plus"></span></a>
			</div>
			<?php
		}
	?>
	<div class="row">
		<table class="table table-bordered table-hover table-responsive-md">
			<thead class="thead-light">
			<tr>
				<th style="width: 120px;">Date</th>
				<th style="width: 120px;">Durée (min)</th>
				<th style="width: 300px;">Sujet</th>
				<th style="width: 150px;">Nb. Documents</th>
				<th style="width: 100px;">Type</th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$script = '';
				if (count($listeEvaluations) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listeEvaluations as $evaluation){
						$script .= '<tr class="lignedata">';
						$script .= '<td>'.$evaluation->getDate().'</td>';
						$script .= '<td>'.$evaluation->getDuree().'</td>';
						$script .= '<td class="text-left">'.$evaluation->getSujet().'</td>';
						$script .= '<td>'.count($evaluation->getDocuments()).'<a href="index.php?p=periodesformation&a=listedocuments&idpf='.$pf->getId().'&idevaluation='.$evaluation->getId().'" title="Voir les documents pour cette évaluation"><span class="glyphicon glyphicon-book" style="margin-left:10px;"></span></a></td>';
						$script .= '<td>'.$evaluation->getType()->getLibelle().'</td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=editevaluation&idpf='.$pf->getId().'&idevaluation='.$evaluation->getId().'" title="Editer l\'évaluation"><span class="glyphicon glyphicon-edit"></span></a></td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=gestnotes&idpf='.$pf->getId().'&idevaluation='.$evaluation->getId().'&idmodule='.$module->getId().'" title="Voir les notes pour cette évaluation"><span class="glyphicon glyphicon-tags"></span></a></td>';
						$script .= '<td><a style="cursor: pointer" data-name="dropevaluation" data-id="'.$evaluation->getId().'" title="Supprimer l\'evaluation"><span class="glyphicon glyphicon-remove"></span></a></td>';
						$script .= '</tr>';
					}
				}
				print($script);
			?>
			</tbody>
		</table>
	</div>
</section>
