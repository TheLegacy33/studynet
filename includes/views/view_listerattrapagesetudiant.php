<section id="content_body" class="row">
	<header class="text-center text-info">Liste de vos rattrapages</header>
	<table>
		<tr>
			<th>Module</th>
			<th>Intervenant</th>
			<th>Statut</th>
			<th colspan="2">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listeRattrapage) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeRattrapage as $rattrapage){
					$script .= '<tr class="lignedata">';
					$script .= '<td style="width: 400px;">'.$rattrapage->getModule()->getLibelle().'</td>';
					$script .= '<td style="width: 200px;">'.$rattrapage->getModule()->getIntervenant().'</td>';
					$script .= '<td style="width: 200px;">'.$rattrapage->getStatut()->getLibelle().'</td>';
					$script .= '<td><a target="_blank" href="index.php?p=rattrapage&a=getsujet&idrattrapage='.$rattrapage->getId().'" title="Télécharger le sujet"><span class="glyphicon glyphicon-download"></span></a></td>';
					$script .= '<td><a href="index.php?p=rattrapage&a=envoireponse&idrattrapage='.$rattrapage->getId().'" title="Poster votre réponse"><span class="glyphicon glyphicon-upload"></span></a></td>';
					$script .= '</tr>';
				}
			}
			print($script);
		?>
	</table>
</section>
