<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS . 'model_personne.php';

	class Intervenant extends Personne {
		private $int_id;

		public function __construct($id = 0, $nom = '', $prenom = '', $email = '', $idPers = 0){
			parent::__construct($idPers, $nom, $prenom, $email);
			$this->int_id = $id;
		}

		public function getId(){
			return $this->int_id;
		}

		public function getPersId(){
			return parent::getId();
		}

		public static function getListe($critere = '*'){
			$SQLQuery = 'SELECT * FROM personne ';
			$SQLQuery .= 'INNER JOIN intervenant ON personne.pers_id = intervenant.pers_id ';
			$SQLQuery .= 'ORDER BY pers_nom, pers_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->execute();

			$retVal = array();

			while ($SQLRow = $SQLStmt->fetchObject()){
				$idInt = Intervenant::getIdByIdPers($SQLRow->pers_id);
				$newPers = new Intervenant($idInt, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
				$newPers->fillAuth(User::getById($SQLRow->us_id));
				$retVal[] = $newPers;
			}

			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListeFromPf($idPf = 0){
			if ($idPf == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM intervenant INNER JOIN personne ON intervenant.pers_id = personne.pers_id ';
			$SQLQuery .= 'INNER JOIN dispenser ON intervenant.int_id = dispenser.int_id ';
 			$SQLQuery .= 'WHERE dispenser.pf_id = :idpf ORDER BY pers_nom, pers_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpf', $idPf);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newInterv = new Intervenant($SQLRow->int_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
				$retVal[] = $newInterv;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

        public static function getIdByIdPers($idPers){
            $SQLQuery = 'SELECT int_id FROM intervenant WHERE pers_id = :idpers';
            $stmt = DAO::getInstance()->prepare($SQLQuery);
            $stmt->bindValue(':idpers', $idPers);
            $stmt->execute();
            $retVal = $stmt->fetchColumn(0);
            $stmt->closeCursor();
            return $retVal;
        }

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM intervenant INNER JOIN personne ON intervenant.pers_id = personne.pers_id WHERE int_id = :idintervenant");
			$SQLStmt->bindValue(':idintervenant', $id);
			$SQLStmt->execute();
			if ($SQLStmt->rowCount() == 0){
				$newInterv = new Intervenant(0, 'N.C.');
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$newInterv = new Intervenant($SQLRow->int_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
				$newInterv->fillAuth(User::getById($SQLRow->us_id));
			}
			$SQLStmt->closeCursor();
			return $newInterv;
		}

		public static function getByPfAndMod($idPf, $idMod){

			$SQLQuery = 'SELECT int_id FROM dispenser WHERE pf_id = :idPf AND mod_id = :idModule';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idPf', $idPf);
			$SQLStmt->bindValue(':idModule', $idMod);

			$SQLStmt->execute();
			if ($SQLStmt->rowCount() == 0){
				$retVal = new Intervenant(0, 'N.C.');
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$retVal = self::getById(intval($SQLRow->int_id));
				$SQLStmt->closeCursor();
			}
			return $retVal;
		}

		public function getNbModules($idModToExclude = 0){
			$SQLQuery = 'SELECT COUNT(mod_id) FROM dispenser WHERE int_id = :idInterv ';
			if ($idModToExclude > 0){
				$SQLQuery .= 'AND mod_id != :idModToExlude';
			}
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idInterv', $this->getId());
			if ($idModToExclude > 0){
				$SQLStmt->bindValue(':idModToExlude', $idModToExclude);
			}

			$SQLStmt->execute();
			$retVal = intval($SQLStmt->fetchColumn(0));
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function exists($idPers){
			$SQLStmt = DAO::getInstance()->prepare("SELECT pers_id FROM intervenant WHERE pers_id = :idPers");
			$SQLStmt->bindValue(':idPers', $idPers);
			$SQLStmt->execute();
			$retval = $SQLStmt->rowCount();
			$SQLStmt->closeCursor();
			return ($retval > 0);
		}

		public static function update($intervenant){
			/**
			 * @var Intervenant $intervenant
			 */
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':nom', $intervenant->getNom());
			$stmt->bindValue(':prenom', $intervenant->getPrenom());
			$stmt->bindValue(':email', $intervenant->getEmail());
			$stmt->bindValue(':idpers', $intervenant->getPersId());
			$stmt->bindValue(':userid', (($intervenant->getUserAuth()->getId() != 0)?$intervenant->getUserAuth()->getId():null));
			if (!$stmt->execute()){
				var_dump($stmt->errorInfo());
			}
		}

		public static function insert($intervenant){
			/**
			 * @var Intervenant $intervenant
			 */
			$SQLQuery = 'INSERT INTO intervenant (pers_id) VALUES (:idPers)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idPers', $intervenant->getPersId());
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
			}
		}

		public static function delete($intervenant){
			/**
			 * @var Intervenant $intervenant
			 */
			$SQLQuery = 'DELETE FROM intervenant WHERE int_id = :idInterv';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idInterv', $intervenant->getId());
			if ($SQLStmt->execute()){
				return true;
			}else{
				var_dump($SQLStmt->errorInfo());
				return false;
			}
		}
	}
?>