<?php
if (!is_null($promo)){
	if ($action == 'listepf'){
		?>
		<nav class="navinterne">
			<a href="index.php?p=promotions&a=listepromotions&idecole=<?php print($promo->getEcole()->getId()); ?>"
			   title="Retour à la liste des promotions"><< Retour</a>
		</nav>
		<?php
	}else{
		?>
		<nav class="navinterne">
			<a href="index.php?p=periodesformation&a=listepf&idpromo=<?php print($promo->getId()); ?>"
			   title="Retour à la liste des périodes de formation"><< Retour</a>
		</nav>
		<?php
	}
}
?>
<section id="content_body" class="container">
	<section class="row card-deck justify-content-center">
		<div class="card bg-light mb-4 shadow-lg text-center" style="max-width: 300px">
			<div class="card-header">Ecole</div>
			<div class="card-body">
				<?php
					if (is_null($promo)){
						print('<a href="index.php?p=ecoles" class="card-link">Toutes</a>');
					}else{
						print('<a href="index.php?p=promotions&idecole='.$promo->getEcole()->getId().'">'.$promo->getEcole()->getNom().'</a>');
					}
				?>
			</div>
		</div>
		<div class="card bg-light mb-4 shadow-lg text-center" style="max-width: 300px">
			<div class="card-header">Promotion</div>
			<div class="card-body">
				<?php
					if (is_null($promo)){
						print('<a href="index.php?p=promotions" class="card-link">Toutes</a>');
					}else {
						if ($action != 'listepf'){
							print('<a href="index.php?p=periodesformation&a=listepf&idpromo='.$promo->getId().'">'.$promo->getLibelle().'</a>');
						}else{
							print($promo->getLibelle());
						}
					}
				?>
			</div>
		</div>
		<?php
			if ($action == 'listepf'){
				?>
				<div class="card bg-light mb-4 shadow-lg text-center" style="max-width: 300px">
					<div class="card-header">Statut</div>
					<div class="card-body">
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
					</div>
				</div>
				<?php
			}else{
				if (!is_null($pf)){
				?>
				<div class="card bg-light mb-4 shadow-lg text-center" style="max-width: 300px">
					<div class="card-header">Session</div>
					<div class="card-body">
						<p class="card-text">Du <?php print($pf->getDateDebut()); ?><br />Au <?php print($pf->getDateFin()); ?></p>
					</div>
					<div class="card-footer">
						<span><a href="index.php?p=periodesformation&a=listeetudiants&idpf=<?=$pf->getId()?>" title="Liste des étudiants"><span class="fas fa-users align-middle"></span></a></span>
						<span><a href="index.php?p=periodesformation&a=listemodules&idpf=<?=$pf->getId()?>" title="Liste des modules"><span class="fas fa-list align-middle"></span></a></span>
						<span><a href="index.php?p=periodesformation&a=participations&idpf=<?=$pf->getId()?>" title="Gérer la participation des étudiants aux modules"><span class="fas fa-check-square align-middle"></span></a></span>
					</div>
				</div>
				<?php
				}
			}
		?>
	</section>
	<?php
	if ($action == 'listepf'){
		?>
			<header class="col-12 text-center text-info">Liste des périodes de formations</header>
			<div class="col-12 btnactions">
				<?php
					if ($user->isAdmin() AND isset($promo)){
						print('<a href="index.php?p=periodesformation&a=ajoutpf&idpromo='.$promo->getId().'" class="btn btn-secondary" title="Ajout d\'une période de formation">Nouvelle période de formation<span class="fa fa-plus"></span></a>');
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
		<?php
	}
	?>
</section>