<section id="content_body" class="row">
	<header class="text-center text-info">Liste des personnes</header>
	<table>
		<tr>
			<th>Nom</th>
			<th>Prénom</th>
			<th>Statut</th>
            <th>Auth ?</th>
			<th colspan="3">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listePersonnes) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listePersonnes as $personne){
				    //var_dump($personne->estEtudiant());
				    if ($personne->estEtudiant()){
				        $type = 'Etudiant';
                    }elseif ($personne->estIntervenant()){
				        $type = 'Intervenant';
                    }elseif ($personne->estResponsablePedago()){
				        $type = 'Responsable Pédagogique';
                    }else {
				        $type = 'Visiteur';
                    }
					$script .= '<tr class="lignedata">';
					$script .= '<td style="width: 150px;">'.$personne->getNom().'</td>';
					$script .= '<td style="width: 150px;">'.$personne->getPrenom().'</td>';
					$script .= '<td style="width: 200px;">'.$type.'</td>';

                    if ($personne->getUserAuth()->exists()){
                        $hasAuth = 'Oui ('.$personne->getUserAuth()->getLogin().')';
                    }else{
                        $hasAuth = 'Non';
                    }

					$script .= '<td style="width: 150px;">'.$hasAuth.'</td>';

					$script .= '<td style="width: 60px;"><a href="index.php?p=users&a=editprofile&idpersonne='.$personne->getId().'" title="Editer le profil"><span class="glyphicon glyphicon-edit"></span></a></td>';
                    $script .= '<td style="width: 60px;"><a href="index.php?p=users&a=sendprofile&idpersonne='.$personne->getId().'" title="Envoyer les informations du profil"><span class="glyphicon glyphicon-envelope"></span></a></td>';
                    if ($user->isAdmin()) {
                        $script .= '<td style="width: 60px;"><a href="index.php?p=users&a=deluser&idpersonne=' . $personne->getId() . '" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a></td>';
                    }
					$script .= '</tr>';
				}
			}
			print($script);
		?>
	</table>
</section>
