<?php
$action = isset($_GET['a'])?$_GET['a']:'view';
include_once ROOTMODELS . 'model_evaluation.php';
include_once ROOTMODELS . 'model_etudiant.php';
include_once ROOTMODELS . 'model_periodeformation.php';
include_once ROOTMODELS . 'model_module.php';

$idetudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
$idpf = isset($_GET['idpf'])?$_GET['idpf']:0;
$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;

if ($idetudiant != 0 AND $idpf != 0 AND $idModule != 0){
	$etudiant = Etudiant::getById($idetudiant);
	$pf = Periodeformation::getById($idpf);
	$module = Module::getById($idModule);
	$module->setIntervenant(Intervenant::getByPfAndMod($idpf, $idModule));

	$listeContenusModule = $module->getContenu();
}elseif ($idetudiant != 0 AND $idpf != 0){
    $etudiant = Etudiant::getById($idetudiant);
    $pf = Periodeformation::getById($idpf);
	$listeModules = Module::getListeFromPf($idpf);
}
if ($action == 'view' OR $action == 'viewdetailsevaluations'){
    if ($idModule == 0){
        include_once ROOTVIEWS . 'view_afficheevaluationstousmodules.php';
    }else{
        include_once ROOTVIEWS . 'view_afficheevaluations.php';
    }
}elseif ($action == 'edit' OR $action == 'editdetailsevaluations'){
	if ($idModule != 0){ //Traitement appliqué pour un module
		if (!empty($_POST)){
			//J'arrive du formulaire je dois mettre à jour les évaluation si besoin
			$commentaireModule = isset($_POST['comm_module']) ? $_POST['comm_module'] : 'Pas de commentaire';
			if (strtolower(trim($commentaireModule)) != 'pas de commentaire'){
				Evaluation::updateAppreciationModule($commentaireModule, $idetudiant, $idModule);
			}
			foreach ($listeContenusModule as $cModule){
				$commCmodule = $_POST['comm_' . $cModule->getId()];
				if (strtolower(trim($commCmodule)) == 'pas de commentaire'){
					$commCmodule = '';
				}
				$evalModule = $_POST['btradioeval_' . $cModule->getId()];
				if ($evalModule == 'A'){
					$acquis = 1;
					$enacquisition = 0;
					$nonacquis = 0;
				}elseif ($evalModule == 'EA'){
					$acquis = 0;
					$enacquisition = 1;
					$nonacquis = 0;
				}else{
					$acquis = 0;
					$enacquisition = 0;
					$nonacquis = 1;
				}
				Evaluation::update(new Evaluation($idetudiant, $module->getIntervenant()->getId(), $cModule->getId(), $acquis, $enacquisition, $nonacquis, $commCmodule));
			}
			header('Location: index.php?p=periodesformation&a=listemodules&idetudiant=' . $idetudiant . '&idpf=' . $idpf);
		}
		include_once ROOTVIEWS . 'view_editevaluations.php';
	}else{
		//Traitement appliqué pour le commentaire grlobal l'ensemble des modules
		if (!empty($_POST)){
			$appgenerale = $_POST['appreciation'];
			if (strtolower(trim($appgenerale)) == 'pas de commentaire'){
				$appgenerale = '';
			}
			Evaluation::updateAppreciationGenerale($appgenerale, $idetudiant, $idpf);
			header('Location: index.php?p=evaluations&a=view&idetudiant=' . $idetudiant . '&idpf=' . $idpf);
		}
		include_once ROOTVIEWS . 'view_editevaluationstousmodules.php';
	}
}elseif ($action == 'editappgenerale'){
	//Traitement appliqué pour le commentaire grlobal l'ensemble des modules
	if (!empty($_POST)){
		$appgenerale = $_POST['appreciation'];
		if (strtolower(trim($appgenerale)) == 'pas de commentaire'){
			$appgenerale = '';
		}
		Evaluation::updateAppreciationGenerale($appgenerale, $idetudiant, $idpf);
		header('Location: index.php?p=periodesformation&a=listemodules&idetudiant=' . $idetudiant . '&idpf=' . $idpf);
	}
	include_once ROOTVIEWS . 'view_editappgenerale.php';
}elseif ($action=='print') {
    include_once ROOTVIEWS . 'view_printevaluation.php';
}else{
	header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>