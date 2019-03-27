<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_etudiant.php';
include_once ROOTMODELS.'model_module.php';
include_once ROOTMODELS.'model_responsablepedago.php';
include_once ROOTMODELS.'model_statutperiodeformation.php';

class Periodeformation {
	private $id, $datedebut, $datefin, $promo, $responsable;
	private $etudiants, $modules, $statut;

	public function __construct($id = 0, $datedebut = '', $datefin = '', $promo = null, $idstatut = 1){
		$this->id = $id;
		$this->datedebut = $datedebut;
		$this->datefin = $datefin;
		$this->promo = $promo;
		$this->etudiants = array();
		$this->modules = array();
		$this->statut = StatutPeriodeFormation::getById($idstatut);
	}
	public function getId(){
		return $this->id;
	}

	public function getDateDebut(){
		$ret = new DateTime($this->datedebut);
		return $ret->format('d/m/Y');
	}

	public function getDateFin(){
		$ret = new DateTime($this->datefin);
		return $ret->format('d/m/Y');
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

	public function getResponsable(){
		return $this->responsable;
	}

	public function fillStudents($listeEtudiants){
		$this->etudiants = $listeEtudiants;
	}

	public function fillModules($listeModules){
		$this->modules = $listeModules;
	}

	public function getStatut(){
		return $this->statut;
	}

	public function setStatut($statut){
		$this->statut = $statut;
	}

	public function setResponsable($responsable){
		$this->responsable = $responsable;
	}

	public static function getListe($idPf = 0, $statut = 1){
		$SQLQuery = 'SELECT * ';
		$SQLQuery .= 'FROM periodeformation INNER JOIN promotion ON periodeformation.promo_id = promotion.promo_id WHERE 1=1 ';
		if ($idPf != 0){
			$SQLQuery .= 'AND periodeformation.pf_id = :idpf ';
		}else{
			$SQLQuery .= 'AND statpf_id = :idstatut ';
		}
		$SQLQuery .= 'ORDER BY promotion.promo_libelle, pf_datedebut DESC, pf_datefin DESC';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		if ($idPf != 0){
			$SQLStmt->bindValue(':idpf', $idPf);
		}else{
			$SQLStmt->bindValue(':idstatut', $statut);
		}

		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, Promotion::getById($SQLRow->promo_id), $SQLRow->statpf_id);
			$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
			$newPf->fillModules(Module::getListeFromPf($SQLRow->pf_id));
			$newPf->setResponsable(ResponsablePedago::getById($SQLRow->resp_id));
			$retVal[] = $newPf;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getListeFromPromo($idPromo = 0, $statut = 1){
		$SQLQuery = 'SELECT * ';
		$SQLQuery .= 'FROM periodeformation INNER JOIN promotion ON periodeformation.promo_id = promotion.promo_id ';
		$SQLQuery .= 'WHERE promotion.promo_id = :idpromo ';
		$SQLQuery .= 'AND statpf_id = :idstatut ';
		$SQLQuery .= 'ORDER BY promotion.promo_libelle, pf_datedebut DESC, pf_datefin DESC';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idpromo', $idPromo);
		$SQLStmt->bindValue(':idstatut', $statut);
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, Promotion::getById($SQLRow->promo_id), $SQLRow->statpf_id);
			$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
			$newPf->fillModules(Module::getListeFromPf($SQLRow->pf_id));
			$newPf->setResponsable(ResponsablePedago::getById($SQLRow->resp_id));
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
		$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, Promotion::getById($SQLRow->promo_id), $SQLRow->statpf_id);
		$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
        $newPf->fillModules(Module::getListeFromPf($SQLRow->pf_id));
		$newPf->setResponsable(ResponsablePedago::getById($SQLRow->resp_id));
		$SQLStmt->closeCursor();
		return $newPf;
	}

	public static function update($pf){
		$SQLQuery = "UPDATE promotion SET promo_libelle = :nom WHERE promo_id = :idpromo";
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':nom', $promo->getLibelle());
		$SQLStmt->bindValue(':idpromo', $promo->getId());

		/*if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}*/
	}

	public static function insert($pf){
		$SQLQuery = 'INSERT INTO promotion(promo_libelle, eco_id) VALUES (:nom, :idecole)';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':nom', $promo->getLibelle());
		$SQLStmt->bindValue(':idecole', $promo->getEcole()->getId());
		/*if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}*/
	}
}