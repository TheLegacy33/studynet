<?php
	namespace Fomulaire;

	class Formulaire{
		private $id, $titre, $date, $commentaire, $actif, $image, $phraseintro;

		private $sections;

		/**
		 * @return mixed
		 */
		public function getId(){
			return $this->id;
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
		public function getActif(){
			return $this->actif;
		}

		/**
		 * @return mixed
		 */
		public function getCommentaire(){
			return $this->commentaire;
		}

		/**
		 * @return mixed
		 */
		public function getDate(){
			return $this->date;
		}

		/**
		 * @return mixed
		 */
		public function getImage(){
			return $this->image;
		}

		/**
		 * @return mixed
		 */
		public function getSections(){
			return $this->sections;
		}

		/**
		 * @param mixed $actif
		 */
		public function setActif($actif){
			$this->actif = $actif;
		}

		/**
		 * @param mixed $commentaire
		 */
		public function setCommentaire($commentaire){
			$this->commentaire = $commentaire;
		}

		/**
		 * @param mixed $date
		 */
		public function setDate($date){
			$this->date = $date;
		}

		/**
		 * @param mixed $image
		 */
		public function setImage($image){
			$this->image = $image;
		}

		/**
		 * @param mixed $titre
		 */
		public function setTitre($titre){
			$this->titre = $titre;
		}

		/**
	 	* @param mixed $sections
	 	*/
		public function setSections($sections){
			$this->sections = $sections;
		}

		public function addSection(Section $section){

		}

		public static function getById($id){

		}

		public static function getListe(){

		}

	}