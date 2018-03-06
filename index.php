<?php

	//define('ROOT', $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']));
	define('ROOT', dirname(__FILE__));
    define('SERVERNAME', $_SERVER['SERVER_NAME']);
	if (substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == 'HTTP/'){
		$rootHtml = 'http://';
	}else{
		$rootHtml = 'https://';
	}
    $rootHtml .= SERVERNAME;

    if (substr(SERVERNAME, strlen(SERVERNAME) - 1, 1) == '/'){
        $rootHtml = substr($rootHtml, 0, strlen($rootHtml) - 1);
    }
    if (dirname($_SERVER['SCRIPT_NAME']) != '/'){
        $rootHtml .= dirname($_SERVER['SCRIPT_NAME']);
    }
    define('ROOTHTML', $rootHtml);
	include_once ROOT.'/includes/controllers/controller_main.php';

?>

