<?php
$action = isset($_GET['a'])?$_GET['a']:'listemodules';
include_once ROOTMODELS.'model_periodeformation.php';
//include_once ROOTMODELS.'model_evaluation.php';

$idetudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
$idpf = isset($_GET['idpf'])?$_GET['idpf']:0;
if ($action == 'listemodules'){
	if ($idetudiant == 0){
		//Affichage de la liste des modules de la pf
		$includeJs = true;
		$scriptname[] = 'js_listemodules.js';

		$listeUnitesEnseignement = UniteEnseignement::getListeFromPromo($promo->getId(), false);
		$listeModulesHorsUE = Module::getListeFromPf($idPf, false);

		include_once ROOTVIEWS.'view_listemodulespf.php';
	}elseif ($idetudiant != 0 AND $idpf != 0){
		//Affichage de la liste des modules suivis par un étudiant
		$etudiant = Etudiant::getById($idetudiant);
		$pf = Periodeformation::getById($idpf, false);

		$listeModules = Module::getListeFromEtudiant($idetudiant);
		include_once ROOTVIEWS.'view_listemodules.php';
	}
}elseif ($action == 'ajoutmodule'){
	$includeJs = true;
	$scriptname[] = 'js_module.js';
	$pf = Periodeformation::getById($idPf);
	$module = new Module();
	$listeIntervenants = Intervenant::getListe(Intervenant::class);
    $listeUnitesEnseignement = $promo->getUnitesEnseignement();
	if (!empty($_POST)){
		$libModule = $_POST['ttLibelle'];
		$detailsModule = $_POST['ttResume'];
		$dureeModule = 0;
		$chrono = Module::getNextChrono($idpf);
		$intervenant = (isset($_POST['cbIntervenant']) AND $_POST['cbIntervenant'] != '0')?Intervenant::getById($_POST['cbIntervenant']):new Intervenant();

		$newModule = new Module(0, $libModule, $detailsModule, $intervenant, $idpf, $dureeModule, $chrono);
		if (Module::insert($newModule)){
			header('Location: index.php?p=periodesformation&a=listemodules&idpf='.$idpf);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS.'view_fichemodule.php';
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
}elseif ($action == 'importmodules'){
	//TODO Adapter le code aux modules
	$checked = false;
	if (!empty($_FILES)){
		$checked = isset($_POST['chkEntete'])?(($_POST['chkEntete'][0] == 'on')?true:false):false;
		$fichier = $_FILES['ttFichier'];
		if (is_uploaded_file($fichier['tmp_name'])){
			$pf = Periodeformation::getById($idPf);
			$promo = Promotion::getByIdPf($idPf);

			$contenuFichier = file($fichier['tmp_name'], FILE_IGNORE_NEW_LINES);
			$message = '';
			$nbEtudiantImportes = 0;
			$nbErreurs = 0;
			foreach ($contenuFichier as $numligne => $ligne){
				if ($checked AND $numligne == 0){
					continue;
				}
				$infosLigne = explode(';', $ligne);
				if (count($infosLigne) < 2 OR count($infosLigne) > 3){
					$message .= 'Erreur sur la ligne '.($numligne + 1).' : '.$ligne.'<br />';
					$nbErreurs++;
				}else{
					$etudiant = new Etudiant(0, $infosLigne[0], $infosLigne[1], (isset($infosLigne[2])?$infosLigne[2]:''));
					$etudiant->setPromo($promo);
					if (Etudiant::exists($etudiant)){
						$message .= "L'etudiant ".$etudiant." existe déjà !<br />";
						$nbErreurs++;
					}else{
						if (!Etudiant::insert(null, null)){
							$message .= "Erreur d'enregistrement de l'etudiant ".$etudiant;
							$nbErreurs++;
						}else{
							$nbEtudiantImportes++;
						}
					}
				}
			}
			if ($nbErreurs == 0){
				header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
			}
		}else{
			$message = "Erreur lors de l'enregistrement du fichier !<br />Veuillez essayer à nouveau ou contacter l'administrateur de l'application.";
		}

	}
	$urlRetour = '<a href="index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf.'" title="Retour à la liste des étudiants"><< Retour</a>';
	$formatAttendu = 'Le format attendu est un fichier CSV dont les valeurs sont séparées par des point-virgules avec une ligne par étudiant.<br />';
	$formatAttendu .= 'Exemple : <code><i>nom</i>;<i>prenom</i>;<i>email</i></code>';
	$formatAttendu .= '<p class="text-danger">Attention à respecter l\'ordre et le format demandé !<br />';
	$formatAttendu .= 'L\'email n\'étant pas obligatoire, il faut tout de même laisser le point-virgule après le prénom !</p>';
	include_once ROOTVIEWS.'view_formimport.php';
}else{
	header('Location: '.ROOTHTML);
}
?>