<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_module.php';

	class UniteEnseignement{
		private $id, $libelle, $chrono;
		private $modules;

		public function __construct($id = 0, $libelle = '', $chrono = 0){
			$this->id = $id;
			$this->libelle = $libelle;
			$this->chrono = $chrono;

			$this->modules = array();
		}

		public function getId(){
			return $this->id;
		}

		public function getLibelle(){
			return $this->libelle;
		}

		public function getChrono(){
			return $this->chrono;
		}

		public function getModules(){
			return $this->modules;
		}

		public function getNbModules(){
			return count($this->modules);
		}

		public function setId($id){
			$this->id = $id;
		}

		public function setLibelle($libelle){
			$this->libelle = $libelle;
		}

		public function setChrono($chrono){
			$this->chrono = $chrono;
		}

		public function fillModules($listeModules){
			$this->modules = $listeModules;
		}

		public function equals(UniteEnseignement $uniteEnseignement){
			if ($this->getId() == $uniteEnseignement->getId() AND $this->getLibelle() == $uniteEnseignement->getLibelle()){
				return true;
			}else{
				return false;
			}
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM uniteenseignement WHERE unit_id = :idunite');
			$SQLStmt->bindValue(':idunite', $id);
			$SQLStmt->execute();
			if ($SQLStmt->rowCount() == 0){
				$newUnit = null;
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$newUnit = new UniteEnseignement($SQLRow->unit_id, $SQLRow->unit_libelle);
				$newUnit->fillModules(Module::getListeFromUe($id));
			}
			$SQLStmt->closeCursor();
			return $newUnit;
		}

		public static function getListeFromPF($idPf){
			if ($idPf == 0){
				return null;
			}else{
				//Récuépration de la liste des ue de la promo associée à la pf
				$SQLQuery = 'SELECT * ';
				$SQLQuery .= 'FROM uniteenseignement INNER JOIN composer ON uniteenseignement.unit_id = composer.unit_id ';
				$SQLQuery .= 'INNER JOIN promotion ON composer.promo_id = promotion.promo_id ';
				$SQLQuery .= 'INNER JOIN periodeformation ON  promotion.promo_id = periodeformation.promo_id ';
				$SQLQuery .= 'WHERE periodeformation.pf_id = :idPf ';
				$SQLQuery .= 'ORDER BY composer.comp_chrono';

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idPf', $idPf);
				$SQLStmt->execute();

				$retVal = array();
				$chrono = 0;
				while ($SQLRow = $SQLStmt->fetchObject()){
					$chrono = $SQLRow->comp_chrono;
					$newUnit = new UniteEnseignement($SQLRow->unit_id, $SQLRow->unit_libelle, $chrono);
					$newUnit->fillModules(Module::getListeFromUeAndPf($SQLRow->unit_id, $idPf));
					$retVal[] = $newUnit;
				}

				//Récupération de la liste des modules sans UE
				$newUnit = new UniteEnseignement(0, 'Sans Unité d\'enseignement', $chrono++);
				$newUnit->fillModules(Module::getListeFromPfSansUE($idPf));
				if ($newUnit->getNbModules() > 0){
					$retVal[] = $newUnit;
				}

				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getListeFromPromo($idPromo, $trialpha = true){
			if ($idPromo == 0){
				return null;
			}else{
				$SQLQuery = 'SELECT * FROM uniteenseignement INNER JOIN composer ON uniteenseignement.unit_id = composer.unit_id ';
				$SQLQuery .= 'WHERE promo_id = :idpromo ';
				if ($trialpha){
					$SQLQuery .= 'ORDER BY unit_libelle';
				}else{
					$SQLQuery .= 'ORDER BY comp_chrono';
				}

				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpromo', $idPromo);
				$SQLStmt->execute();

				$retVal = array();
				while ($SQLRow = $SQLStmt->fetchObject()){
					$newUnit = new UniteEnseignement($SQLRow->unit_id, $SQLRow->unit_libelle, $SQLRow->comp_chrono);
					$newUnit->fillModules(Module::getListeFromUe($SQLRow->unit_id));
					$retVal[] = $newUnit;
				}
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function getNextChrono($idPromo){
			if ($idPromo == 0) {
				return null;
			}else{
				$SQLQuery = 'SELECT IFNULL(MAX(comp_chrono), 0) + 1 FROM composer WHERE promo_id = :idpromo';
				$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
				$SQLStmt->bindValue(':idpromo', $idPromo);
				$SQLStmt->execute();
				$SQLRow = $SQLStmt->fetch();
				$retVal = $SQLRow[0];
				$SQLStmt->closeCursor();
				return $retVal;
			}
		}

		public static function update($uniteenseignement){
			$SQLQuery = "UPDATE uniteenseignement SET unit_libelle = :libelle WHERE unit_id = :iduniteenseignement";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libelle', $uniteenseignement->getLibelle());
			$SQLStmt->bindValue(':iduniteenseignement', $uniteenseignement->getId());

			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}

		public static function insert($uniteenseignement){
			$SQLQuery = 'INSERT INTO module(unit_libelle) ';
			$SQLQuery .= 'VALUES (:libelle)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':libelle', $uniteenseignement->getLibelle());
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}

		public function __toString(){
			return $this->libelle;
		}
	}