<?php
$action = isset($_GET['a'])?$_GET['a']:'listemodules';
include_once ROOTMODELS.'model_periodeformation.php';
//include_once ROOTMODELS.'model_evaluation.php';

$idetudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
$idpf = isset($_GET['idpf'])?$_GET['idpf']:0;
if ($action == 'listemodules'){
	if ($idetudiant != 0 AND $idpf != 0){
		//Affichage de la liste des modules suivis par un étudiant
		$etudiant = Etudiant::getById($idetudiant);
		$pf = Periodeformation::getById($idpf);
		$listeModules = Module::getListeFromPf($idpf);
		include_once ROOTVIEWS.'view_listemodules.php';
	}
}elseif ($action == 'ajoutmodule'){
	$includeJs = true;
	$scriptname[] = 'js_module.js';
	$pf = Periodeformation::getById($idPf);
	$module = new Module();
	$listeIntervenants = Intervenant::getListe(Intervenant::class);
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
	if (!empty($_POST)){
		$module->setLibelle(trim($_POST['ttLibelle']));
		$module->setDetails(trim($_POST['ttResume']));
		$module->setDuree(0);
		$module->setChrono($module->getChrono());
		$intervenant = (isset($_POST['cbIntervenant']) AND $_POST['cbIntervenant'] != '0')?Intervenant::getById($_POST['cbIntervenant']):new Intervenant();
		$module->setIntervenant($intervenant);

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