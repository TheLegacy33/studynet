<?php
	include_once ROOTMODELS.'model_authentification.php';

	if (isset($_SESSION['user'])){
		Authentification::loadSession();
		$user = Authentification::getUser();
	}else{
		$user = new Personne();
	}
	if (isset($section)){
		if ($section == 'auth'){
			if (!empty($_POST)){
				if (isset($_POST['logout'])){
					session_destroy();
					header('Location: '.ROOTHTML);
				}else{
					if ($user->checkAuth($_POST['ttLogin'], $_POST['ttPassword'])){
						$userType = Personne::getType($user->getId());
						if ($userType == Etudiant::class){
							$userTyped = Etudiant::getById(Etudiant::getIdByIdPers($user->getPersId()));
						}elseif ($userType == ResponsablePedago::class){
							$userTyped = ResponsablePedago::getById(ResponsablePedago::getIdByIdPers($user->getPersId()));
						}elseif ($userType == Intervenant::class){
							$userTyped = Intervenant::getById(Intervenant::getIdByIdPers($user->getPersId()));
						}else{
							$userTyped = Personne::getById($user->getPersId());
						}

						$userTyped->clonepers($user);
						$user = $userTyped;

						Authentification::setUser($user);
						Authentification::saveSession();
						header('Location: '.ROOTHTML);
					}else{
						header('Location: '.ROOTHTML);
					}
				}
			}else{
				header('Location: '.ROOTHTML);
			}
		}
	}
?>