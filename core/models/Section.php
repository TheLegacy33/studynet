<?php

	namespace Fomulaire;

	class Section{
		private $id, $titre, $actif;
		private $questions;

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
		public function getQuestions(){
			return $this->questions;
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
		 * @param mixed $questions
		 */
		public function setQuestions($questions){
			$this->questions = $questions;
		}

		public function addQuestion(Question $question){

		}

		public static function getById($id){

		}

		public static function getListe(Formulaire $formulaire){

		}
	}