<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS . 'model_personne.php';
	include_once ROOTMODELS.'model_etudiant.php';
	include_once ROOTMODELS.'model_intervenant.php';
	include_once ROOTMODELS.'model_responsablepedago.php';


	class Authentification {
		private static $user;

		public static function setUser($user){
			self::$user = $user;
		}

		public static function getUser(){
		    return self::$user;
        }

		public static function userLogged(){
			if (self::$user == null){
				return false;
			}else{
				return self::$user->isAuthentified();
			}
		}

		public static function loadSession(){
            self::$user = unserialize($_SESSION['user']);
        }

		public static function saveSession(){
			$_SESSION['user'] = serialize(self::$user);
		}
	}

	class User {
		private $id, $login, $password, $isAdmin;
		private $authentified, $exists;

		public function __construct($id = 0, $login = '', $password = ''){
		    $this->id = $id;
			$this->login = $login;
			$this->password = $password;
			$this->isAdmin = false;
			$this->authentified = false;
			$this->exists = false;
		}

		public function isAuthentified($auth = null){
		    if (is_null($auth)){
                return $this->authentified;
            }else{
		        $this->authentified = $auth;
            }
		}

		public function isAdmin($admin = null){
		    if (is_null($admin)){
                return $this->isAdmin;
            }else{
		        $this->isAdmin = $admin;
            }

        }

        public function exists($exists = null){
		    if (is_null($exists)){
		        return $this->exists;
            }else{
		        $this->exists = $exists;
            }
        }

        public function getId(){
		    return $this->id;
        }

        public function getLogin(){
		    return $this->login;
        }

        public function getPassword(){
		    return $this->password;
        }

        public function setId($id){
		    $this->id = $id;
        }

        public function setLogin($login){
		    $this->login = $login;
        }

        public function setPassword($password){
		    $this->password = $password;
        }

		public function canEdit($what, $pf = null, $module = null, $contenumodule = null){
			if ($this->isAdmin()){
				return true;
			}else{
				//Edition possible en fonction de la variable $what qui dit ce qui doit être modifie, la période de formation et le statut de l'utilisateur
				return false;
			}
		}

		public static function getById($id){
		    $SQLQuery = 'SELECT * FROM userAuth WHERE us_id = :idUser';
		    $stmt = DAO::getInstance()->prepare($SQLQuery);
		    $stmt->bindValue(':idUser', $id);
		    $stmt->execute();
		    if ($stmt->rowCount() > 0){
                $SQLRow = $stmt->fetchObject();
                $newUser = new User($SQLRow->us_id, $SQLRow->us_login, $SQLRow->us_password);
                $newUser->isAdmin($SQLRow->us_isadmin);
                $newUser->exists(true);
            }else{
		        $newUser = new User();
		    }
		    $stmt->closeCursor();
		    return $newUser;
        }

        public static function update($userAuth){
            $SQLQuery = 'UPDATE userAuth SET us_login = :loginuser, us_password = :passworduser WHERE us_id = :iduser';
            $stmt = DAO::getInstance()->prepare($SQLQuery);
            $stmt->bindValue(':loginuser', $userAuth->getLogin());
            $stmt->bindValue(':passworduser', $userAuth->getPassword());
            $stmt->bindValue(':iduser', $userAuth->getId());
            $stmt->execute();
        }
    }
?>