<?php
	ini_set('memory_limit', '1024M'); // or you could use 1G

	$action = isset($_GET['a'])?$_GET['a']:'listepf';
	include_once ROOTMODELS . 'model_periodeformation.php';
	$promo = null;
	$pf = null;
	$active = isset($_GET['active'])?$_GET['active']:'1';
	$statut = StatutPeriodeFormation::getById($active);

	$idPromo = isset($_GET['idpromo'])?$_GET['idpromo']:0;
	$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;

	if ($action == 'listepf'){
		if (isset($_GET['idpromo'])) {
			$promo = Promotion::getById($idPromo);
			$listePf = $promo->getPf($statut);
		}else{
			if ($idPf != 0){
				$promo = Promotion::getByIdPf($idPf);
				$pf = Periodeformation::getById($idPf);
			}
			$listePf = Periodeformation::getListe($statut);
		}

		$includeJs = true;
		$scriptname[] = 'js_listepf.js';

		$listeStatutPf = StatutPeriodeFormation::getListe();
		include_once ROOTVIEWS . 'view_listeperiodesformations.php';
	}elseif ($action == 'ajoutpf'){
		$includeJs = true;
		$scriptname = ['js_periodeformation.js', 'js_formscripts.js'];

		$pf = new Periodeformation();
		$promo = Promotion::getById($idPromo);
		$listePersonnes = Personne::getListe(array('I', 'R'));
		$listeStatutPf = StatutPeriodeFormation::getListe();

		if (!empty($_POST)){
			$dateDebut = $_POST['ttDateDebut'];
			$dateFin = $_POST['ttDateFin'];
			$duree = $_POST['ttDuree'];
			$statut = (isset($_POST['cbStatut']) AND $_POST['cbStatut'] != '0')?StatutPeriodeFormation::getById($_POST['cbStatut']):null;

			//Je rajoute la personne en tant que responsable pédago
			$personne = (isset($_POST['cbResponsable']) AND $_POST['cbResponsable'] != '0')?Personne::getById($_POST['cbResponsable']):null;
			if (!ResponsablePedago::exists($personne->getId())){
				ResponsablePedago::insert($personne);
			}
			$responsable = ResponsablePedago::getById(ResponsablePedago::getIdByIdPers($personne->getId()));

            $newPf = new Periodeformation(0, $dateDebut, $dateFin, $idPromo, $statut->getId(), $duree);
			$newPf->setResponsable($responsable);

			if (Periodeformation::insert($newPf)){
				header('Location: index.php?p=periodesformation&a=listepf&idpromo='.$idPromo);
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS . 'view_fichepf.php';
	}elseif ($action == 'editpf'){
		$includeJs = true;
		$scriptname = ['js_periodeformation.js', 'js_formscripts.js'];

		$promo = Promotion::getByIdPf($idPf);
		$pf = Periodeformation::getById($idPf);
		$listePersonnes = Personne::getListe(array('I', 'R'));
		$listeStatutPf = StatutPeriodeFormation::getListe();

		if (!empty($_POST)){

			$dateDebut = $_POST['ttDateDebut'];
			$dateFin = $_POST['ttDateFin'];
			$duree = $_POST['ttDuree'];
			$statut = (isset($_POST['cbStatut']) AND $_POST['cbStatut'] != '0')?StatutPeriodeFormation::getById($_POST['cbStatut']):null;

			$personne = (isset($_POST['cbResponsable']) AND $_POST['cbResponsable'] != '0')?Personne::getById($_POST['cbResponsable']):null;
			if (!ResponsablePedago::exists($personne->getId())){
				ResponsablePedago::insert($personne);
			}
			$newresponsable = ResponsablePedago::getById(ResponsablePedago::getIdByIdPers($personne->getId()));
			$oldresponsable = $pf->getResponsable();
			$pf->setDuree($duree);
			$pf->setDateDebut($dateDebut);
			$pf->setDateFin($dateFin);
			$pf->setResponsable($newresponsable);
			$pf->setStatut($statut);

			if (Periodeformation::update($pf)){
				if (!$newresponsable->equals($oldresponsable)){
					if ($oldresponsable->getNbPf($pf->getId()) == 0){
						ResponsablePedago::delete($oldresponsable) or die('erreur');
					}
				}

				header('Location: index.php?p=periodesformation&a=listepf&idpromo='.$promo->getId());
			}else{
				var_dump("Erreur d'enregistrement");
			}
		}
		include_once ROOTVIEWS . 'view_fichepf.php';

	}else{
		if (isset($idPf) AND $idPf != 0){
			$promo = Promotion::getByIdPf($idPf);
			$pf = Periodeformation::getById($idPf);
			$listePf = Array($pf);

			include_once ROOTVIEWS . 'view_enteteperiodesformations.php';
			if ($action == 'listemodules' OR $action == 'ajoutmodule' OR $action == 'editmodule' OR $action == 'importmodules' OR $action == 'editcontenumodule' OR $action == 'ajoutcontenumodule'){
				include_once ROOTCTRL . 'controller_modules.php';
			}elseif ($action == 'listeetudiants' OR $action == 'ajoutetudiant' OR $action == 'editetudiant' OR $action == 'importetudiants'){
				include_once ROOTCTRL . 'controller_etudiants.php';
			}elseif ($action == 'evalenseignement'){
				include_once ROOTCTRL . 'controller_evalenseignement.php';
			}elseif ($action == 'editappgenerale' OR $action == 'viewdetailsevaluations' OR $action == 'editdetailsevaluations'){
				include_once ROOTCTRL . 'controller_evaluation.php';
			}elseif ($action == 'participations'){
				//Gestion de l'affectation des étudiants aux modules de la pf
				$includeJs = true;
				$scriptname[] = 'js_participations.js';
				$listeEtudiants = Etudiant::getListeFromPf($idPf);
				$listeModules = Module::getListeFromPf($idPf);
				include_once ROOTVIEWS . 'view_gestparticipation.php';
			}else{
				header('Location: '.ROOTHTML);
			}
		}
	}
?>