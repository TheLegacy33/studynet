<?php
	/**
	 * @var $listeEtudiants
	 * @var Personne $user
	 * @var Etudiant $etudiant
	 * @var Module $module
	 * @var Intervenant $intervenant
	 * @var EvaluationModule $evaluation
	 */
?>

<section id="content_body" class="row">
	<nav class="navinterne">
		<a href="index.php?p=periodesformation&a=listeevaluations&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($evaluation->getModule()->getId()); ?>" title="Retour à la liste des étudiants"><< Retour</a>
	</nav>
	<header class="text-center text-info header-section">
		Notes des étudiants pour l'évaluation du <?php print($evaluation->getDate()); ?>
	</header>
	<div class="row">
		<div class="col-xs-12 text-center">
            <?php
				$intervenant = $module->getIntervenant();
				if ($user->isAdmin() OR $pf->getResponsable() == $user OR $intervenant->equals($user)){
					if ($action != 'editnotes'){
						?>
						<div class="row btnactions">
							<a href="index.php?p=periodesformation&a=editnotes&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($evaluation->getModule()->getId()); ?>&idevaluation=<?php print($evaluation->getId()); ?>" class="btn btn-default" title="Modifier les notes">Modifier<span class="glyphicon glyphicon-edit"></span></a>
						</div>
						<?php
					}else{
						?>
						<div class="row btnactions" id="btAct">
							<button>Tous présents</button>
							<button>Tous absents justifiés</button>
							<button>Tous absents non justifiés</button>
							<button>Tous non rendu</button>
							<button>Tous non évalué</button>
						</div>
						<?php
					}
				}
            ?>
			<div class="col-xs-12" style="height: 500px; overflow-y: scroll; margin-bottom: 5px">
				<table id="tbetudiants">
					<tr>
						<th style="width: 350px;">Etudiant</th>
						<th style="width: 200px;">Statut<span id="helpstatut" class="glyphicon glyphicon-question-sign" style="cursor: pointer; margin-left: 10px"></span></th>
						<th style="width: 80px;">Note</th>
					</tr>
					<?php
						$script = '';
						if (count($listeEtudiants) == 0){
							$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
						}else{
							$moyenne = 0;
							$nbNotes = 0;
							$tabIndexEnd = pow(count($listeEtudiants), 2);
							$tabIndexStart = 0;
							foreach ($listeEtudiants as $etudiant){
								$tabIndexEnd++;
								$tabIndexStart++;
								$script .= '<tr class="lignedata" data-id="'.$etudiant->getId().'">';
								$script .= '<td class="text-left">'.$etudiant->getNomComplet().'</td>';
								$note = $evaluation->getStudentNote($etudiant->getId());
								$statutEtudiant = $evaluation->getStudentStatut($etudiant->getId());
								if ($action == 'editnotes'){
									$script .= '<td class="text-center">';
									//$script .= '<select tabindex="'.$tabIndexEnd.'" class="fieldstatut" name="cbstatut" data-ideval="'.$evaluation->getId().'" data-idstudent="'.$etudiant->getId().'">';
									$script .= '<select class="fieldstatut" name="cbstatut" data-ideval="'.$evaluation->getId().'" data-idstudent="'.$etudiant->getId().'">';
									foreach (StatutEvaluation::getListe() as $statut){
										if ($statut == $statutEtudiant){
											$script .= '<option value="'.$statut->getId().'" selected>'.$statut->getLibelle().'</option>';
										}else{
											$script .= '<option value="'.$statut->getId().'">'.$statut->getLibelle().'</option>';
										}
									}
									$script .= '</select>';
									$script .= '</td>';
									$script .= '<td class="text-center"><input maxlength="5" tabindex="'.$tabIndexStart.'" class="fieldnote" name="ttnotes" data-ideval="'.$evaluation->getId().'" data-idstudent="'.$etudiant->getId().'" type="text" value="'.number_format($note, 2).'" /></td>';
									//$script .= '<td class="text-center"><input class="fieldnote" name="ttnotes" data-ideval="'.$evaluation->getId().'" data-idstudent="'.$etudiant->getId().'" type="text" value="'.number_format($evaluation->getStudentNote($etudiant->getId()), 2).'" /></td>';
								}else{
									$script .= '<td class="text-center">'.$evaluation->getStudentStatut($etudiant->getId())->getLibelle().'</td>';
									$script .= '<td class="text-center">'.number_format($note, 2, ',', ' ').'</td>';
								}
								$script .= '</tr>';

								//Pour le calcul de la moyenne de l'évaluation
								if ($statutEtudiant->impactMoyenne()){
									$nbNotes++;
									$moyenne += $note;
								}
							}
							if ($action == 'gestnotes'){
								if ($nbNotes > 0){
									$moyenne /= $nbNotes;
								}else{
									$moyenne = $nbNotes;
								}
								$script .= '<tr class="lignetotal">';
								$script .= '<td colspan="2" class="text-right">Moyenne de l\'évaluation</td>';
								$script .= '<td>'.number_format($moyenne, 2, ',', ' ').'</td>';
								$script .= '</tr>';
							}
						}
						print($script);
					?>
				</table>
			</div>
			<?php
				if ($user->isAdmin() OR $pf->getResponsable() == $user OR $intervenant->equals($user)){
					if ($action == 'editnotes'){
						?>
						<div class="row btnactions">
							<a href="index.php?p=periodesformation&a=gestnotes&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($evaluation->getModule()->getId()); ?>&idevaluation=<?php print($evaluation->getId()); ?>" class="btn btn-default" title="Terminer la saisie de ces notes">Terminer<span class="glyphicon glyphicon-edit"></span></a>
						</div>
						<?php
					}
				}
			?>
		</div>
	</div>
	<script type="text/javascript">

	</script>
</section>
