<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_etudiant.php';
include_once ROOTMODELS.'model_ecole.php';
include_once ROOTMODELS.'model_uniteenseignement.php';

class Promotion {
	private $id, $libelle, $ecole;
	private $etudiants, $unitesenseignement;

	public function __construct($id = 0, $libelle, $ecole){
		$this->id = $id;
		$this->libelle = $libelle;
		$this->ecole = $ecole;

		$this->etudiants = array();
		$this->unitesenseignement = array();
	}

	public function getLibelle(){
		return $this->libelle;
	}

	public function getEcole(){
		return $this->ecole;
	}

	public function getId(){
		return $this->id;
	}

	public function getEffectif(){
		return count($this->etudiants);
	}

	public function fillStudents($listeEtudiants){
		$this->etudiants = $listeEtudiants;
	}

	public function getUnitesEnseignement(){
		return $this->unitesenseignement;
	}

	public function fillUnitesEnseignement($listeUnitesEnseignement){
		$this->unitesenseignement = $listeUnitesEnseignement;
	}

	public static function getListe(){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM promotion");
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPromo = new Promotion($SQLRow->promo_libelle, Ecole::getById($SQLRow->eco_id));
			$newPromo->fillStudents(Etudiant::getListeFromPromo($SQLRow->promo_id));
			$newPromo->fillUnitesEnseignement(UniteEnseignement::getListeFromPromo($SQLRow->promo_id));
			$retVal[] = $newPromo;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getListeFromEcole($idEcole){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM promotion WHERE eco_id = :idecole");
		$SQLStmt->bindValue(':idecole', $idEcole);
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle, Ecole::getById($SQLRow->eco_id));
			$newPromo->fillStudents(Etudiant::getListeFromPromo($SQLRow->promo_id));
			$newPromo->fillUnitesEnseignement(UniteEnseignement::getListeFromPromo($SQLRow->promo_id));
			$retVal[] = $newPromo;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getListeFromEcoleForAPI($idEcole){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM promotion WHERE eco_id = :idecole");
		$SQLStmt->bindValue(':idecole', $idEcole);
		$SQLStmt->execute();
		$retVal = $SQLStmt->fetchAll(PDO::FETCH_OBJ);
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public static function getById($id){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM promotion WHERE promo_id = :idpromo");
		$SQLStmt->bindValue(':idpromo', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle, Ecole::getById($SQLRow->eco_id));
		$newPromo->fillStudents(Etudiant::getListeFromPromo($SQLRow->promo_id));
		$newPromo->fillUnitesEnseignement(UniteEnseignement::getListeFromPromo($SQLRow->promo_id));
		$SQLStmt->closeCursor();
		return $newPromo;
	}

	public static function getByIdPf($id){
		$SQLQuery = 'SELECT * ';
		$SQLQuery .= 'FROM promotion INNER JOIN periodeformation ON promotion.promo_id = periodeformation.promo_id ';
		$SQLQuery .= 'WHERE periodeformation.pf_id = :idpf';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idpf', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle, Ecole::getById($SQLRow->eco_id));
		$newPromo->fillStudents(Etudiant::getListeFromPromo($SQLRow->promo_id));
		$newPromo->fillUnitesEnseignement(UniteEnseignement::getListeFromPromo($SQLRow->promo_id));
		$SQLStmt->closeCursor();
		return $newPromo;
	}
}