<?php
	/**
	 * Controller principal qui va charger l'ensemble des controllers
	 * @var Personne $user
	 */
	const DEBUGMODE = false;
	const SESSIONNAME = 'STUDYNETSESS';
	const APPNAME = 'StudyNet';
	define('MAINDIR', basename(ROOT));
	const ROOTCTRL = ROOT . '/core/controllers/';
	const ROOTVIEWS = ROOT . '/core/views/';
	const ROOTMODELS = ROOT . '/core/models/';
	const ROOTSCRIPTS = ROOT . '/includes/scripts/';
	const ROOTTEMPLATE = ROOT . '/includes/tpl/';
	const ROOTLIBS = ROOT . '/includes/libs/';
	const ROOTEXPORTS = ROOT . '/exports/';
	const ROOTUPLOADS = ROOT . '/uploads/';
	const ROOTHTMLEXPORTS = ROOTHTML . '/exports/';
	const ROOTHTMLUPLOADS = ROOTHTML . '/uploads/';
	const ROOTHTMLSCRIPTS = ROOTHTML . '/includes/scripts/';
	const ROOTHTMLSCRIPTSJS = ROOTHTML . '/includes/scripts/js/';
	const ROOTHTMLTEMPLATE = ROOTHTML . '/includes/tpl/';
	const ROOTHTMLLIBS = ROOTHTML . '/includes/libs/';

	if (!file_exists(ROOTEXPORTS)){
		mkdir(ROOTEXPORTS);
		@chmod(ROOTEXPORTS, 0775);
	}

	if (!file_exists(ROOTUPLOADS)){
		mkdir(ROOTUPLOADS);
		@chmod(ROOTUPLOADS, 0775);
	}

	ini_set('display_errors', 'on');

	include_once ROOTSCRIPTS.'fonctions.php';

	$page = basename($_SERVER['SCRIPT_NAME']);
	if ($page != 'index.php'){
		header('Location: '.ROOTHTML);
	}else{
		$title = 'Intranet : Le service ENT';
		$pageTitle = 'StudyNet Services';

		$section = $_GET['p'] ?? '';
		$action = $_GET['a'] ?? '';
		if ($section == 'api'){
			include_once ROOTCTRL.'controller_api.php';
		}else{
			include_once ROOTCTRL.'controller_auth.php';
			if (isset($user)){
				if (!$user->isAuthentified()){
					$section = '';
					$action = '';
				}
			}

			$entity = '';
			if ($user->isAdmin()){
				$entity = 'admin/';
			}elseif ($user->estEtudiant()){
				$entity = 'etudiant/';
			}elseif ($user->estIntervenant()){
				$entity = 'intervenant/';
			}else{
				var_dump("Type user non valide !");
			}
			if ($action != 'print' AND $section != 'ajax'){
				include_once ROOTTEMPLATE.'view_haut_page.php';
			}

			if ($section == ''){
				//Chargement des éléments de la page d'accueil
//				include_once ROOTVIEWS.'view_accueil.php';

				//Inclusion du formulaire d'authentification
				if (!$user->isAuthentified()){
					$includeJs = true;
					$scriptname = ['js_login.js', 'js_formscripts.js'];

					include_once ROOTVIEWS.'view_loginform.php';
				}else{
					include_once ROOTVIEWS.'view_logoutform.php';

					//Dans le cas de l'authentification par un étudiant, vérifier si il doit récupérer des sujets de rattrapage.
					//Si oui, affichage de l'information d'avertissement et lui donner le lien
					if ($user->estEtudiant()){
						if ($user->hasRattrapages()){
							$section = 'rattrapages';
							$action = 'listeforetudiant';
							include_once(ROOTCTRL.$entity.'controller_rattrapage.php');
						}
					}
				}
			}elseif ($section == 'ecoles'){
				//Gestion des écoles
				include_once ROOTCTRL.$entity.'controller_ecoles.php';
			}elseif ($section == 'promotions'){
				//Gestion des promotions
				include_once ROOTCTRL.$entity.'controller_promotions.php';
			}elseif ($section == 'periodesformation'){
				//Gestion des périodes de formations
				include_once ROOTCTRL.$entity.'controller_pf.php';
			}elseif ($section == 'modules'){
				//Gestion des modules
				include_once ROOTCTRL.$entity.'controller_modules.php';
			}elseif ($section == 'evaluations'){
				//Gestion des évaluations
				include_once ROOTCTRL.$entity.'controller_evaluation.php';
			}elseif ($section == "etudiants"){
				//Gestion des étudiants
				include_once ROOTCTRL.$entity.'controller_etudiants.php';
			}elseif ($section == "intervenants"){
				//Gestion des intervenants
				include_once ROOTCTRL.'controller_intervenant.php';
			}elseif ($section == "users"){
				//Gestion des utilisateurs
				include_once ROOTCTRL . 'controller_users.php';
			}elseif ($section == "personnes"){
				//Gestion des personnes
				include_once ROOTCTRL.$entity.'controller_personnes.php';
			}elseif ($section == "ajax"){
				//Gestion des api
				include_once ROOTCTRL.'controller_ajax.php';
			}elseif ($section == 'rattrapages'){
				include_once(ROOTCTRL.$entity.'controller_rattrapage.php');
			}else{
				header('Location: '.ROOTHTML);
			}

			if ($action != 'print' AND $section != 'ajax'){
				include_once ROOTTEMPLATE.'view_bas_page.php';
			}
		}
	}
