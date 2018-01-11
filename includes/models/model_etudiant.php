<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';
	include_once ROOTMODELS.'model_personne.php';

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
			$SQLQuery = 'SELECT evaluer.* FROM evaluer WHERE etu_id = :idetudiant AND cmod_id = :idcmod AND int_id = :idintervenant';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idcmod', $idcmod);
			$SQLStmt->bindValue(':idetudiant', $this->id);
			$SQLStmt->bindValue(':idintervenant', ContenuModule::getById($idcmod)->getModule()->getIntervenant()->getId());
			$SQLStmt->execute();

			$SQLRow = $SQLStmt->fetchObject();
			$retVal = $SQLRow->eval_acquis?'A':($SQLRow->eval_enacquisition?'EA':'NA');

			$SQLStmt->closeCursor();
			return $retVal;
		}

		public function hasRattrapages(){
		    $SQLQuery = 'SELECT COUNT(rat_id) FROM rattrapage INNER JOIN statutrattrapage ON rattrapage.statr_id = statutrattrapage.statr_id WHERE etu_id = :idetudiant AND statr_libelle = \'En cours\'';
		    $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		    $SQLStmt->bindValue(':idetudiant', $this->etu_id);
		    $SQLStmt->execute();
		    $SQLRow = $SQLStmt->fetch();

		    $retVal = $SQLRow[0];
		    $SQLStmt->closeCursor();
		    return ($retVal > 0);
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
			$SQLQuery .= 'INNER JOIN participer ON etudiant.etu_id = participer.etu_id ';
			$SQLQuery .= 'INNER JOIN promotion ON promotion.promo_id = etudiant.promo_id ';
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
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':idetudiant', $idEtudiant);
			$stmt->execute();
			$retVal = $stmt->fetchColumn(0);
			$stmt->closeCursor();
			return $retVal;
		}

        public static function getPromoById($idEtudiant){
			$SQLQuery = 'SELECT promo_id FROM etudiant WHERE etu_id = :idetudiant';
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':idetudiant', $idEtudiant);
			$stmt->execute();
			$retVal = $stmt->fetchColumn(0);
			$stmt->closeCursor();
			return $retVal;
		}

		public static function update($etudiant){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':nom', $etudiant->getNom());
			$stmt->bindValue(':prenom', $etudiant->getPrenom());
			$stmt->bindValue(':email', $etudiant->getEmail());
			$stmt->bindValue(':idpers', $etudiant->getPersId());
			$stmt->bindValue(':userid', (($etudiant->getUserAuth()->getId() != 0)?$etudiant->getUserAuth()->getId():null));
			if (!$stmt->execute()){
				var_dump($stmt->errorInfo());
			}

			$SQLQuery = "UPDATE etudiant SET etu_photo = :photo WHERE etu_id = :idetudiant";
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':photo', $etudiant->getPhoto());
			$stmt->bindValue(':idetudiant', $etudiant->getId());
			if (!$stmt->execute()){
				var_dump($stmt->errorInfo());
			}
		}

	}