<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_etudiant.php';
	include_once ROOTMODELS.'model_module.php';

	class StatutRattrapage{
	    private $statr_id, $statr_libelle;

	    public function __construct($id = 0, $libelle = ''){
	        $this->statr_id = $id;
	        $this->statr_libelle = $libelle;
        }

        public function getId(){
	        return $this->statr_id;
        }

        public function getLibelle(){
	        return $this->statr_libelle;
        }

        public static function getById($id){
            $SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutrattrapage WHERE statr_id = :idstatut");
            $SQLStmt->bindValue(':idstatut', $id);
            $SQLStmt->execute();
            $SQLRow = $SQLStmt->fetchObject();
            $newPromo = new StatutRattrapage($SQLRow->statr_id, $SQLRow->statr_libelle);
            $SQLStmt->closeCursor();
            return $newPromo;
        }

        public static function getListe(){
            $SQLStmt = DAO::getInstance()->prepare("SELECT * FROM statutrattrapage ORDER BY statr_libelle");
            $SQLStmt->execute();
            $retVal = array();
            while ($SQLRow = $SQLStmt->fetchObject()){
                $newStatut = new StatutRattrapage($SQLRow->statr_id, $SQLRow->statr_libelle);
                $retVal[] = $newStatut;
            }
            $SQLStmt->closeCursor();
            return $retVal;
        }
    }

	class Rattrapage {
		private $rat_id, $ficsujet, $daterecup, $ficretour, $dateretour, $md5sujet, $md5docretour;
		private $etudiant, $module, $statut;

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

        public function setEtudiant($etudiant){
            $this->etudiant = $etudiant;
        }

        public function setModule($module){
            $this->module = $module;
        }

        public function setStatut($statut){
		    $this->statut = $statut;
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
                $newRattrapage = new Rattrapage($SQLRow->rat_id, $SQLRow->rat_daterecupsujet, $SQLRow->rat_dateretouretudiant, $SQLRow->rat_fichiersujet, $SQLRow->rat_md5ficsujet, $SQLRow->rat_fichierretouretudiant, $SQLRow->rat_md5retetudiant);
                $newRattrapage->setEtudiant(Etudiant::getById($SQLRow->etu_id));
                $newRattrapage->setModule(Module::getById($SQLRow->mod_id));
                $retVal[] = $newRattrapage;
            }
            $SQLStmt->closeCursor();
            return $retVal;
        }

        public static function getById($id){

		}

		public static function update($attrapage){

		}
	}