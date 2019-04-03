<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_etudiant.php';
include_once ROOTMODELS.'model_module.php';
include_once ROOTMODELS.'model_responsablepedago.php';
include_once ROOTMODELS.'model_statutperiodeformation.php';

class Periodeformation {
	private $id, $datedebut, $datefin, $duree, $promo, $responsable;
	private $etudiants, $unitesenseignements, $statut;

	public function __construct($id = 0, $datedebut = '', $datefin = '', $promo = null, $idstatut = 1, $duree = 0){
		$this->id = $id;
		$this->datedebut = $datedebut;
		$this->datefin = $datefin;
		$this->promo = $promo;
		$this->etudiants = array();
		$this->unitesenseignements = array();
		$this->statut = StatutPeriodeFormation::getById($idstatut);
		$this->duree = $duree;
	}
	public function getId(){
		return $this->id;
	}

	public function getDateDebut(){
		if ($this->datedebut == ''){
			return null;
		}else{
			$ret = new DateTime($this->datedebut);
			return $ret->format('d/m/Y');
		}
	}

	public function getDateFin(){
		$ret = new DateTime($this->datefin);
		return $ret->format('d/m/Y');
	}

	public function getPromo(){
		return $this->promo;
	}

	public function getStudents(){
		return $this->etudiants;
	}

	public function getEffectif(){
		return count($this->etudiants);
	}

	public function getNbModules(){
		$retVal = 0;
		foreach ($this->unitesenseignements as $ue){
			$retVal += $ue->getNbModules();
		}
		return $retVal;
	}

	public function getUE(){
		return $this->unitesenseignements;
	}

	public function getDuree(){
		return $this->duree;
	}

	public function getResponsable(){
		return $this->responsable;
	}

	public function fillStudents($listeEtudiants){
		$this->etudiants = $listeEtudiants;
	}

	public function fillUE($listeUE){
		$this->unitesenseignements = $listeUE;
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

	public function setDuree($duree){
		$this->duree = $duree;
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
			$newPf = Periodeformation::getById($SQLRow->pf_id);
			$retVal[] = $newPf;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getListeFromPromo($idPromo = 0, $statut = 1){
		$SQLQuery = 'SELECT * ';
		$SQLQuery .= 'FROM periodeformation ';
		$SQLQuery .= 'WHERE promo_id = :idpromo ';
		$SQLQuery .= 'AND statpf_id = :idstatut ';
		$SQLQuery .= 'ORDER BY pf_datedebut DESC, pf_datefin DESC';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idpromo', $idPromo);
		$SQLStmt->bindValue(':idstatut', $statut);
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPf = Periodeformation::getById($SQLRow->pf_id);
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
		$newPf->fillUE(UniteEnseignement::getListeFromPF($SQLRow->pf_id));
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

	public static function insert(Periodeformation $pf){
		$SQLQuery = 'INSERT INTO periodeformation(pf_datedebut, pf_datefin, pf_duree, promo_id, resp_id, statpf_id) ';
		$SQLQuery .= 'VALUES (:datedebut, :datefin, :duree, :idpromo, :idresp, :idstatut)';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':datedebut', date_fr_to_mysql($pf->getDateDebut()));
		$SQLStmt->bindValue(':datefin', date_fr_to_mysql($pf->getDateFin()));
		$SQLStmt->bindValue(':duree', $pf->getDuree());
		$SQLStmt->bindValue(':idpromo', $pf->getPromo()->getId());
		$SQLStmt->bindValue(':idresp', (!is_null($pf->getResponsable())?$pf->getResponsable()->getId():null));
		$SQLStmt->bindValue(':idstatut', $pf->getStatut()->getId());
		if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}
	}
}