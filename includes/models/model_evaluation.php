<?php
	include_once ROOTMODELS.'DAO.php';

	class Evaluation {
		private $commentaire, $acquis, $nonacquis, $enacquisition;
		private $idetudiant, $idintervenant, $idcontenumodule;

		public function __construct($idetudiant = 0, $idintervenant = 0, $idcontenumodule = 0, $acquis = 0, $enacquisition = 0, $nonacquis = 0, $commentaire = ''){
			$this->acquis = $acquis;
			$this->nonacquis = $nonacquis;
			$this->enacquisition = $enacquisition;
			$this->idetudiant = $idetudiant;
			$this->idintervenant = $idintervenant;
			$this->idcontenumodule = $idcontenumodule;

			$this->commentaire = $commentaire;
		}

		public function getPrimaryKey(){
		    return $this->idetudiant.$this->idintervenant.$this->idcontenumodule;
        }

		public function getCommentaire(){
		    return $this->commentaire;
        }

        public function getIdEtudiant(){
		    return $this->idetudiant;
        }

        public function getIdIntervenant(){
            return $this->idintervenant;
        }

        public function getIdContenuModule(){
            return $this->idcontenumodule;
        }

        public function estAcquis(){
            return $this->acquis;
        }

        public function estNonAcquis(){
            return $this->nonacquis;
        }

        public function estEnCoursAcquisition(){
            return $this->enacquisition;
        }

        public static function getAppreciationModule($idEtudiant, $idIntervenant, $idModule){
            $SQLQuery = 'SELECT * FROM appreciation WHERE etu_id = :idetudiant AND mod_id = :idmod AND int_id = :idintervenant';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idmod', $idModule);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idintervenant', $idIntervenant);
            $SQLStmt->execute();

            $SQLRow = $SQLStmt->fetchObject();
            $retVal = $SQLRow->app_contenu;
            $SQLStmt->closeCursor();
            return $retVal;
        }

        public static function getAppreciationGenerale($idEtudiant, $idPf){
            $SQLQuery = 'SELECT part_appreciation FROM participer WHERE etu_id = :idetudiant AND pf_id = :idPf';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idPf', $idPf);
            $SQLStmt->execute();

            $SQLRow = $SQLStmt->fetchObject();
            $retVal = $SQLRow->part_appreciation;
            $SQLStmt->closeCursor();
            return $retVal;
        }

        public static function getById($idEtudiant, $idIntervenant, $idContenuModule){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM evaluer WHERE etu_id = :idetudiant AND cmod_id = :idcmod AND int_id = :idintervenant');
			$SQLStmt->bindValue(':idetudiant', $idEtudiant);
			$SQLStmt->bindValue(':idcmod', $idContenuModule);
			$SQLStmt->bindValue(':idintervenant', $idIntervenant);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newEval = new Evaluation($idEtudiant, $idIntervenant, $idContenuModule, $SQLRow->eval_acquis, $SQLRow->eval_enacquisition, $SQLRow->eval_nonacquis, $SQLRow->eval_commentaire);
			$SQLStmt->closeCursor();
			return $newEval;
		}

        public static function updateAppreciationModule($commentaireModule, $idEtudiant, $idModule, $idIntervenant){
            $SQLQuery = 'UPDATE appreciation ';
            $SQLQuery .= 'SET app_contenu = :commentaire ';
            $SQLQuery .= 'WHERE etu_id = :idetudiant AND mod_id = :idmod AND int_id = :idintervenant';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':commentaire', $commentaireModule);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idmod', $idModule);
            $SQLStmt->bindValue(':idintervenant', $idIntervenant);
            $SQLStmt->execute();
        }

        public static function updateAppreciationGenerale($appreciation, $idEtudiant, $idPf){
            $SQLQuery = 'UPDATE participer ';
            $SQLQuery .= 'SET part_appreciation = :appreciation ';
            $SQLQuery .= 'WHERE etu_id = :idetudiant AND pf_id = :idpf';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':appreciation', $appreciation);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idpf', $idPf);
            $SQLStmt->execute();
        }

        public static function update($evaluation){
            $SQLQuery = 'UPDATE evaluer SET eval_acquis = :acquis, eval_enacquisition = :enacquisition, eval_nonacquis = :nonacquis, eval_commentaire = :commentaire ';
            $SQLQuery .= 'WHERE etu_id = :idetudiant AND cmod_id = :idcmod AND int_id = :idintervenant';
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