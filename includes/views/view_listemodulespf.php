<?php
?>
<section id="content_body" class="row">
	<header class="text-center text-info" style="font-size: 20px">Liste des modules pour la période de formation
		du <?php print($pf->getDateDebut()); ?> au <?php print($pf->getDateDebut()); ?>
	</header>
	<table>
		<tr>
			<th>Libellé</th>
		</tr>
		<?php
			$script = '';
			if (count($listeModules) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeModules as $module){
					$script .= '<tr>';
					$script .= '<td style="width: 400px; font-weight: bold;">'.$module->getLibelle().'<span class="intervenant">'.$module->getIntervenant().'</span></td>';
                    $script .= '</tr>';
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
			print($script);
		?>
	</table>
</section>
