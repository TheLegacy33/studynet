<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_promotion.php';

	class Ecole {
		const defaultLogo = 'logo_ecole.png';
		private $id, $nom, $logo;
		private array $promotions;

		public function __construct($id = 0, $nom = '', $logo = ''){
			$this->id = $id;
			$this->nom = $nom;
			$this->logo = ($logo==''?null:$logo);

			$this->promotions = array();
		}

		public function getNom(){
			return $this->nom;
		}

		public static function getDefaultLogo() : string{
			return Ecole::defaultLogo;
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

		public function setPromotions($promotions){
			$this->promotions = $promotions;
		}

		public function getPromotions() : array{
			return $this->promotions;
		}

		public function getNbPromos() : int{
			return (count($this->promotions));
		}

		public static function getListe() : array{
			$SQLStmt = DAO::getInstance()->prepare("SELECT eco_id FROM studynet.ecole ORDER BY eco_nom");
			$SQLStmt->execute();
			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newEcole = Ecole::getById($SQLRow->eco_id);
				$retVal[] = $newEcole;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getListeForAPI() : array{
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM studynet.ecole ORDER BY eco_nom");
			$SQLStmt->execute();
			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$SQLRow->eco_logo = ROOTHTMLUPLOADS.$SQLRow->eco_logo;
				$retVal[] = $SQLRow;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id) : Ecole{
			$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM studynet.ecole WHERE eco_id = :idecole");
			$SQLStmt->bindValue(':idecole', $id);
			$SQLStmt->execute();
			$SQLRow = $SQLStmt->fetchObject();
			$newEcole = new Ecole($SQLRow->eco_id, $SQLRow->eco_nom, $SQLRow->eco_logo);
			$newEcole->setPromotions(Promotion::getListe($newEcole));
			$SQLStmt->closeCursor();
			return $newEcole;
		}

		public static function update(Ecole $ecole) : bool{
			$SQLQuery = "UPDATE studynet.ecole SET eco_nom = :nom, eco_logo = :logo WHERE eco_id = :idecole";
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

		public static function insert(Ecole $ecole) : bool{
			$SQLQuery = 'INSERT INTO studynet.ecole(eco_nom, eco_logo) VALUES (:nom, :logo)';
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