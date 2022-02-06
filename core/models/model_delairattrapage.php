<?php
	class DelaiRattrapage{
		private $valeur, $unite, $valInterval;

		public function __construct($val = 1, $unit = 'h'){
			$this->valeur = $val;
			$this->unite = $unit;
			switch ($unit){
				case 'd':{
					$this->valInterval = 'P'.$this->valeur.'D';
					break;
				}
				case 'm':{
					$this->valInterval = 'P'.$this->valeur.'M';
					break;
				}
				case 'y':{
					$this->valInterval = 'P'.$this->valeur.'Y';
					break;
				}
				case 'h':
				default: {
					$this->valInterval = 'PT'.$this->valeur.'H';
					break;
				}
			}
		}

		public function __toString(){
			return $this->valeur.' '.$this->unite;
		}

		public function setValeur($valeur){
			$this->valeur = $valeur;
		}

		public function setUnite($unite){
			$this->unite = $unite;
		}

		public function getValeur(){
			return $this->valeur;
		}

		public function getUnite(){
			return $this->unite;
		}

		public function getInterval(){
			return $this->valInterval;
		}
	}
?>