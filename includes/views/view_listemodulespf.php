<section id="content_body" class="container">
	<header class="col-12 text-center text-info header-section">
		Liste des modules
	</header>
	<?php
		if ($user->isAdmin() OR $pf->getResponsable() == $user){
			?>
			<div class="col-12 btnactions">
				<a href="index.php?p=periodesformation&a=ajoutmodule&idpf=<?php print($pf->getId()); ?>" class="btn btn-secondary" title="Ajout d'un module">Nouveau module<span class="fa fa-plus"></span></a>
			</div>
			<?php
		}
	?>
	<div class="col-12">
		<table>
			<tr>
				<th style="width: 600px;"></th>
				<th style="width: 100px;" colspan="3">Actions</th>
			</tr>
			<?php
				$script = '';
				if (count($listeUnitesEnseignement) == 0 AND count($listeModulesHorsUE) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listeUnitesEnseignement as $uniteenseignement){
						$script .= '<tr><td style="font-style: italic; color: blue; text-align: left; padding-left: 2px">'.$uniteenseignement->getLibelle().'</td>';
						$script .= '<td style="background-color: gray" colspan="3"></td></tr>';

						foreach ($uniteenseignement->getModules() as $module){
							$script .= '<tr>';
							$script .= '<td style="font-style: italic; text-align: left; padding-left: 20px">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=editmodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Editer le module"><span class="fa fa-edit"></span></a></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=listeevaluations&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Voir les évaluations notées pour ce module"><span class="fa fa-tasks"></span></a></td>';
							$script .= '<td><a style="cursor: pointer" data-name="dropmodule" data-id="'.$module->getId().'" title="Supprimer le module"><span class="fa fa-trash"></span></a></td>';
							$script .= '</tr>';
							if ($module->hasContenu()){
								$script .= '<tr>';
								$script .= '<td>';
								$script .= '<table style="width: 98%; border: none; margin: 5px auto">';
								foreach ($module->getContenu() as $contenuModule){
									$script .= '<tr class="lignetab">';
									$script .= '<td style="border: none; text-align: left">'.$contenuModule->getLibelle().'</td>';
									$script .= '</tr>';
								}
								$script .= '</table>';
								$script .= '</td>';
								$script .= '</tr>';
							}
						}
					}


					if (count($listeModulesHorsUE) != 0){
						$script .= '<tr><td style="font-style: italic; color: blue; text-align: left; padding-left: 2px">Sans Unité d\'enseignement</td>';
                        $script .= '<td style="background-color: gray" colspan="3"></td></tr>';

						foreach ($listeModulesHorsUE as $module){
							$script .= '<tr>';
							$script .= '<td style="font-style: italic; text-align: left; padding-left: 20px">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=editmodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Editer le module"><span class="fa fa-edit"></span></a></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=listeevaluations&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Voir les évaluations notées pour ce module"><span class="fa fa-tasks"></span></a></td>';
							$script .= '<td><a style="cursor: pointer" data-name="dropmodule" data-id="'.$module->getId().'" title="Supprimer le module"><span class="fa fa-trash"></span></a></td>';
							$script .= '</tr>';
							if ($module->hasContenu()){
								$script .= '<tr>';
								$script .= '<td>';
								$script .= '<table style="width: 98%; border: none; margin: 5px auto">';
								foreach ($module->getContenu() as $contenuModule){
									$script .= '<tr class="lignetab">';
									$script .= '<td style="border: none; text-align: left">'.$contenuModule->getLibelle().'</td>';
									$script .= '</tr>';
								}
								$script .= '</table>';
								$script .= '</td>';
								$script .= '</tr>';
							}
						}
					}
				}
				print($script);
			?>
		</table>
	</div>
</section>
