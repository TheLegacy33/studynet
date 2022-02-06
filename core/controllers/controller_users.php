<?php
	/**
	 * @var $action
	 */
include_once ROOTMODELS . 'model_authentification.php';

if ($action == 'profile'){
	//Visu profil
	$includeJs = true;
	$scriptname = ['js_profile.js']; //, 'js_formscripts.js'];
	if (!empty($_POST)){
		$newLogin = $_POST['ttLogin'];
		$newPassword = $_POST['ttPassword'];
		$newNom = $_POST['ttNom'];
		$newPrenom = $_POST['ttPrenom'];
		$newEmail = $_POST['ttEmail'];
		if (isset($user)){
			if ($user->getUserAuth()->getLogin() != $newLogin OR
				$user->getUserAuth()->getPassword() != $newPassword OR
				$user->getNom() != $newNom OR
				$user->getPrenom() != $newPrenom OR
				$user->getEmail() != $newEmail){

				//Nécessite d'updater l'enregistrement et agir en fonction des modifications apportées.
				$logoutNeeded = false;

				if ($user->getUserAuth()->getLogin() != $newLogin OR
					$user->getUserAuth()->getPassword() != $newPassword){

					$logoutNeeded = true;
					$user->getUserAuth()->setLogin($newLogin);
					$user->getUserAuth()->setPassword($newPassword);
					User::update($user->getUserAuth());
				}

				$user->setNom($newNom);
				$user->setPrenom($newPrenom);
				$user->setEmail($newEmail);
				User::update($user->getUserAuth());

				Authentification::saveSession();
				if ($user->estEtudiant() AND !empty($_FILES)){
					$photo = $_FILES['ttPhoto'];
					$newNomPhoto = ROOTUPLOADS.'photo_'.$user->getNom().'_'.$user->getPrenom().'.'.pathinfo($photo['name'], PATHINFO_EXTENSION);
					if (!move_uploaded_file($photo['tmp_name'], $newNomPhoto)){
						$newNomPhoto = '';
					}
					$user->setPhoto($newNomPhoto);
					Etudiant::update($user);
				}else{
					Personne::update($user);
				}

				if ($logoutNeeded){
					session_destroy();
				}
				header('Location: '.ROOTHTML);
			}
		}
	}
	include_once(ROOTVIEWS.'view_profile.php');
}elseif ($action == 'editprofile'){

}elseif ($action == 'subscribe'){

}elseif ($action == 'renewpassword'){

}elseif ($action == 'validateprofile'){

}elseif ($action == 'validateprofile'){

}else{
	header('Location: '.ROOTHTML);
}
?>