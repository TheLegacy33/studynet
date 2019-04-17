<?php

include_once (ROOTSCRIPTS.'Menu.php');

class NavBar{
	private $menus = array();

	public function addMenu($m){
		$this->menus[] = $m;
	}

	public function render(){
		$script = '<ul class="navbar-nav mr-auto mt-2 mt-lg-0">';
		foreach ($this->menus as $menu){
			$script .= "<li class=\"nav-item\">$menu</li>";
		}
		$script .= '</ul>';
		return $script;
	}

	public static function fill($user){
		$nav = new NavBar();
		$nav->addMenu(new Menu('index.php', 'Accueil'));
        if ($user->isAuthentified()){
            if (!$user->estEtudiant()){
                $nav->addMenu(new Menu('index.php?p=ecoles', 'Ecoles', 'Les écoles'));
                $nav->addMenu(new Menu('index.php?p=periodesformation', 'Sessions', 'Les périodes de formations'));
            }elseif ($user->estEtudiant()){

			}elseif ($user->estIntervenant()){

			}elseif ($user->estVisiteur()){

			}
			if ($user->isAdmin()){
				$nav->addMenu(new Menu('index.php?p=personnes&a=listepersonnes', 'Personnes', 'Les personnes de l\'application'));
			}
        }

        return $nav;
	}
}

?>