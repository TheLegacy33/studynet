<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_etudiant.php';
	include_once ROOTMODELS.'model_module.php';
	include_once ROOTMODELS.'model_statutrattrapage.php';
	include_once ROOTMODELS.'model_delairattrapage.php';

	class Rattrapage {
		private $rat_id, $ficsujet, $daterecup, $ficretour, $dateretour, $md5sujet, $md5docretour;
		private $etudiant, $module, $statut, $delai;

		public function __construct($rat_id = 0, $daterecupsujet = null, $dateretouretudiant = null, $fichiersujet = null, $md5ficsujet = null, $fichierretouretudiant = null, $md5retetudiant = null, $statutId = 1){
			$this->rat_id = $rat_id;
			$this->ficsujet = $fichiersujet;
			$this->daterecup = $daterecupsujet;
			$this->dateretour = $dateretouretudiant;
			$this->ficretour = $fichierretouretudiant;
			$this->md5sujet = $md5ficsujet;
			$this->md5docretour = $md5retetudiant;
			$this->etudiant = null;
			$this->module = null;
			$this->delai = new DelaiRattrapage();
			$this->statut = StatutRattrapage::getById($statutId);
		}

		public function getId(){
			return $this->rat_id;
		}

		public function getEtudiant(){
			return $this->etudiant;
		}

		public function getModule(){
			return $this->module;
		}

		public function getStatut(){
			return $this->statut;
		}

		public function getDateRecup(){
			$ret = new DataTime($this->daterecup);
			return $ret->format('d/m/Y');
		}

		public function getDateRetour(){
			$ret = new DateTime($this->dateretour);
			return $ret->format('d/m/Y');
		}

		public function getFicSujet(){
			return $this->ficsujet;
		}

		public function getFicRetour(){
			return $this->ficretour;
		}

		public function getMd5FicRetour(){
			return $this->md5docretour;
		}

		public function getMd5FicSujet(){
			return $this->md5sujet;
		}

		public function getDelai(){
			return $this->delai;
		}

		public function setEtudiant($etudiant){
			$this->etudiant = $etudiant;
		}

		public function setModule($module){
			$this->module = $module;
		}

		public function setStatut($statut){
			$this->statut = $statut;
		}

		public function setDelai($delai){
			$this->delai = $delai;
		}

		public function setDateRecup($datetime){
			$this->daterecup = $datetime;
		}

		public function setDateRendu($datetime){
			$this->dateretour = $datetime;
		}

		public function setFicRetour($fichier){
			$this->ficretour = $fichier;
		}

		public function setMd5Retour($md5){
			$this->md5docretour = $md5;
		}

		public function setFicSujet($fichier){
			$this->ficsujet = $fichier;
		}

		public function setMd5Sujet($md5){
			$this->md5sujet = $md5;
		}

		public function downloaded(){
			return !is_null($this->daterecup);
		}

		public function uploaded(){
			return !is_null($this->dateretour);
		}

		public function expired(){
			$DTNow = new DateTime('now');
			$DTRecup = new DateTime($this->daterecup);
			$DTRenduAttendue = $DTRecup->add(new DateInterval($this->delai->getInterval()))->add(new DateInterval('PT1M'));
			return($DTNow > $DTRenduAttendue);
		}

		public static function getListeForEtudiant($idEtudiant = 0){
			if ($idEtudiant == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM rattrapage INNER JOIN statutrattrapage ON rattrapage.statr_id = statutrattrapage.statr_id  ';
			$SQLQuery .= 'WHERE etu_id = :idetudiant ';
			$SQLQuery .= 'ORDER BY rattrapage.statr_id, rat_daterecupsujet';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idetudiant', $idEtudiant);
			$SQLStmt->execute();

			$retVal = array();
			while ($SQLRow = $SQLStmt->fetchObject()){
				$newRattrapage = new Rattrapage($SQLRow->rat_id, $SQLRow->rat_daterecupsujet, $SQLRow->rat_dateretouretudiant, $SQLRow->rat_fichiersujet, $SQLRow->rat_md5ficsujet, $SQLRow->rat_fichierretouretudiant, $SQLRow->rat_md5retetudiant, $SQLRow->statr_id);
				$newRattrapage->setEtudiant(Etudiant::getById($SQLRow->etu_id));
				$newRattrapage->setModule(Module::getById($SQLRow->mod_id));
				$newRattrapage->setDelai(new DelaiRattrapage($SQLRow->rat_valdelai, $SQLRow->rat_unitdelai));
				$retVal[] = $newRattrapage;
			}
			$SQLStmt->closeCursor();
			return $retVal;
		}

		public static function getById($id){
			if ($id == 0){
				return null;
			}
			$SQLQuery = 'SELECT * FROM rattrapage INNER JOIN statutrattrapage ON rattrapage.statr_id = statutrattrapage.statr_id  ';
			$SQLQuery .= 'WHERE rat_id = :idrattrapage';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idrattrapage', $id);
			$SQLStmt->execute();

			if ($SQLStmt->rowCount() == 0){
				$newRattrapage = null;
			}else{
				$SQLRow = $SQLStmt->fetchObject();
				$newRattrapage = new Rattrapage($SQLRow->rat_id, $SQLRow->rat_daterecupsujet, $SQLRow->rat_dateretouretudiant, $SQLRow->rat_fichiersujet, $SQLRow->rat_md5ficsujet, $SQLRow->rat_fichierretouretudiant, $SQLRow->rat_md5retetudiant);
				$newRattrapage->setEtudiant(Etudiant::getById($SQLRow->etu_id));
				$newRattrapage->setModule(Module::getById($SQLRow->mod_id));
				$newRattrapage->setDelai(new DelaiRattrapage($SQLRow->rat_valdelai, $SQLRow->rat_unitdelai));
			}
			$SQLStmt->closeCursor();
			return $newRattrapage;
		}

		public static function existsForStudent($idRattrapage, $idEtudiant){
			if ($idRattrapage == 0 OR $idEtudiant == 0){
				return false;
			}
			$SQLQuery = 'SELECT COUNT(rat_id) FROM rattrapage ';
			$SQLQuery .= 'WHERE rat_id = :idrattrapage ';
			$SQLQuery .= 'AND etu_id = :idEtudiant';
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':idrattrapage', $idRattrapage);
			$SQLStmt->bindValue(':idEtudiant', $idEtudiant);
			$SQLStmt->execute();

			$SQLRow = $SQLStmt->fetch();
			$retVal = $SQLRow[0];
			$SQLStmt->closeCursor();
			return $retVal > 0;
		}

		public static function update(Rattrapage $rattrapage){
			//Je commence par récupérer à nouveau les données du rattrapage
			$ratToUpdate = Rattrapage::getById($rattrapage->getId());

			$SQLQuery = 'UPDATE rattrapage SET rat_id = rat_id, ';

			if ($ratToUpdate->getFicSujet() != $rattrapage->getFicSujet()){
				$SQLQuery .= 'rat_fichiersujet = :fichiersujet, ';
			}
			if ($ratToUpdate->getDateRecup() != $rattrapage->getDateRecup()){
				$SQLQuery .= 'rat_daterecupsujet = :daterecupsujet, ';
			}
			if ($ratToUpdate->getDateRetour() != $rattrapage->getDateRetour()){
				$SQLQuery .= 'rat_dateretouretudiant = :dateretouretudiant, ';
			}
			if ($ratToUpdate->getFicRetour() != $rattrapage->getFicRetour()){
				$SQLQuery .= 'rat_fichierretouretudiant = :fichierretouretudiant, ';
			}
			if ($ratToUpdate->getMd5FicRetour() != $rattrapage->getMd5FicRetour()){
				$SQLQuery .= 'rat_md5retetudiant = :md5ficretour, ';
			}
			if ($ratToUpdate->getMd5FicSujet() != $rattrapage->getMd5FicSujet()){
				$SQLQuery .= 'rat_md5ficsujet = :md5ficsujet, ';
			}
			if ($ratToUpdate->getStatut()->getId() != $rattrapage->getStatut()->getId()){
				$SQLQuery .= 'statr_id = :idstatut, ';
			}
			if ($ratToUpdate->getDelai()->getValeur() != $rattrapage->getDelai()->getValeur()){
				$SQLQuery .= 'rat_valdelai = :valdelai, ';
			}
			if ($ratToUpdate->getDelai()->getUnite() != $rattrapage->getDelai()->getUnite()){
				$SQLQuery .= 'rat_unitdelai = :unitdelai, ';
			}

			$SQLQuery = substr($SQLQuery, 0 ,strlen($SQLQuery) - 2).' ';
			$SQLQuery .= 'WHERE rat_id = :idrattrapage';

			$stmt = DAO::getInstance()->prepare($SQLQuery);
			if ($ratToUpdate->getFicSujet() != $rattrapage->getFicSujet()){
				$stmt->bindValue(':fichiersujet', $rattrapage->getFicSujet());
			}
			if ($ratToUpdate->getDateRecup() != $rattrapage->getDateRecup()){
				$stmt->bindValue(':daterecupsujet', $rattrapage->getDateRecup());
			}
			if ($ratToUpdate->getDateRetour() != $rattrapage->getDateRetour()){
				$stmt->bindValue(':dateretouretudiant', $rattrapage->getDateRetour());
			}
			if ($ratToUpdate->getFicRetour() != $rattrapage->getFicRetour()){
				$stmt->bindValue(':fichierretouretudiant', $rattrapage->getFicRetour());
			}
			if ($ratToUpdate->getMd5FicRetour() != $rattrapage->getMd5FicRetour()){
				$stmt->bindValue(':md5ficretour', $rattrapage->getMd5FicRetour());
			}
			if ($ratToUpdate->getMd5FicSujet() != $rattrapage->getMd5FicSujet()){
				$stmt->bindValue(':md5ficsujet', $rattrapage->getMd5FicSujet());
			}
			if ($ratToUpdate->getStatut()->getId() != $rattrapage->getStatut()->getId()){
				$stmt->bindValue(':idstatut', $rattrapage->getStatut()->getId());
			}
			if ($ratToUpdate->getDelai()->getValeur() != $rattrapage->getDelai()->getValeur()){
				$stmt->bindValue(':valdelai', $rattrapage->getDelai()->getValeur());
			}
			if ($ratToUpdate->getDelai()->getUnite() != $rattrapage->getDelai()->getUnite()){
				$stmt->bindValue(':unitdelai', $rattrapage->getDelai()->getUnite());
			}
			$stmt->bindValue(':idrattrapage', $rattrapage->getId());

			if ($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}
	}