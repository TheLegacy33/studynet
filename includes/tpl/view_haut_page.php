<?php

/**
 * Définition des éléments de haut de page
 * @var $title
 * 			Titre de la page
 * @var $pageTitle
 * 			Titre sur la page
 * @var $user
 * 			Utillisateur
 *
 */
	include_once ROOTSCRIPTS.'NavBar.php';
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<base href="/<?php print(MAINDIR); ?>/">
		<meta charset="UTF-8">
		<title><?php print($title); ?></title>

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--<link crossorigin="anonymous" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
			  integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
			  rel="stylesheet">

		<link crossorigin="anonymous" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"
			  integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
			  rel="stylesheet">

		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
		<link rel="stylesheet" href="<?php print(ROOTHTMLLIBS) ?>bootstrap_4.3.1.min.css">
		<link rel="stylesheet" href="<?php print(ROOTHTMLLIBS) ?>fa_5.11.2_all.css">
		<link rel="stylesheet" href="<?php print(ROOTHTMLLIBS) ?>jquery-ui_1.12.1.css"> -->
		<link rel="stylesheet" href="<?php print(ROOTHTMLLIBS) ?>bootstrap_4.6.0.min.css">
		<link rel="stylesheet" href="<?php print(ROOTHTMLLIBS) ?>fa_5.11.2_all.css">
		<link rel="stylesheet" href="<?php print(ROOTHTMLLIBS) ?>jquery-ui_1.12.1.css">

		<link href="<?php print(ROOTHTML) ?>/includes/styles/styles.css" rel="stylesheet" />
	</head>
	<body>
		<section id="wrapper" class="container">
			<header id="logo" class="row d-flex align-items-center">
				<div class="flex-grow-0"><img class="img-fluid" src="<?php print(ROOTHTML) ?>/includes/images/logo.png" alt="logo"/></div>
				<div class="flex-grow-1 text-center"><h2><?php print($pageTitle); ?></h2></div>
			</header>
			<nav id="navgenerale" class="row navbar navbar-expand-md navbar-dark">
				<!-- Toggler/collapsibe Button -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<?php
					$menus = NavBar::fill($user);
					print($menus->render());
				?>
				</div>
			</nav>