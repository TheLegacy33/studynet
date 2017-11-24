<?php
	include_once ROOTMODELS.'DAO.php';

	class Intervenant {
		private $id, $nom, $prenom;

		public function __construct($id = 0, $nom, $prenom, $photo = ''){
			$this->id = $id;
			$this->nom = $nom;
			$this->prenom = $prenom;
		}

		public function getId(){
			return $this->id;
		}

		public function getNom(){
			return $this->nom;
		}

		public function getPrenom(){
			return $this->prenom;
		}

		public static function getListeFromPf($idPf = 0){
			if ($idPf == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM intervenant INNER JOIN module ON intervenant.int_id = module.int_id ';
 			$SQLQuery .= 'WHERE pf_id = :idpf ORDER BY int_nom, int_prenom';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpf', $idPf);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEtud = new Intervenant($SQLRow->int_id, $SQLRow->int_nom, $SQLRow->int_prenom);
				$retVal[] = $newEtud;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM intervenant WHERE int_id = :idintervenant");
			$SQLStmt->bindValue(':idintervenant', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newInterv = new Intervenant($SQLRow->int_id, $SQLRow->int_nom, $SQLRow->int_prenom);
			$SQLStmt->closeCursor();
			return $newInterv;
		}

		public function __toString(){
			return $this->nom.' '.$this->prenom;
		}
	}