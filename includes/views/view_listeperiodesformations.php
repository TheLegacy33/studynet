<section id="content_body" class="row">
	<header class="text-center text-info">Liste des périodes de formations</header>
	<div class="row">
		<span class="col-sm-offset-3 col-sm-3 text-center"><label style="margin-right: 5px">Ecole : </label>
			<?php
				if (is_null($promo)){
					print('Toutes');
				}else{
					print($promo->getEcole()->getNom());
				}
			?>
		</span>
		<span class="col-sm-3 text-center"><label style="margin-right: 5px">Promotion : </label>
			<?php
				if (is_null($promo)){
					print('Toutes');
				}else {
					print($promo->getLibelle());
				}
			?>
		</span>
	</div>
	<div class="row">
		<table>
			<tr>
				<?php
					if (is_null($promo)){
						print('<th style="width: 150px;" colspan="2">Formation</th>');
					}
				?>
				<th style="width: 120px;">Date Début</th>
				<th style="width: 120px;">Date Fin</th>
				<th style="width: 100px;">Effectif</th>
				<th style="width: 100px;">Nb Modules</th>
				<th style="width: 250px;">Resp. Peda.</th>
				<th  style="width: 100px;" colspan="3">Actions</th>
			</tr>
			<?php
				$script = '';
				if (count($listePf) == 0){
					$script .= '<tr><td colspan="6">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listePf as $pf){
						$script .= '<tr class="lignedata">';
						if ($promo == null){
							$script .= '<td>'.$pf->getPromo()->getEcole()->getNom().'</td>';
							$script .= '<td>'.$pf->getPromo()->getLibelle().'</td>';
						}
						$script .= '<td>'.$pf->getDateDebut().'</td>';
						$script .= '<td>'.$pf->getDateFin().'</td>';
						$script .= '<td>'.$pf->getEffectif().'</td>';
						$script .= '<td>'.$pf->getNbModules().'</td>';
						$script .= '<td>'.$pf->getResponsable().'</td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=listeetudiants&idpf='.$pf->getId().'" title="Liste des étudiants"><span class="glyphicon glyphicon-user"></span></a></td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=listemodules&idpf='.$pf->getId().'" title="Liste des modules"><span class="glyphicon glyphicon-list"></td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=participations&idpf='.$pf->getId().'" title="Gérer la participation des étudiants aux modules"><span class="glyphicon glyphicon-check"></td>';
						$script .= '</tr>';
					}
				}
				print($script);
			?>
		</table>
	</div>
</section>