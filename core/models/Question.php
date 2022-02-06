<?php
	namespace Fomulaire;

	class Question{
		private $id, $titre, $actif;
		private $reponses;

		/**
		 * @return mixed
		 */
		public function getId(){
			return $this->id;
		}

		/**
		 * @return mixed
		 */
		public function getActif(){
			return $this->actif;
		}

		/**
		 * @return mixed
		 */
		public function getTitre(){
			return $this->titre;
		}

		/**
		 * @return mixed
		 */
		public function getReponses(){
			return $this->reponses;
		}

		/**
		 * @param mixed $titre
		 */
		public function setTitre($titre){
			$this->titre = $titre;
		}

		/**
		 * @param mixed $actif
		 */
		public function setActif($actif){
			$this->actif = $actif;
		}

		/**
		 * @param mixed $reponses
		 */
		public function setReponses($reponses){
			$this->reponses = $reponses;
		}

		public function addReponse(Reponse $reponse){

		}

		public static function getById($id){

		}

		public static function getListe(Section $section){

		}
	}