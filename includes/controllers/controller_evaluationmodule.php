<?php
$action = isset($_GET['a'])?$_GET['a']:'view';
include_once ROOTMODELS.'model_evaluationmodule.php';
include_once ROOTMODELS.'model_etudiant.php';
include_once ROOTMODELS.'model_periodeformation.php';
include_once ROOTMODELS.'model_module.php';

if (!file_exists(ROOTUPLOADS.'/sujets/')){
	mkdir(ROOTUPLOADS.'/sujets/', 0755, true);
	@chmod(ROOTUPLOADS.'/sujets/', 0777);
}

if (!file_exists(ROOTUPLOADS.'/reponses/')){
	mkdir(ROOTUPLOADS.'/reponses/', 0755, true);
	@chmod(ROOTUPLOADS.'/reponses/', 0777);
}

define('ROOTHTMLDOCS', ROOTHTMLUPLOADS.'documents/');
define('ROOTUPLOADSDOCS', ROOTUPLOADS.'/documents/');

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

	include_once ROOTVIEWS.'view_listeevaluationsmodule.php';
}elseif ($action == 'gestnotes' OR $action == 'editnotes'){
    $listeEtudiants = Etudiant::getListeFromModule($idModule);
    $evaluation->fillNotes($listeEtudiants);

	$includeJs = true;
	$scriptname = ['js_evaluations.js', 'js_formscripts.js'];

    include_once ROOTVIEWS.'view_notesevaluationmodule.php';
}else{
	header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>