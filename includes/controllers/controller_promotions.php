<?php
	$action = isset($_GET['a'])?$_GET['a']:'listepromotions';
	include_once ROOTMODELS.'model_promotion.php';

	if ($action == 'listepromotions'){
		$idEcole = isset($_GET['idecole'])?$_GET['idecole']:0;
		if ($idEcole == 0){
			$listePromos = Promotion::getListe();
		}else{
			$ecole = Ecole::getById($idEcole);
			$listePromos = $ecole->getPromos();
		}
		include_once ROOTVIEWS.'view_listepromotions.php';
	}elseif ($action == 'ajoutpromo'){
		$includeJs = true;
		$scriptname[] = 'js_promotion.js';

		$promo = new Promotion();
		$idEcole = isset($_GET['idecole'])?$_GET['idecole']:0;
		$ecole = Ecole::getById($idEcole);

		if (!empty($_POST)){
			$nomPromo = $_POST['ttNom'];

			$newPromo = new Promotion(0, $nomPromo, $ecole);
			if (Promotion::insert($newPromo)){
				header('Location: index.php?p=promotions&a=listepromotions&idecole='.$ecole->getId());
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS.'view_fichepromo.php';

	}elseif ($action == 'editpromo'){
		$includeJs = true;
		$scriptname[] = 'js_promotion.js';

		$idPromo = isset($_GET['idpromo'])?$_GET['idpromo']:0;
		$promo = Promotion::getById($idPromo);
		$ecole = $promo->getEcole();

		if (!empty($_POST)){
			$promo->setLibelle(trim($_POST['ttNom']));

			if (Promotion::update($promo)){
				header('Location: index.php?p=promotions&a=listepromotions&idecole='.$ecole->getId());
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS.'view_fichepromo.php';
	}else{
		header('Location: '.ROOTHTML);
	}
?>