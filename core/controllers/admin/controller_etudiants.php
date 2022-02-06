<?php
$action = isset($_GET['a'])?$_GET['a']:'listeetudiants';
include_once ROOTMODELS . 'model_periodeformation.php';


function traitePhoto(Etudiant $etudiant, $fichiers){
	if (!empty($fichiers)){
		$photo = $fichiers['ttPhoto'];
		$newNomPhoto = 'photo_'.$etudiant->getNom().'_'.$etudiant->getPrenom().'.'.pathinfo($photo['name'], PATHINFO_EXTENSION);
		$pathFicPhoto = ROOTUPLOADS.$newNomPhoto;
		if (!move_uploaded_file($photo['tmp_name'], $pathFicPhoto)){
			var_dump("Une erreur est survenue lors de l'enregistrement de la photo.<br /> Veuillez réessayer plus tard.");
		}
		$etudiant->setPhoto($newNomPhoto);
	}else{
		$etudiant->setPhoto(null);
	}
}

$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
if ($action == 'listeetudiants') {
	$listeEtudiants = Etudiant::getListeFromPf($idPf);
	include_once ROOTVIEWS . 'view_listeetudiants.php';
}elseif ($action == 'ajoutetudiant'){
	$includeJs = true;
	$scriptname = ['js_etudiant.js', 'js_formscripts.js'];

	$pf = Periodeformation::getById($idPf);
	$etudiant = new Etudiant();

	if (!empty($_POST)){
		$etudiant->setNom(trim($_POST['ttNom']));
		$etudiant->setPrenom(trim($_POST['ttPrenom']));
		$etudiant->setEmail(trim($_POST['ttEmail']));
		DAO::getInstance()->beginTransaction();
		if (Etudiant::exists($etudiant)){
			$etudiant->populate();
			if ($etudiant->existsInPf($idPf)){
				DAO::getInstance()->rollBack();
			}else{
				if ($pf->addStudent($etudiant)){
					DAO::getInstance()->commit();
					header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
				}else{
					DAO::getInstance()->rollBack();
					var_dump("Erreur d'ajout de l'étudiant à la pf");
				}
			}
		}else{
			traitePhoto($etudiant, $_FILES);
			if (Etudiant::insert($etudiant)){
				if ($pf->addStudent($etudiant)){
					DAO::getInstance()->commit();
					header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
				}else{
					DAO::getInstance()->rollBack();
					var_dump("Erreur d'ajout de l'étudiant à la pf");
				}
			}else{
				DAO::getInstance()->rollBack();
				var_dump("Erreur d'enregistrement");
			}
		}
	}
	include_once ROOTVIEWS . 'view_ficheetudiant.php';
}elseif ($action == 'editetudiant'){
	$includeJs = true;
	$scriptname = ['js_etudiant.js', 'js_formscripts.js'];

	$idEtudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
	$etudiant = Etudiant::getById($idEtudiant);
	if (!empty($_POST)){
		$etudiant->setNom(trim($_POST['ttNom']));
		$etudiant->setPrenom(trim($_POST['ttPrenom']));
		$etudiant->setEmail(trim($_POST['ttEmail']));

		traitePhoto($etudiant, $_FILES);

		if (Etudiant::update($etudiant)){
			header('Location: index.php?p=periodesformation&a=listeetudiants&idpf='.$idPf);
		}else{
			var_dump("Erreur d'enregistrement");
		}
	}
	include_once ROOTVIEWS . 'view_ficheetudiant.php';
}elseif ($action == 'importetudiants'){
	$checked = false;
	if (!empty($_FILES)){
		$checked = isset($_POST['chkEntete'])? $_POST['chkEntete'][0] == 'on' : false;
		$fichier = $_FILES['ttFichier'];
		if (is_uploaded_file($fichier['tmp_name'])){
			$pf = Periodeformation::getById($idPf);

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
					$etudiant = new Etudiant();
					$etudiant->setNom(trim($infosLigne[0]));
					$etudiant->setPrenom(trim($infosLigne[1]));
					$etudiant->setEmail(trim((isset($infosLigne[2])?$infosLigne[2]:'')));

                    DAO::getInstance()->beginTransaction();
                    if (Etudiant::exists($etudiant)){
                    	$etudiant->populate();
                        if ($etudiant->existsInPf($idPf)){
                            $message .= "L'etudiant ".$etudiant." existe déjà dans cette période de formation!<br />";
							$nbErreurs++;
                            DAO::getInstance()->rollBack();
                        }else{
                            if ($pf->addStudent($etudiant)){
								$message .= "L'etudiant ".$etudiant." a été ajouté à cette période de formation!<br />";
                                DAO::getInstance()->commit();
                                $nbEtudiantImportes++;
                            }else{
                                DAO::getInstance()->rollBack();
                                $nbErreurs++;
                            }
                        }
                    }else{
                        if (Etudiant::insert($etudiant)){
                            if ($pf->addStudent($etudiant)){
                                DAO::getInstance()->commit();
                                $nbEtudiantImportes++;
                            }else{
                                DAO::getInstance()->rollBack();
                                $nbErreurs++;
                            }
                        }else{
                            DAO::getInstance()->rollBack();
                            $message .= "Erreur d'enregistrement de l'etudiant ".$etudiant;
                            $nbErreurs++;
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
	include_once ROOTVIEWS . 'view_formimport.php';
}else{
	header('Location: '.ROOTHTML);
}

?>