<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_contenumodule.php';
	include_once ROOTMODELS.'model_intervenant.php';
	include_once ROOTMODELS.'model_periodeformation.php';
	include_once ROOTMODELS.'model_uniteenseignement.php';

	class Module{
		private $id, $libelle, $details, $duree;
		private $intervenant, $contenu, $uniteenseignement;

		public function __construct($id = 0, $libelle = '', $details = '', $intervenant = null, $duree = 0, $UniteEnseignement = null){
			$this->id = $id;
			$this->libelle = $libelle;
			$this->details = $details;
			$this->intervenant = $intervenant;
			$this->duree = $duree;
			$this->uniteenseignement = $UniteEnseignement;

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

		public function getDuree(){
			return $this->duree;
		}

        public function getUniteEnseignement(){
			return $this->uniteenseignement;
		}

        public function hasContenu(){
			return (!empty($this->contenu));
		}

		public function setId($id){
			$this->id = $id;
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

		public function setDuree($duree){
			$this->duree = $duree;
		}

		public function fillContenu($contenu){
			$this->contenu = $contenu;
		}

		public function setUniteEnseignement(UniteEnseignement $uniteenseignement){
			$this->uniteenseignement = $uniteenseignement;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM module WHERE mod_id = :idmodule');
			$SQLStmt->bindValue(':idmodule', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$uniteEnseignement = (is_null($SQLRow->unit_id)?UniteEnseignement::getEmptyUE():UniteEnseignement::getById($SQLRow->unit_id));
			$newModule = new Module($SQLRow->mod_id, $SQLRow->mod_libelle, $SQLRow->mod_details, Intervenant::getById($SQLRow->int_id), $SQLRow->mod_duree, $uniteEnseignement);
			$newModule->fillContenu(ContenuModule::getListeFromModule($id));
			$SQLStmt->closeCursor();
			return $newModule;
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
					$newModule = Module::getById($SQLRow->mod_id);
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
//				$SQLQuery .= 'ORDER BY ratt_chrono';

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':iduniteenseignement', $idUniteEnseignement);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = Module::getById($SQLRow->mod_id);
					$retVal[] = $newModule;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getListeFromPf($idPf){
			if ($idPf == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT module.mod_id FROM module INNER JOIN rattacher ON module.mod_id = rattacher.mod_id ';
				$SQLQuery .= 'WHERE pf_id = :idpf ';
				$SQLQuery .= 'ORDER BY ratt_chrono';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpf', $idPf);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = Module::getById($SQLRow->mod_id);
					$retVal[] = $newModule;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getListeFromUeAndPf($idUniteEnseignement, $idPf){
			if ($idUniteEnseignement == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT module.mod_id ';
				$SQLQuery .= 'FROM module INNER JOIN rattacher ON module.mod_id = rattacher.mod_id ';
				$SQLQuery .= 'WHERE rattacher.pf_id = :idPf ';
				$SQLQuery .= 'AND unit_id = :idue ';
				$SQLQuery .= 'ORDER BY ratt_chrono';

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idue', $idUniteEnseignement);
				$SQLStmt->bindValue(':idPf', $idPf);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = Module::getById($SQLRow->mod_id);
					$retVal[] = $newModule;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getListeFromPfSansUE($idPf = 0){
			if ($idPf == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT module.mod_id FROM module INNER JOIN rattacher ON module.mod_id = rattacher.mod_id ';
				$SQLQuery .= 'WHERE pf_id = :idpf ';
				$SQLQuery .= 'AND unit_id is null ';
				$SQLQuery .= 'ORDER BY ratt_chrono';

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpf', $idPf);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newModule = Module::getById($SQLRow->mod_id);
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
				$SQLQuery = 'SELECT IFNULL(MAX(ratt_chrono), 0) + 1 FROM rattacher WHERE pf_id = :idpf';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpf', $idpf);
				$SQLStmt->execute();
				$SQLRow = $SQLStmt->fetch();
				$retVal = $SQLRow[0];
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function update(Module $module){
			$SQLQuery = "UPDATE module SET mod_libelle = :libelle, mod_details = :details, int_id = :idintervenant, mod_duree = :duree, unit_id = :idunit WHERE mod_id = :idmod";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libelle', $module->getLibelle());
			$SQLStmt->bindValue(':details', $module->getDetails());
			$SQLStmt->bindValue(':idintervenant', (!is_null($module->getIntervenant()) AND $module->getIntervenant()->getId() != 0)?$module->getIntervenant()->getId():null);
			$SQLStmt->bindValue(':idunit', (!is_null($module->getUniteEnseignement()) AND $module->getUniteEnseignement()->getId() != 0)?$module->getUniteEnseignement()->getId():null);
			$SQLStmt->bindValue(':duree', $module->getDuree());
			$SQLStmt->bindValue(':idmod', $module->getId());

			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}

		public static function insert(Module $module, Periodeformation $pf){
			$SQLQuery = 'INSERT INTO module(mod_libelle, mod_details, int_id, mod_duree, unit_id) VALUES (:libModule, :detailModule, :idIntervenant, :dureeModule, :idunit)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libModule', $module->getLibelle());
			$SQLStmt->bindValue(':detailModule', $module->getDetails());
			$SQLStmt->bindValue(':idIntervenant',(!is_null($module->getIntervenant()) AND $module->getIntervenant()->getId() != 0)?$module->getIntervenant()->getId():null);
			$SQLStmt->bindValue(':idunit', (!is_null($module->getUniteEnseignement()) AND $module->getUniteEnseignement()->getId() != 0)?$module->getUniteEnseignement()->getId():null);
			$SQLStmt->bindValue(':dureeModule', $module->getDuree());
			DAO::getInstance()->beginTransaction();
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				DAO::getInstance()->rollBack();
				return false;
			}else{
				$module->setId(DAO::getInstance()->lastInsertId());
				$SQLQuery = 'INSERT INTO rattacher (mod_id, pf_id, ratt_chrono) VALUES (:idmod, :idpf, :chrono)';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idmod', $module->getId());
				$SQLStmt->bindValue(':idpf', $pf->getId());
				$SQLStmt->bindValue(':chrono', Module::getNextChrono($pf->getId()));
				if (!$SQLStmt->execute()){
					var_dump($SQLStmt->errorInfo());
					DAO::getInstance()->rollBack();
					return false;
				}else{
					DAO::getInstance()->commit();
					return true;
				}
			}
		}
	}