<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';
	class Etudiant {
		private $id, $nom, $prenom, $photo;

		public function __construct($id = 0, $nom, $prenom, $photo = ''){
			$this->id = $id;
			$this->nom = $nom;
			$this->prenom = $prenom;
			$this->photo = $photo;
		}

		public function getId(){
			return $this->id;
		}

		public function getNom(){
			return $this->nom;
		}

		public function getPhoto(){
			return $this->photo;
		}

		public function getPrenom(){
			return $this->prenom;
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
			if ($idPromo == 0){
				$SQLQuery = "SELECT * FROM etudiant";
			}else{
				$SQLQuery = "SELECT * FROM etudiant WHERE promo_id = :idpromo";
			}
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpromo', $idPromo);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->etu_nom, $SQLRow->etu_prenom, $SQLRow->etu_photo);
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListeFromPf($idPf = 0){
			if ($idPf == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM etudiant INNER JOIN participer ON etudiant.etu_id = participer.etu_id ';
			$SQLQuery .= 'INNER JOIN promotion ON promotion.promo_id = etudiant.promo_id ';
 			$SQLQuery .= 'WHERE pf_id = :idpf ORDER BY etu_nom, etu_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpf', $idPf);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->etu_nom, $SQLRow->etu_prenom, $SQLRow->etu_photo);
				$newEtud->setPromo(Promotion::getById($SQLRow->promo_id));
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM etudiant WHERE etu_id = :idetudiant");
			$SQLStmt->bindValue(':idetudiant', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newEtud = new Etudiant($SQLRow->etu_id, $SQLRow->etu_nom, $SQLRow->etu_prenom, $SQLRow->etu_photo);
			$newEtud->setPromo(Promotion::getById($SQLRow->promo_id));
			$SQLStmt->closeCursor();
			return $newEtud;
		}
	}