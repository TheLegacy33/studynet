<?php
?>
<section id="content_body" class="row">
	<header class="text-center text-info">Liste des promotions</header>
	<table>
		<tr>
			<th>Ecole</th>
			<th>Nom</th>
			<th>Effectif Total</th>
			<th colspan="5">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listePromos) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listePromos as $promo){
					$script .= '<tr>';
					$script .= '<td style="width: 200px;">'.$promo->getEcole()->getNom().'</td>';
					$script .= '<td style="width: 200px;">'.$promo->getLibelle().'</td>';
					$script .= '<td>'.$promo->getEffectif().'</td>';
					$script .= '<td><a href="index.php?p=periodesformation&a=listepf&idpromo='.$promo->getId().'" title="Liste des sessions"><span class="glyphicon glyphicon-list"></td>';
				}
			}
			print($script);
		?>
	</table>
</section>
