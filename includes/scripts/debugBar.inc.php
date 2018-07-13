<?php
	require ROOT. '/vendor/autoload.php';
	use DebugBar\StandardDebugBar;

	$debugbar = new StandardDebugBar();
	$debugbarRenderer = $debugbar->getJavascriptRenderer();
	$debugbarRenderer->setBaseUrl('/epsinet/vendor/maximebf/debugbar/src/DebugBar/Resources/');
?>

