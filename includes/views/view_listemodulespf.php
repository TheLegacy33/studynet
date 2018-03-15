<section id="content_body" class="row">
	<header class="text-center text-info header-section">
		Liste des modules
	</header>
	<?php
		if ($user->isAdmin() OR $pf->getResponsable() == $user){
			?>
			<div class="row btnactions">
				<a href="index.php?p=periodesformation&a=ajoutmodule&idpf=<?php print($pf->getId()); ?>" class="btn btn-default" title="Ajout d'un module">Nouveau module<span class="glyphicon glyphicon-plus"></span></a>
			</div>
			<?php
		}
	?>
	<div class="row">
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
						$script .= '<tr><td style="font-style: italic; color: blue; text-align: left; padding-left: 2px">'.$uniteenseignement->getLibelle().'</td></tr>';

						foreach ($uniteenseignement->getModules() as $module){
							$script .= '<tr>';
							$script .= '<td style="font-style: italic; text-align: left; padding-left: 20px">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=editmodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Editer le module"><span class="glyphicon glyphicon-edit"></span></a></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=listeevaluations&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Voir les évaluations pour ce module"><span class="glyphicon glyphicon-tasks"></span></a></td>';
							$script .= '<td><a style="cursor: pointer" data-name="dropmodule" data-id="'.$module->getId().'" title="Supprimer le module"><span class="glyphicon glyphicon-remove"></span></a></td>';
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
						$script .= '<tr><td style="font-style: italic; color: blue; text-align: left; padding-left: 2px">Sans Unité d\'enseignement</td></tr>';

						foreach ($listeModulesHorsUE as $module){
							$script .= '<tr>';
							$script .= '<td style="font-style: italic; text-align: left; padding-left: 20px">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=editmodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Editer le module"><span class="glyphicon glyphicon-edit"></span></a></td>';
							$script .= '<td><a href="index.php?p=periodesformation&a=listeevaluations&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Voir les évaluations pour ce module"><span class="glyphicon glyphicon-tasks"></span></a></td>';
							$script .= '<td><a style="cursor: pointer" data-name="dropmodule" data-id="'.$module->getId().'" title="Supprimer le module"><span class="glyphicon glyphicon-remove"></span></a></td>';
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
