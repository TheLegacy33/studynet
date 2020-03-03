<?php
	/**
	 * @var Personne $user
	 * @var $type
	 * @var $listePersonnes
	 * @var Personne $personne
	 */
?>

<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des personnes</header>
	<section id="divfiltre" class="col-12 text-center">
		<label for="cbFiltreType"></label>
		<select id="cbFiltreType">
			<?php
				$script = '<option value="*" '.(($type=='*')?' selected':'').'>Tous</option>';
				if ($user->isAdmin()){
					$script .= '<option value="administrateur" '.(($type=='administrateur')?' selected':'').'>Administrateurs</option>';
				}
				$script .= '<option value="'.Etudiant::class.'" '.(($type==Etudiant::class)?' selected':'').'>Etudiant</option>';
				$script .= '<option value="'.Intervenant::class.'" '.(($type==Intervenant::class)?' selected':'').'>Intervenant</option>';
				$script .= '<option value="'.ResponsablePedago::class.'" '.(($type==ResponsablePedago::class)?' selected':'').'>Responsables Pédagogiques</option>';
				$script .= '<option value="visiteur" '.(($type=='visiteur')?' selected':'').'>Visiteurs</option>';
				print ($script);
			?>
		</select>
	</section>
	<section class="col-12 mt-2">
		<table class="table table-bordered table-hover table-responsive-md">
			<thead class="thead-light">
			<tr>
				<th style="width: 200px;">Nom</th>
				<th style="width: 150px;">Prénom</th>
				<th style="width: 200px;">Profils</th>
				<th style="width: 200px;">Auth ?</th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			</thead>
			<tbody>
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
							$hasAuth = 'Oui ('.$personne->getUserAuth()->getLogin().')&nbsp;<a href="#" style="cursor: pointer;" data-name="renewpassword" data-id="'.$personne->getPersId().'" title="Renouveler le mot de passe"><span class="fas fa-sync-alt"></span></a>';
							$hasAuth .= '&nbsp;<a href="#" style="cursor: pointer;" data-name="dropuserauth" data-id="'.$personne->getPersId().'" title="Supprimer les informations d\'authentification"><span class="fa fa-trash-alt"></span></a>';
						}else{
							$hasAuth = 'Non';
						}

						$script .= '<td>'.$hasAuth.'</td>';

						$script .= '<td style="width: 30px;"><a href="index.php?p=personnes&a=editprofile&idpersonne='.$personne->getPersId().'" title="Editer le profil"><span class="fa fa-edit"></span></a></td>';
						if ($user->isAdmin()) {
							$script .= '<td style="width: 30px;">';
							if (trim($personne->getEmail()) != ''){
								$script .= '<a href="#" style="cursor: pointer;" data-name="sendmail" data-id="'.$personne->getPersId().'" title="Envoyer les informations du profil"><span class="fas fa-at"></span></a></td>';
							}else{
								$script .= '<span class="fa fa-at"></span>';
							}
							$script .= '</td>';

							if (($personne->getNomComplet() != $user->getNomComplet()) AND $personne->getNom() != "VISITEUR" AND !$personne->isAdmin()){
								$script .= '<td style="width: 30px;"><a data-name="dropuser" data-id="'.$personne->getPersId().'" href="index.php?p=personnes&a=delete&idpersonne='.$personne->getPersId().'" title="Supprimer"><span class="fa fa-trash-alt"></span></a></td>';
							}else{
								$script .= '<td style="width: 30px;"></td>';
							}
						}
						$script .= '</tr>';
					}
				}
				print($script);
			?>
			</tbody>
		</table>
	</section>
</section>
