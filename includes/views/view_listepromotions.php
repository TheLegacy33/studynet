<?php
	/**
	 * @var Personne $user
	 * @var Ecole $ecole
	 * @var $listePromos
	 * @var Promotion $promo
	 */
?>
<nav class="navinterne">
	<a href="index.php?p=ecoles&a=listeecoles" title="Retour à la liste des écoles"><< Retour</a>
</nav>
<section id="content_body" class="container">
	<header class="col-12 text-center text-info">Liste des promotions <?php if (isset($ecole)) { print(' pour '.$ecole->getNom()); } ?></header>
	<div class="col-12 btnactions">
		<?php
			if (isset($user)){
				if ($user->isAdmin() AND isset($ecole)){
					print('<a href="index.php?p=promotions&a=ajoutpromo&idecole='.$ecole->getId().'" class="btn btn-secondary" title="Ajout d\'une promotion">Nouvelle promotion<span class="fa fa-plus"></span></a>');
				}
			}
		?>
	</div>
	<div class="container mt-2">
		<?php
			$script = '';
			if (isset($listePromos)){
				if (count($listePromos) == 0){
					$script .= 'Aucune promotion disponible !';
				}else{
					$num = 0;
					$script .= '<div class="row card-deck justify-content-center">';
					foreach ($listePromos as $promo){
						$num++;
						$script .= '<div class="card mb-4 bg-light shadow-lg text-center" style="max-width: 300px">';
						$script .= '<div class="card-header text-light bg-secondary">';
						$script .= '<h4 class="my-0 font-weight-normal">'.(isset($ecole)?$ecole->getNom():Ecole::getById($promo->getIdEcole())->getNom()).'</h4>';
						$script .= '</div>';
						$script .= '<div class="card-body">';
						$script .= $promo->getLibelle();
						$script .= '</div>';
						$script .= '<div class="card-footer">';
						$script .= '<span><a href="index.php?p=periodesformation&a=listepf&idpromo='.$promo->getId().'" title="Liste des périodes de formation" class="fa fa-list"></a></span>';
						$script .= '<span><a href="index.php?p=promotions&a=editpromo&idpromo='.$promo->getId().'" title="Modifier" class="fa fa-edit"></a></span>';
						$script .= '<span><a href="index.php?p=promotions&a=deletepromo&idpromo='.$promo->getId().'" title="Supprimer" class="fa fa-trash-alt"></a></span>';
						$script .= '</div>';
						$script .= '</div>';

						if ($num%5==0){
							$script .= '</div>';
							$script .= '<div class="row card-deck justify-content-center">';
						}
					}
					$script .= '</div>';
				}
			}
			print($script);
		?>
	</div>
</section>
