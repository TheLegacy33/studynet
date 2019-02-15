<?php
	$action = isset($_GET['a'])?$_GET['a']:'liste';
	include_once ROOTMODELS.'model_ecole.php';

	$listeEcoles = Ecole::getListe();

	include_once ROOTVIEWS.'view_listeecoles.php';
?>