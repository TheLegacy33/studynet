<section id="content_body" class="row">
	<header class="text-center text-info">Liste des écoles</header>
	<table>
		<tr>
			<th>Nom</th>
			<th>Logo</th>
			<th colspan="3">Actions</th>
		</tr>
		<?php
			$script = '';
			if (count($listeEcoles) == 0){
				$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
			}else{
				foreach ($listeEcoles as $ecole){
					$script .= '<tr>';
					$script .= '<td style="width: 200px;">'.$ecole->getNom().'</td>';
					$script .= '<td style="width: 300px;"><img src="'.ROOTHTMLUPLOADS.$ecole->getLogo().'" class="logotableau" /></td>';
					$script .= '<td style="width: 100px;"><a href="index.php?p=promotions&a=listepromotions&idecole='.$ecole->getId().'" title="Liste des promotions"><span class="glyphicon glyphicon-list"></span></a><span class="badge">'.$ecole->getNbPromos().'</span></td>';
					$script .= '<td style="width: 100px;"><a href="index.php?p=ecoles&a=edit&id='.$ecole->getId().'" title="Modifier"><span class="glyphicon glyphicon-edit"></span></a></td>';
					$script .= '<td style="width: 100px;"><a href="index.php?p=ecoles&a=del&id='.$ecole->getId().'" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a></td>';
					$script .= '</tr>';
				}
			}
			print($script);
		?>
	</table>
</section>
