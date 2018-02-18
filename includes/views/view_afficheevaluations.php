<?php
    $commentaireModule = Evaluation::getAppreciationModule($etudiant->getId(), $module->getIntervenant()->getId(), $module->getId());
    if ($commentaireModule == null){
        $commentaireModule = 'Pas de commentaire';
    }
?>
<nav class="navinterne">
    <?php print('<a href="index.php?p=periodesformation&a=listemodules&idetudiant='.$idetudiant.'&idpf='.$idpf.'" title="Retour à la liste des modules"><< Retour</a>'); ?>
</nav>
<section id="content_body" class="row formaffiche">
	<header class="text-center text-info" style="font-size: 20px">
		Evaluations du module <?php print($module->getLibelle()); ?> pour
		<?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
	</header>


	<section class="row">
		<label>Commentaire du module :</label><br />
		<p class="commentaire"><?php print($commentaireModule); ?></p>
	</section>
	<?php
		$script = '';
		foreach ($listeContenusModule as $contenuModule){
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
		print($script);
	?>
</section>
