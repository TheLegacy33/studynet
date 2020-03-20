<?php
	/**
	 * @var $listeEtudiants
	 * @var $listeModules
	 * @var Etudiant $etudiant
	 * @var Module $module
	 */
?>
<section id="content_body" class="row">
	<header class="col-12 text-center text-info header-section">
		Participation des étudiant aux modules de la période de formation
	</header>
	<div class="row">
		<div class="col-3 text-center" style="height: 500px; overflow-y: scroll">
			Liste des étudiants
			<table id="tbetudiants">
				<tr>
					<th style="width: 600px;">Etudiant</th>
				</tr>
				<?php
					$script = '';
					if (count($listeEtudiants) == 0){
						$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
					}else{
						foreach ($listeEtudiants as $etudiant){
							$script .= '<tr class="lignedata" data-id="'.$etudiant->getId().'"><td class="text-left" style="cursor: pointer">'.$etudiant->getNomComplet().'</td></tr>';
						}
					}
					print($script);
				?>
			</table>
		</div>
		<div class="col-9 text-center">
			Liste des modules
			<table id="tbmodules">
				<tr>
					<th style="width: 600px;">Module</th>
					<th style="width: 100px;">Action</th>
				</tr>
				<?php
					$script = '';
					if (count($listeModules) == 0){
						$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
					}else{
						foreach ($listeModules as $module){
							$script .= '<tr class="lignedata">';
							$script .= '<td class="text-left" style="cursor: pointer">'.($module->getCode() != ''?$module->getCode().' - ':'').$module->getLibelle().'</td>';
							$script .= '<td class="text-center" style="cursor: pointer"><input type="checkbox" name="chk_'.$module->getId().'" data-name="chkmodule" /></td>';
							$script .= '</tr>';
						}
					}
					$script .= '<tr class="lignedata"><td></td><td class="text-center">';
					$script .= '<span data-id="chk_all" title="Tout cocher" class="fa fa-check" style="margin-right: 5px; cursor: pointer"></span>';
					$script .= '<span data-id="chk_none" title="Tout décocher" class="fa fa-ban" style="margin-left: 5px; cursor: pointer"></span>';
					$script .= '</td></tr>';
					print($script);
				?>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		var idPf = <?php print($idPf); ?>;
	</script>
</section>
