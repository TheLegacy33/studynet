<?php
	/**
	 * Controller principal qui va charger l'ensemble des controllers
	 */
	define('SESSIONNAME', 'EPSINETSESS');

	define('ROOTCTRL', ROOT.'/includes/controllers/');
	define('ROOTVIEWS', ROOT.'/includes/views/');
	define('ROOTMODELS', ROOT.'/includes/models/');
	define('ROOTSCRIPTS', ROOT.'/includes/scripts/');
	define('ROOTTEMPLATE', ROOT.'/includes/tpl/');

	define('ROOTEXPORTS', ROOT.'/exports/');
	define('ROOTUPLOADS', ROOT.'/uploads/');
	define('ROOTHTMLEXPORTS', ROOTHTML.'/exports/');
	define('ROOTHTMLUPLOADS', ROOTHTML.'/uploads/');
	define('ROOTHTMLSCRIPTS', ROOTHTML.'/includes/scripts/');
	define('ROOTHTMLSCRIPTSJS', ROOTHTMLSCRIPTS.'js/');

	if (!file_exists(ROOTEXPORTS)){
		mkdir(ROOTEXPORTS);
		@chmod(ROOTEXPORTS, 0775);
	}

	if (!file_exists(ROOTUPLOADS)){
		mkdir(ROOTUPLOADS);
		@chmod(ROOTUPLOADS, 0775);
	}

	include_once ROOTSCRIPTS.'fonctions.php';

	$page = basename($_SERVER['SCRIPT_NAME']);
	if ($page != 'index.php'){
		header('Location: '.ROOTHTML);
	}else{
		$title = 'Intranet : Les évaluations';
		$pageTitle = 'LocalNet Evaluations';

		$section = isset($_GET['p'])?$_GET['p']:'';
		$action = isset($_GET['a'])?$_GET['a']:'';

		include_once ROOTCTRL.'controller_auth.php';

		if (!$user->isAuthentified()){
			$section = '';
			$action = '';
		}

		if ($action != 'print' AND $section != 'api'){
			include_once ROOTTEMPLATE.'view_haut_page.php';
		}

		if ($section == ''){
			//Chargement des éléments de la page d'accueil
			include_once ROOTVIEWS.'view_accueil.php';

			//Inclusion du formulaire d'authentification
			if (!$user->isAuthentified()){
				include_once ROOTVIEWS.'view_loginform.php';
			}else{
				include_once ROOTVIEWS.'view_logoutform.php';

				//Dans le cas de l'authentification par un étudiant, vérifier si il doit récupérer des sujets de rattrapage.
				//Si oui, affichage de l'information d'avertissement et lui donner le lien
				if ($user->estEtudiant()){
				    if ($user->hasRattrapages()){
                        $section = 'rattrapages';
                        $action = 'listeforetudiant';
					    include_once(ROOTCTRL.'controller_rattrapage.php');
                    }
				}
			}
		}elseif ($section == 'ecoles'){
			//Gestion des écoles
			include_once ROOTCTRL.'controller_ecoles.php';
		}elseif ($section == 'promotions'){
			//Gestion des promotions
			include_once ROOTCTRL.'controller_promotions.php';
		}elseif ($section == 'periodesformation'){
			//Gestion des périodes de formations
			include_once ROOTCTRL.'controller_pf.php';
		}elseif ($section == 'modules'){
			//Gestion des modules
			include_once ROOTCTRL.'controller_modules.php';
		}elseif ($section == 'evaluations'){
			//Gestion des évaluations
			include_once ROOTCTRL.'controller_evaluation.php';
		}elseif ($section == "etudiants"){
			//Gestion des étudiants
			include_once ROOTCTRL . 'controller_etudiants.php';
		}elseif ($section == "intervenants"){
			//Gestion des intervenants
			include_once ROOTCTRL.'controller_intervenant.php';
		}elseif ($section == "users"){
			//Gestion des utilisateurs
			include_once ROOTCTRL . 'controller_users.php';
		}elseif ($section == "personnes"){
			//Gestion des personnes
			include_once ROOTCTRL.'controller_personnes.php';
		}elseif ($section == "api"){
			//Gestion des api
			include_once ROOTCTRL.'controller_api.php';
		}elseif ($section == 'rattrapages'){
            include_once(ROOTCTRL.'controller_rattrapage.php');
		}else{
			header('Location: '.ROOTHTML);
		}

		if ($action != 'print' AND $section != 'api'){
			include_once ROOTTEMPLATE.'view_bas_page.php';
		}
	}
?>