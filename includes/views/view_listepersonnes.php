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
					$script .= '<tr class="lignedata">';
					$script .= '<td style="width: 150px;">'.$personne->getNom().'</td>';
					$script .= '<td style="width: 150px;">'.$personne->getPrenom().'</td>';
					$script .= '<td style="width: 200px;">'.$personne->get_class().'</td>';

                    if ($personne->getUserAuth()->exists()){
                        $hasAuth = 'Oui ('.$personne->getUserAuth()->getLogin().')';
                    }else{
                        $hasAuth = 'Non';
                    }

					$script .= '<td style="width: 150px;">'.$hasAuth.'</td>';

					$script .= '<td style="width: 60px;"><a href="index.php?p=personnes&a=editprofile&idpersonne='.$personne->getPersId().'" title="Editer le profil"><span class="glyphicon glyphicon-edit"></span></a></td>';
					if ($user->isAdmin()) {
						$script .= '<td style="width: 60px;">';
						if (trim($personne->getEmail()) != ''){
							$script .= '<a style="cursor: pointer;" data-name="sendmail" data-id="'.$personne->getPersId().'" title="Envoyer les informations du profil"><span class="glyphicon glyphicon-envelope"></span></a></td>';
						}else{
							$script .= '<span class="glyphicon glyphicon-envelope"></span>';
						}
						$script .= '</td>';

						if (($personne->getNomComplet() != $user->getNomComplet()) AND $personne->getNom() != "VISITEUR" AND !$personne->isAdmin()){
                        	$script .= '<td style="width: 60px;"><a href="index.php?p=personnes&a=delete&idpersonne='.$personne->getPersId().'" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a></td>';
						}
                    }
					$script .= '</tr>';
				}
			}
			print($script);
		?>
	</table>
</section>
