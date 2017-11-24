<?php
$action = isset($_GET['a'])?$_GET['a']:'listemodules';
include_once ROOTMODELS.'model_periodeformation.php';
//include_once ROOTMODELS.'model_evaluation.php';

/*
 * si liste modules alors
 * 	si idetudiant et idpf alors
 * 		Afficher les modules de l'étudiant pour la pf
 * 	sinon
 * 		Afficher tous les modules pour toutes les PF
 * 	fin si
 * fin si
 *
*/

$idetudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
$idpf = isset($_GET['idpf'])?$_GET['idpf']:0;
if ($action == 'listemodules'){
	if ($idetudiant != 0 AND $idpf != 0){
		$etudiant = Etudiant::getById($idetudiant);
		$pf = Periodeformation::getById($idpf);
		$listeModules = Module::getListeFromPf($idpf);
		include_once ROOTVIEWS.'view_listemodules.php';
	}
}



?>