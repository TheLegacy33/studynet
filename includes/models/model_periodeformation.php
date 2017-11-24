<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_etudiant.php';
include_once ROOTMODELS.'model_module.php';

class Periodeformation {
	private $id, $datedebut, $datefin, $promo;
	private $etudiants, $modules;

	public function __construct($id = 0, $datedebut, $datefin, $promo){
		$this->id = $id;
		$this->datedebut = $datedebut;
		$this->datefin = $datefin;
		$this->promo = $promo;
		$this->etudiants = array();
		$this->modules = array();
	}
	public function getId(){
		return $this->id;
	}

	public function getDateDebut(){
		return $this->datedebut;
	}

	public function getDateFin(){
		return $this->datefin;
	}

	public function getPromo(){
		return $this->promo;
	}

	public function getModules(){
	    return $this->modules;
    }

	public function getEffectif(){
		return count($this->etudiants);
	}

	public function getNbModules(){
		return count($this->modules);
	}


	public function fillStudents($listeEtudiants){
		$this->etudiants = $listeEtudiants;
	}

	public function fillModules($listeModules){
		$this->modules = $listeModules;
	}

	public static function getListe($idPf = 0){
		$SQLQuery = 'SELECT * FROM periodeformation ';
		if ($idPf != 0){
			$SQLQuery .= 'WHERE pf_id = :idpf ';
		}
		$SQLQuery .= 'ORDER BY pf_datefin DESC, pf_datedebut DESC';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		if ($idPf != 0){
			$SQLStmt->bindValue(':idpf', $idPf);
		}
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, Promotion::getById($SQLRow->promo_id));
			$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
			$newPf->fillModules(Module::getListeFromPf($SQLRow->pf_id));
			$retVal[] = $newPf;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getListeFromPromo($idPromo = 0){
		$SQLQuery = 'SELECT * FROM periodeformation ';
		$SQLQuery .= 'WHERE promo_id = :idpromo ';
		$SQLQuery .= 'ORDER BY pf_datefin DESC, pf_datedebut DESC';

		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idpromo', $idPromo);
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, Promotion::getById($SQLRow->promo_id));
			$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
			$newPf->fillModules(Module::getListeFromPf($SQLRow->pf_id));
			$retVal[] = $newPf;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getById($id){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM periodeformation WHERE pf_id = :idpf");
		$SQLStmt->bindValue(':idpf', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, Promotion::getById($SQLRow->promo_id));
		$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
        $newPf->fillModules(Module::getListeFromPf($SQLRow->pf_id));
		$SQLStmt->closeCursor();
		return $newPf;
	}
}