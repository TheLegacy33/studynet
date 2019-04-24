<?php
	/**
	 * @var $listeRattrapages
	 * @var Rattrapage $rattrapage
	 * @var Module $module
	 * @var StatutRattrapage $statutRattrapage
	 */
?>
<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Vos rattrapages</header>
	<table class="table table-bordered table-hover table-responsive-md">
		<thead class="thead-light">
		<tr>
			<th style="width: 300px;">Module</th>
			<th style="width: 250px;">Intervenant</th>
			<th style="width: 100px;">Statut</th>
			<th style="width: 100px;" colspan="2">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
			$script = '';
			if (count($listeRattrapages) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeRattrapages as $rattrapage){

					$module = $rattrapage->getModule();
					$statutRattrapage = $rattrapage->getStatut();

					$script .= '<tr class="lignedata">';
					$script .= '<td style="text-align: left">'.$module->getLibelle().'</td>';
					$script .= '<td>'.$module->getIntervenant().'</td>';
					$script .= '<td>'.$statutRattrapage->getLibelle().'</td>';

					$widthColAction = '70px';
					if ($statutRattrapage !== StatutRattrapage::getByLibelle('En cours')){
                        $script .= '<td style="width: '.$widthColAction.'"></td>';
                        $script .= '<td style="width: '.$widthColAction.'"></td>';
                    }else{
                        if ($statutRattrapage === StatutRattrapage::getByLibelle('En cours') AND !$rattrapage->uploaded()) {
                            $script .= '<td style="width: '.$widthColAction.';" title="' . ($rattrapage->downloaded() ? $rattrapage->getDateRecup() : '') . '"><a data-id="lnkdld" href="index.php?p=rattrapages&a=getsujet&idrattrapage='.$rattrapage->getId() . '" title="Télécharger le sujet"><span class="glyphicon glyphicon-download"></span></a></td>';
                        }else{
                            $script .= '<td style="width: '.$widthColAction.'"></td>';
                        }
                        if ($rattrapage->downloaded()){
                            if (!$rattrapage->uploaded()){
                                $script .= '<td style="width: '.$widthColAction.'"><a href="index.php?p=rattrapages&a=postreponse&idrattrapage='.$rattrapage->getId().'" title="Poster votre réponse"><span class="glyphicon glyphicon-upload"></span></a></td>';
                            }else{
                                $script .= '<td style="width: '.$widthColAction.'">Transmis</td>';
                            }
                        }else{
                            $script .= '<td style="width: '.$widthColAction.'"></td>';
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