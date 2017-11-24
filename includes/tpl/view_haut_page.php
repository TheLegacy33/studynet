<?php

/**
 * Définition des éléments de haut de page
 */

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title><?php print($title); ?></title>

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<!-- Bootstrap -->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
			  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
			  integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link href="includes/styles/styles.css" rel="stylesheet" />
	</head>
	<body>
		<section id="wrapper" class="container">
			<header id="logo" class="row">
				<img class="col-xs-3" src="includes/images/logo.png" alt="logo"/>
				<p class="col-xs-9 text-center">Les applications de l'Intranet</p>
			</header>
			<nav id="navgenerale" class="row">
				<ul>
					<li><a href="index.php" title="Accueil">Accueil</a></li>
					<li><a href="index.php?p=ecoles" title="Les écoles">Ecoles</a></li>
					<li><a href="index.php?p=periodesformation" title="Les périodes de formations">Sessions</a></li>
					<!--<li><a href="index.php?p=modules" title="Les modules">Modules</a></li>-->
					<!--<li><a href="index.php?p=evaluations" title="Les évaluations">Evaluations</a></li>-->
				</ul>
			</nav>