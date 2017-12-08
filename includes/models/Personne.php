<?php

abstract class Personne{
	private $id, $nom, $prenom, $email;

	public function __construct($id = 0, $nom, $prenom, $email = ''){
		$this->id = $id;
		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->email == $email;
	}

	public function getId(){
		return $this->id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function getPrenom(){
		return $this->prenom;
	}

	public function getNomComplet(){
		return $this->nom.' '.$this->prenom;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function setPrenom($prenom){
		$this->prenom = $prenom;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function __toString(){
		return $this->nom.' '.$this->prenom;
	}
}

?>