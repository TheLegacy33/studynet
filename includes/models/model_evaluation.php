<?php
	include_once ROOTMODELS.'DAO.php';

	class Evaluation {
		private $commentaire, $acquis, $nonacquis, $enacquisition;
		private $idetudiant, $idintervenant, $idcontenumodule;

		public function __construct($idetudiant = 0, $idintervenant = 0, $idcontenumodule = 0, $acquis = 0, $enacquisition = 0, $nonacquis = 0, $commentaire = ''){
			$this->acquis = (intval($acquis) != 0);
			$this->nonacquis = (intval($nonacquis) != 0);
			$this->enacquisition = (intval($enacquisition) != 0);
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

        public static function getAppreciationGenerale($idEtudiant, $idIntervenant = 0){
            $SQLQuery = 'SELECT * FROM appreciationgenerale WHERE etu_id = :idetudiant ';
            if ($idIntervenant != 0){
                $SQLQuery .= 'AND int_id = :idintervenant';
            }
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            if ($idIntervenant != 0){
                $SQLStmt->bindValue(':idintervenant', $idIntervenant);
            }
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->execute();

            $SQLRow = $SQLStmt->fetchObject();
            $retVal = $SQLRow->app_contenu;
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
			$newPromo = new Evaluation($idEtudiant, $idIntervenant, $idContenuModule, $SQLRow->eval_acquis, $SQLRow->eval_enacquisition, $SQLRow->eval_nonacquis, $SQLRow->eval_commentaire);
			$SQLStmt->closeCursor();
			return $newPromo;
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

        public static function updateAppreciationGenerale($commentaire, $idEtudiant, $idIntervenant = 4){
            $SQLQuery = 'UPDATE appreciationgenerale ';
            $SQLQuery .= 'SET app_contenu = :commentaire ';
            $SQLQuery .= 'WHERE etu_id = :idetudiant AND int_id = :idintervenant';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':commentaire', $commentaire);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idintervenant', $idIntervenant);
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