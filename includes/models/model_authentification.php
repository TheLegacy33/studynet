<?php
	include_once ROOTMODELS.'DAO.php';
	include_once ROOTMODELS.'model_user.php';

	class Authentification {
		private static $user;

		public static function setUser(Personne $user){
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
?>