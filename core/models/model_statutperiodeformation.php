<?php
include_once ROOTMODELS.'DAO.php';

class StatutPeriodeFormation{
	private $statpf_id, $statpf_libelle;

	public function __construct($id = 0, $libelle = ''){
		$this->statpf_id = $id;
		$this->statpf_libelle = $libelle;
	}

	public function getId(){
		return $this->statpf_id;
	}

	public function getLibelle(){
		return $this->statpf_libelle;
	}

	public static function getById($id){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutpf WHERE statpf_id = :idstatut");
		$SQLStmt->bindValue(':idstatut', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		$newStatut = new StatutPeriodeFormation($SQLRow->statpf_id, $SQLRow->statpf_libelle);
		$SQLStmt->closeCursor();
		return $newStatut;
	}

	public static function getByLibelle($libelle){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutpf WHERE statpf_libelle = :libstatut");
		$SQLStmt->bindValue(':libstatut', $libelle);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		$newStatut = new StatutPeriodeFormation($SQLRow->statpf_id, $SQLRow->statpf_libelle);
		$SQLStmt->closeCursor();
		return $newStatut;
	}

	public static function getListe(){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutpf ORDER BY statpf_libelle");
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newStatut = new StatutPeriodeFormation($SQLRow->statpf_id, $SQLRow->statpf_libelle);
			$retVal[] = $newStatut;
		}
		$SQLStmt->closeCursor();
		return $retVal;
	}

	public function __toString(){
		return $this->statpf_libelle;
	}

	public function equals(StatutPeriodeFormation $statut){
		if ($this->getId() == $statut->getId() AND $this->getLibelle() == $statut->getLibelle()){
			return true;
		}else{
			return false;
		}
	}
}
?>