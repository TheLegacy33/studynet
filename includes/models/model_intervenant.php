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

		public static function getListe($critere = Intervenant::class){
			return parent::getListe($critere);
		}

		public static function getListeFromPf($idPf = 0){
			if ($idPf == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM intervenant INNER JOIN personne ON intervenant.pers_id = personne.pers_id INNER JOIN module ON intervenant.int_id = module.int_id ';
 			$SQLQuery .= 'WHERE pf_id = :idpf ORDER BY pers_nom, pers_prenom';
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

		public static function update($intervenant){
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
	}