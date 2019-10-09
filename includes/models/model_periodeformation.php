<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_etudiant.php';
include_once ROOTMODELS.'model_module.php';
include_once ROOTMODELS.'model_responsablepedago.php';
include_once ROOTMODELS.'model_statutperiodeformation.php';

class Periodeformation {
	private $id, $datedebut, $datefin, $duree, $idPromo, $responsable;
	private $etudiants, $modules, $statut, $unitesenseignement;

	public function __construct($id = 0, $datedebut = '', $datefin = '', $idPromo = 0, $idstatut = 1, $duree = 0){
		$this->id = $id;
		$this->datedebut = $datedebut;
		$this->datefin = $datefin;
		$this->idPromo = $idPromo;
		$this->etudiants = array();
		$this->modules = array();
		$this->unitesenseignement = array();
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

	public function setDateDebut($date){
		$this->datedebut = $date;
	}

	public function getDateFin(){
		if ($this->datefin == ''){
			return null;
		}else{
			$ret = new DateTime($this->datefin);
			return $ret->format('d/m/Y');
		}
	}

	public function setDateFin($date){
		$this->datefin = $date;
	}

	public function getIdPromo(){
		return $this->idPromo;
	}

	public function setIdPromo($idPromo){
		$this->idPromo = $idPromo;
	}

	public function getStudents(){
		return $this->etudiants;
	}

	public function getEffectif(){
		return count($this->etudiants);
	}

	public function getNbModules(){
		return count($this->modules);
	}

	public function getModules(){
		return $this->modules;
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

	public function setModules($modules){
		$this->modules = $modules;
	}

	public function getUnitesenseignement()	{
		return $this->unitesenseignement;
	}

	public function setUnitesenseignement($unitesenseignement){
		$this->unitesenseignement = $unitesenseignement;
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

	public static function getListe(StatutPeriodeFormation $statut = null){
		$SQLQuery = 'SELECT periodeformation.pf_id ';
		$SQLQuery .= 'FROM periodeformation INNER JOIN promotion ON periodeformation.promo_id = promotion.promo_id WHERE 1=1 ';
		$SQLQuery .= 'AND statpf_id = :idstatut ';
		$SQLQuery .= 'ORDER BY promotion.promo_libelle, pf_datedebut DESC, pf_datefin DESC';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idstatut', $statut->getId());
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPf = Periodeformation::getById($SQLRow->pf_id);
			$retVal[] = $newPf;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getListeFromPromo(Promotion $promo = null){
		$SQLQuery = 'SELECT pf_id ';
		$SQLQuery .= 'FROM periodeformation ';
		$SQLQuery .= 'WHERE promo_id = :idpromo ';
		$SQLQuery .= 'ORDER BY pf_datedebut DESC, pf_datefin DESC';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idpromo', $promo->getId());
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
		$newPf = new Periodeformation($SQLRow->pf_id, $SQLRow->pf_datedebut, $SQLRow->pf_datefin, $SQLRow->promo_id, $SQLRow->statpf_id);
		$newPf->fillStudents(Etudiant::getListeFromPf($SQLRow->pf_id));
		$newPf->setUnitesenseignement(UniteEnseignement::getListeFromPf($SQLRow->pf_id));
		$newPf->setModules(Module::getListeFromPf($SQLRow->pf_id));
		$newPf->setResponsable(ResponsablePedago::getById($SQLRow->resp_id));
		$SQLStmt->closeCursor();
		return $newPf;
	}

	public static function update(PeriodeFormation $pf){
		$SQLQuery = 'UPDATE periodeformation ';
		$SQLQuery .= 'SET pf_datedebut = :datedebut, ';
		$SQLQuery .= 'pf_datefin = :datefin, ';
		$SQLQuery .= 'pf_duree = :duree, ';
		$SQLQuery .= 'promo_id = :idpromo, ';
		$SQLQuery .= 'resp_id = :idresp, ';
		$SQLQuery .= 'statpf_id = :idstatut ';
		$SQLQuery .= 'WHERE pf_id = :idpf';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':datedebut', date_fr_to_mysql($pf->getDateDebut()));
		$SQLStmt->bindValue(':datefin', date_fr_to_mysql($pf->getDateFin()));
		$SQLStmt->bindValue(':duree', $pf->getDuree());
		$SQLStmt->bindValue(':idpromo', $pf->getIdPromo());
		$SQLStmt->bindValue(':idresp', (!is_null($pf->getResponsable())?$pf->getResponsable()->getId():null));
		$SQLStmt->bindValue(':idstatut', $pf->getStatut()->getId());
		$SQLStmt->bindValue(':idpf', $pf->getId());

		if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}
	}

	public static function insert(Periodeformation $pf){
		$SQLQuery = 'INSERT INTO periodeformation(pf_datedebut, pf_datefin, pf_duree, promo_id, resp_id, statpf_id)
					VALUES (:datedebut, :datefin, :duree, :idpromo, :idresp, :idstatut)';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':datedebut', date_fr_to_mysql($pf->getDateDebut()));
		$SQLStmt->bindValue(':datefin', date_fr_to_mysql($pf->getDateFin()));
		$SQLStmt->bindValue(':duree', $pf->getDuree());
		$SQLStmt->bindValue(':idpromo', $pf->getIdPromo());
		$SQLStmt->bindValue(':idresp', (!is_null($pf->getResponsable())?$pf->getResponsable()->getId():null));
		$SQLStmt->bindValue(':idstatut', $pf->getStatut()->getId());
		if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}
	}

	public function addStudent(Etudiant $etudiant){
		//Je rajoute l'étudiant à la pf
		$SQLQuery3 = "INSERT INTO integrer (etu_id, pf_id) VALUES (:idetudiant, :idPf)";
		$SQLStmt3 = DAO::getInstance()->prepare($SQLQuery3);
		$SQLStmt3->bindValue(':idetudiant', $etudiant->getId());
		$SQLStmt3->bindValue(':idPf', $this->getId());
		if (!$SQLStmt3->execute()) {
			var_dump($SQLStmt3->errorInfo());
			return false;
		}else{
			$SQLQuery4 = "INSERT INTO appreciationGenerale(etu_id, pf_id) VALUES (:idetudiant, :idPf)";
			$SQLStmt4 = DAO::getInstance()->prepare($SQLQuery4);
			$SQLStmt4->bindValue(':idetudiant', $etudiant->getId());
			$SQLStmt4->bindValue(':idPf', $this->getId());
			if (!$SQLStmt4->execute()){
				var_dump($SQLStmt4->errorInfo());
				return false;
			}else{
				return true;
			}
		}
	}

	public function findStudent(Etudiant $etudiant){
		$retVal = null;
		$SQLQuery = "SELECT etu_id FROM integrer WHERE pf_id = :idPf AND etu_id = :idetudiant";
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idetudiant', $etudiant->getId());
		$SQLStmt->bindValue(':idPf', $this->getId());
		if ($SQLStmt->execute()){
			$SQLRow = $SQLStmt->fetchObject();
			if ($SQLStmt->rowCount() == 0){
				$retVal = new Etudiant();
			}else{
				$retVal = Etudiant::getById($SQLRow->etu_id);
			}
			$SQLStmt->closeCursor();
		}else{
			$retVal = new Etudiant();
		}
		return $retVal;
	}
}