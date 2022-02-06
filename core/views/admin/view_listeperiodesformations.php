<?php
	/**
	 * @var Promotion $promo
	 * @var $listeStatutPf
	 * @var StatutPeriodeFormation $statutPf
	 * @var StatutPeriodeFormation $statut
	 * @var Personne $user
	 */

	if (!is_null($promo)){
		?>
		<nav class="navinterne">
			<a href="index.php?p=promotions&a=listepromotions&idecole=<?php print($promo->getIdEcole()); ?>"
			   title="Retour à la liste des promotions"><< Retour</a>
		</nav>
		<?php
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
						print('<a href="index.php?p=promotions&idecole='.$promo->getIdEcole().'">'.Ecole::getById($promo->getIdEcole())->getNom().'</a>');
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
		<div class="card bg-light mb-4 shadow-lg text-center" style="max-width: 300px">
			<div class="card-header">Statut</div>
			<div class="card-body">

				<label for="cbactive"></label>
				<select id="cbactive" name="cbactive">
				<?php
					$script = '';
					if (isset($listeStatutPf)){
						foreach ($listeStatutPf as $statutPf){
							$selected = '';
							if ($statut->equals($statutPf)){
								$selected = ' selected';
							}
							$script .= '<option value="'.$statutPf->getId().'" '.$selected.'>'.$statutPf.'</option>';
						}
					}
					print($script);
				?>
				</select>
			</div>
		</div>
	</section>
	<header class="col-12 text-center text-info">Liste des périodes de formations</header>
	<div class="col-12 btnactions">
		<?php
			if (isset($user)){
				if ($user->isAdmin() AND isset($promo)){
					print('<a href="index.php?p=periodesformation&a=ajoutpf&idpromo='.$promo->getId().'" class="btn btn-secondary" title="Ajout d\'une période de formation">Nouvelle période de formation<span class="fa fa-plus"></span></a>');
				}
			}
		?>
	</div>
	<div class="col-12 mt-2">
		<table class="table table-bordered table-hover table-responsive-md">
			<thead class="thead-light">
			<tr>
				<?php
					if (is_null($promo)){
						print('<th style="width: 150px;" colspan="2">Formation</th>');
					}
				?>
				<th style="width: 120px;">Date Début</th>
				<th style="width: 120px;">Date Fin</th>
				<th style="width: 100px;">Effectif</th>
				<th style="width: 120px;">Nb Modules</th>
				<th style="width: 400px;">Resp. Peda.</th>
				<th  style="" colspan="6">Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$script = '';
				if (isset($listePf)){
					if (count($listePf) == 0){
						$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
					}else{
						foreach ($listePf as $pf){
							$script .= '<tr class="lignedata">';
							if (is_null($promo)){
								$script .= '<td>'.Ecole::getById(Promotion::getById($pf->getIdPromo())->getIdEcole())->getNom().'</td>';
								$script .= '<td>'.Promotion::getById($pf->getIdPromo())->getLibelle().'</td>';
							}
							$script .= '<td>'.$pf->getDateDebut().'</td>';
							$script .= '<td>'.$pf->getDateFin().'</td>';
							$script .= '<td>'.$pf->getEffectif().'</td>';
							$script .= '<td>'.$pf->getNbModules().'</td>';
							$script .= '<td>'.$pf->getResponsable().'</td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=listeetudiants&idpf='.$pf->getId().'" title="Liste des étudiants"><span class="fa fa-users align-middle"></span></a></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=listemodules&idpf='.$pf->getId().'" title="Liste des modules"><span class="fa fa-list align-middle"></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=participations&idpf='.$pf->getId().'" title="Gérer la participation des étudiants aux modules"><span class="fa fa-check-square align-middle"></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=evalenseignement&idpf='.$pf->getId().'" title="Gérer le formulaire d\'évaluation des enseignements"><span class="fa fa-certificate align-middle"></td>';
							if ($user->isAdmin()){
								$script .= '<td><a href="index.php?p=periodesformation&a=editpf&idpf='.$pf->getId().'" title="Modifier la période de formation"><span class="fa fa-edit"></td>';
								//$script .= '<td><a href="index.php?p=periodesformation&a=delpf&idpf='.$pf->getId().'" title="Supprimerla période de formation"><span class="fa fa-trash-alt"></td>';
								$script .= '<td><a style="cursor: pointer" data-name="droppf" data-id="'.$pf->getId().'" title="Supprimerla période de formation"><span class="fa fa-trash"></td>';
							}
							$script .= '</tr>';
						}
					}
				}
				print($script);
			?>
			</tbody>
		</table>
	</div>
</section>