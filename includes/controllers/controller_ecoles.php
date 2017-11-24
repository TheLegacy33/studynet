<?php
	include_once ROOTMODELS.'model_ecole.php';

	$listeEcoles = Ecole::getListe();

	include_once ROOTVIEWS.'view_listeecoles.php';
?>