<?php
/**
 * @var $listeEtudiants
 * @var Etudiant $etudiant
 * @var Personne $user
 * @var Periodeformation $pf
 */
?>
<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des étudiants</header>
	<div class="col-12 btnactions">
		<?php
			if (isset($user)){
				if ($user->isAdmin() OR $pf->getResponsable() == $user){
					?>
						<a href="index.php?p=periodesformation&a=ajoutetudiant&idpf=<?php print($pf->getId()); ?>" class="btn btn-secondary" title="Ajout d'un étudiant">Nouvel étudiant<span class="fa fa-plus"></span></a>
						<a href="index.php?p=periodesformation&a=importetudiants&idpf=<?php print($pf->getId()); ?>" class="btn btn-secondary" title="Importer une liste d'étudiants">Importer une liste<span class="fa fa-upload"></span></a>
					<?php
				}
			}
		?>
		<a href="index.php?p=etudiants&a=exportliste&idpf=<?php print($pf->getId()); ?>" class="btn btn-secondary" title="Exporter une liste d'étudiants">Exporter une liste<span class="fa fa-download"></span></a>
	</div>
	<div class="col-12 mt-2">
		<table class="table table-bordered table-hover table-responsive-md">
			<thead class="thead-light">
			<tr>
				<th style="width: 200px;">Nom</th>
				<th style="width: 200px;">Prénom</th>
				<th style="width: 300px;">Email</th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$script = '';
				if (isset($listeEtudiants)){
					if (count($listeEtudiants) == 0){
						$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
					}else{
						foreach ($listeEtudiants as $etudiant){
							$script .= '<tr class="lignedata">';
							$script .= '<td>'.$etudiant->getNom().'</td>';
							$script .= '<td>'.$etudiant->getPrenom().'</td>';
							$script .= '<td>'.$etudiant->getEmail().'</td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=editetudiant&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Modifier les informations de l\'étudiant"><span class="fa fa-edit"></span></a></td>';
							if ($etudiant->getModulesCount($pf->getId()) > 0){
								$script .= '<td><a href="index.php?p=periodesformation&a=listemodules&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Voir les évaluations des modules suivis par l\'étudiant"><span class="fa fa-tasks"></span></a></td>';
								$script .= '<td><a target="_blank" href="index.php?p=evaluations&a=print&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Générer le PDF des évaluations"><span class="fa fa-file"></span></a></td>';
							}else{
								$script .= '<td><span class="fa fa-tasks" title="Voir les évaluations des modules suivis par l\'étudiant"></span></td>';
								$script .= '<td><span class="fa fa-file" title="Générer le PDF des évaluations"></span></td>';
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
