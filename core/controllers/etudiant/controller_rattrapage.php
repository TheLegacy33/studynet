<?php
include_once ROOTMODELS . 'model_rattrapage.php';
/**
 * @var $user
 * @var $action
 */
if (!file_exists(ROOTUPLOADS.'/sujets/')){
	mkdir(ROOTUPLOADS.'/sujets/', 0755, true);
	@chmod(ROOTUPLOADS.'/sujets/', 0777);
}

if (!file_exists(ROOTUPLOADS.'/reponses/')){
	mkdir(ROOTUPLOADS.'/reponses/', 0755, true);
	@chmod(ROOTUPLOADS.'/reponses/', 0777);
}

define('ROOTHTMLSUJETS', ROOTHTMLUPLOADS.'sujets/');
define('ROOTUPLOADSSUJETS', ROOTUPLOADS.'/sujets/');

define('ROOTHTMLREPONSES', ROOTHTMLUPLOADS.'reponses/');
define('ROOTUPLOADSREPONSES', ROOTUPLOADS.'reponses/');

$idEtudiant = $user->getId();
$idRattrapage = isset($_GET['idrattrapage'])?$_GET['idrattrapage']:0;

if ($action == 'listeforetudiant'){
	$includeJs = true;
	$scriptname = ['js_listerattrapages.js', 'js_formscripts.js'];

	$listeRattrapage = Rattrapage::getListeForEtudiant($idEtudiant);
	if (count($listeRattrapage) > 0){

		//Vérification de la validité du rattrapage
		foreach ($listeRattrapage as $rattrapage){
			//var_dump($rattrapage);
			if ($rattrapage->expired() AND (!$rattrapage->uploaded() OR $rattrapage->getStatut() == StatutRattrapage::getByLibelle('En cours'))){

				$rattrapage->setStatut(StatutRattrapage::getByLibelle('Expiré'));
				Rattrapage::update($rattrapage);
			}
		}

		include_once ROOTVIEWS . 'view_listerattrapages.php';
	}
}elseif ($action == 'getsujet'){
	if (!Rattrapage::existsForStudent($idRattrapage, $user->getId())){
		header('Location: '.ROOTHTML);
	}
	$rattrapage = Rattrapage::getById($idRattrapage);
	if ($rattrapage->downloaded()){
		//Le sujet a déjà été récupéré : affichage de la date et message
		$firstdld = false;
		try{
			$dateRecup = new DateTime($rattrapage->getDateRecup());
		}catch (Exception $e){
			$dateRecup = $rattrapage->getDateRecup();
		}
		$dateRenduAttendue = $dateRecup->add(new DateInterval($rattrapage->getDelai()->getInterval()))->add(new DateInterval('PT1M'))->format('Y-m-d H:i:00');
	}else{
		$firstdld = true;
		$includeJs = true;
		$scriptname = ['js_rattrapages.js', 'js_formscripts.js'];

		//Récupération du sujet : affichage du message d'avertissement et mise à disposition du lien avec timer pour le téléchargement
		$DTNow = new DateTime('now');
		$rattrapage->setDateRecup($DTNow->format('Y-m-d H:i:00'));
		$DTRecup = new DateTime($rattrapage->getDateRecup());
		$dateRenduAttendue = $DTRecup->add(new DateInterval($rattrapage->getDelai()->getInterval()))->add(new DateInterval('PT1M'))->format('Y-m-d H:i:00');

		//Update de l'enregistrement avec la date de téléchargement active
		Rattrapage::update($rattrapage);
	}
	include_once ROOTVIEWS . 'view_downloadsujetrattrapage.php';
}elseif ($action == 'postreponse'){
	if (!Rattrapage::existsForStudent($idRattrapage, $user->getId())){
		header('Location: '.ROOTHTML);
	}
	$rattrapage = Rattrapage::getById($idRattrapage);
	$cansend = true;
	if ($rattrapage->uploaded()){
		$cansend = false;
		$message = "Vous avez déjà transmis votre réponse pour ce sujet de rattrapage !";
	}else{
		if (!empty($_FILES)){
			//Sauvegarde des informations du rattrapage et traitement de l'upload du fichier
			$fichierEtudiant = $_FILES['ttFicRetour'];
			if ($fichierEtudiant['name'] == '' OR $fichierEtudiant['size'] == 0 OR $fichierEtudiant['error'] == 4){
				$message = "Vous n'avez transmis aucun fichier ou votre fichier est vide !";
			}else{
				$DTNow = new DateTime('now');
				$newNomFicRendu = 'rendu_'.$user->getNom().'_'.$user->getPrenom().'_'.$DTNow->format('Ymd_His').'.'.pathinfo($fichierEtudiant['name'], PATHINFO_EXTENSION);
				$pathFicRendu = ROOTUPLOADSREPONSES.$newNomFicRendu;

				if (!move_uploaded_file($fichierEtudiant['tmp_name'], $pathFicRendu)){
					$message = "Une erreur est survenue lors de l'enregistrement de votre fichier.<br /> Veuillez réessayer ou transmettre directement à l'intervenant en charge du rattrapage de ce module";
				}else{

					$rattrapage->setDateRendu($DTNow->format('Y-m-d H:i:s'));
					$rattrapage->setFicRetour($newNomFicRendu);
					$rattrapage->setStatut(StatutRattrapage::getByLibelle('Terminé'));
					$rattrapage->setMd5Retour(md5_file($pathFicRendu));
					Rattrapage::update($rattrapage);

					$message = "Votre document a bien été transmis !";
					$cansend = false;
				}
			}
		}
	}
	include_once ROOTVIEWS . 'view_formrendurattrapage.php';
}else{
	header('Location: '.ROOTHTML);
}
?>