<?php
	class DAO{
		private static $dsn = 'mysql:host=serveur;port=3306;charset=utf8;dbname=epsinet';
//		private static $dsn = 'mysql:host=localhost;port=3306;charset=utf8;dbname=epsinet';
		private static $user = 'root';
		private static $pass = '@dmDev@tom';

		private static $_instance;

		public function __construct(){
		}

		public static function getInstance(){
			/*if (!isset(self::$_instance)) {*/
			if (self::$_instance == null) {
				try {
					/*
					 * Ouverture de la connexion à la base de données
					 */
					self::$_instance = new PDO(self::$dsn, self::$user, self::$pass);
					//parent::__construct($this->dsn, $this->user, $this->pass);
				} catch (PDOException $ex) {
					/*
					 * En cas d'erreur, gestion d'une exception (à voir plus tard)
					 */
					print($ex->getMessage());
				}
			}
			return self::$_instance;
		}
	}