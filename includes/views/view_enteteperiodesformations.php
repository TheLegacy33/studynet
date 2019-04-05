<?php
if (!is_null($promo)){
	?>
	<nav class="navinterne">
		<a href="index.php?p=periodesformation&a=listepf&idpromo=<?php print($promo->getId()); ?>"
		   title="Retour à la liste des périodes de formation"><< Retour</a>
	</nav>
	<?php
}
?>
<section class="container content_body">
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
		<?php
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
		?>
	</section>
</section>