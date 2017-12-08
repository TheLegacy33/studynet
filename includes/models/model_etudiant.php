<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';
	include_once ROOTMODELS.'Personne.php';

	class Etudiant extends Personne {
		private $id, $photo;

		public function __construct($id = 0, $nom, $prenom, $photo = '', $email = ''){
			parent::__construct($id, $nom, $prenom, $email);
			$this->id = $id;
			$this->photo = $photo;
		}

		public function getId(){
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
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->etu_photo, $SQLRow->pers_email);
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
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->etu_photo, $SQLRow->pers_email);
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
			$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->etu_photo, $SQLRow->pers_email);
			$newEtud->setPromo(Promotion::getById($SQLRow->promo_id));
			$SQLStmt->closeCursor();
			return $newEtud;
		}
	}