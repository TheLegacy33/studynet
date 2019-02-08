<?php
?>
<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des promotions</header>
	<table>
		<tr>
			<th style="width: 200px;">Ecole</th>
			<th style="width: 200px;">Nom</th>
			<th style="width: 150px;">Effectif Total</th>
			<th style="width: 100px;">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listePromos) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listePromos as $promo){
					$script .= '<tr class="lignedata">';
					$script .= '<td>'.$promo->getEcole()->getNom().'</td>';
					$script .= '<td>'.$promo->getLibelle().'</td>';
					$script .= '<td>'.$promo->getEffectif().'</td>';
					$script .= '<td><a href="index.php?p=periodesformation&a=listepf&idpromo='.$promo->getId().'" title="Liste des sessions"><span class="fas fa-list align-middle"></td>';
				}
			}
			print($script);
		?>
	</table>
</section>
