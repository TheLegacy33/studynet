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
	if ($action == 'listemodules' OR $action == 'ajoutmodule' OR $action == 'editmodule' OR $action == 'importmodules'){
		include_once ROOTCTRL.'controller_modules.php';
	}elseif ($action == 'listeetudiants' OR $action == 'ajoutetudiant' OR $action == 'editetudiant' OR $action == 'importetudiants'){
		include_once ROOTCTRL.'controller_etudiants.php';
	}elseif ($action == 'listeevaluations' OR $action == 'gestnotes' OR $action == 'editnotes'){
		$idmodule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
		include_once ROOTCTRL.'controller_evaluationmodule.php';
	}elseif ($action == 'editappgenerale' OR $action == 'viewdetailsevaluations' OR $action == 'editdetailsevaluations'){
		include_once ROOTCTRL.'controller_evaluation.php';
	}elseif ($action == 'participations'){
		//Gestion de l'affectation des étudiants aux modules de la pf
		$includeJs = true;
		$scriptname[] = 'js_participations.js';
		$listeEtudiants = Etudiant::getListeFromPf($idPf);
		$listeModules = Module::getListeFromPf($idPf);
		include_once ROOTVIEWS.'view_gestparticipation.php';
	}else{
		header('Location: '.ROOTHTML);
	}
}
?>