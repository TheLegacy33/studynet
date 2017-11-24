<?php
	include_once ROOTMODELS.'model_promotion.php';

	$idEcole = isset($_GET['idecole'])?$_GET['idecole']:0;
	$listePromos = Promotion::getListeFromEcole($idEcole);

	include_once ROOTVIEWS.'view_listepromotions.php';
?>