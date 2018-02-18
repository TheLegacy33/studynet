<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';
	include_once ROOTMODELS.'model_personne.php';
	include_once ROOTMODELS.'model_evaluation.php';

	class Etudiant extends Personne {
		private $etu_id, $promo, $photo;

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

		public function getPromo(){
			return $this->promo;
		}

		public function setId($id){
			$this->etu_id = $id;
		}

		public function setPersId($id){
			parent::setId($id);
		}

		public function setPromo($promo){
			$this->promo = $promo;
		}

		public function setPhoto($photo){
		    $this->photo = $photo;
        }

		public function clonepers($etudToClone){
            parent::clonepers($etudToClone);
            $this->setPromo(Promotion::getById(Etudiant::getPromoById($this->etu_id)));
            $this->setPhoto(Etudiant::getPhotoById($this->etu_id));
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

		public static function getListeFromPromo($idPromo = 0){
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
			$SQLQuery .= 'INNER JOIN promotion ON etudiant.promo_id = promotion.promo_id ';
			$SQLQuery .= 'INNER JOIN periodeformation ON promotion.promo_id = periodeformation.promo_id ';
 			$SQLQuery .= 'WHERE pf_id = :idpf ORDER BY pers_nom, pers_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpf', $idPf);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->etu_photo, $SQLRow->pers_id);
				$newEtud->setPromo(Promotion::getById($SQLRow->promo_id));
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM etudiant INNER JOIN personne ON etudiant.pers_id = personne.pers_id WHERE etu_id = :idetudiant");
			$SQLStmt->bindValue(':idetudiant', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->etu_photo, $SQLRow->pers_id);
			$newEtud->setPromo(Promotion::getById($SQLRow->promo_id));
			$SQLStmt->closeCursor();
			return $newEtud;
		}

		public static function getIdByIdPers($idPers){
		    $SQLQuery = 'SELECT etu_id FROM etudiant WHERE pers_id = :idpers';
            $stmt = DAO::getInstance()->prepare($SQLQuery);
            $stmt->bindValue(':idpers', $idPers);
            $stmt->execute();
            $retVal = $stmt->fetchColumn(0);
            $stmt->closeCursor();
            return $retVal;
        }

        public static function getPhotoById($idEtudiant){
			$SQLQuery = 'SELECT etu_photo FROM etudiant WHERE etu_id = :idetudiant';
			$SQLstmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLstmt->bindValue(':idetudiant', $idEtudiant);
			$SQLstmt->execute();
			$retVal = $SQLstmt->fetchColumn(0);
			$SQLstmt->closeCursor();
			return $retVal;
		}

        public static function getPromoById($idEtudiant){
			$SQLQuery = 'SELECT promo_id FROM etudiant WHERE etu_id = :idetudiant';
			$SQLstmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLstmt->bindValue(':idetudiant', $idEtudiant);
			$SQLstmt->execute();
			$retVal = $SQLstmt->fetchColumn(0);
			$SQLstmt->closeCursor();
			return $retVal;
		}

		public static function update($etudiant){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
			$SQLstmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLstmt->bindValue(':nom', $etudiant->getNom());
			$SQLstmt->bindValue(':prenom', $etudiant->getPrenom());
			$SQLstmt->bindValue(':email', $etudiant->getEmail());
			$SQLstmt->bindValue(':idpers', $etudiant->getPersId());
			$SQLstmt->bindValue(':userid', (($etudiant->getUserAuth()->getId() != 0)?$etudiant->getUserAuth()->getId():null));
			if (!$SQLstmt->execute()){
				var_dump($SQLstmt->errorInfo());
				return false;
			}else{
				$SQLQuery = "UPDATE etudiant SET etu_photo = :photo WHERE etu_id = :idetudiant";
				$SQLstmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLstmt->bindValue(':photo', $etudiant->getPhoto());
				$SQLstmt->bindValue(':idetudiant', $etudiant->getId());
				if (!$SQLstmt->execute()){
					var_dump($SQLstmt->errorInfo());
					return false;
				}
			}
			return true;
		}

		public static function insert($etudiant, $pf){
			$SQLQuery1 = "INSERT INTO personne(pers_nom, pers_prenom, pers_email) VALUES (:nom, :prenom, :email)";
			$SQLstmt1 = DAO::getInstance()->prepare($SQLQuery1);
			$SQLstmt1->bindValue(':nom', $etudiant->getNom());
			$SQLstmt1->bindValue(':prenom', $etudiant->getPrenom());
			$SQLstmt1->bindValue(':email', $etudiant->getEmail());

			DAO::getInstance()->beginTransaction();
			if (!$SQLstmt1->execute()) {
				var_dump($SQLstmt1->errorInfo());
				DAO::getInstance()->rollBack();
				return false;
			}else{
				$etudiant->setPersId(DAO::getInstance()->lastInsertId());
				$SQLQuery2 = "INSERT INTO etudiant(etu_photo, promo_id, pers_id) VALUES (:photo, :idpromo, :idpers)";
				$SQLstmt2 = DAO::getInstance()->prepare($SQLQuery2);
				$SQLstmt2->bindValue(':photo', (!is_null($etudiant->getPhoto())?$etudiant->getPhoto():null));
				$SQLstmt2->bindValue(':idpromo', $etudiant->getPromo()->getId());
				$SQLstmt2->bindValue(':idpers', $etudiant->getPersId());
				if (!$SQLstmt2->execute()) {
					var_dump($SQLstmt2->errorInfo());
					DAO::getInstance()->rollBack();
					return false;
				}else{
					$etudiant->setId(DAO::getInstance()->lastInsertId());
					$SQLQuery3 = "INSERT INTO appreciationGenerale(etu_id, pf_id) VALUES (:idetudiant, :idPf)";
					$SQLStmt3 = DAO::getInstance()->prepare($SQLQuery3);
					$SQLStmt3->bindValue(':idetudiant', $etudiant->getId());
					$SQLStmt3->bindValue(':idPf', $pf->getId());
					if (!$SQLStmt3->execute()) {
						var_dump($SQLStmt3->errorInfo());
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