<?php
	$action = isset($_GET['a'])?$_GET['a']:'listepf';
	include_once ROOTMODELS.'model_periodeformation.php';

	$promo = null;
	$pf = null;
	$active = isset($_GET['active'])?$_GET['active']:'1';


	$idPromo = isset($_GET['idpromo'])?$_GET['idpromo']:0;
	$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;

	if ($action == 'listepf'){
		if (isset($_GET['idpromo'])) {
			$promo = Promotion::getById($idPromo);
			$listePf = Periodeformation::getListeFromPromo($idPromo, $active);
		}else{
			if ($idPf != 0){
				$promo = Promotion::getByIdPf($idPf);
				$pf = Periodeformation::getById($idPf);
			}
			$listePf = Periodeformation::getListe($idPf, $active);
		}
		//Gestion du filtre de périodes de formations actives ou non
		$includeJs = true;
		$scriptname[] = 'js_listepf.js';

		$listeStatutPf = StatutPeriodeFormation::getListe();
		include_once ROOTVIEWS.'view_listeperiodesformations.php';
	}elseif ($action == 'ajoutpf'){

	}elseif ($action == 'editpf'){

	}else{

		if (isset($idPf) AND $idPf != 0){
			$promo = Promotion::getByIdPf($idPf);
			$pf = Periodeformation::getById($idPf);
			$listePf = Periodeformation::getListe($idPf, $active);

			include_once ROOTVIEWS.'view_listeperiodesformations.php';

			if ($action == 'listemodules' OR $action == 'ajoutmodule' OR $action == 'editmodule' OR $action == 'importmodules'){
				include_once ROOTCTRL.'controller_modules.php';
			}elseif ($action == 'listeetudiants' OR $action == 'ajoutetudiant' OR $action == 'editetudiant' OR $action == 'importetudiants'){
				include_once ROOTCTRL.'controller_etudiants.php';
			}elseif ($action == 'listeevaluations' OR $action == 'gestnotes' OR $action == 'editnotes'){
				include_once ROOTCTRL.'controller_evaluationmodule.php';
			}elseif ($action == 'editappgenerale' OR $action == 'viewdetailsevaluations' OR $action == 'editdetailsevaluations'){
				include_once ROOTCTRL.'controller_evaluation.php';
			}elseif ($action == 'participations'){
				//Gestion de l'affectation des étudiants aux modules de la pf
				$includeJs = true;
				$scriptname[] = 'js_participations.js';
				$listeEtudiants = Etudiant::getListeFromPf($idPf);
				$listeModules = Module::getListeFromPf($idPf);
				include_once ROOTVIEWS.'view_gestparticipation.php';
			}else{
				header('Location: '.ROOTHTML);
			}
		}
	}


?>