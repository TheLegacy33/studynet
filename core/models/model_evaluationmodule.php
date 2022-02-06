<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_statutevaluation.php';
	include_once ROOTMODELS.'model_typeevaluation.php';
	include_once ROOTMODELS.'model_documentsevaluation.php';

	class EvaluationModule {
		private $id, $date, $duree, $sujet, $coefficient, $type, $documents, $module;
		private $notes, $statuts;

		public function __construct($id = 0, $date = '', $duree = 0, $sujet = '', $coeff = 1){
			$this->id = $id;
			$this->date = $date;
			$this->duree = $duree;
			$this->sujet = $sujet;
			$this->coefficient = $coeff;
			$this->type = TypeEvaluation::getByLibelle('Devoir');
			$this->documents = array();
			$this->module = new Module();
			$this->notes = array();
			$this->statuts = array();
		}

		public function setId($id){
			$this->id = $id;
		}

		public function setDate($date){
			$this->date = $date;
		}

		public function setDuree($duree){
			$this->duree = $duree;
		}

		public function setType($type){
			$this->type = $type;
		}

		public function setSujet($sujet){
			$this->sujet = $sujet;
		}

		public function setCoefficient($coefficient){
			$this->coefficient = $coefficient;
		}

		public function fillDocuments(){
			$this->documents = DocumentsEvaluation::getListeFromEval($this->id);
		}

		public function fillNotes($listeEtudiants){
		    foreach ($listeEtudiants as $etudiant){
		        $this->notes[$etudiant->getId()] = EvaluationModule::getNote($this->id, $etudiant->getId());
		        $this->statuts[$etudiant->getId()] = EvaluationModule::getStatut($this->id, $etudiant->getId());
            }
		}

		public function setModule($module){
			$this->module = $module;
		}

		public function getId(){
			return $this->id;
		}

		public function getDate(){
			$ret = new DateTime($this->date);
			return $ret->format('d/m/Y');
		}

		public function getDuree(){
			return $this->duree;
		}

		public function getType(){
			return $this->type;
		}

		public function getSujet(){
			return $this->sujet;
		}

		public function getCoefficient(){
			return $this->coefficient;
		}

		public function getDocuments(){
			return $this->documents;
		}

		public function getModule(){
			return $this->module;
		}

		public function getStudentNote($idEtudiant){
		    return $this->notes[$idEtudiant];
        }

        public function getStudentStatut($idEtudiant){
			return $this->statuts[$idEtudiant];
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM evaluation WHERE eval_id = :idevaluation');
			$SQLStmt->bindValue(':idevaluation', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newEvaluation = new EvaluationModule($SQLRow->eval_id, $SQLRow->eval_date, $SQLRow->eval_duree, $SQLRow->eval_sujet, $SQLRow->eval_coefficient);
			$newEvaluation->fillDocuments();
			$newEvaluation->setModule(Module::getById($SQLRow->mod_id));
			$SQLStmt->closeCursor();
			return $newEvaluation;
		}

		public static function getListeFromModule($idModule){
			$SQLQuery = 'SELECT * FROM evaluation WHERE mod_id = :idmodule ORDER BY eval_date DESC';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue('idmodule', $idModule);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEvaluation = new EvaluationModule($SQLRow->eval_id, $SQLRow->eval_date, $SQLRow->eval_duree, $SQLRow->eval_sujet);
				$newEvaluation->setType(TypeEvaluation::getById($SQLRow->type_id));
				$newEvaluation->fillDocuments();
				$newEvaluation->setModule(Module::getById($SQLRow->mod_id));
				$retVal[] = $newEvaluation;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListeFromStudentForModule($idModule, $idEtudiant){
			$SQLQuery = 'SELECT * FROM evaluation inner join evaluermodule ON evaluation.eval_id = evaluermodule.eval_id ';
			$SQLQuery .= 'WHERE mod_id = :idmodule ';
			$SQLQuery .= 'AND etu_id = :idetudiant ';
			$SQLQuery .= 'ORDER BY eval_date DESC';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue('idmodule', $idModule);
			$SQLStmt->bindValue('idetudiant', $idEtudiant);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEvaluation = new EvaluationModule($SQLRow->eval_id, $SQLRow->eval_date, $SQLRow->eval_duree, $SQLRow->eval_sujet);
				$newEvaluation->setType(TypeEvaluation::getById($SQLRow->type_id));
				$newEvaluation->fillDocuments();
				$newEvaluation->setModule(Module::getById($SQLRow->mod_id));
				$retVal[] = $newEvaluation;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function hasNote($idevaluation, $idetudiant){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM evaluermodule WHERE eval_id = :idevaluation AND etu_id = :idetudiant');
			$SQLStmt->bindValue(':idevaluation', $idevaluation);
			$SQLStmt->bindValue(':idetudiant', $idetudiant);
			$SQLStmt->execute();
			$retVal = ($SQLStmt->rowCount() > 0);
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getNote($idevaluation, $idetudiant){
            $SQLStmt = DAO::getInstance()->prepare('SELECT * FROM evaluermodule WHERE eval_id = :idevaluation AND etu_id = :idetudiant');
            $SQLStmt->bindValue(':idevaluation', $idevaluation);
            $SQLStmt->bindValue(':idetudiant', $idetudiant);
            $SQLStmt->execute();

            if ($SQLStmt->rowCount() == 0){
                $retVal = 0;
            }else{
                $SQLRow = $SQLStmt->fetchObject();
                $retVal = $SQLRow->eval_note;
                $SQLStmt->closeCursor();
            }
            return $retVal;
        }

        public static function setNote($idevaluation, $idetudiant, $note, $statut){
			if (EvaluationModule::hasNote($idevaluation, $idetudiant)){
				$SQLQuery = 'UPDATE evaluermodule SET eval_note = :note, statev_id = :idstatut 
							WHERE eval_id = :idevaluation AND etu_id = :idetudiant';
			}else{
				$SQLQuery = 'INSERT INTO evaluermodule(eval_id, etu_id, statev_id, eval_note) 
							VALUES (:idevaluation, :idetudiant, :idstatut, :note)';
			}
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idevaluation', $idevaluation);
			$SQLStmt->bindValue(':idetudiant', $idetudiant);
			$SQLStmt->bindValue(':idstatut', $statut);
			$SQLStmt->bindValue(':note', $note);
			if ($SQLStmt->execute()){
				return true;
			}else{
				var_dump($SQLStmt->errorInfo());
				return false;
			}
		}

        public static function getStatut($idevaluation, $idetudiant){
            $SQLStmt = DAO::getInstance()->prepare('SELECT * FROM evaluermodule WHERE eval_id = :idevaluation AND etu_id = :idetudiant');
            $SQLStmt->bindValue(':idevaluation', $idevaluation);
            $SQLStmt->bindValue(':idetudiant', $idetudiant);
            $SQLStmt->execute();

            if ($SQLStmt->rowCount() == 0){
                $retVal = StatutEvaluation::getByLibelle('Non Evalué');
            }else{
                $SQLRow = $SQLStmt->fetchObject();
                $retVal = StatutEvaluation::getById($SQLRow->statev_id);
                $SQLStmt->closeCursor();
            }
            return $retVal;
        }

		public static function update($evaluation){
			$SQLQuery = 'UPDATE evaluer SET eval_acquis = :acquis, eval_enacquisition = :enacquisition, eval_nonacquis = :nonacquis, eval_commentaire = :commentaire 
						WHERE etu_id = :idetudiant AND cmod_id = :idcmod AND int_id = :idintervenant';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':commentaire', $evaluation->getCommentaire());
			$SQLStmt->bindValue(':idetudiant', $evaluation->getIdEtudiant());
			$SQLStmt->bindValue(':idcmod', $evaluation->getIdContenuModule());
			$SQLStmt->bindValue(':idintervenant', $evaluation->getIdIntervenant());
			$SQLStmt->bindValue(':acquis', $evaluation->estAcquis());
			$SQLStmt->bindValue(':enacquisition', $evaluation->estEnCoursAcquisition());
			$SQLStmt->bindValue(':nonacquis', $evaluation->estNonAcquis());
			$SQLStmt->execute();
		}

	}
?>