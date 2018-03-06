<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_module.php';

	class ContenuModule{
		private $id, $libelle, $details, $idmodule;

		public function __construct($id = 0, $libelle, $details = '', $idModule){
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

		public function getDetails(){
			return $this->details;
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
	}