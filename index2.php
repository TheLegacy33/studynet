<?php
	require __DIR__ . '/vendor/autoload.php';
	use DebugBar\StandardDebugBar;

	$debugbar = new StandardDebugBar();
	$debugbarRenderer = $debugbar->getJavascriptRenderer();
	$debugbarRenderer->setBaseUrl('/epsinet/vendor/maximebf/debugbar/src/DebugBar/Resources/');

	$debugbar["messages"]->addMessage("hello world!");
?>
<html>
<head>
	<base href="/epsinet/">
	<?php echo $debugbarRenderer->renderHead() ?>
</head>
<body>
...
<?php echo $debugbarRenderer->render() ?>
</body>
</html>