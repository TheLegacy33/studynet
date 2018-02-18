<section id="content_body" class="row">
	<header class="text-center text-info header-section">
		Participation des étudiant aux modules de la période de formation
	</header>
	<div class="row">
		<div class="col-xs-3 text-center">
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
							$script .= '<tr class="lignedata" data-id="'.$etudiant->getId().'"><td class="text-left">'.$etudiant->getNomComplet().'</td></tr>';
						}
					}
					print($script);
				?>
			</table>
		</div>
		<div class="col-xs-9 text-center">
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
							$script .= '<td class="text-left">'.$module->getLibelle().'</td>';
							$script .= '<td class="text-center"><input type="checkbox" name="chk_'.$module->getId().'" data-name="chkmodule" /></td>';
							$script .= '</tr>';
						}
					}
					$script .= '<tr class="lignedata"><td></td><td class="text-center">';
					$script .= '<span name="chk_all" title="Tout cocher" class="glyphicon glyphicon-ok-sign" style="margin-right: 5px; cursor: pointer"></span>';
					$script .= '<span name="chk_none" title="Tout décocher" class="glyphicon glyphicon-remove-sign" style="margin-left: 5px; cursor: pointer"></span>';
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
