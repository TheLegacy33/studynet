<?php
include_once ROOTMODELS.'model_auth.php';

if ($action == 'profile'){
		//Visu profil
	$includeJs = true;
	$scriptname = 'js_profile.js';
	if (!empty($_POST)){
		$newLogin = $_POST['ttLogin'];
		$newPassword = $_POST['ttPassword'];
		$newNom = $_POST['ttNom'];
		$newPrenom = $_POST['ttPrenom'];
		$newEmail = $_POST['ttEmail'];

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
            Personne::update($user);

            Authentification::saveSession();

            if ($user->estEtudiant() AND !empty($_FILES)){

            }

            if ($logoutNeeded){
                session_destroy();
            }
            header('Location: '.ROOTHTML);
		}
	}
	include_once(ROOTVIEWS.'view_profile.php');
}elseif ($action == 'listepersonnes'){
    $listePersonnes = Personne::getListe();
    include_once ROOTVIEWS.'view_listepersonnes.php';
}elseif ($action == 'editprofile'){

}elseif ($action == 'subscribe'){

}elseif ($action == 'renewpassword'){

}elseif ($action == 'validateprofile'){

}elseif ($action == 'validateprofile'){

}else{
	header('Location: '.ROOTHTML);
}
?>