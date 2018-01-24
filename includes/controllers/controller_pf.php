<?php
$action = isset($_GET['a'])?$_GET['a']:'listepf';
include_once ROOTMODELS.'model_periodeformation.php';

$promo = null;
if ($action == 'listepf' && isset($_GET['idpromo'])) {
	$idPromo = $_GET['idpromo'];
	$promo = Promotion::getById($idPromo);
	$listePf = Periodeformation::getListeFromPromo($idPromo);
}elseif ($action == 'listeetudiants' OR $action == 'listemodules' OR $action == 'ajoutmodule' OR $action == 'importmodules' OR $action == 'editmodule'){
	$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
	$promo = Promotion::getByIdPf($idPf);
	$listePf = Periodeformation::getListe($idPf);
}else{
	$listePf = Periodeformation::getListe();
}

include_once ROOTVIEWS.'view_listeperiodesformations.php';

if ($action == 'listeetudiants'){
	$listeEtudiants = Etudiant::getListeFromPf($idPf);
	include_once ROOTVIEWS.'view_listeetudiants.php';
}elseif ($action == 'listemodules'){
	//Affichage de la liste des modules de la pf
	$includeJs = true;
	$scriptname[] = 'js_listemodules.js';
	$pf = Periodeformation::getById($idPf);
	$listeModules = Module::getListeFromPf($idPf);
	include_once ROOTVIEWS.'view_listemodulespf.php';
}elseif ($action == 'ajoutmodule' OR $action == 'editmodule'){
	include_once ROOTCTRL.'controller_modules.php';
}elseif ($action == 'importmodules'){
	$pf = Periodeformation::getById($idPf);
	include_once ROOTVIEWS.'view_formimportmodules.php';
}
?>