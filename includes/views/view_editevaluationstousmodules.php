<?php
$appreciationG = Evaluation::getAppreciationGenerale($etudiant->getId(), $pf->getId());
?>

<section id="content_body" class="row formaffiche">
	<nav class="navinterne">
		<?php print('<a href="index.php?p=evaluations&a=view&idetudiant='.$idetudiant.'&idpf='.$idpf.'" title="Retour à la liste des modules"><< Retour</a>'); ?>
	</nav>
	<header class="text-center text-info" style="font-size: 20px">
		Evaluations de <?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
	</header>
    <form action="" method="post">
        <section class="row">
            <label>Commentaire du module :</label><br />
			<?php
				if (is_null($appreciationG)){
					$script = '<textarea name="appreciation" style="width: 100%; height: 100px" placeholder="Pas de commentaire"></textarea>';
				}else{
					$script = '<textarea name="appreciation" style="width: 100%; height: 100px">'.$appreciationG.'</textarea>';
				}
				print ($script);
			?>
        </section>
        <section class="formbtn">
            <input type="submit" value="Enregistrer les modifications" />
        </section>
    </form>
    <?php
        $script = '';
        foreach ($listeModules as $module){
            $commentaireModule = Evaluation::getAppreciationModule($etudiant->getId(), $module->getIntervenant()->getId(), $module->getId());
            if ($commentaireModule == null){
                $commentaireModule = 'Pas de commentaire';
            }
            $script .= '<section class="row col-xs-12">';
            $script .= '<h3>'.$module->getLibelle().'</h3>';
            $script .= '<label>Commentaire du module :</label><br />';
            $script .= '<p class="commentaire">'.$commentaireModule.'</p>';
            $script .= '</section>';


            foreach ($module->getContenu() as $contenuModule){
                $eval = Evaluation::getById($etudiant->getId(), $module->getIntervenant()->getId(), $contenuModule->getId());
                $acquis = $eval->estAcquis()?' checked':'';
                $enacquisition = $eval->estEnCoursAcquisition()?' checked':'';
                $nonacquis = $eval->estNonAcquis()?' checked':'';

                $script .= '<section class="col-xs-8 col-xs-offset-2 evalcontenumodule">';
                $script .= '<header class="libelle">'.$contenuModule->getLibelle().'</header>';
				$script .= '<label>Commentaire :</label><br /><p class="commentaire">'.($eval->getCommentaire()!=null?$eval->getCommentaire():'Pas de commentaire').'</p>';
                $script .= '<section class="radio">';

                $radioName = 'btradioeval_'.$contenuModule->getId();
                $radioId = 'btradioeval_'.$eval->getPrimaryKey();
                $script .= '<span><input type="radio" name="'.$radioName.'" id="'.$radioId.'" value="A"'.$acquis.' disabled><label for="'.$radioId.'" class="'.($eval->estAcquis()?'eval_A':'').'">Acquis</label></span>';
                $script .= '<span><input type="radio" name="'.$radioName.'" id="'.$radioId.'" value="EA"'.$enacquisition.' disabled><label for="'.$radioId.'" class="'.($eval->estEnCoursAcquisition()?'eval_EA':'').'">En cours d\'acquisition</label></span>';
                $script .= '<span><input type="radio" name="'.$radioName.'" id="'.$radioId.'" value="NA"'.$nonacquis.' disabled><label for="'.$radioId.'" class="'.($eval->estNonAcquis()?'eval_NA':'').'">Non Acquis</label></span>';
                $script .= '</section>';
                $script .= '</section>';
            }
        }
		print($script);
	?>
</section>
