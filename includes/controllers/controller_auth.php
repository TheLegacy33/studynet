<?php
	include_once ROOTMODELS.'model_auth.php';

	if (isset($_SESSION['user'])){
		Authentification::loadSession();
		$user = Authentification::getUser();
	}else{
		$user = new Personne();
	}
	if ($section == 'auth'){
		if (!empty($_POST)){
			if (isset($_POST['logout'])){
				session_destroy();
				header('Location: '.ROOTHTML);
			}else{
				$user = new Personne();
				if ($user->checkAuth($_POST['ttLogin'], $_POST['ttPassword'])){

				    $userType = Personne::getType($user->getId());
				    if ($userType == 'etudiant'){
				        $userTyped = new Etudiant();
				        $userTyped->clone($user);
				        $user = $userTyped;
                    }elseif ($userType == 'responsablepedago'){
                        $userTyped = new ResponsablePedago();
                        $userTyped->clone($user);
                        $user = $userTyped;
                    }elseif ($userType == 'intervenant'){
                        $userTyped = new Intervenant();
                        $userTyped->clone($user);
                        $user = $userTyped;
                    }

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
?>