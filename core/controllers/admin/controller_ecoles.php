<?php
	$action = $_GET['a'] ?? 'listeecoles';
	include_once ROOTMODELS.'model_ecole.php';

	if ($action == 'listeecoles'){
		$listeEcoles = Ecole::getListe();

		include_once ROOTVIEWS . 'view_listeecoles.php';

	}elseif ($action == 'ajoutecole'){
		$includeJs = true;
		$scriptname = ['js_ecole.js', 'js_formscripts.js'];

		$ecole = new Ecole();
		if (!empty($_POST)){
			$nomEcole = $_POST['ttNom'];
			$logoEcole = '';

			if (!empty($_FILES)){
				$fichier = $_FILES['ttFichier'];
				if (is_uploaded_file($fichier['tmp_name'])){
					$extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
					$logoEcole = 'logos_ecoles/'.trim(strtolower($nomEcole).'.'.$extension);
					move_uploaded_file($fichier['tmp_name'], ROOTUPLOADS.$logoEcole);
				}
			}
			$newEcole = new Ecole(0, $nomEcole, $logoEcole);
			if (Ecole::insert($newEcole)){
				header('Location: index.php?p=ecoles&a=listeecoles');
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS . 'view_ficheecole.php';

	}elseif ($action == 'editecole'){
		$includeJs = true;
		$scriptname = ['js_ecole.js', 'js_formscripts.js'];

		$idEcole = $_GET['idecole'] ?? 0;
		$ecole = Ecole::getById($idEcole);
		if (!empty($_POST)){
			$ecole->setNom(trim($_POST['ttNom']));
			$logoEcole = $ecole->getLogo();
			if (!empty($_FILES)){
				$fichier = $_FILES['ttFichier'];
				if (is_uploaded_file($fichier['tmp_name'])){
					$extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
					$logoEcole = 'logos_ecoles/'.trim(strtolower($ecole->getNom()).'.'.$extension);
					move_uploaded_file($fichier['tmp_name'], ROOTUPLOADS.$logoEcole);
				}
			}
			$ecole->setLogo($logoEcole);
			if (Ecole::update($ecole)){
				header('Location: index.php?p=ecoles&a=listeecoles');
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS . 'view_ficheecole.php';
	}else{
		header('Location: '.ROOTHTML);
	}
