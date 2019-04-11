<nav class="navinterne">
	<a href="index.php?p=periodesformation&a=listepf&idpromo=<?php print($promo->getId()); ?>" title="Retour à la liste des périodes de formation"><< Retour</a>
</nav>
<section id="content_body" class="container">
	<form action="" method="post" id="frmSaisie">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Informations de la période de formation</div>
			<div class="card-body">
				<div class="form-group">
					<label for="ttDateDebut">Date début :</label><input type="date" class="form-control" name="ttDateDebut" id="ttDateDebut" value="<?php print(date_fr_to_mysql($pf->getDateDebut())); ?>"/>
				</div>
				<div class="form-group">
					<label for="ttDateFin">Date fin :</label><input type="date" class="form-control" name="ttDateFin" id="ttDateFin" value="<?php print(date_fr_to_mysql($pf->getDateFin())); ?>"/>
				</div>
				<div class="form-group">
					<label for="ttDuree">Durée :</label><input type="number" min="0" step="10" pattern="\d+" class="form-control" name="ttDuree" id="ttDuree" value="<?php print($pf->getDuree()); ?>"/>
				</div>
				<div class="form-group">
					<label for="cbResponsable">Référent pédagogique :</label>
					<select class="form-control" name="cbResponsable" id="cbResponsable">
						<?php
							$script = '<option value="0"> --- </option>';
							if (isset($listePersonnes)){
								foreach ($listePersonnes as $personne){
									$selected = '';
									if (!is_null($pf->getResponsable())){
										if ($personne->equals(Personne::getById($pf->getResponsable()->getPersId()))){
											$selected = ' selected';
										}
									}
									$script .= '<option value="'.$personne->getId().'"'.$selected.'>'.$personne.'</option>';
								}
							}
							print($script);
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="cbStatut">Statut :</label>
					<select class="form-control" name="cbStatut" id="cbStatut">
						<?php
							$script = '';
							if (isset($listeStatutPf)){
								foreach ($listeStatutPf as $statutPf){
									$selected = '';
									if ($statutPf->equals($pf->getStatut())){
										$selected = ' selected';
									}
									$script .= '<option value="'.$statutPf->getId().'"'.$selected.'>'.$statutPf.'</option>';
								}
							}
							print($script);
						?>
					</select>
				</div>


			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-3">Enregistrer<span class="fa fa-save"></span></button>
				<button type="reset" class="btn btn-secondary">Annuler<span class="fa fa-ban"></span></button>
			</div>
		</div>
	</form>
</section>