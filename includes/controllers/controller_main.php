<?php
	/**
	 * Controller principal qui va charger l'ensemble des controllers
	 */
	session_start();

	define('ROOTCTRL', ROOT.'/includes/controllers/');
	define('ROOTVIEWS', ROOT.'/includes/views/');
	define('ROOTMODELS', ROOT.'/includes/models/');
	define('ROOTSCRIPTS', ROOT.'/includes/scripts/');
	define('ROOTTEMPLATE', ROOT.'/includes/tpl/');

	define('ROOTEXPORTS', ROOT.'/exports/');
	define('ROOTUPLOADS', ROOT.'/uploads/');

	define('ROOTHTMLEXPORTS', ROOTHTML.'/exports/');
	define('ROOTHTMLUPLOADS', ROOTHTML.'/uploads/');

	$page = basename($_SERVER['SCRIPT_NAME']);
	if ($page != 'index.php'){
		header('Location: '.ROOTHTML);
	}else{
		$title = "Page d'accueil des applications intranet EPSI";

        $section = isset($_GET['p'])?$_GET['p']:'';
        $action = isset($_GET['a'])?$_GET['a']:'';

		include_once ROOTCTRL.'controller_auth.php';

		$canEdit = false;
		if (!$user->isAuthentified()){
			$section = '';
			$action = '';
		}else{
			$canEdit = $user->getRole() == 'admin';
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
			include_once ROOTCTRL.'controller_ecoles.php';
		}elseif ($section == 'promotions'){
			include_once ROOTCTRL.'controller_promotions.php';
		}elseif ($section == 'periodesformation'){
			include_once ROOTCTRL.'controller_pf.php';
		}elseif ($section == 'modules'){
			include_once ROOTCTRL.'controller_modules.php';
		}elseif ($section == 'evaluations'){
			include_once ROOTCTRL.'controller_evaluation.php';
		}elseif ($section == "etudiants"){
			include_once ROOTCTRL.'controller_etudiant.php';
		}else{
				header('Location: '.ROOTHTML);
		}

        if ($action != 'print'){
            include_once ROOTTEMPLATE.'view_bas_page.php';
        }
	}
?>