<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';

	class Ecole {
		private $id, $nom, $logo;
		private $promotions;

		public function __construct($id = 0, $nom = '', $logo = ''){
			$this->id = $id;
			$this->nom = $nom;
			$this->logo = $logo;

			$this->promotions = array();
		}

		public function getNom(){
			return $this->nom;
		}

		public function setNom($nom){
			$this->nom = $nom;
		}

		public function getId(){
			return $this->id;
		}

		public function getLogo(){
			return $this->logo;
		}

		public function setLogo($logo){
			$this->logo = $logo;
		}

		public function fillPromotions($listePromos){
			$this->promotions = $listePromos;
		}

		public function getNbPromos(){
			return (count($this->promotions));
		}

		public static function getListe(){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM ecole ORDER BY eco_nom");
			$SQLStmt->execute();
			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEcole = new Ecole($SQLRow->eco_id, $SQLRow->eco_nom, $SQLRow->eco_logo);
				$newEcole->fillPromotions(Promotion::getListeFromEcole($SQLRow->eco_id));
				$retVal[] = $newEcole;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListeForAPI(){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM ecole ORDER BY eco_nom");
			$SQLStmt->execute();
			$retVal = $SQLStmt->fetchAll(PDO::FETCH_OBJ);
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM ecole WHERE eco_id = :idecole");
			$SQLStmt->bindValue(':idecole', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newPromo = new Ecole($SQLRow->eco_id, $SQLRow->eco_nom, $SQLRow->eco_logo);
			$SQLStmt->closeCursor();
			return $newPromo;
		}

		public static function update($ecole){
			$SQLQuery = "UPDATE ecole SET eco_nom = :nom, eco_logo = :logo WHERE eco_id = :idecole";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':nom', $ecole->getNom());
			$SQLStmt->bindValue(':logo', $ecole->getLogo());
			$SQLStmt->bindValue(':idecole', $ecole->getId());

			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}

		public static function insert($ecole){
			$SQLQuery = 'INSERT INTO ecole(eco_nom, eco_logo) ';
			$SQLQuery .= 'VALUES (:nom, :logo)';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':nom', $ecole->getNom());
			$SQLStmt->bindValue(':logo', $ecole->getLogo());
			if (!$SQLStmt->execute()){
				var_dump($SQLStmt->errorInfo());
				return false;
			}else{
				return true;
			}
		}
	}
?>