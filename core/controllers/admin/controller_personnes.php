<?php
	/**
	 * @var $action
	 */
include_once ROOTMODELS . 'model_authentification.php';

if ($action == 'listepersonnes'){
	$includeJs = true;
	$scriptname[] = 'js_listepersonnes.js';

	$type = (isset($_GET['type']))?$_GET['type']:'*';
	if ($type == '*' OR $type == 'administrateur' OR $type == 'visiteur'){
		$typeToGet = $type;
	}else{
		$typeToGet = array($type);
	}
    $listePersonnes = Personne::getListe($typeToGet);
    include_once ROOTVIEWS . 'view_listepersonnes.php';
}elseif ($action == 'editprofile'){
	$idPersonne = isset($_GET['idpersonne'])?$_GET['idpersonne']:0;
	if ($idPersonne == 0){
		if (!empty($_POST)){
			var_dump($_POST);
		}else{
			header('Location: '.ROOTHTML.'/index.php?p=personnes&a=listepersonnes');
		}
	}else{
		$includeJs = true;
		$scriptname = ['js_personne.js']; //, 'js_formscripts.js'];

		$personne = Personne::getById($idPersonne);

		if (!empty($_POST)){
			$newLogin = trim(isset($_POST['ttLogin'])?$_POST['ttLogin']:'');
			$newPassword = trim(isset($_POST['ttPassword'])?$_POST['ttPassword']:'');
			$newNom = trim($_POST['ttNom']);
			$newPrenom = trim($_POST['ttPrenom']);
			$newEmail = trim($_POST['ttEmail']);

			if ($personne->getUserAuth()->getLogin() != $newLogin OR
				$personne->getUserAuth()->getPassword() != $newPassword OR
				$personne->getNom() != $newNom OR
				$personne->getPrenom() != $newPrenom OR
				$personne->getEmail() != $newEmail OR
				!empty($_FILES)){

				//Traitement des information d'authentification
				if (($personne->getUserAuth()->getLogin() != $newLogin AND $newLogin != '') OR
					($personne->getUserAuth()->getPassword() != $newPassword AND $newPassword != '')){

					$personne->getUserAuth()->setLogin($newLogin);
					$personne->getUserAuth()->setPassword($newPassword);
					if ($personne->getUserAuth()->getId() == 0){
						User::insert($personne->getUserAuth());
					}else{
						User::update($personne->getUserAuth());
					}
				}

				//Traitement des informations de la personne
				$personne->setNom($newNom);
				$personne->setPrenom($newPrenom);
				$personne->setEmail($newEmail);

				if ($personne->estEtudiant() AND !empty($_FILES)){
					$photo = $_FILES['ttPhoto'];
					$newNomPhoto = 'photo_'.$personne->getNom().'_'.$personne->getPrenom().'.'.pathinfo($photo['name'], PATHINFO_EXTENSION);
					$pathFicPhoto = ROOTUPLOADS.$newNomPhoto;
					if (!move_uploaded_file($photo['tmp_name'], $pathFicPhoto)){
						$message = "Une erreur est survenue lors de l'enregistrement de votre photo.<br /> Veuillez réessayer plus tard.";
					}
					$personne->setPhoto($newNomPhoto);
					Etudiant::update($personne);
				}else{
					Personne::update($personne);
				}
				header('Location: '.ROOTHTML.'/index.php?p=personnes&a=listepersonnes');
			}
		}
		include_once ROOTVIEWS . 'view_fichepersonne.php';
	}
}elseif ($action == 'subscribe'){

}elseif ($action == 'validateprofile'){

}elseif ($action == 'deletepersonne'){
	$idPersonne = isset($_GET['idpersonne'])?$_GET['idpersonne']:0;
	if ($idPersonne != 0){
		$personne = Personne::getById($idPersonne);
		var_dump($personne);
	}
	header('Location: '.ROOTHTML.'/index.php?p=personnes&a=listepersonnes');
}else{
	header('Location: '.ROOTHTML);
}
?>