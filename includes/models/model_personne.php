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
    public function clonepers($persToClone){
        $this->setId($persToClone->getId());
        $this->setPrenom($persToClone->getPrenom());
        $this->setNom($persToClone->getNom());
        $this->setEmail($persToClone->getEmail());
        $this->fillAuth($persToClone->getUserAuth());
    }

    public static function exists($id){
	    $stmt = DAO::getInstance()->prepare("SELECT pers_id FROM personne WHERE us_id = :iduser");
	    $stmt->bindValue(':iduser', $id);
	    $stmt->execute();
	    $retval = $stmt->rowCount();
	    $stmt->closeCursor();
	    return $retval;
    }

    public static function getType($idPers){
        $stmt = DAO::getInstance()->prepare("SELECT count(etu_id) FROM etudiant WHERE pers_id = :idpers");
        $stmt->bindValue(':idpers', $idPers);
        $stmt->execute();
        $estetudiant = $stmt->fetchColumn(0);
        $stmt->closeCursor();

        $stmt = DAO::getInstance()->prepare("SELECT count(int_id) FROM intervenant WHERE pers_id = :idpers");
        $stmt->bindValue(':idpers', $idPers);
        $stmt->execute();
        $estintervenant = $stmt->fetchColumn(0);
        $stmt->closeCursor();

        $stmt = DAO::getInstance()->prepare("SELECT count(resp_id) FROM responsablePedago WHERE pers_id = :idpers");
        $stmt->bindValue(':idpers', $idPers);
        $stmt->execute();
        $estresponsablepedago = $stmt->fetchColumn(0);
        $stmt->closeCursor();

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
        $stmt = DAO::getInstance()->prepare("SELECT * FROM personne WHERE us_id = :iduser");
        $stmt->bindValue(':iduser', $id);
        $stmt->execute();
        $retVal = $stmt->fetchObject();
        $stmt->closeCursor();
        return $retVal;
    }

    public static function getListe(){
	    $SQLQuery = "SELECT * FROM personne ORDER BY pers_nom, pers_prenom";
        $SQLStmt = DAO::getInstance()->prepare($SQLQuery);
        $SQLStmt->execute();

        $retVal = array();
        while ($SQLRow = $SQLStmt->fetchObject()){
            if (self::getType($SQLRow->pers_id) == Intervenant::class){
            	$idInt = Intervenant::getIdByIdPers($SQLRow->pers_id);
                $newPers = new Intervenant($idInt, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
            }elseif (self::getType($SQLRow->pers_id) == ResponsablePedago::class){
            	$idResp = ResponsablePedago::getIdByIdPers($SQLRow->pers_id);
                $newPers = new ResponsablePedago($idResp, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
            }elseif (self::getType($SQLRow->pers_id) == Etudiant::class){
            	$idEtud = Etudiant::getIdByIdPers($SQLRow->pers_id);
                $newPers = new Etudiant($idEtud, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, Etudiant::getPhotoById($idEtud), $SQLRow->pers_id);
            }else{
                $newPers = new Personne($SQLRow->pers_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
            }
            $newPers->fillAuth(User::getById($SQLRow->us_id));
            $retVal[] = $newPers;
        }
        $SQLStmt->closeCursor();
        return $retVal;
    }

	public static function getById($id){
		$SQLStmt = DAO::getInstance()->prepare('SELECT * FROM personne WHERE pers_id = :idpers');
		$SQLStmt->bindValue(':idpers', $id);
		$SQLStmt->execute();
		$SQLRow = $SQLStmt->fetchObject();
		if (self::getType($SQLRow->pers_id) == Intervenant::class){
			$idInt = Intervenant::getIdByIdPers($SQLRow->pers_id);
			$newPers = new Intervenant($idInt, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
		}elseif (self::getType($SQLRow->pers_id) == ResponsablePedago::class){
			$idResp = ResponsablePedago::getIdByIdPers($SQLRow->pers_id);
			$newPers = new ResponsablePedago($idResp, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, $SQLRow->pers_id);
		}elseif (self::getType($SQLRow->pers_id) == Etudiant::class){
			$idEtud = Etudiant::getIdByIdPers($SQLRow->pers_id);
			$newPers = new Etudiant($idEtud, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email, Etudiant::getPhotoById($idEtud), $SQLRow->pers_id);
		}else{
			$newPers = new Personne($SQLRow->pers_id, $SQLRow->pers_nom, $SQLRow->pers_prenom, $SQLRow->pers_email);
		}
		$newPers->fillAuth(User::getById($SQLRow->us_id));

		$retVal[] = $newPers;
		$SQLStmt->closeCursor();
		return $newPers;
	}

    public static function update($personne){
	    $SQLQuery = "UPDATE personne SET pers_nom = :nom, pers_prenom = :prenom, pers_email = :email, us_id = :userid WHERE pers_id = :idpers";
	    $stmt = DAO::getInstance()->prepare($SQLQuery);
	    $stmt->bindValue(':nom', $personne->getNom());
        $stmt->bindValue(':prenom', $personne->getPrenom());
        $stmt->bindValue(':email', $personne->getEmail());
        $stmt->bindValue(':idpers', $personne->getId());
        $stmt->bindValue(':userid', ($personne->getUserAuth()->getId() != 0)?$personne->getUserAuth()->getId():null);
        $stmt->execute();
    }
}

?>