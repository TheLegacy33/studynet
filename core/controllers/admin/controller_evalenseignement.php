<?php

	var_dump('Evaluation des enseignements');
	die();

$action = isset($_GET['a'])?$_GET['a']:'view';
include_once ROOTMODELS . 'model_evaluationmodule.php';
include_once ROOTMODELS . 'model_etudiant.php';
include_once ROOTMODELS . 'model_periodeformation.php';
include_once ROOTMODELS . 'model_module.php';

$idetudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
$idpf = isset($_GET['idpf'])?$_GET['idpf']:0;
$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
$idEvaluation = isset($_GET['idevaluation'])?$_GET['idevaluation']:0;

if ($idetudiant != 0){
	$etudiant = Etudiant::getById($idetudiant);
}
if ($idpf != 0) {
	$pf = Periodeformation::getById($idpf);
}
if ($idModule != 0) {
	$module = Module::getById($idModule);
	$module->setIntervenant(Intervenant::getByPfAndMod($idpf, $idModule));
}
if ($idEvaluation != 0) {
	$evaluation = EvaluationModule::getById($idEvaluation);
}

if ($action == 'ajoutevaluation'){

}elseif ($action == 'editevaluation'){

}elseif ($action=='print') {
    include_once ROOTVIEWS.'view_printevaluationmodule.php';
}elseif ($action == 'listeevaluations'){
	$listeEvaluations = EvaluationModule::getListeFromModule($idModule);

	include_once ROOTVIEWS . 'view_listeevaluationsmodule.php';
}else{
	header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>