<?php
	include_once ROOTMODELS.'model_auth.php';

	if (isset($_SESSION['user'])){
		$user = unserialize($_SESSION['user']);
		Authentification::setUser($user);
	}else{
		$user = new User();
	}

	if ($section == 'auth'){
		Authentification::setUser(new User());
		if (!empty($_POST)){
			if (isset($_POST['logout'])){
				session_destroy();
				header('Location: '.ROOTHTML);
			}else{
				$user = new User();
				if ($user->checkAuth($_POST['ttLogin'], $_POST['ttPassword'])){
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