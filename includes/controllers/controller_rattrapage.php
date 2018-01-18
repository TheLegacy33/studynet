<?php
include_once ROOTMODELS.'model_rattrapage.php';

$idEtudiant = $user->getId();
$idRattrapage = isset($_GET['idrattrapage'])?$_GET['idrattrapage']:0;
if ($action == 'listeforetudiant'){
    $includeJs = true;
    $scriptname[] = 'js_listerattrapages.js';

	$listeRattrapage = Rattrapage::getListeForEtudiant($idEtudiant);
	if (count($listeRattrapage) > 0){

	    //Vérification de la validité du rattrapage
        foreach ($listeRattrapage as $rattrapage){
            //var_dump($rattrapage);
            if ($rattrapage->expired() AND (!$rattrapage->uploaded() OR $rattrapage->getStatut() == StatutRattrapage::getByLibelle('En cours'))){

                $rattrapage->setStatut(StatutRattrapage::getByLibelle('Expiré'));
                Rattrapage::update($rattrapage);
            }
        }

	    include_once ROOTVIEWS.'view_listerattrapagesetudiant.php';
	}
}elseif ($action == 'getsujet'){
    $rattrapage = Rattrapage::getById($idRattrapage);
    if ($rattrapage->downloaded()){
        //Le sujet a déjà été récupéré : affichage de la date et message
        $firstdld = false;
        $dateRecup = new DateTime($rattrapage->getDateRecup());
        $dateRenduAttendue = $dateRecup->add(new DateInterval($rattrapage->getDelai()->getInterval()))->add(new DateInterval('PT1M'))->format('Y-m-d H:i:00');
    }else{
        $firstdld = true;
        $includeJs = true;
        $scriptname[] = 'js_rattrapages.js';
        //Récupération du sujet : affichage du message d'avertissement et mise à disposition du lien avec timer pour le téléchargement
        $DTNow = new DateTime('now');
        $rattrapage->setDateRecup($DTNow->format('Y-m-d H:i:00'));
        $DTRecup = new DateTime($rattrapage->getDateRecup());
        $dateRenduAttendue = $DTRecup->add(new DateInterval($rattrapage->getDelai()->getInterval()))->add(new DateInterval('PT1M'))->format('Y-m-d H:i:00');

        //Update de l'enregistrement avec la date de téléchargement active
        Rattrapage::update($rattrapage);
    }
    include_once ROOTVIEWS.'view_downloadsujetrattrapage.php';
}elseif ($action == 'postreponse'){
    var_dump('Envoi de la réponse');
}else{
    header('Location: '.ROOTHTML);
}
?>