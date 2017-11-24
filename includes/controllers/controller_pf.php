<?php
$action = isset($_GET['a'])?$_GET['a']:'listepf';
include_once ROOTMODELS.'model_periodeformation.php';

$promo = null;
if ($action == 'listepf' && isset($_GET['idpromo'])) {
	$idPromo = $_GET['idpromo'];
	$promo = Promotion::getById($idPromo);
	$listePf = Periodeformation::getListeFromPromo($idPromo);
}elseif ($action == 'listeetudiants'){
	$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
	$promo = Promotion::getByIdPf($idPf);
	$listePf = Periodeformation::getListe($idPf);
}else{
	$listePf = Periodeformation::getListe();
}


include_once ROOTVIEWS.'view_listeperiodesformations.php';

if ($action == 'listeetudiants'){
	$idPf = $_GET['idpf'];
	$listeEtudiants = Etudiant::getListeFromPf($idPf);
	include_once ROOTVIEWS.'/view_listeetudiants.php';
}
?>