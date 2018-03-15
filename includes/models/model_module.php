<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_contenumodule.php';
	include_once ROOTMODELS.'model_intervenant.php';
	include_once ROOTMODELS.'model_periodeformation.php';
	include_once ROOTMODELS.'model_uniteenseignement.php';

	class Module{
		private $id, $libelle, $details, $idpf, $duree, $chrono, $idUe;
		private $intervenant, $contenu;

		public function __construct($id = 0, $libelle = '', $details = '', $intervenant = null, $idpf = null, $duree = 0, $chrono = 0, $idUniteEnseignement = 0){
			$this->id = $id;
			$this->libelle = $libelle;
			$this->details = $details;
			$this->intervenant = $intervenant;
			$this->idpf = $idpf;
			$this->duree = $duree;
			$this->chrono = $chrono;
			$this->idUe = $idUniteEnseignement;

			$this->contenu = array();
		}

		public function getIdpf(){
			return $this->idpf;
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

		public function getDuree(){
			return $this->duree;
		}

		public function getChrono(){
			return $this->chrono;
		}

		public function getIdPeriodeFormation(){
		    return $this->idpf;
        }

        public function getIdUniteEnseignement(){
			return $this->idUe;
		}

        public function hasContenu(){
			return (!empty($this->contenu));
		}

		public function setId($id){
			$this->id = $id;
		}

		public function setIdpf($idpf){
			$this->idpf = $idpf;
		}

		public function setContenu($contenu){
			$this->contenu = $contenu;
		}

		public function setLibelle($libelle){
			$this->libelle = $libelle;
		}

		public function setDetails($details){
			$this->details = $details;
		}

		public function setIntervenant($intervenant){
			$this->intervenant = $intervenant;
		}

		public function setChrono($chrono){
			$this->chrono = $chrono;
		}

		public function setDuree($duree){
			$this->duree = $duree;
		}

		public function fillContenu($contenu){
			$this->contenu = $contenu;
		}

		public function setIdUniteEnseignement($iduniteenseignement){
			$this->idUe = $iduniteenseignement;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM module WHERE mod_id = :idmodule');
			$SQLStmt->bindValue(':idmodule', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id), $SQLRow->pf_id, $SQLRow->mod_duree, $SQLRow->mod_chrono, $SQLRow->unit_id);
			$newModule->fillContenu(ContenuModule::getListeFromModule($id));
			$SQLStmt->closeCursor();
			return $newModule;
		}

		public static function getListeFromPf($idPf = 0, $uniteaffected = true){
			if ($idPf == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT * FROM module ';
				$SQLQuery .= 'WHERE pf_id = :idpf ';
				if (!$uniteaffected){
					$SQLQuery .= 'AND unit_id IS NULL ';
				}
				$SQLQuery .= 'ORDER BY mod_chrono';

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpf', $idPf);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id), $SQLRow->pf_id, $SQLRow->mod_duree, $SQLRow->mod_chrono, $SQLRow->unit_id);
					$newModule->fillContenu(ContenuModule::getListeFromModule($SQLRow->mod_id));
					$retVal[] = $newModule;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getListeFromEtudiant($idEtudiant = 0, $uniteaffected = true){
			if ($idEtudiant == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT * FROM module INNER JOIN participer ON module.mod_id = participer.mod_id ';
				$SQLQuery .= 'WHERE etu_id = :idetudiant ';
				if (!$uniteaffected){
					$SQLQuery .= 'AND unit_id IS NULL ';
				}
				$SQLQuery .= 'ORDER BY mod_chrono';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idetudiant', $idEtudiant);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id), $SQLRow->pf_id, $SQLRow->mod_duree, $SQLRow->mod_chrono, $SQLRow->unit_id);
					$newModule->fillContenu(ContenuModule::getListeFromModule($SQLRow->mod_id));
					$retVal[] = $newModule;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getListeFromUe($idUniteEnseignement){
			if ($idUniteEnseignement == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT * FROM module ';
				$SQLQuery .= 'WHERE unit_id = :iduniteenseignement ';
				$SQLQuery .= 'ORDER BY mod_chrono';

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':iduniteenseignement', $idUniteEnseignement);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id), $SQLRow->pf_id, $SQLRow->mod_duree, $SQLRow->mod_chrono, $SQLRow->unit_id);
					$newModule->fillContenu(ContenuModule::getListeFromModule($SQLRow->mod_id));
					$retVal[] = $newModule;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getNextChrono($idpf){
			if ($idpf == 0) {
				return null;
			}else{
				$SQLQuery = 'SELECT IFNULL(MAX(mod_chrono), 0) + 1 FROM module WHERE pf_id = :idpf';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpf', $idpf);
				$SQLStmt->execute();
				$SQLRow = $SQLStmt->fetch();
				$retVal = $SQLRow[0];
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function update($module){
			$SQLQuery = "UPDATE module SET mod_libelle = :libelle, mod_details = :details, int_id = :idintervenant, mod_duree = :duree, mod_chrono = :chrono, unit_id = :idunit WHERE mod_id = :idmod";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libelle', $module->getLibelle());
			$SQLStmt->bindValue(':details', $module->getDetails());
			$SQLStmt->bindValue(':idintervenant', (!is_null($module->getIntervenant()) AND $module->getIntervenant()->getId() != 0)?$module->getIntervenant()->getId():null);
			$SQLStmt->bindValue(':idunit', (!is_null($module->getIdUniteEnseignement()) AND $module->getIdUniteEnseignement() != 0)?$module->getIdUniteEnseignement():null);
			$SQLStmt->bindValue(':duree', $module->getDuree());
			$SQLStmt->bindValue(':chrono', $module->getChrono());
			$SQLStmt->bindValue(':idmod', $module->getId());

			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}

		public static function insert($module){
			$SQLQuery = 'INSERT INTO module(mod_libelle, mod_details, int_id, pf_id, mod_duree, mod_chrono, unit_id) ';
			$SQLQuery .= 'VALUES (:libModule, :detailModule, :idIntervenant, :idPf, :dureeModule, :chronoModule, :idunit)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libModule', $module->getLibelle());
			$SQLStmt->bindValue(':detailModule', $module->getDetails());
			$SQLStmt->bindValue(':idIntervenant',(!is_null($module->getIntervenant()) AND $module->getIntervenant()->getId() != 0)?$module->getIntervenant()->getId():null);
			$SQLStmt->bindValue(':idunit', (!is_null($module->getIdUniteEnseignement()) AND $module->getIdUniteEnseignement() != 0)?$module->getIdUniteEnseignement():null);
			$SQLStmt->bindValue(':idPf', $module->getIdPeriodeFormation());
			$SQLStmt->bindValue(':dureeModule', $module->getDuree());
			$SQLStmt->bindValue(':chronoModule', $module->getChrono());
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}
	}