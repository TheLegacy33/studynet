<?php
	class DAO{
		private static $dsnserveur = 'mysql:host=serveur;port=3306;charset=utf8;dbname=studynet';
		private static $dsnlocalhost = 'mysql:host=localhost;port=3306;charset=utf8;dbname=studynet';
		private static $user = 'root';
		private static $pass = '@dmDev@tom';
		private static $passAvalone = '@dmAvR0o';

		private static $_instance;

		public function __construct(){
		}

		public static function getInstance(){
            /*
             * Ouverture de la connexion à la base de données
             */
			if (self::$_instance == null) {
				try {
				    //Connexion sur Avalone
					$dsn = self::$dsnlocalhost;
					self::$_instance = new PDO($dsn, self::$user, self::$passAvalone, array(PDO::ATTR_PERSISTENT => true));
				} catch (PDOException $ex) {
					try{
					    //Connexion sur le portable
						$dsn = self::$dsnlocalhost;
						self::$_instance = new PDO($dsn, self::$user, self::$pass, array(PDO::ATTR_PERSISTENT => true));
					}catch (PDOException $ex){
                        try{
                            //Connexion sur le serveur
                            $dsn = self::$dsnserveur;
                            self::$_instance = new PDO($dsn, self::$user, self::$pass, array(PDO::ATTR_PERSISTENT => true));
                        }catch (PDOException $ex){
                            print($ex->getMessage());
                        }
					}
				}
			}
			return self::$_instance;
		}
	}