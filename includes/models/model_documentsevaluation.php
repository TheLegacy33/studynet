<?php
	include_once ROOTMODELS.'DAO.php';

	class DocumentsEvaluation{
		private $id, $titre, $emplacement, $idEvaluation;

		public function __construct($id = 0, $titre = '', $emplacement = '', $idEval = 0){
			$this->id = $id;
			$this->titre = $titre;
			$this->emplacement = $emplacement;
			$this->idEvaluation = $idEval;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function setEmplacement($emplacement){
			$this->emplacement = $emplacement;
		}

		public function setTitre($titre){
			$this->titre = $titre;
		}

		public function getId(){
			return $this->id;
		}

		public function getTitre(){
			return $this->titre;
		}

		public function getEmplacement(){
			return $this->emplacement;
		}

		public static function getListeFromEval($idEvaluation){
			$SQLQuery = 'SELECT * FROM documentsevaluation WHERE eval_id = :idevaluation';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idevaluation', $idEvaluation);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newDocument = new DocumentsEvaluation($SQLRow->doce_id, $SQLRow->doce_titre, $SQLRow->doce_emplacement, $idEvaluation);
				$retVal[] = $newDocument;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}
	}
?>