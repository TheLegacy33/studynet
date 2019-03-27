<?php
	include_once ROOTMODELS.'DAO.php';

	class StatutRattrapage{
		private $statr_id, $statr_libelle;

		public function __construct($id = 0, $libelle = ''){
			$this->statr_id = $id;
			$this->statr_libelle = $libelle;
		}

		public function getId(){
			return $this->statr_id;
		}

		public function getLibelle(){
			return $this->statr_libelle;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutrattrapage WHERE statr_id = :idstatut");
			$SQLStmt->bindValue(':idstatut', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newStatut = new StatutRattrapage($SQLRow->statr_id, $SQLRow->statr_libelle);
			$SQLStmt->closeCursor();
			return $newStatut;
		}

		public static function getByLibelle($libelle){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutrattrapage WHERE statr_libelle = :libstatut");
			$SQLStmt->bindValue(':libstatut', $libelle);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newStatut = new StatutRattrapage($SQLRow->statr_id, $SQLRow->statr_libelle);
			$SQLStmt->closeCursor();
			return $newStatut;
		}

		public static function getListe(){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutrattrapage ORDER BY statr_libelle");
			$SQLStmt->execute();
			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newStatut = new StatutRattrapage($SQLRow->statr_id, $SQLRow->statr_libelle);
				$retVal[] = $newStatut;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}
	}
?>