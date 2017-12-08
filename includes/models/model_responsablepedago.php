<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'Personne.php';

	class ResponsablePedago extends Personne {
		private $id;

		public function __construct($id = 0, $nom, $prenom, $email = ''){
			parent::__construct($id, $nom, $prenom, $email);
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM responsablePedago INNER JOIN personne ON responsablePedago.pers_id = personne.pers_id WHERE resp_id = :idresponsable");
			$SQLStmt->bindValue(':idresponsable', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newInterv = new Intervenant($SQLRow->int_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
			$SQLStmt->closeCursor();
			return $newInterv;
		}
	}