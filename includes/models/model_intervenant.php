<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'Personne.php';

	class Intervenant extends Personne {
		private $id, $photo, $email;

		public function __construct($id = 0, $nom, $prenom, $email = ''){
			parent::__construct($id, $nom, $prenom, $email);
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
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
				$newEtud = new Intervenant($SQLRow->int_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM intervenant INNER JOIN personne ON intervenant.pers_id = personne.pers_id WHERE int_id = :idintervenant");
			$SQLStmt->bindValue(':idintervenant', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newInterv = new Intervenant($SQLRow->int_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
			$SQLStmt->closeCursor();
			return $newInterv;
		}
	}