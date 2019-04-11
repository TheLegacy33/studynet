<?php
//	http://localhost/studynet/index.php?p=api&a=getListeEcoles&key=123456789

//	http://localhost/studynet/index.php?p=api&a=getListePromos&idecole=1&key=123456789

	$action = trim(isset($_GET['a'])?$_GET['a']:'');
	$key =  trim(isset($_GET['key'])?$_GET['key']:'');
	if ($action != '' && $key != ''){
		$keyAuth = hash("sha256", "123456789");

		if ($key == $keyAuth OR 1){
			if ($action == 'getListeEcoles'){
				include_once ROOTMODELS.'model_ecole.php';
				try{
					$listeEcoles = Ecole::getListeForAPI();
					header('Content-Type: application/json; charset=utf-8');
					print(json_encode($listeEcoles));
				}catch (Exception $ex){
					var_dump($ex);
				}
			}elseif ($action == 'getListePromos'){
				include_once ROOTMODELS.'model_promotion.php';
				$idEcole = isset($_GET['idecole'])?$_GET['idecole']:0;
				$listePromos = Promotion::getListeFromEcoleForAPI($idEcole);
				header('Content-Type: application/json; charset=utf-8');
				print(json_encode($listePromos));
			}else{
				header('Content-Type: text/html; charset=utf-8');
				header("HTTP/1.0 404 Not Found");
			}
		}else{
			header('Content-Type: text/html; charset=utf-8');
			header("HTTP/1.0 403 Forbidden");
		}
	}else{
		header('Content-Type: text/html; charset=utf-8');
		header("HTTP/1.0 404 Not Found");
	}
?>