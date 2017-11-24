<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_contenumodule.php';
	include_once ROOTMODELS.'model_intervenant.php';

	class Module{
		private $id, $libelle, $details;
		private $intervenant;

		public function __construct($id = 0, $libelle, $details = '', $intervenant){
			$this->id = $id;
			$this->libelle = $libelle;
			$this->details = $details;
			$this->intervenant = $intervenant;

			$this->contenu = array();
		}

		public function getLibelle(){
			return $this->libelle;
		}

		public function getDetails(){
			return $this->details;
		}

		public function getId(){
			return $this->id;
		}

		public function getContenu(){
			return $this->contenu;
		}

		public function getIntervenant(){
			return $this->intervenant;
		}

		public function fillContenu($contenu){
			$this->contenu = $contenu;
		}

		public static function getListeFromPf($idPf = 0){
			if ($idPf == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM module WHERE pf_id = :idpf ORDER BY mod_chrono';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idpf', $idPf);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id));
				$newModule->fillContenu(ContenuModule::getListeFromModule($SQLRow->mod_id));
				$retVal[] = $newModule;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM module WHERE mod_id = :idmodule');
			$SQLStmt->bindValue(':idmodule', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id));
			$newModule->fillContenu(ContenuModule::getListeFromModule($id));
			$SQLStmt->closeCursor();
			return $newModule;
		}
	}