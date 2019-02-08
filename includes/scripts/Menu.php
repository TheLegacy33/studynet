<?php

class Menu{
	private $link, $libelle, $title;

	public function  __construct($link, $libelle, $title = ''){
		$this->link = $link;
		$this->libelle = $libelle;
		$this->title = $title;
	}

	public function __toString(){
		return '<a class="nav-link" href="'.$this->link.'" title="'.$this->title.'">'.$this->libelle.'</a>';
	}
}
?>