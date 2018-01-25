<section id="content_body" class="row">
	<header class="text-center text-info">Vos rattrapages</header>
	<table>
		<tr>
			<th style="width: 300px;">Module</th>
			<th style="width: 250px;">Intervenant</th>
			<th style="width: 100px;">Statut</th>
			<th style="width: 100px;" colspan="2">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listeRattrapage) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeRattrapage as $rattrapage){
					$script .= '<tr class="lignedata">';
					$script .= '<td style="text-align: left">'.$rattrapage->getModule()->getLibelle().'</td>';
					$script .= '<td>'.$rattrapage->getModule()->getIntervenant().'</td>';
					$script .= '<td>'.$rattrapage->getStatut()->getLibelle().'</td>';

					$widthColAction = '70px';
					if ($rattrapage->getStatut() != StatutRattrapage::getByLibelle('En cours')){
                        $script .= '<td style="width: '.$widthColAction.'"></td>';
                        $script .= '<td style="width: '.$widthColAction.'"></td>';
                    }else{
                        if ($rattrapage->getStatut() == StatutRattrapage::getByLibelle('En cours') AND !$rattrapage->uploaded()) {
                            $script .= '<td style="width: '.$widthColAction.';" title="' . ($rattrapage->downloaded() ? $rattrapage->getDateRecup() : '') . '"><a data="lnkdld" href="index.php?p=rattrapages&a=getsujet&idrattrapage=' . $rattrapage->getId() . '" title="Télécharger le sujet"><span class="glyphicon glyphicon-download"></span></a></td>';
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
	</table>
</section>