<?php
	$action = isset($_GET['a'])?$_GET['a']:'listeecoles';
	include_once ROOTMODELS.'model_ecole.php';

	if ($action == 'listeecoles'){
		$listeEcoles = Ecole::getListe();

		include_once ROOTVIEWS.'view_listeecoles.php';

	}elseif ($action == 'ajoutecole'){
		$includeJs = true;
		$scriptname[] = 'js_ecole.js';


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
				header('Location: index.php?p=ecole&a=listeecoles');
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS.'view_ficheecole.php';

	}elseif ($action == 'editmodule'){
		$includeJs = true;
		$scriptname[] = 'js_module.js';
		$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
		$module = Module::getById($idModule);
		$listeIntervenants = Intervenant::getListe(Intervenant::class);
		$listeUnitesEnseignement = $promo->getUnitesEnseignement();

		if (!empty($_POST)){
			$module->setLibelle(trim($_POST['ttLibelle']));
			$module->setDetails(trim($_POST['ttResume']));
			$module->setDuree(0);
			$module->setChrono($module->getChrono());
			$intervenant = (isset($_POST['cbIntervenant']) AND $_POST['cbIntervenant'] != '0')?Intervenant::getById($_POST['cbIntervenant']):null;
			$module->setIntervenant($intervenant);
			$iduniteenseignement = (isset($_POST['cbUniteEnseignement']) AND $_POST['cbUniteEnseignement'] != '0')?$_POST['cbUniteEnseignement']:null;
			$module->setIdUniteEnseignement($iduniteenseignement);
			if (Module::update($module)){
				header('Location: index.php?p=periodesformation&a=listemodules&idpf='.$idpf);
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS.'view_fichemodule.php';
	}else{
		header('Location: '.ROOTHTML);
	}
?>