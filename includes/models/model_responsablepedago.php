<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS . 'model_personne.php';

	class ResponsablePedago extends Personne {
		private $resp_id;

		public function __construct($id = 0, $nom = '', $prenom = '', $email = '', $idPers = 0){
			parent::__construct($idPers, $nom, $prenom, $email);
			$this->resp_id = $id;
		}

		public function getId(){
			return $this->resp_id;
		}

		public function getPersId(){
			return parent::getId();
		}

        public static function getIdByIdPers($idPers){
            $SQLQuery = 'SELECT resp_id FROM responsablePedago WHERE pers_id = :idpers';
            $stmt = DAO::getInstance()->prepare($SQLQuery);
            $stmt->bindValue(':idpers', $idPers);
            $stmt->execute();
            $retVal = $stmt->fetchColumn(0);
            $stmt->closeCursor();
            return $retVal;
        }

        public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM responsablePedago INNER JOIN personne ON responsablePedago.pers_id = personne.pers_id WHERE resp_id = :idresponsable");
			$SQLStmt->bindValue(':idresponsable', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newInterv = new ResponsablePedago($SQLRow->resp_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
			$SQLStmt->closeCursor();
			return $newInterv;
		}

		public static function update($resppeda){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':nom', $resppeda->getNom());
			$stmt->bindValue(':prenom', $resppeda->getPrenom());
			$stmt->bindValue(':email', $resppeda->getEmail());
			$stmt->bindValue(':idpers', $resppeda->getPersId());
			$stmt->bindValue(':userid', (($resppeda->getUserAuth()->getId() != 0)?$resppeda->getUserAuth()->getId():null));
			if (!$stmt->execute()){
				var_dump($stmt->errorInfo());
			}
		}
	}