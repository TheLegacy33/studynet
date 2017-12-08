<section id="content_body" class="row">
	<header class="text-center text-info">Liste des étudiants</header>
	<table>
		<tr>
			<th>Nom</th>
			<th>Prénom</th>
			<th>Promotion</th>
			<th colspan="4">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listeEtudiants) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeEtudiants as $etudiant){
					$script .= '<tr class="lignedata">';
					$script .= '<td style="width: 200px;">'.$etudiant->getNom().'</td>';
					$script .= '<td style="width: 200px;">'.$etudiant->getPrenom().'</td>';
					$script .= '<td style="width: 200px;">'.$etudiant->getPromo()->getLibelle().'</td>';
					$script .= '<td><a href="index.php?p=etudiants&a=fiche&idetudiant='.$etudiant->getId().'" title="Informations"><span class="glyphicon glyphicon-info-sign"></span></a></td>';
					$script .= '<td><a href="index.php?p=modules&a=listemodules&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Voir les modules suivis par l\'étudiant"><span class="glyphicon glyphicon-list"></span></a></td>';
					$script .= '<td><a href="index.php?p=evaluations&a=view&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Voir le détails des évaluations de l\'étudiant"><span class="glyphicon glyphicon-tasks"></span></a></td>';
					$script .= '<td><a target="_blank" href="index.php?p=evaluations&a=print&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Générer le PDF"><span class="glyphicon glyphicon-print"></span></a></td>';
					$script .= '</tr>';
				}
			}
			print($script);
		?>
	</table>
</section>
