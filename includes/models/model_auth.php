<?php
	include_once ROOTMODELS.'DAO.php';

	class Authentification {
		private static $user;

		public static function setUser($user){
			self::$user = $user;
		}

		public static function userLogged(){
			if (self::$user == null){
				return false;
			}else{
				return self::$user->isAuthentified();
			}
		}

		public static function saveSession(){
			$_SESSION['user'] = serialize(self::$user);
		}
	}

	class User{
		private $nom, $role;
		private $authentified;

		public function __construct($nom = '', $role = 'etudiant'){
			$this->nom = $nom;
			$this->role = $role;
			$this->authentified = false;
		}

		public function getNom(){
			return $this->nom;
		}

		public function getRole(){
			return $this->role;
		}

		public function isAuthentified(){
			return $this->authentified;
		}

		public function setAuthentification($val){
			$this->authentified = $val;
		}

		public function checkAuth($login, $password){
			if (trim($login) == '' OR trim($password) == ''){
				$this->authentified = false;
			}else{
				if ($login == 'The_Legacy') {
					if ($password == '@dmDev@tom') {
						$this->nom = 'The_Legacy';
						$this->role = 'admin';
						$this->authentified = true;
					} else {
						$this->authentified = false;
					}
				}elseif ($login == 'Consult'){
					if ($password == 'C0nsulT'){
						$this->nom = 'Consult';
						$this->role = 'visu';
						$this->authentified = true;
					}else{
						$this->authentified = false;
					}
				}else{
					//recherche dans la base des utilisateurs
					$this->authentified = false;
				}
			}
			return $this->authentified;
		}
	}
?>