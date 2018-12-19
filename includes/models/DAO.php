<?php
	class DAO{
		private static $dsnserveur = 'mysql:host=%MYSQL_HOST%;port=%MYSQL_PORT%;charset=utf8;dbname=%MYSQL_BDD%';
//		private static $dsnlocalhost = 'mysql:host=localhost;port=3306;charset=utf8;dbname=studynet';
		private static $user = 'root';
		private static $pass = '@dmDev@tom';
//		private static $passAvalone = '@dmAvR0o';

		private static $_instance;

		public function __construct(){
		}

		public static function getInstance(){
            /*
             * Ouverture de la connexion à la base de données
             */
			self::$dsnserveur = str_replace('%MYSQL_HOST%', getenv('MYSQL_HOST'), self::$dsnserveur);
			self::$dsnserveur = str_replace('%MYSQL_PORT%', getenv('MYSQL_PORT'), self::$dsnserveur);
			self::$dsnserveur = str_replace('%MYSQL_BDD%', getenv('MYSQL_BDD'), self::$dsnserveur);
			self::$user = getenv('MYSQL_USER');
			self::$pass = getenv('MYSQL_PASS');

			if (self::$_instance == null) {
				try{
					//Connexion sur le serveur
					self::$_instance = new PDO(self::$dsnserveur, self::$user, self::$pass);
				}catch (PDOException $ex){
					exit($ex->getMessage());
				}
			}
			return self::$_instance;
		}
	}