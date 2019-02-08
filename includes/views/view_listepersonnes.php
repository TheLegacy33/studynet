<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des personnes</header>
	<section id="divfiltre" class="text-center">
		<select id="cbFiltreType">
			<?php
			print('<option value="*"'.(($type=='*')?' selected':'').'>Tous</option>');
			if ($user->isAdmin()){
				print('<option value="administrateur"'.(($type=='administrateur')?' selected':'').'>Administrateurs</option>');
			}
			print('<option value="'.Etudiant::class.'"'.(($type==Etudiant::class)?' selected':'').'>Etudiant</option>');
			print('<option value="'.Intervenant::class.'"'.(($type==Intervenant::class)?' selected':'').'>Intervenant</option>');
			print('<option value="'.ResponsablePedago::class.'"'.(($type==ResponsablePedago::class)?' selected':'').'>Responsables Pédagogiques</option>');
			print('<option value="visiteur"'.(($type=='visiteur')?' selected':'').'>Visiteurs</option>');
			?>
		</select>
	</section>
	<section class="row">
		<table>
			<tr>
				<th style="width: 200px;">Nom</th>
				<th style="width: 150px;">Prénom</th>
				<th style="width: 200px;">Profils</th>
				<th style="width: 200px;">Auth ?</th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			<?php
				$script = '';
				if (count($listePersonnes) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listePersonnes as $personne){
						$script .= '<tr class="lignedata">';
						$script .= '<td>'.$personne->getNom().'</td>';
						$script .= '<td>'.$personne->getPrenom().'</td>';
						$script .= '<td>'.$personne->get_class().'</td>';

						if ($personne->getUserAuth()->exists()){
							$hasAuth = 'Oui ('.$personne->getUserAuth()->getLogin().')&nbsp;<a style="cursor: pointer;" data-name="renewpassword" data-id="'.$personne->getPersId().'" class="glyphicon glyphicon-refresh" title="Renouveler le mot de passe"></a>';
							$hasAuth .= '&nbsp;<a style="cursor: pointer;" data-name="dropuserauth" data-id="'.$personne->getPersId().'" class="glyphicon glyphicon-remove" title="Supprimer les informations d\'authentification"></a>';
						}else{
							$hasAuth = 'Non';
						}

						$script .= '<td>'.$hasAuth.'</td>';

						$script .= '<td style="width: 30px;"><a href="index.php?p=personnes&a=editprofile&idpersonne='.$personne->getPersId().'" title="Editer le profil"><span class="glyphicon glyphicon-edit"></span></a></td>';
						if ($user->isAdmin()) {
							$script .= '<td style="width: 30px;">';
							if (trim($personne->getEmail()) != ''){
								$script .= '<a style="cursor: pointer;" data-name="sendmail" data-id="'.$personne->getPersId().'" title="Envoyer les informations du profil"><span class="glyphicon glyphicon-envelope"></span></a></td>';
							}else{
								$script .= '<span class="glyphicon glyphicon-envelope"></span>';
							}
							$script .= '</td>';

							if (($personne->getNomComplet() != $user->getNomComplet()) AND $personne->getNom() != "VISITEUR" AND !$personne->isAdmin()){
								$script .= '<td style="width: 30px;"><a data-name="dropuser" data-id="'.$personne->getPersId().'" href="index.php?p=personnes&a=delete&idpersonne='.$personne->getPersId().'" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a></td>';
							}else{
								$script .= '<td style="width: 30px;"></td>';
							}
						}
						$script .= '</tr>';
					}
				}
				print($script);
			?>
		</table>
	</section>
</section>
