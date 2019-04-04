<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_ecole.php';
include_once ROOTMODELS.'model_uniteenseignement.php';

class Promotion {
	private $id, $libelle;
	private $pf;

	public function __construct($id = 0, $libelle = ''){
		$this->id = $id;
		$this->libelle = $libelle;

		$this->pf = array();
	}

	public function setLibelle($libelle){
		$this->libelle = $libelle;
	}

	public function getLibelle(){
		return $this->libelle;
	}

	public function getId(){
		return $this->id;
	}

	public function getPf(){
		return $this->pf;
	}

	public function setPf($pf){
		$this->pf = $pf;
	}

	public static function getListe(Ecole $ecole = null){
		$SQLQuery = 'SELECT * FROM promotion ';
		if (!is_null($ecole)){
			$SQLQuery .= 'WHERE eco_id = :idEcole ';
		}
		$SQLQuery .= 'ORDER BY promo_libelle';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		if (!is_null($ecole)){
			$SQLStmt->bindValue(':idecole', $ecole->getId());
		}
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle);
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
		$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle);
		$newPromo->setPf(Periodeformation::getListeFromPromo($id));
		$SQLStmt->closeCursor();
		return $newPromo;
	}

	public static function getByIdPf($id){
		$SQLQuery = 'SELECT promo_id FROM periodeformation ';
		$SQLQuery .= 'WHERE pf_id = :idpf';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':idpf', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		$newPromo = Promotion::getById($SQLRow->promo_id);
		$SQLStmt->closeCursor();
		return $newPromo;
	}

	public static function update($promo){
		$SQLQuery = "UPDATE promotion SET promo_libelle = :nom WHERE promo_id = :idpromo";
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':nom', $promo->getLibelle());
		$SQLStmt->bindValue(':idpromo', $promo->getId());

		if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}
	}

	public static function insert($promo){
		$SQLQuery = 'INSERT INTO promotion(promo_libelle, eco_id) VALUES (:nom, :idecole)';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':nom', $promo->getLibelle());
		$SQLStmt->bindValue(':idecole', $promo->getEcole()->getId());
		if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}
	}
}