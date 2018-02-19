<nav class="navinterne">
    <a href="index.php?p=periodesformation&a=listeevaluations&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($evaluation->getModule()->getId()); ?>" title="Retour à la liste des étudiants"><< Retour</a>
</nav>
<section id="content_body" class="row">
	<header class="text-center text-info header-section">
		Notes des étudiants pour l'évaluation du <?php print($evaluation->getDate()); ?>
	</header>
	<div class="row">
		<div class="col-xs-12 text-center">
            <?php
            if ($user->isAdmin() OR $pf->getResponsable() == $user OR $module->getIntervenant()->equals($user)){
                if ($action == 'editnotes'){
                    ?>
                    <div class="row btnactions">
                        <button type="submit" class="btn btn-default" title="Valider les notes saisies">Valider<span class="glyphicon glyphicon-ok"></span></button>
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="row btnactions">
                        <a href="index.php?p=periodesformation&a=editnotes&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($evaluation->getModule()->getId()); ?>&idevaluation=<?php print($evaluation->getId()); ?>" class="btn btn-default" title="Modifier les notes">Modifier<span class="glyphicon glyphicon-edit"></span></a>
                    </div>
                    <?php
                }
            }
            ?>
			<table id="tbetudiants">
				<tr>
					<th style="width: 400px;">Etudiant</th>
					<th style="width: 80px;">Note</th>
				</tr>
				<?php
					$script = '';
					if (count($listeEtudiants) == 0){
						$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
					}else{
						foreach ($listeEtudiants as $etudiant){
							$script .= '<tr class="lignedata" data-id="'.$etudiant->getId().'">';
							$script .= '<td class="text-left">'.$etudiant->getNomComplet().'</td>';
							if ($action == 'editnotes'){
                                $script .= '<td class="text-center"><input class="fieldnote" name="ttnotes" data-ideval="'.$evaluation->getId().'" data_idstudent="'.$etudiant->getId().'" type="text" value="'.number_format($evaluation->getStudentNote($etudiant->getId()), 2).'" /></td>';
                            }else{
                                $script .= '<td class="text-center">'.number_format($evaluation->getStudentNote($etudiant->getId()), 2).'</td>';
                            }
							$script .= '</tr>';
						}
					}
					print($script);
				?>
			</table>
		</div>
	</div>
	<script type="text/javascript">

	</script>
</section>
