<?php
	/**
	 * @var $listeUnitesEnseignement
	 * @var Module $module
	 * @var $listeModules
	 * @var UniteEnseignement $uniteenseignement
	 * @var ContenuModule $contenuModule
	 * @var Periodeformation $pf
	 * @var Personne $user
	 */
?>
<section id="content_body" class="container">
	<header class="col-12 text-center text-info header-section">
		Liste des modules
	</header>
	<?php
		if (isset($user)){
			if ($user->isAdmin() OR $pf->getResponsable() == $user){
				?>
				<div class="col-12 btnactions">
					<a href="<?php print(ROOTHTML); ?>/index.php?p=periodesformation&a=ajoutmodule&idpf=<?php print($pf->getId()); ?>" class="btn btn-secondary" title="Ajout d'un module">Nouveau module<span class="fa fa-plus"></span></a>
				</div>
				<?php
			}
		}
	?>
	<div class="col-12">
		<table class="table table-bordered table-hover table-responsive-md">
			<thead class="thead-light">
			<tr>
				<th style="width: 600px;"></th>
				<th style="width: 100px;" colspan="4">Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$script = '';

				if (isset($listeUnitesEnseignement)){
					if (count($listeUnitesEnseignement) == 0){
						$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
					}else{
						foreach ($listeUnitesEnseignement as $uniteenseignement){
							$script .= '<tr><td style="font-style: italic; color: blue; text-align: left; padding-left: 2px">'.$uniteenseignement->getLibelle().'</td>';
							$script .= '<td style="background-color: gray" colspan="4"></td></tr>';
							if (isset($listeModules)){
								foreach ($listeModules as $module){
									if ($uniteenseignement->equals($module->getUniteEnseignement())){
										$script .= '<tr>';
										$script .= '<td style="font-style: italic; text-align: left; padding-left: 20px">'.$module->getLibelle().($module->getCode() != ''?' - '.$module->getCode():'').'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
										$script .= '<td><a href="index.php?p=periodesformation&a=editmodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Editer le module"><span class="fa fa-edit"></span></a></td>';
										$script .= '<td><a href="index.php?p=periodesformation&a=editcontenumodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Gérer le contenu du module"><span class="fa fa-tasks"></span></a></td>';
										$script .= '<td><a style="cursor: pointer" data-name="dropmodule" data-id="'.$module->getId().'" title="Supprimer le module"><span class="fa fa-trash"></span></a></td>';
										//$script .= '<td><a href="index.php?p=periodesformation&a=evalenseignement&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Evaluation des enseignements du module"><span class="fa fa-certificate"></span></a></td>';
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
						}
					}
				}
				print($script);
			?>
			</tbody>
		</table>
	</div>
</section>
