<?php
$action = isset($_GET['a'])?$_GET['a']:'listemodules';
include_once ROOTMODELS.'model_periodeformation.php';

$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
if ($action == 'listeetudiants') {
	$listeEtudiants = Etudiant::getListeFromPf($idPf);
	include_once ROOTVIEWS.'view_listeetudiants.php';
}elseif ($action == 'ajoutetudiant'){
	$includeJs = true;
	$scriptname[] = 'js_etudiant.js';
	$pf = Periodeformation::getById($idPf);
	$etudiant = new Etudiant();
	if (!empty($_POST)){
		$etudiant->setNom(trim($_POST['ttNom']));
		$etudiant->setPrenom(trim($_POST['ttPrenom']));
		$etudiant->setEmail(trim($_POST['ttEmail']));
		$etudiant->setPromo(Promotion::getByIdPf($idPf));
		if (!empty($_FILES)){
			$photo = $_FILES['ttPhoto'];
			$newNomPhoto = 'photo_'.$personne->getNom().'_'.$personne->getPrenom().'.'.pathinfo($photo['name'], PATHINFO_EXTENSION);
			$pathFicPhoto = ROOTUPLOADS.$newNomPhoto;
			if (!move_uploaded_file($photo['tmp_name'], $pathFicPhoto)){
				$message = "Une erreur est survenue lors de l'enregistrement de la photo.<br /> Veuillez réessayer plus tard.";
			}
			$etudiant->setPhoto($newNomPhoto);
		}else{
			$etudiant->setPhoto(null);
		}
		if (Etudiant::insert($etudiant, $pf)){
			header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS.'view_ficheetudiant.php';
}elseif ($action == 'editetudiant'){
	$includeJs = true;
	$scriptname[] = 'js_etudiant.js';
	$idEtudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
	$etudiant = Etudiant::getById($idEtudiant);
	$listeIntervenants = Intervenant::getListe(Intervenant::class);
	if (!empty($_POST)){
		$etudiant->setNom(trim($_POST['ttNom']));
		$etudiant->setPrenom(trim($_POST['ttPrenom']));
		$etudiant->setEmail(trim($_POST['ttEmail']));

		if (!empty($_FILES)) {
			$photo = $_FILES['ttPhoto'];
			$newNomPhoto = 'photo_'.$personne->getNom().'_'.$personne->getPrenom().'.'.pathinfo($photo['name'], PATHINFO_EXTENSION);
			$pathFicPhoto = ROOTUPLOADS . $newNomPhoto;
			if (!move_uploaded_file($photo['tmp_name'], $pathFicPhoto)) {
				$message = "Une erreur est survenue lors de l'enregistrement de la photo.<br /> Veuillez réessayer plus tard.";
			}
			$etudiant->setPhoto($newNomPhoto);
		}else{
			$etudiant->setPhoto(null);
		}

		if (Etudiant::update($etudiant)){
			header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS.'view_ficheetudiant.php';
}elseif ($action == 'importetudiants'){
	$checked = false;
	if (!empty($_FILES)){
		$checked = isset($_POST['chkEntete'])?(($_POST['chkEntete'][0] == 'on')?true:false):false;
		$fichier = $_FILES['ttFichier'];
		if (is_uploaded_file($fichier['tmp_name'])){
			$pf = Periodeformation::getById($idPf);
			$promo = Promotion::getByIdPf($idPf);

			$contenuFichier = file($fichier['tmp_name'], FILE_IGNORE_NEW_LINES);
			$message = '';
			$nbEtudiantImportes = 0;
			$nbErreurs = 0;
			foreach ($contenuFichier as $numligne => $ligne){
				if ($checked AND $numligne == 0){
					continue;
				}
				$infosLigne = explode(';', $ligne);
				if (count($infosLigne) < 2 OR count($infosLigne) > 3){
					$message .= 'Erreur sur la ligne '.($numligne + 1).' : '.$ligne.'<br />';
					$nbErreurs++;
				}else{
					$etudiant = new Etudiant(0, $infosLigne[0], $infosLigne[1], (isset($infosLigne[2])?$infosLigne[2]:''));
					$etudiant->setPromo($promo);
					if (Etudiant::exists($etudiant)){
						$message .= "L'etudiant ".$etudiant." existe déjà !<br />";
						$nbErreurs++;
					}else{
						if (!Etudiant::insert($etudiant, $pf)){
							$message .= "Erreur d'enregistrement de l'etudiant ".$etudiant;
							$nbErreurs++;
						}else{
							$nbEtudiantImportes++;
						}
					}
				}
			}
			if ($nbErreurs == 0){
				header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
			}
		}else{
			$message = "Erreur lors de l'enregistrement du fichier !<br />Veuillez essayer à nouveau ou contacter l'administrateur de l'application.";
		}

	}
	$urlRetour = '<a href="index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf.'" title="Retour à la liste des étudiants"><< Retour</a>';
	$formatAttendu = 'Le format attendu est un fichier CSV dont les valeurs sont séparées par des point-virgules avec une ligne par étudiant.<br />';
	$formatAttendu .= 'Exemple : <code><i>nom</i>;<i>prenom</i>;<i>email</i></code>';
	$formatAttendu .= '<p class="text-danger">Attention à respecter l\'ordre et le format demandé !<br />';
	$formatAttendu .= 'L\'email n\'étant pas obligatoire, il faut tout de même laisser le point-virgule après le prénom !</p>';
	include_once ROOTVIEWS.'view_formimport.php';
}else{
	header('Location: '.ROOTHTML);
}

?>