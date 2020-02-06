<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_module.php';

	class ContenuModule{
		private $id, $libelle, $details, $idmodule;

		public function __construct($id = 0, $libelle = '', $details = '', $idModule = 0){
			$this->id = $id;
			$this->libelle = $libelle;
			$this->details = $details;
			$this->idmodule = $idModule;
		}

		public function getId(){
			return $this->id;
		}

		public function getLibelle(){
			return $this->libelle;
		}

		public function setLibelle($libelle){
			$this->libelle = $libelle;
		}

		public function getDetails(){
			return $this->details;
		}

		public function setDetails($details){
			$this->details = $details;
		}

		public function getIdModule(){
			return $this->idmodule;
		}

		public function getModule(){
		    return Module::getById($this->idmodule);
        }

		public static function getListeFromModule($idMod = 0){
			if ($idMod == 0){
				return null;
			}
			$SQLQuery = "SELECT * FROM contenumodule WHERE mod_id = :idmod ORDER BY cmod_chrono";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idmod', $idMod);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newModule = new ContenuModule($SQLRow->cmod_id, $SQLRow->cmod_libelle, $SQLRow->cmod_details, $SQLRow->mod_id);
				$retVal[] = $newModule;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM contenumodule WHERE cmod_id = :idcontenumodule");
			$SQLStmt->bindValue(':idcontenumodule', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newContenuModule = new ContenuModule($SQLRow->cmod_id, $SQLRow->cmod_libelle, $SQLRow->cmod_details, $SQLRow->mod_id);
			$SQLStmt->closeCursor();
			return $newContenuModule;
		}

		public static function getNextChrono($idModule){
			if ($idModule == 0) {
				return null;
			}else{
				$SQLQuery = 'SELECT IFNULL(MAX(cmod_chrono), 0) + 1 FROM contenumodule WHERE mod_id = :modid';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':modid', $idModule);
				$SQLStmt->execute();
				$SQLRow = $SQLStmt->fetch();
				$retVal = $SQLRow[0];
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function insert(ContenuModule $contenumodule){
			$SQLQuery = 'INSERT INTO contenumodule(cmod_libelle, cmod_details, cmod_chrono, mod_id) VALUES (:libCModule, :detailCModule, :chrono, :idmodule)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libCModule', $contenumodule->getLibelle());
			$SQLStmt->bindValue(':detailCModule', $contenumodule->getDetails());
			$SQLStmt->bindValue(':chrono', ContenuModule::getNextChrono($contenumodule->getIdModule()));
			$SQLStmt->bindValue(':idmodule', $contenumodule->getIdModule());
			DAO::getInstance()->beginTransaction();
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				DAO::getInstance()->rollBack();
				return false;
			}
			DAO::getInstance()->commit();
			return true;
		}

		public static function update(ContenuModule $contenumodule){
			$SQLQuery = 'UPDATE contenumodule SET cmod_libelle = :libCModule, cmod_details = :detailCModule WHERE cmod_id = :idcmodule AND mod_id = :idmodule';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libCModule', $contenumodule->getLibelle());
			$SQLStmt->bindValue(':detailCModule', $contenumodule->getDetails());
			$SQLStmt->bindValue(':idmodule', $contenumodule->getIdModule());
			$SQLStmt->bindValue(':idcmodule', $contenumodule->getId());
			DAO::getInstance()->beginTransaction();
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				DAO::getInstance()->rollBack();
				return false;
			}
			DAO::getInstance()->commit();
			return true;
		}
	}