<?php
$dbConn = null;
try{
	/*
 	* Ouverture de la connexion à la base de données
 	*/
	$dbConn = new PDO('mysql:host=localhost;port=3306;charset=utf8;dbname=repertoire', 'root', '@dmDev@tom');
}catch (Exception $ex){
	/*
	 * En cas d'erreur, gestion d'une exception (à voir plus tard)
	 */
	print($ex->getMessage());
}

?>