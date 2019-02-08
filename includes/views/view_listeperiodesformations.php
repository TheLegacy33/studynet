<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des périodes de formations</header>
	<div class="col-12">
		<span class="col-4 text-center"><label style="margin-right: 5px">Ecole : </label>
			<?php
				if (is_null($promo)){
					print('Toutes');
				}else{
					print($promo->getEcole()->getNom());
				}
			?>
		</span>
		<span class="col-4 text-center"><label style="margin-right: 5px">Promotion : </label>
			<?php
				if (is_null($promo)){
					print('Toutes');
				}else {
					print($promo->getLibelle());
				}
			?>
		</span>
		<?php
			if ($action == 'listepf'){
				?>
				<span class="col-4 text-center"><label style="margin-right: 5px">Statut : </label>
					<select name="cbactive">
					<?php
						$script = '';
						foreach ($listeStatutPf as $statutPf){
							$selected = '';
							if ($active == $statutPf->getId()){
								$selected = ' selected';
							}
							$script .= '<option value="'.$statutPf->getId().'"'.$selected.'>'.$statutPf.'</option>';
						}
						print($script);
					?>
					</select>
				</span>
				<?php
			}
		?>
	</div>
	<div class="col-12 mt-2">
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
						if (is_null($promo)){
							$script .= '<td>'.$pf->getPromo()->getEcole()->getNom().'</td>';
							$script .= '<td>'.$pf->getPromo()->getLibelle().'</td>';
						}
						$script .= '<td>'.$pf->getDateDebut().'</td>';
						$script .= '<td>'.$pf->getDateFin().'</td>';
						$script .= '<td>'.$pf->getEffectif().'</td>';
						$script .= '<td>'.$pf->getNbModules().'</td>';
						$script .= '<td>'.$pf->getResponsable().'</td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=listeetudiants&idpf='.$pf->getId().'" title="Liste des étudiants"><span class="fas fa-users align-middle"></span></a></td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=listemodules&idpf='.$pf->getId().'" title="Liste des modules"><span class="fas fa-list align-middle"></td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=participations&idpf='.$pf->getId().'" title="Gérer la participation des étudiants aux modules"><span class="fas fa-check-square align-middle"></td>';
						$script .= '</tr>';
					}
				}
				print($script);
			?>
		</table>
	</div>
</section>