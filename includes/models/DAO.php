<?php
	class DAO{
		private static $dsnserveur = 'mysql:host=serveur;port=3306;charset=utf8;dbname=epsinet';
		private static $dsnlocalhost = 'mysql:host=localhost;port=3306;charset=utf8;dbname=epsinet';
		private static $user = 'root';
		private static $pass = '@dmDev@tom';

		private static $_instance;

		public function __construct(){
		}

		public static function getInstance(){
			if (self::$_instance == null) {
				try {
					/*
					 * Ouverture de la connexion à la base de données
					 */
					$dsn = null;
//					$fp = @fsockopen("serveur:3306", -1, $errno, $errstr, 2);
//					if($fp) {
//					} else {
//					}
					$dsn = self::$dsnlocalhost;
					self::$_instance = new PDO($dsn, self::$user, self::$pass, array(PDO::ATTR_PERSISTENT => true));
				} catch (PDOException $ex) {
					try{
						$dsn = self::$dsnserveur;
						self::$_instance = new PDO($dsn, self::$user, self::$pass, array(PDO::ATTR_PERSISTENT => true));
					}catch (PDOException $ex){
						print($ex->getMessage());
					}
				}
			}
			return self::$_instance;
		}
	}