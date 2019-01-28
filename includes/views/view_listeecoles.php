<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des écoles</header>

	<div class="container">

			<?php
				$script = '';
				if (count($listeEcoles) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					$num = 0;
					$script .= '<div class="card-deck text-center">';
					foreach ($listeEcoles as $ecole){
						$num++;
						$script .= '<div class="card mb-4 box-shadow">';
							$script .= '<div class="card-header">';
								$script .= '<h4 class="my-0 font-weight-normal">'.$ecole->getNom().'</h4>';
							$script .= '</div>';
							$script .= '<div class="card-body">';
								$script .= '<img src="'.ROOTHTMLUPLOADS.$ecole->getLogo().'" class="logoecole" />';
							$script .= '</div>';
							$script .= '<div class="card-footer">';
								$script .= '<a href="index.php?p=promotions&a=listepromotions&idecole='.$ecole->getId().'" title="Liste des promotions"><span class="glyphicon glyphicon-list"></span></a><span class="badge">'.$ecole->getNbPromos().'</span>';
								$script .= '<a href="index.php?p=ecoles&a=edit&id='.$ecole->getId().'" title="Modifier"><span class="glyphicon glyphicon-edit"></span></a>';
								$script .= '<a href="index.php?p=ecoles&a=del&id='.$ecole->getId().'" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a>';
							$script .= '</div>';
						$script .= '</div>';

						if ($num%3==0){
							$script .= '</div>';
							$script .= '<div class="row card-deck text-center">';
						}
					}
					$script .= '</div>';
				}
				print($script);
				?>
	</div>
</section>