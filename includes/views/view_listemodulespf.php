<?php

?>
<section id="content_body" class="row">
	<header class="text-center text-info" style="font-size: 20px">Liste des modules pour la période de formation
		du <?php print($pf->getDateDebut()); ?> au <?php print($pf->getDateDebut()); ?>
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
				<th>Libellé</th>
				<th colspan="3">Actions</th>
			</tr>
			<?php
				$script = '';
				if (count($listeModules) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($listeModules as $module){
						$script .= '<tr>';
						$script .= '<td style="width: 400px; font-weight: bold; font-style: italic">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
						$script .= '<td style="width: 30px;"><a href="index.php?p=periodesformation&a=editmodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'" title="Editer le module"><span class="glyphicon glyphicon-edit"></span></a></td>';
						$script .= '<td style="width: 30px;"><a style="cursor: pointer" data-name="dropmodule" data-id="'.$module->getId().'" title="Supprimer le module"><span class="glyphicon glyphicon-remove"></span></a></td>';
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
				print($script);
			?>
		</table>
	</div>
</section>
