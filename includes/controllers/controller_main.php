<?php
	/**
	 * Controller principal qui va charger l'ensemble des controllers
	 */

	define('ROOTCTRL', ROOT.'/includes/controllers/');
	define('ROOTVIEWS', ROOT.'/includes/views/');
	define('ROOTMODELS', ROOT.'/includes/models/');
	define('ROOTSCRIPTS', ROOT.'/includes/scripts/');
	define('ROOTTEMPLATE', ROOT.'/includes/tpl/');

	define('ROOTEXPORTS', ROOT.'/exports/');
	define('ROOTUPLOADS', ROOT.'/uploads/');

	define('ROOTHTMLEXPORTS', ROOTHTML.'/exports/');
	define('ROOTHTMLUPLOADS', ROOTHTML.'/uploads/');

	include_once ROOTSCRIPTS.'fonctions.php';
	$page = basename($_SERVER['SCRIPT_NAME']);
	if ($page != 'index.php'){
		header('Location: '.ROOTHTML);
	}else{
		$title = 'Intranet EPSI : Les évaluations';
		$pageTitle = 'EPSINET Evaluations';

        $section = isset($_GET['p'])?$_GET['p']:'';
        $action = isset($_GET['a'])?$_GET['a']:'';

		include_once ROOTCTRL.'controller_auth.php';

		if (!$user->isAuthentified()){
			$section = '';
			$action = '';
		}

        if ($action != 'print'){
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
			include_once ROOTCTRL.'controller_etudiant.php';
		}else{
			header('Location: '.ROOTHTML);
		}

        if ($action != 'print'){
            include_once ROOTTEMPLATE.'view_bas_page.php';
        }
	}
?>