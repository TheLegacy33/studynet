<?php
$action = isset($_GET['a'])?$_GET['a']:'listepf';
include_once ROOTMODELS.'model_periodeformation.php';

$promo = null;
if ($action == 'listepf' && isset($_GET['idpromo'])) {
	$idPromo = $_GET['idpromo'];
	$promo = Promotion::getById($idPromo);
	$listePf = Periodeformation::getListeFromPromo($idPromo);
}else{
	$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
	if ($idPf == 0){
		$promo = null;
	}else{
		$promo = Promotion::getByIdPf($idPf);
	}
	$listePf = Periodeformation::getListe($idPf);
}

include_once ROOTVIEWS.'view_listeperiodesformations.php';

if (isset($idPf) AND $idPf != 0){
	$pf = Periodeformation::getById($idPf);
	if ($action == 'listeetudiants'){
		include_once ROOTCTRL.'controller_etudiants.php';
	}elseif ($action == 'listemodules'){
		//Affichage de la liste des modules de la pf
		$includeJs = true;
		$scriptname[] = 'js_listemodules.js';
		$listeModules = Module::getListeFromPf($idPf);
		include_once ROOTVIEWS.'view_listemodulespf.php';
	}elseif ($action == 'ajoutmodule' OR $action == 'editmodule'){
		include_once ROOTCTRL.'controller_modules.php';
	}elseif ($action == 'importmodules'){
		include_once ROOTVIEWS.'view_formimportmodules.php';
	}elseif ($action == 'ajoutetudiant' OR $action == 'editetudiant' OR $action == 'importetudiants'){
		include_once ROOTCTRL.'controller_etudiants.php';
	}
}
?>