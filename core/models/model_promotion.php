<?php
include_once ROOTMODELS.'DAO.php';
include_once ROOTMODELS.'model_ecole.php';
include_once ROOTMODELS.'model_uniteenseignement.php';

class Promotion {
	private $id, $libelle, $idEcole;
	private $pf;

	public function __construct($id = 0, $libelle = '', $idEcole = 0){
		$this->id = $id;
		$this->libelle = $libelle;
		$this->idEcole = $idEcole;
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

	public function getPf(StatutPeriodeFormation $statut = null){
		$retVal = array();
		if (is_null($statut)){
			$retVal = $this->pf;
		}else{
			foreach ($this->pf as $pf){
				if ($pf->getStatut()->equals($statut)){
					$retVal[] = $pf;
				}
			}
		}
		return $retVal;
	}

	public function setPf($pf){
		$this->pf = $pf;
	}

	public function getIdEcole(){
		return $this->idEcole;
	}

	public function setIdEcole($idEcole){
		$this->idEcole = $idEcole;
	}

	public static function getListe(Ecole $ecole = null){
		$SQLQuery = 'SELECT promo_id, promo_libelle, promotion.eco_id FROM promotion INNER JOIN ecole ON promotion.eco_id = ecole.eco_id ';
		if (!is_null($ecole)){
			$SQLQuery .= 'WHERE ecole.eco_id = :idEcole ';
		}
		$SQLQuery .= 'ORDER BY eco_nom, promo_libelle';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		if (!is_null($ecole)){
			$SQLStmt->bindValue(':idEcole', $ecole->getId());
		}
		$SQLStmt->execute();
		$retVal = array();
		while ($SQLRow = $SQLStmt->fetchObject()){
			$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle, $SQLRow->eco_id);
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
		$newPromo = new Promotion($SQLRow->promo_id, $SQLRow->promo_libelle, $SQLRow->eco_id);
		$newPromo->setPf(Periodeformation::getListeFromPromo($newPromo));
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

	public static function update(Promotion $promo){
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

	public static function insert(Promotion $promo){
		$SQLQuery = 'INSERT INTO promotion(promo_libelle, eco_id) VALUES (:nom, :idecole)';
		$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
		$SQLStmt->bindValue(':nom', $promo->getLibelle());
		$SQLStmt->bindValue(':idecole', $promo->getIdEcole());
		if (!$SQLStmt->execute()){
			var_dump($SQLStmt->errorInfo());
			return false;
		}else{
			return true;
		}
	}
}