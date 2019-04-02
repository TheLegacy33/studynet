<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';
	include_once ROOTMODELS.'model_personne.php';
	include_once ROOTMODELS.'model_evaluation.php';
	include_once ROOTMODELS.'model_evaluationmodule.php';

	class Etudiant extends Personne {
		private $etu_id, $pf, $photo; //TODO : remplacer promo par pf

		public function __construct($id = 0, $nom = '', $prenom = '', $email = '', $photo = '', $idPers = 0){
			parent::__construct($idPers, $nom, $prenom, $email);
			$this->etu_id = $id;
			$this->photo = $photo;
			$this->promo = null;
		}

		public function getId(){
			return $this->etu_id;
		}

		public function getPersId(){
			return $this->id;
		}

		public function getPhoto(){
			return $this->photo;
		}

		public function getPf(){
			return $this->pf;
		}

		public function setId($id){
			$this->etu_id = $id;
		}

		public function setPersId($id){
			parent::setId($id);
		}

		public function setPf(Periodeformation $pf){
			$this->pf = $pf;
		}

		public function setPhoto($photo){
		    $this->photo = $photo;
        }

		public function clonepers(Personne $etudToClone){
            parent::clonepers($etudToClone);
            $this->setPf($etudToClone->getPf());
            $this->setPhoto($etudToClone->getPhoto());
        }

        public function getEvaluationContenuModule($idcmod){
			$idIntervenant = ContenuModule::getById($idcmod)->getModule()->getIntervenant()->getId();
			$SQLQuery = 'SELECT evaluer.* FROM evaluer WHERE etu_id = :idetudiant AND cmod_id = :idcmod AND int_id = :idintervenant';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idcmod', $idcmod);
			$SQLStmt->bindValue(':idetudiant', $this->etu_id);
			$SQLStmt->bindValue(':idintervenant', $idIntervenant);
			$SQLStmt->execute();
			if ($SQLStmt->rowCount() == 0){
				$newEvaluation = new Evaluation($this->etu_id, $idIntervenant, $idcmod);
				Evaluation::insert($newEvaluation);
				$retVal = 'NA';
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$retVal = $SQLRow->eval_acquis?'A':($SQLRow->eval_enacquisition?'EA':'NA');
			}

			$SQLStmt->closeCursor();
			return $retVal;
		}

		public function hasRattrapages(){
		    $SQLQuery = 'SELECT COUNT(rat_id) FROM rattrapage INNER JOIN statutrattrapage ON rattrapage.statr_id = statutrattrapage.statr_id WHERE etu_id = :idetudiant'; //' AND statr_libelle = \'En cours\'';
		    $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		    $SQLStmt->bindValue(':idetudiant', $this->etu_id);
		    $SQLStmt->execute();
		    $SQLRow = $SQLStmt->fetch();

		    $retVal = $SQLRow[0];
		    $SQLStmt->closeCursor();
		    return ($retVal > 0);
        }

        public function getModulesCount($idPf, $idModule = 0){
		    $SQLQuery = 'SELECT COUNT(participer.mod_id) FROM participer INNER JOIN module ON participer.mod_id = module.mod_id ';
		    $SQLQuery .= 'WHERE module.pf_id = :idpf ';
		    $SQLQuery .= 'AND etu_id = :idetudiant ';
		    if ($idModule != 0){
		        $SQLQuery .= 'AND participer.mod_id = :idmodule';
            }
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idetudiant', $this->etu_id);
            $SQLStmt->bindValue(':idpf', $idPf);
            if ($idModule != 0){
                $SQLStmt->bindValue(':idmodule', $idModule);
            }
            $SQLStmt->execute();
            $SQLRow = $SQLStmt->fetch();

            $retVal = $SQLRow[0];
            $SQLStmt->closeCursor();
            return ($retVal);
        }

        public function setModuleParticipation($idPf, $idModule, $participe){
		    if ($idModule == 0 OR $idPf == 0){
                $retVal = false;
            }else{
		        if ($participe){
                    if ($this->getModulesCount($idPf, $idModule) == 0){
                        //Je rajoute l'étudiant à ce module
                        $retVal = $this->addToModule($idModule);
                    }else{
                        var_dump("Etudiant déjà inscrit pour ce module !");
                        $retVal = true;
                    }
                }else{
                    if ($this->getModulesCount($idPf, $idModule) != 0){
                        //Il est déjà inscrit, je le retire si il n'a pas d'évaluation sur ce module
                        $listeModules = EvaluationModule::getListeFromStudentForModule($idModule, $this->etu_id);
                        if (!empty($listeModules)){
                            var_dump("L'étudiant a des évaluations sur ce module !");
                            $retVal = false;
                        }else{
                            $retVal = $this->removeFromModule($idModule);
                        }
                    }else{
                        var_dump("L'etudiant n'était pas inscrit à ce module !");
                        return true;
                    }
                }
            }
            return $retVal;
        }

        public function addToModule($idModule){
            $SQLQuery = 'INSERT INTO participer(etu_id, mod_id) VALUES (:idetudiant, :idmodule)';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idetudiant', $this->etu_id);
            $SQLStmt->bindValue(':idmodule', $idModule);
            if (!$SQLStmt->execute()){
                var_dump($SQLStmt->errorInfo());
                return false;
            }else{
                return true;
            }
        }

        public function removeFromModule($idModule){
            $SQLQuery = 'DELETE FROM participer WHERE etu_id = :idetudiant AND mod_id = :idmodule';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idetudiant', $this->etu_id);
            $SQLStmt->bindValue(':idmodule', $idModule);
            if (!$SQLStmt->execute()){
                var_dump($SQLStmt->errorInfo());
                return false;
            }else{
                return true;
            }
        }

        public static function exists($etudiant){
			$SQLQuery = "SELECT etu_id FROM personne INNER JOIN etudiant ON personne.pers_id = etudiant.pers_id ";
			$SQLQuery .= "WHERE UPPER(pers_nom) = UPPER(:nometudiant) AND UPPER(pers_prenom) = UPPER(:prenometudiant)";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':nometudiant', $etudiant->getNom());
			$SQLStmt->bindValue(':prenometudiant', $etudiant->getPrenom());
			$SQLStmt->execute();
			$retVal = $SQLStmt->rowCount();
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListe($critere = Etudiant::class){
			return parent::getListe($critere);
		}

		public static function getListeFromModule($idModule){
            $SQLQuery = "SELECT * FROM participer ";
            $SQLQuery .= "WHERE mod_id = :idmodule";
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idmodule', $idModule);
            $SQLStmt->execute();
            $retVal = array();
            while ($SQLRow = $SQLStmt->fetchObject()){
                $newEtud = Etudiant::getById($SQLRow->etu_id);
                $retVal[] = $newEtud;
            }
            $SQLStmt->closeCursor();
            return $retVal;
        }

		public static function getListeFromPromo($idPromo = 0){ //TODO Adapter selon la PF
			$SQLQuery = "SELECT * FROM etudiant INNER JOIN personne ON etudiant.pers_id = personne.pers_id";
			if ($idPromo != 0){
				$SQLQuery .= " WHERE promo_id = :idpromo";
			}
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpromo', $idPromo);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->etu_photo, $SQLRow->pers_id);
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListeFromPf($idPf = 0){
			if ($idPf == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM etudiant INNER JOIN personne ON etudiant.pers_id = personne.pers_id ';
			$SQLQuery .= 'INNER JOIN integrer ON etudiant.etu_id = integrer.etu_id ';
 			$SQLQuery .= 'WHERE pf_id = :idpf ORDER BY pers_nom, pers_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpf', $idPf);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->etu_photo, $SQLRow->pers_id);
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLQuery = 'SELECT * FROM etudiant INNER JOIN personne ON etudiant.pers_id = personne.pers_id ';
			$SQLQuery .= 'INNER JOIN integrer ON etudiant.etu_id = integrer.etu_id ';
			$SQLQuery .= "WHERE etu_id = :idetudiant";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idetudiant', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->etu_photo, $SQLRow->pers_id);
			$newEtud->fillAuth(User::getById($SQLRow->us_id));
			$newEtud->setPf(Periodeformation::getById($SQLRow->pf_id));
			$SQLStmt->closeCursor();
			return $newEtud;
		}

		public static function getIdByIdPers($idPers){
		    $SQLQuery = 'SELECT etu_id FROM etudiant WHERE pers_id = :idpers';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idpers', $idPers);
            $SQLStmt->execute();
            $retVal = $SQLStmt->fetchColumn(0);
            $SQLStmt->closeCursor();
            return $retVal;
        }

        public static function getPhotoById($idEtudiant){
			$SQLQuery = 'SELECT etu_photo FROM etudiant WHERE etu_id = :idetudiant';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->execute();
			$retVal = $SQLStmt->fetchColumn(0);
            $SQLStmt->closeCursor();
			return $retVal;
		}

		public static function update($etudiant){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':nom', $etudiant->getNom());
            $SQLStmt->bindValue(':prenom', $etudiant->getPrenom());
            $SQLStmt->bindValue(':email', $etudiant->getEmail());
            $SQLStmt->bindValue(':idpers', $etudiant->getPersId());
            $SQLStmt->bindValue(':userid', (($etudiant->getUserAuth()->getId() != 0)?$etudiant->getUserAuth()->getId():null));
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				$SQLQuery = "UPDATE etudiant SET etu_photo = :photo WHERE etu_id = :idetudiant";
                $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
                $SQLStmt->bindValue(':photo', $etudiant->getPhoto());
                $SQLStmt->bindValue(':idetudiant', $etudiant->getId());
				if (!$SQLStmt->execute()){
					var_dump($SQLStmt->errorInfo());
					return false;
				}
			}
			return true;
		}

		public static function insert($etudiant, $pf){
			$SQLQuery1 = "INSERT INTO personne(pers_nom, pers_prenom, pers_email) VALUES (:nom, :prenom, :email)";
			$SQLStmt1 = DAO::getInstance()->prepare($SQLQuery1);
            $SQLStmt1->bindValue(':nom', $etudiant->getNom());
            $SQLStmt1->bindValue(':prenom', $etudiant->getPrenom());
            $SQLStmt1->bindValue(':email', $etudiant->getEmail());

			DAO::getInstance()->beginTransaction();
			if (!$SQLStmt1->execute()) {
				var_dump($SQLStmt1->errorInfo());
				DAO::getInstance()->rollBack();
				return false;
			}else{
				$etudiant->setPersId(DAO::getInstance()->lastInsertId());
				$SQLQuery2 = "INSERT INTO etudiant(etu_photo, pers_id) VALUES (:photo, :idpromo, :idpers)";
				$SQLStmt2 = DAO::getInstance()->prepare($SQLQuery2);
                $SQLStmt2->bindValue(':photo', (!is_null($etudiant->getPhoto())?$etudiant->getPhoto():null));
                $SQLStmt2->bindValue(':idpers', $etudiant->getPersId());
				if (!$SQLStmt2->execute()) {
					var_dump($SQLStmt2->errorInfo());
					DAO::getInstance()->rollBack();
					return false;
				}else{
					$etudiant->setId(DAO::getInstance()->lastInsertId());

					//Je rajoute l'étudiant à la pf
					$SQLQuery3 = "INSERT INTO integrer (etu_id, pf_id) VALUES (:idetudiant, :idPf)";
					$SQLStmt3 = DAO::getInstance()->prepare($SQLQuery3);
					$SQLStmt3->bindValue(':idetudiant', $etudiant->getId());
					$SQLStmt3->bindValue(':idPf', $pf->getId());
					if (!$SQLStmt3->execute()) {
						var_dump($SQLStmt3->errorInfo());
						DAO::getInstance()->rollBack();
						return false;
					}else{
						$SQLQuery4 = "INSERT INTO appreciationGenerale(etu_id, pf_id) VALUES (:idetudiant, :idPf)";
						$SQLStmt4 = DAO::getInstance()->prepare($SQLQuery4);
						$SQLStmt4->bindValue(':idetudiant', $etudiant->getId());
						$SQLStmt4->bindValue(':idPf', $pf->getId());
						if (!$SQLStmt4->execute()){
							var_dump($SQLStmt4->errorInfo());
							DAO::getInstance()->rollBack();
							return false;
						}else{
							DAO::getInstance()->commit();
							return true;
						}
					}
				}
			}
		}
	}