<?php
	$action = trim(isset($_GET['a'])?$_GET['a']:'');
	$key =  trim(isset($_GET['key'])?$_GET['key']:'');
	if ($action != '' && $key != ''){
		$keyAuth = hash("sha256", "123456789");
		if ($key == $keyAuth){
			if ($action == 'getListeEcoles'){
				include_once ROOTMODELS.'model_ecole.php';
				try{
					$listeEcoles = Ecole::getListeForAPI();
					print(json_encode($listeEcoles));
				}catch (Exception $ex){
					var_dump($ex);
				}
			}elseif ($action == 'getListePromos'){
				include_once ROOTMODELS.'model_promotion.php';
				$idEcole = isset($_GET['idecole'])?$_GET['idecole']:0;
				$listePromos = Promotion::getListeFromEcoleForAPI($idEcole);

				print(json_encode($listePromos));
			}else{
				header("HTTP/1.0 404 Not Found");
			}
		}
	}else{
		header("HTTP/1.0 403 Forbidden");
	}
?>