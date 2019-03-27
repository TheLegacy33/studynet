<?php
	include_once ROOTMODELS.'DAO.php';

	class TypeEvaluation{
		private $id, $libelle;

		public function __construct($id = 0, $libelle = ''){
			$this->id = $id;
			$this->libelle = $libelle;
		}

		public function getId(){
			return $this->id;
		}

		public function getLibelle(){
			return $this->libelle;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function setLibelle($libelle){
			$this->libelle = $libelle;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM typeevaluation WHERE type_id = :idtype');
			$SQLStmt->bindValue(':idtype', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newTypeEval = new TypeEvaluation($SQLRow->type_id, $SQLRow->type_libelle);
			$SQLStmt->closeCursor();
			return $newTypeEval;
		}

		public static function getByLibelle($libelle){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM typeevaluation WHERE type_libelle = :libelletype');
			$SQLStmt->bindValue(':libelletype', $libelle);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newTypeEval = new TypeEvaluation($SQLRow->type_id, $SQLRow->type_libelle);
			$SQLStmt->closeCursor();
			return $newTypeEval;
		}

		public static function getListe(){
			$SQLQuery = 'SELECT * FROM typeevaluation';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newTypeEval = new TypeEvaluation($SQLRow->type_id, $SQLRow->type_libelle);
				$retVal[] = $newTypeEval;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}
	}
?>