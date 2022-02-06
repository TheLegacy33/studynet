<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_personne.php';
	include_once ROOTMODELS.'model_etudiant.php';
	include_once ROOTMODELS.'model_intervenant.php';
	include_once ROOTMODELS.'model_responsablepedago.php';

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
		    if (!is_null($auth)){
		        $this->authentified = $auth;
            }
			return $this->authentified;
		}

		public function isAdmin($admin = null){
		    if (!is_null($admin)){
                $this->isAdmin = $admin;
            }
			return $this->isAdmin;
		}

        public function exists($exists = null){
		    if (!is_null($exists)){
		        $this->exists = $exists;
            }
			return $this->exists;
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


		public static function geneRandomPassword(){
			$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			$pass = array();
			$alphaLength = strlen($alphabet) - 1;
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
			return implode($pass);
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

        public static function update(User $userAuth){
            $SQLQuery = 'UPDATE userAuth SET us_login = :loginuser, us_password = :passworduser WHERE us_id = :iduser';
            $stmt = DAO::getInstance()->prepare($SQLQuery);
            $stmt->bindValue(':loginuser', $userAuth->getLogin());
            $stmt->bindValue(':passworduser', $userAuth->getPassword());
            $stmt->bindValue(':iduser', $userAuth->getId());
            $stmt->execute();
			$userAuth->exists(true);
        }

        public static function insert(User $userAuth){
			$SQLQuery = 'INSERT INTO userAuth(us_login, us_password) VALUES (:loginuser, :passworduser)';
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':loginuser', $userAuth->getLogin());
			$stmt->bindValue(':passworduser', $userAuth->getPassword());
			$stmt->execute();
			$userAuth->exists(true);
			$userAuth->setId(DAO::getInstance()->lastInsertId());
		}

		public static function delete($idUSer){
			$SQLQuery = 'DELETE FROM userAuth WHERE us_id = :idUser';
			$stmt = DAO::getInstance()->prepare($SQLQuery);
			$stmt->bindValue(':idUser', $idUSer);
			$stmt->execute();
		}
    }
