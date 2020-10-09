<?php
$action = isset($_GET['a'])?$_GET['a']:'listemodules';
include_once ROOTMODELS . 'model_periodeformation.php';
//include_once ROOTMODELS.'model_evaluation.php';

$idetudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
$idpf = isset($_GET['idpf'])?$_GET['idpf']:0;
$pf = Periodeformation::getById($idpf);
if ($action == 'listemodules'){
	if ($idetudiant == 0){
		//Affichage de la liste des modules de la pf
		$includeJs = true;
		$scriptname[] = 'js_listemodules.js';

		$listeUnitesEnseignement = $pf->getUnitesenseignement();
		$listeModules = $pf->getModules();
		include_once ROOTVIEWS . 'view_listemodulespf.php';
	}elseif ($idetudiant != 0 AND $idpf != 0){
		//Affichage de la liste des modules suivis par un étudiant
		$etudiant = Etudiant::getById($idetudiant);

		$listeModules = Module::getListeFromEtudiant($idetudiant, $idpf);
		include_once ROOTVIEWS . 'view_listemodulesetudiants.php';
	}
}elseif ($action == 'ajoutmodule'){
	$includeJs = true;
	$scriptname = ['js_module.js', 'js_formscripts.js'];

	$module = new Module();
	$module->setDuree(20);
	$module->setUniteEnseignement(UniteEnseignement::getEmptyUE());
	$listeUnitesEnseignement = $pf->getUnitesenseignement();

	$listePersonnes = Personne::getListe(array('I', 'R'));

	if (!empty($_POST)){
		$libModule = $_POST['ttLibelle'];
		$codeModule = $_POST['ttCode'];
		$detailsModule = $_POST['ttResume'];
		$dureeModule = intval($_POST['ttDuree']);
		$uniteenseignement = (isset($_POST['cbUniteEnseignement']) AND $_POST['cbUniteEnseignement'] != '0')?UniteEnseignement::getById($_POST['cbUniteEnseignement']):new UniteEnseignement();

		//Je rajoute la personne en tant qu'intervenant
		$personne = (isset($_POST['cbIntervenant']) AND $_POST['cbIntervenant'] != '0')?Personne::getById($_POST['cbIntervenant']):null;
		if (is_null($personne)){
			$intervenant = new Intervenant();
		}else{
			if (!Intervenant::exists($personne->getId())){
				Intervenant::insert($personne);
			}
			$intervenant = Intervenant::getById(Intervenant::getIdByIdPers($personne->getId()));
		}

		$newModule = new Module(0, $libModule, $detailsModule, $dureeModule, $uniteenseignement, $codeModule);
		$newModule->setIntervenant($intervenant);

		if (Module::insert($newModule, $pf)){
			header('Location: index.php?p=periodesformation&a=listemodules&idpf='.$idpf);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS . 'view_fichemodule.php';
}elseif ($action == 'editmodule'){
	$includeJs = true;
	$scriptname = ['js_module.js', 'js_formscripts.js'];

	$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
	$module = Module::getById($idModule);
	$module->setIntervenant(Intervenant::getByPfAndMod($idPf, $idModule));
	$listePersonnes = Personne::getListe('!E');
	$listeUnitesEnseignement = $pf->getUnitesenseignement();

	if (!empty($_POST)){
		$personne = (isset($_POST['cbIntervenant']) AND $_POST['cbIntervenant'] != '0')?Personne::getById($_POST['cbIntervenant']):null;
		if (is_null($personne)){
			$newintervenant = new Intervenant();
		}else{
			if (!Intervenant::exists($personne->getId())){
				Intervenant::insert($personne);
			}
			$newintervenant = Intervenant::getById(Intervenant::getIdByIdPers($personne->getId()));
		}
		$oldintervenant = $module->getIntervenant();

		$module->setIntervenant($newintervenant);

		$module->setLibelle(trim($_POST['ttLibelle']));
		$module->setCode(trim($_POST['ttCode']));
		$module->setDetails(trim($_POST['ttResume']));
		$module->setDuree(intval($_POST['ttDuree']));

		$uniteenseignement = (isset($_POST['cbUniteEnseignement']) AND $_POST['cbUniteEnseignement'] != '0')?UniteEnseignement::getById($_POST['cbUniteEnseignement']):UniteEnseignement::getEmptyUE();
		$module->setUniteEnseignement($uniteenseignement);

		if (Module::update($module, $pf)){
			header('Location: index.php?p=periodesformation&a=listemodules&idpf='.$idpf);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS . 'view_fichemodule.php';
}elseif ($action == 'editcontenumodule'){
	$includeJs = true;
	$scriptname = ['js_contenumodule.js', 'js_formscripts.js'];
	$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
	$idCModule = isset($_GET['idcmodule'])?$_GET['idcmodule']:0;
	$module = Module::getById($idModule);
	$module->setIntervenant(Intervenant::getByPfAndMod($idPf, $idModule));

	if (!isset($_REQUEST['idcmodule'])){
		include_once ROOTVIEWS . 'view_listecontenumodule.php';
	}else{
		$contenuModule = ContenuModule::getById($idCModule);
		if (!empty($_POST)){
			$contenuModule->setLibelle($_POST['ttLibelle']);
			if (ContenuModule::update($contenuModule)){
				header('Location: index.php?p=periodesformation&a=editcontenumodule&idpf='.$idpf.'&idmodule='.$idModule);
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS . 'view_fichecontenumodule.php';
	}
}elseif ($action == 'ajoutcontenumodule'){
	$includeJs = true;
	$scriptname = ['js_contenumodule.js', 'js_formscripts.js'];
	$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
	$module = Module::getById($idModule);
	$module->setIntervenant(Intervenant::getByPfAndMod($idPf, $idModule));
	$contenuModule = new ContenuModule();
	if (!empty($_POST)){
		$libCModule = $_POST['ttLibelle'];

		$newCModule = new ContenuModule(0, $libCModule, '', $idModule);

		if (ContenuModule::insert($newCModule)){
			header('Location: index.php?p=periodesformation&a=editcontenumodule&idpf='.$idpf.'&idmodule='.$idModule);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS . 'view_fichecontenumodule.php';
}elseif ($action == 'importmodules'){
	//TODO Adapter le code aux modules
	//	$checked = false;
	//	if (!empty($_FILES)){
	//		$checked = isset($_POST['chkEntete'])?(($_POST['chkEntete'][0] == 'on')?true:false):false;
	//		$fichier = $_FILES['ttFichier'];
	//		if (is_uploaded_file($fichier['tmp_name'])){
	//			$pf = Periodeformation::getById($idPf);
	//			$promo = Promotion::getByIdPf($idPf);
	//
	//			$contenuFichier = file($fichier['tmp_name'], FILE_IGNORE_NEW_LINES);
	//			$message = '';
	//			$nbEtudiantImportes = 0;
	//			$nbErreurs = 0;
	//			foreach ($contenuFichier as $numligne => $ligne){
	//				if ($checked AND $numligne == 0){
	//					continue;
	//				}
	//				$infosLigne = explode(';', $ligne);
	//				if (count($infosLigne) < 2 OR count($infosLigne) > 3){
	//					$message .= 'Erreur sur la ligne '.($numligne + 1).' : '.$ligne.'<br />';
	//					$nbErreurs++;
	//				}else{
	//					$etudiant = new Etudiant(0, $infosLigne[0], $infosLigne[1], (isset($infosLigne[2])?$infosLigne[2]:''));
	//					$etudiant->setPromo($promo);
	//					if (Etudiant::exists($etudiant)){
	//						$message .= "L'etudiant ".$etudiant." existe déjà !<br />";
	//						$nbErreurs++;
	//					}else{
	//						if (!Etudiant::insert(null, null)){
	//							$message .= "Erreur d'enregistrement de l'etudiant ".$etudiant;
	//							$nbErreurs++;
	//						}else{
	//							$nbEtudiantImportes++;
	//						}
	//					}
	//				}
	//			}
	//			if ($nbErreurs == 0){
	//				header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
	//			}
	//		}else{
	//			$message = "Erreur lors de l'enregistrement du fichier !<br />Veuillez essayer à nouveau ou contacter l'administrateur de l'application.";
	//		}

	//	}
	$urlRetour = '<a href="'.ROOTHTML.'/index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf.'" title="Retour à la liste des étudiants"><< Retour</a>';
	$formatAttendu = 'Le format attendu est un fichier CSV dont les valeurs sont séparées par des point-virgules avec une ligne par étudiant.<br />';
	$formatAttendu .= 'Exemple : <code><i>nom</i>;<i>prenom</i>;<i>email</i></code>';
	$formatAttendu .= '<p class="text-danger">Attention à respecter l\'ordre et le format demandé !<br />';
	$formatAttendu .= 'L\'email n\'étant pas obligatoire, il faut tout de même laisser le point-virgule après le prénom !</p>';
	include_once ROOTVIEWS . 'view_formimport.php';
}else{
	header('Location: '.ROOTHTML);
}
?>