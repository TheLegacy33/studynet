<?php
include_once ROOTMODELS.'model_rattrapage.php';

if ($action == 'listeforetudiant'){
	$idEtudiant = $user->getId();
	$listeRattrapage = Rattrapage::getListeForEtudiant($idEtudiant);

	if (count($listeRattrapage) > 0){
	    include_once ROOTVIEWS . 'view_listerattrapagesetudiant.php';
	}
}
?>