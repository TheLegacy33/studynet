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

		public static function getListe($critere = ResponsablePedago::class){
			return parent::getListe($critere);
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

		public static function update($resppeda){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':nom', $resppeda->getNom());
            $SQLStmt->bindValue(':prenom', $resppeda->getPrenom());
            $SQLStmt->bindValue(':email', $resppeda->getEmail());
            $SQLStmt->bindValue(':idpers', $resppeda->getPersId());
            $SQLStmt->bindValue(':userid', (($resppeda->getUserAuth()->getId() != 0)?$resppeda->getUserAuth()->getId():null));
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
			}
		}
	}