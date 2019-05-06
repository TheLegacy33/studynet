<?php

class Personne{
	protected $id, $nom, $prenom, $email;
	protected $userAuth;

	public function __construct($id = 0, $nom = '', $prenom = '', $email = ''){
		$this->id = $id;
		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->email = $email;
		$this->userAuth = new User();
	}

	public function getId(){
		return $this->id;
	}

	public function getPersId(){
		return $this->id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function getPrenom(){
		return $this->prenom;
	}

	public function getNomComplet(){
		return $this->nom.' '.$this->prenom;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getUserAuth(){
	    return $this->userAuth;
    }

	public function setId($id){
	    $this->id = $id;
    }

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function setPrenom($prenom){
		$this->prenom = $prenom;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function __toString(){
	    if ($this->estVisiteur()){
	        return $this->getNom();
        }else{
            return $this->getNomComplet();
        }
	}

	public function equals(Personne $personne){
	    if ($this->getPersId() == $personne->getPersId() AND $this->getNomComplet() == $personne->getNomComplet()){
	        return true;
        }else{
	        return false;
        }
    }

    public function estEtudiant(){
        //return Personne::getType($this->id)=='etudiant';
        return get_class($this) == Etudiant::class;
    }

    public function estIntervenant(){
        /*return Personne::getType($this->id)=='intervenant';*/
        return get_class($this) == Intervenant::class;
    }

    public function estResponsablePedago(){
        /*return Personne::getType($this->id)=='responsablepedago';*/
        return get_class($this) == ResponsablePedago::class;
    }

    public function estVisiteur(){
        return $this->id == 0;
    }

	//TODO : Implémenter la méthode de vérification des capacités d'édition
	public function canEdit($what, $pf = null, $module = null, $contenumodule = null){
		if ($this->isAdmin()){
			return true;
		}else{
			//Edition possible en fonction de la variable $what qui dit ce qui doit être modifie, la période de formation et le statut de l'utilisateur
			return false;
		}
	}

    public function checkAuth($login, $password){
        if (trim($login) == '' OR trim($password) == ''){
            return false;
        }else{

            /* Aller chercher dans la bdd les informations de l'utilisateur */
            $stmt = DAO::getInstance()->prepare('SELECT * FROM userAuth WHERE us_login = :login');
            $stmt->bindValue(':login', $login);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                //Le login n'existe pas
                return false;
            }else{
                //Est-ce que le mot de passe est le bon ?
                $SQLRow = $stmt->fetchObject();
                if ($SQLRow->us_password != $password){
                    return false;
                }else{
                    //Je dois gérer plusieurs cas :
                    //Si il n'y a pas de personne liée au user alors uniquement consult
                    //sinon Si isAdmin => role Admin + récupération des informations de la personne
                    //Sinon Récupération des informations de la personne.

                    $this->userAuth = new User();
                    $this->userAuth->setId($SQLRow->us_id);
                    $this->userAuth->setLogin($login);
                    $this->userAuth->setPassword($password);
                    $this->userAuth->isAdmin($SQLRow->us_isadmin);
                    $this->userAuth->isAuthentified(true);
                    $this->userAuth->exists(true);
                    $stmt->closeCursor();
                    if (!Personne::exists($this->userAuth->getId())){
                        $this->setId(0);
                        $this->setEmail('');
                        $this->setNom('Visiteur');
                        $this->setPrenom('Visiteur');
                    }else{
                        $infosPers = Personne::getInfosByUserId($this->userAuth->getId());
                        $this->setId($infosPers->pers_id);
                        $this->setNom($infosPers->pers_nom);
                        $this->setPrenom($infosPers->pers_prenom);
                        $this->setEmail($infosPers->pers_email);
                    }
                    return true;
                }
            }
        }
    }

    public function isAuthentified(){
	    return is_null($this->userAuth) OR $this->userAuth->isAuthentified();
    }

    public function isAdmin(){
	    return is_null($this->userAuth) OR $this->userAuth->isAdmin();
    }

    public function fillAuth($userAuth){
	    $this->userAuth = $userAuth;
    }

	function get_class(){
		if ($this instanceof Etudiant){
			return "Etudiant";
		}elseif ($this instanceof ResponsablePedago){
			return "Responsable Pédagogique";
		}elseif ($this instanceof Intervenant){
			return "Intervenant";
		}else{
			return "Visiteur";
		}
	}

    public function clonepers(Personne $persToClone){
        $this->setId($persToClone->getId());
        $this->setPrenom($persToClone->getPrenom());
        $this->setNom($persToClone->getNom());
        $this->setEmail($persToClone->getEmail());
        $this->fillAuth($persToClone->getUserAuth());
    }

    public function removeUserAuth(){
		$idUser = $this->userAuth->getId();
		$this->userAuth = new User();
		Personne::update($this);
		User::delete($idUser);
	}

    public static function exists($id){
		$SQLStmt = DAO::getInstance()->prepare("SELECT pers_id FROM personne WHERE us_id = :iduser");
		$SQLStmt->bindValue(':iduser', $id);
		$SQLStmt->execute();
	    $retval = $SQLStmt->rowCount();
		$SQLStmt->closeCursor();
	    return ($retval > 0);
    }

    public static function getType($idPers){
		$SQLStmt = DAO::getInstance()->prepare("SELECT count(etu_id) FROM etudiant WHERE pers_id = :idpers");
		$SQLStmt->bindValue(':idpers', $idPers);
		$SQLStmt->execute();
        $estetudiant = $SQLStmt->fetchColumn(0);
		$SQLStmt->closeCursor();

		$SQLStmt = DAO::getInstance()->prepare("SELECT count(int_id) FROM intervenant WHERE pers_id = :idpers");
		$SQLStmt->bindValue(':idpers', $idPers);
		$SQLStmt->execute();
        $estintervenant = $SQLStmt->fetchColumn(0);
		$SQLStmt->closeCursor();

		$SQLStmt = DAO::getInstance()->prepare("SELECT count(resp_id) FROM responsablePedago WHERE pers_id = :idpers");
		$SQLStmt->bindValue(':idpers', $idPers);
		$SQLStmt->execute();
        $estresponsablepedago = $SQLStmt->fetchColumn(0);
		$SQLStmt->closeCursor();

        if ($estetudiant){
            $retVal = Etudiant::class;
        }elseif ($estresponsablepedago){
            $retVal = ResponsablePedago::class;
        }elseif ($estintervenant){
            $retVal = Intervenant::class;
        }else{
        	return get_class();
		}
        return $retVal;
    }

    public static function getInfosByUSerId($id){
		$SQLStmt = DAO::getInstance()->prepare("SELECT * FROM personne WHERE us_id = :iduser");
		$SQLStmt->bindValue(':iduser', $id);
		$SQLStmt->execute();
        $retVal = $SQLStmt->fetchObject();
		$SQLStmt->closeCursor();
        return $retVal;
    }

    public static function getListe($critere = '*'){
		$SQLQuery = 'SELECT * FROM personne ';
		if (is_array($critere)){
			$SQLQuery = '';
			foreach ($critere as $item => $value){
				if ($value == 'I' OR $value == Intervenant::class){
					$SQLQuery .= 'SELECT personne.* FROM personne INNER JOIN intervenant ON personne.pers_id = intervenant.pers_id ';
				}elseif ($value == 'R' OR $value == ResponsablePedago::class){
					$SQLQuery .= 'SELECT personne.* FROM personne INNER JOIN responsablePedago ON personne.pers_id = responsablePedago.pers_id ';
				}elseif ($value == 'E' OR $value == Etudiant::class){
					$SQLQuery .= 'SELECT personne.* FROM personne INNER JOIN etudiant ON personne.pers_id = etudiant.pers_id ';
				}
				if ($item != array_key_last($critere)){
					$SQLQuery .= 'UNION ';
				}
			}
		}else{
			if ($critere != '*'){
				if ($critere == 'visiteur'){
					$SQLQuery .= 'WHERE pers_id NOT IN (SELECT pers_id FROM intervenant) ';
					$SQLQuery .= 'AND pers_id NOT IN (SELECT pers_id FROM etudiant) ';
					$SQLQuery .= 'AND pers_id NOT IN (SELECT pers_id FROM responsablePedago) ';
				}elseif ($critere == 'administrateur'){
					$SQLQuery .= 'INNER JOIN userAuth ON personne.us_id = userAuth.us_id ';
					$SQLQuery .= 'WHERE us_isadmin = 1 ';
				}elseif ($critere == '!E'){
					$SQLQuery .= 'WHERE pers_id NOT IN (SELECT pers_id FROM etudiant) ';
				}
			}
		}

	    $SQLQuery .= 'ORDER BY pers_nom, pers_prenom';
        $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
        $SQLStmt->execute();

        $retVal = array();

//        if ($critere == '*'){
			while ($SQLRow = $SQLStmt->fetchObject()){
				$userType = Personne::getType($SQLRow->pers_id);
				if ($userType == Intervenant::class){
					$idInt = Intervenant::getIdByIdPers($SQLRow->pers_id);
					$newPers = new Intervenant($idInt, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
				}elseif ($userType == ResponsablePedago::class){
					$idResp = ResponsablePedago::getIdByIdPers($SQLRow->pers_id);
					$newPers = new ResponsablePedago($idResp, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
				}elseif ($userType == Etudiant::class){
					$idEtud = Etudiant::getIdByIdPers($SQLRow->pers_id);
					$newPers = new Etudiant($idEtud, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, Etudiant::getPhotoById($idEtud), $SQLRow->pers_id);
				}else{
					$newPers = new Personne($SQLRow->pers_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
				}
				$newPers->fillAuth(User::getById($SQLRow->us_id));
				$retVal[] = $newPers;
			}
//		}else{
//			while ($SQLRow = $SQLStmt->fetchObject()){
//				$newPers = new Personne($SQLRow->pers_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
//				$newPers->fillAuth(User::getById($SQLRow->us_id));
//				$retVal[] = $newPers;
//			}
//		}

        $SQLStmt->closeCursor();
        return $retVal;
    }

	public static function getById($id){
		$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM personne WHERE pers_id = :idpers');
		$SQLStmt->bindValue(':idpers', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
//		$userType = self::getType($SQLRow->pers_id);
//		if ($userType == Intervenant::class){
//			$idInt = Intervenant::getIdByIdPers($SQLRow->pers_id);
//			$newPers = new Intervenant($idInt, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
//		}elseif ($userType == ResponsablePedago::class){
//			$idResp = ResponsablePedago::getIdByIdPers($SQLRow->pers_id);
//			$newPers = new ResponsablePedago($idResp, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
//		}elseif ($userType == Etudiant::class){
//			$idEtud = Etudiant::getIdByIdPers($SQLRow->pers_id);
//			$newPers = new Etudiant($idEtud, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, Etudiant::getPhotoById($idEtud), $SQLRow->pers_id);
//		}else{
			$newPers = new Personne($SQLRow->pers_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
//		}
		$newPers->fillAuth(User::getById($SQLRow->us_id));

		$retVal[] = $newPers;
		$SQLStmt->closeCursor();
		return $newPers;
	}

    public static function update($personne){
		if (!is_null($personne)){
			$SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':nom', $personne->getNom());
			$SQLStmt->bindValue(':prenom', $personne->getPrenom());
			$SQLStmt->bindValue(':email', $personne->getEmail());
			$SQLStmt->bindValue(':userid', ($personne->getUserAuth()->getId() != 0)?$personne->getUserAuth()->getId():null);
			if (get_class($personne) == Personne::class){
				$SQLStmt->bindValue(':idpers', $personne->getId());
			}else{
				$SQLStmt->bindValue(':idpers', $personne->getPersId());
			}
			$SQLStmt->execute();
		}
    }

    public static function insert($personne){
		if (!is_null($personne)){
			$SQLQuery = "INSERT INTO personne (pers_nom, pers_prenom, pers_email, us_id) VALUES (:nom, :prenom, :email, :userid)";
			$SQLStmt = DAO::getInstance()->prepare($SQLQuery);
			$SQLStmt->bindValue(':nom', $personne->getNom());
			$SQLStmt->bindValue(':prenom', $personne->getPrenom());
			$SQLStmt->bindValue(':email', $personne->getEmail());
			$SQLStmt->bindValue(':userid', ($personne->getUserAuth()->getId() != 0)?$personne->getUserAuth()->getId():null);
			if (get_class($personne) == Personne::class){
				$SQLStmt->bindValue(':idpers', $personne->getId());
			}else{
				$SQLStmt->bindValue(':idpers', $personne->getPersId());
			}
			$SQLStmt->execute();
		}
	}
}
?>