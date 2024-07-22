<?php
	$action = $_GET['a'] ?? 'listepromotions';
	include_once ROOTMODELS . 'model_promotion.php';

	if ($action == 'listepromotions'){
		$idEcole = $_GET['idecole'] ?? 0;
		if ($idEcole == 0){
			$listePromos = Promotion::getListe();
		}else{
			$ecole = Ecole::getById($idEcole);
			$listePromos = $ecole->getPromotions();
		}
		include_once ROOTVIEWS.$entity.'view_listepromotions.php';
	}elseif ($action == 'ajoutpromo'){
		$includeJs = true;
		$scriptname = ['js_promotion.js', 'js_formscripts.js'];

		$promo = new Promotion();
		$idEcole = $_GET['idecole'] ?? 0;

		if (!empty($_POST)){
			$nomPromo = $_POST['ttNom'];

			$newPromo = new Promotion(0, $nomPromo, $idEcole);
			if (Promotion::insert($newPromo)){
				header('Location: index.php?p=promotions&a=listepromotions&idecole='.$idEcole);
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS.$entity.'view_fichepromo.php';

	}elseif ($action == 'editpromo'){
		$includeJs = true;
		$scriptname = ['js_promotion.js', 'js_formscripts.js'];

		$idPromo = $_GET['idpromo'] ?? 0;
		$promo = Promotion::getById($idPromo);
		$idEcole = $promo->getIdEcole();

		if (!empty($_POST)){
			$promo->setLibelle(trim($_POST['ttNom']));

			if (Promotion::update($promo)){
				header('Location: index.php?p=promotions&a=listepromotions&idecole='.$idEcole);
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS.$entity.'view_fichepromo.php';
	}else{
		header('Location: '.ROOTHTML);
	}
?>