<?php

/**
 * Définition des éléments de haut de page
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
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
			  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
			  integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
		-->
		<link crossorigin="anonymous" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
			  integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
			  rel="stylesheet">

<!--		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"-->
<!--			  integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">-->
		<link crossorigin="anonymous" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
			  integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
			  rel="stylesheet">

		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

		<link href="includes/styles/styles.css" rel="stylesheet" />
	</head>
	<body>
		<section id="wrapper" class="container">
			<header id="logo" class="row">
				<div class="col-3 float-left"><img src="includes/images/logo.png" alt="logo"/></div>
				<div class="col-9 text-center"><p><?php print($pageTitle); ?></p></div>
			</header>
			<nav id="navgenerale" class="row">
				<?php
					$menus = NavBar::fill($user);
					print($menus->render());
				?>
			</nav>