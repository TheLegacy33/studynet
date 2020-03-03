<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_personne.php';

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

		public static function getListe($critere = '*'){
			$SQLQuery = 'SELECT * FROM personne ';
			$SQLQuery .= 'INNER JOIN responsablePedago ON personne.pers_id = responsablePedago.pers_id ';
			$SQLQuery .= 'ORDER BY pers_nom, pers_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->execute();

			$retVal = array();

			while ($SQLRow = $SQLStmt->fetchObject()){
				$idResp = ResponsablePedago::getIdByIdPers($SQLRow->pers_id);
				$newPers = new ResponsablePedago($idResp, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
				$newPers->fillAuth(User::getById($SQLRow->us_id));
				$retVal[] = $newPers;
			}

			$SQLStmt->closeCursor();
			return $retVal;
		}

        public static function getIdByIdPers($idPers){
            $SQLQuery = 'SELECT resp_id FROM responsablePedago WHERE pers_id = :idpers';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idpers', $idPers);
            $SQLStmt->execute();
            $retVal = $SQLStmt->fetchColumn(0);
            $SQLStmt->closeCursor();
            return $retVal;
        }

        public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM responsablePedago INNER JOIN personne ON responsablePedago.pers_id = personne.pers_id WHERE resp_id = :idresponsable");
			$SQLStmt->bindValue(':idresponsable', $id);
			$SQLStmt->execute();
			if ($SQLStmt->rowCount() == 0){
                $newRespPeda = new ResponsablePedago();
            }else{
                $SQLRow = $SQLStmt->fetchObject();
                $newRespPeda = new ResponsablePedago($SQLRow->resp_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
                $newRespPeda->fillAuth(User::getById($SQLRow->us_id));
            }
			$SQLStmt->closeCursor();
			return $newRespPeda;
		}

		public function getNbPf($idPfToExclude = 0){
			$SQLQuery = 'SELECT COUNT(pf_id) FROM periodeformation WHERE resp_id = :idResp ';
			if ($idPfToExclude > 0){
				$SQLQuery .= 'AND pf_id != :idPfToExlude';
			}
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idResp', $this->getId());
			if ($idPfToExclude > 0){
				$SQLStmt->bindValue(':idPfToExlude', $idPfToExclude);
			}

			$SQLStmt->execute();
			$retVal = intval($SQLStmt->fetchColumn(0));
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function exists($idPers){
			$SQLQuery = "SELECT pers_id FROM responsablePedago WHERE pers_id = :idPers";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idPers', $idPers);
			$SQLStmt->execute();
			$retval = $SQLStmt->rowCount();
			$SQLStmt->closeCursor();
			return ($retval > 0);
		}

		public static function update($resppeda){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':nom', $resppeda->getNom());
            $SQLStmt->bindValue(':prenom', $resppeda->getPrenom());
            $SQLStmt->bindValue(':email', $resppeda->getEmail());
            $SQLStmt->bindValue(':idpers', $resppeda->getPersId());
            $SQLStmt->bindValue(':userid', (($resppeda->getUserAuth()->getId() != 0)?$resppeda->getUserAuth()->getId():null));
			if ($SQLStmt->execute()){
				return true;
			}else{
				var_dump($SQLStmt->errorInfo());
				return false;
			}
		}

		public static function insert($responsable){
			$SQLQuery = 'INSERT INTO responsablePedago (pers_id) VALUES (:idPers)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idPers', $responsable->getPersId());
			if ($SQLStmt->execute()){
				return true;
			}else{
				var_dump($SQLStmt->errorInfo());
				return false;
			}
		}

		public static function delete($responsable){
			$SQLQuery = 'DELETE FROM responsablePedago WHERE resp_id = :idResp';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idResp', $responsable->getId());
			if ($SQLStmt->execute()){
				return true;
			}else{
				var_dump($SQLStmt->errorInfo());
				return false;
			}
		}
	}