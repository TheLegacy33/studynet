<section id="content_body" class="row">
	<header class="col-12 text-center text-info">Liste des écoles</header>
	<div class="col-12 btnactions">
		<?php
			if ($user->isAdmin()){
				?>
				<a href="index.php?p=ecoles&a=ajoutecole" class="btn btn-secondary" title="Ajout d'une école">Nouvelle école<span class="fa fa-plus"></span></a>
				<?php
			}
		?>
	</div>
	<div class="container mt-2">
			<?php
				$script = '';
				if (count($listeEcoles) == 0){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					$num = 0;
					$script .= '<div class="card-deck text-center">';
					foreach ($listeEcoles as $ecole){
						$num++;
						$script .= '<div class="card mb-4 shadow-lg">';
							$script .= '<div class="card-header text-light bg-secondary">';
								$script .= '<h4 class="my-0 font-weight-normal">'.$ecole->getNom().'</h4>';
							$script .= '</div>';
							$script .= '<div class="card-body">';
								$script .= '<img src="'.ROOTHTMLUPLOADS.$ecole->getLogo().'" class="logoecole" />';
							$script .= '</div>';
							$script .= '<div class="card-footer">';
								$script .= '<span><a href="index.php?p=promotions&a=listepromotions&idecole='.$ecole->getId().'" title="Liste des promotions" class="fas fa-list align-middle"><span class="badge badge-info align-middle">'.$ecole->getNbPromos().'</span></a></span>';
								$script .= '<span><a href="index.php?p=ecoles&a=edit&id='.$ecole->getId().'" title="Modifier"class="fas fa-edit align-middle"></a></span>';
								$script .= '<span><a href="index.php?p=ecoles&a=del&id='.$ecole->getId().'" title="Supprimer" class="fas fa-trash-alt alt-middle"></a></span>';
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