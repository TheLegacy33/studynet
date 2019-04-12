<?php
	include_once ROOTMODELS . 'model_authentification.php';

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
					$user = new Personne();
					if ($user->checkAuth($_POST['ttLogin'], $_POST['ttPassword'])){

						$userType = Personne::getType($user->getId());
						if ($userType == Etudiant::class){
							$userTyped = new Etudiant(Etudiant::getIdByIdPers($user->getPersId()));
						}elseif ($userType == ResponsablePedago::class){
							$userTyped = new ResponsablePedago(ResponsablePedago::getIdByIdPers($user->getPersId()));
						}elseif ($userType == Intervenant::class){
							$userTyped = new Intervenant(Intervenant::getIdByIdPers($user->getPersId()));
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