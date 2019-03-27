<?php
	include_once ROOTMODELS.'DAO.php';

	class StatutEvaluation{
		private $statev_id, $statev_libelle, $statev_impactmoyenne;

		public function __construct($id = 0, $libelle = '', $impactmoyenne = false){
			$this->statev_id = $id;
			$this->statev_libelle = $libelle;
			$this->statev_impactmoyenne = $impactmoyenne;
		}

		public function getId(){
			return $this->statev_id;
		}

		public function getLibelle(){
			return $this->statev_libelle;
		}

		public function impactMoyenne(){
			return $this->statev_impactmoyenne;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statuteval WHERE statev_id = :idstatut");
			$SQLStmt->bindValue(':idstatut', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newStatut = new StatutEvaluation($SQLRow->statev_id, $SQLRow->statev_libelle, $SQLRow->statev_impactmoyenne);
			$SQLStmt->closeCursor();
			return $newStatut;
		}

		public static function getByLibelle($libelle){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statuteval WHERE statev_libelle = :libstatut");
			$SQLStmt->bindValue(':libstatut', $libelle);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newStatut = new StatutEvaluation($SQLRow->statev_id, $SQLRow->statev_libelle, $SQLRow->statev_impactmoyenne);
			$SQLStmt->closeCursor();
			return $newStatut;
		}

		public static function getListe(){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statuteval ORDER BY statev_libelle");
			$SQLStmt->execute();
			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newStatut = new StatutEvaluation($SQLRow->statev_id, $SQLRow->statev_libelle, $SQLRow->statev_impactmoyenne);
				$retVal[] = $newStatut;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}
	}
?>