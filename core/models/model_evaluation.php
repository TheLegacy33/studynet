<?php
	include_once ROOTMODELS.'DAO.php';

	class Evaluation {
		private $commentaire, $acquis, $nonacquis, $enacquisition;
		private $idetudiant, $idintervenant, $idcontenumodule;

		public function __construct($idetudiant = 0, $idintervenant = 0, $idcontenumodule = 0, $acquis = 0, $enacquisition = 0, $nonacquis = 1, $commentaire = ''){
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

		public static function getById($idEtudiant, $idIntervenant, $idContenuModule){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM evaluer WHERE etu_id = :idetudiant AND cmod_id = :idcmod AND int_id = :idintervenant');
			$SQLStmt->bindValue(':idetudiant', $idEtudiant);
			$SQLStmt->bindValue(':idcmod', $idContenuModule);
			$SQLStmt->bindValue(':idintervenant', $idIntervenant);
			$SQLStmt->execute();
			//TODO : Est-ce vraiment utile d'avoir l'intervenant ? Vu que le module est rattaché à son intervenant ...
			if ($SQLStmt->rowCount() == 0){
				$newEval = new Evaluation($idEtudiant, $idIntervenant, $idContenuModule);
				Evaluation::insert($newEval);
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$newEval = new Evaluation($idEtudiant, $idIntervenant, $idContenuModule, $SQLRow->eval_acquis, $SQLRow->eval_enacquisition, $SQLRow->eval_nonacquis, $SQLRow->eval_commentaire);
			}
			$SQLStmt->closeCursor();
			return $newEval;
		}

        public static function getAppreciationModule($idEtudiant, $idIntervenant, $idModule){
            $SQLQuery = 'SELECT * FROM participer WHERE etu_id = :idetudiant AND mod_id = :idmod';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idmod', $idModule);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->execute();

			$SQLRow = $SQLStmt->fetchObject();
			$retVal = ($SQLRow->part_appreciation != ''?$SQLRow->part_appreciation:null);
            $SQLStmt->closeCursor();
            return $retVal;
        }

        public static function getAppreciationGenerale($idEtudiant, $idPf){
            $SQLQuery = 'SELECT app_contenu FROM appreciationGenerale WHERE etu_id = :idetudiant AND pf_id = :idPf';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idPf', $idPf);
            $SQLStmt->execute();

            if ($SQLStmt->rowCount() == 0){
            	$SQLQuery = 'INSERT INTO appreciationGenerale(etu_id, pf_id) VALUES (:idetudiant, :idPf)';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idetudiant', $idEtudiant);
				$SQLStmt->bindValue(':idPf', $idPf);
				if (!$SQLStmt->execute()){
					var_dump($SQLStmt->errorInfo());
				}
				$retVal = null;
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$retVal = ($SQLRow->app_contenu != ''?$SQLRow->app_contenu:null);
			}
			$SQLStmt->closeCursor();
            return $retVal;
        }

        public static function updateAppreciationModule($commentaireModule, $idEtudiant, $idModule){
            $SQLQuery = 'UPDATE participer ';
            $SQLQuery .= 'SET part_appreciation = :commentaire ';
            $SQLQuery .= 'WHERE etu_id = :idetudiant AND mod_id = :idmod';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':commentaire', $commentaireModule);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idmod', $idModule);
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return true;
			}else{
				return false;
			}
        }

        public static function updateAppreciationGenerale($appreciation, $idEtudiant, $idPf){
            $SQLQuery = 'UPDATE appreciationGenerale ';
            $SQLQuery .= 'SET app_contenu = :appreciation ';
            $SQLQuery .= 'WHERE etu_id = :idetudiant AND pf_id = :idpf';
            $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
            $SQLStmt->bindValue(':appreciation', $appreciation);
            $SQLStmt->bindValue(':idetudiant', $idEtudiant);
            $SQLStmt->bindValue(':idpf', $idPf);
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return true;
			}else{
				return false;
			}
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
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return true;
			}else{
				return false;
			}
        }

		public static function insert($evaluation){
			$SQLQuery = 'INSERT INTO evaluer (eval_acquis, eval_enacquisition, eval_nonacquis, eval_commentaire, etu_id, cmod_id, int_id) 
						VALUES(:acquis, :enacquisition, :nonacquis, :commentaire, :idetudiant, :idcmod, :idintervenant)';

			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':commentaire', $evaluation->getCommentaire());
			$SQLStmt->bindValue(':idetudiant', $evaluation->getIdEtudiant());
			$SQLStmt->bindValue(':idcmod', $evaluation->getIdContenuModule());
			$SQLStmt->bindValue(':idintervenant', $evaluation->getIdIntervenant());
			$SQLStmt->bindValue(':acquis', $evaluation->estAcquis());
			$SQLStmt->bindValue(':enacquisition', $evaluation->estEnCoursAcquisition());
			$SQLStmt->bindValue(':nonacquis', $evaluation->estNonAcquis());
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return true;
			}else{
				return false;
			}
		}
	}
?>