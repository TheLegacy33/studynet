<?php
	namespace Fomulaire;

	class Reponse{
		private $id, $libelle, $valeur;

		/**
		 * @return mixed
		 */
		public function getId(){
			return $this->id;
		}

		/**
		 * @return mixed
		 */
		public function getLibelle(){
			return $this->libelle;
		}

		/**
		 * @return mixed
		 */
		public function getValeur(){
			return $this->valeur;
		}

		/**
		 * @param mixed $libelle
		 */
		public function setLibelle($libelle){
			$this->libelle = $libelle;
		}

		/**
		 * @param mixed $valeur
		 */
		public function setValeur($valeur){
			$this->valeur = $valeur;
		}

		public static function getById($id){

		}

		public static function getListe(Question $question){

		}
	}