<section id="content_body" class="row">
	<header class="text-center text-info">Liste des étudiants</header>
	<div class="row btnactions">
	<?php
	if ($user->isAdmin() OR $pf->getResponsable() == $user){
		?>
			<a href="index.php?p=periodesformation&a=ajoutetudiant&idpf=<?php print($pf->getId()); ?>" class="btn btn-default" title="Ajout d'un étudiant">Nouvel étudiant<span class="glyphicon glyphicon-plus"></span></a>
			<a href="index.php?p=periodesformation&a=importetudiants&idpf=<?php print($pf->getId()); ?>" class="btn btn-default" title="Importer une liste d'étudiants">Importer une liste<span class="glyphicon glyphicon-import"></span></a>
		<?php
	}
	?>
		<a href="index.php?p=etudiants&a=exportliste&idpf=<?php print($pf->getId()); ?>" class="btn btn-default" title="Exporter une liste d'étudiants">Exporter une liste<span class="glyphicon glyphicon-export"></span></a>
	</div>
	<div class="row">
		<table>
			<tr>
				<th style="width: 200px;">Nom</th>
				<th style="width: 200px;">Prénom</th>
				<th style="width: 300px;">Email</th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			<?php
				$script = '';
				if (count($listeEtudiants) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listeEtudiants as $etudiant){
						$script .= '<tr class="lignedata">';
						$script .= '<td>'.$etudiant->getNom().'</td>';
						$script .= '<td>'.$etudiant->getPrenom().'</td>';
						$script .= '<td>'.$etudiant->getEmail().'</td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=editetudiant&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Modifier les informations de l\'étudiant"><span class="glyphicon glyphicon-edit"></span></a></td>';
						if ($etudiant->getModulesCount($pf->getId()) > 0){
                            $script .= '<td><a href="index.php?p=periodesformation&a=listemodules&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Voir les évaluations des modules suivis par l\'étudiant"><span class="glyphicon glyphicon-tasks"></span></a></td>';
                            $script .= '<td><a target="_blank" href="index.php?p=evaluations&a=print&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Générer le PDF"><span class="glyphicon glyphicon-print"></span></a></td>';
                        }else{
                            $script .= '<td><span class="glyphicon glyphicon-tasks"></span></td>';
                            $script .= '<td><span class="glyphicon glyphicon-print"></span></td>';
                        }
						$script .= '</tr>';
					}
				}
				print($script);
			?>
		</table>
	</div>
</section>
