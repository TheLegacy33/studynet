<section id="content_body" class="container">
	<header class="col-12 text-center text-info">Liste des écoles</header>
	<div class="col-12 btnactions">
		<?php
			if ($user->isAdmin()){
				print('<a href="index.php?p=ecoles&a=ajoutecole" class="btn btn-secondary" title="Ajout d\'une école">Nouvelle école<span class="fa fa-plus"></span></a>');
			}
		?>
	</div>
	<div class="container mt-2">
			<?php
				$script = '';
				if (count($listeEcoles) == 0){
					$script .= 'Aucune donnée disponible !';
				}else{
					$num = 0;
					$script .= '<div class="row card-deck justify-content-center">';
					foreach ($listeEcoles as $ecole){
						$num++;
						$script .= '<div class="card bg-light mb-4 shadow-lg text-center" style="max-width: 300px">';
							$script .= '<div class="card-header text-light bg-secondary">';
								$script .= '<h4 class="my-0 font-weight-normal">'.$ecole->getNom().'</h4>';
							$script .= '</div>';
							$script .= '<div class="card-body">';
								$script .= '<img src="'.ROOTHTMLUPLOADS.$ecole->getLogo().'" class="logoecole" />';
							$script .= '</div>';
							$script .= '<div class="card-footer">';
								$script .= '<span><a href="index.php?p=promotions&a=listepromotions&idecole='.$ecole->getId().'" title="Liste des promotions" class="fa fa-list"><span class="badge badge-info align-middle">'.$ecole->getNbPromos().'</span></a></span>';
								$script .= '<span><a href="index.php?p=ecoles&a=editecole&idecole='.$ecole->getId().'" title="Modifier"class="fa fa-edit"></a></span>';
								$script .= '<span><a href="index.php?p=ecoles&a=delecole&idecole='.$ecole->getId().'" title="Supprimer" class="fa fa-trash-alt"></a></span>';
							$script .= '</div>';
						$script .= '</div>';

						if ($num%4==0){
							$script .= '</div>';
							$script .= '<div class="row card-deck justify-content-center">';
						}
					}
					$script .= '</div>';
				}
				print($script);
			?>
	</div>
</section>