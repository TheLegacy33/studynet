<?php
    $commentaireModule = Evaluation::getAppreciationModule($etudiant->getId(), $module->getIntervenant()->getId(), $module->getId());
?>
<section id="content_body" class="row formaffiche">
	<nav class="navinterne">
		<?php print('<a href="index.php?p=periodesformation&a=listemodules&idetudiant='.$idetudiant.'&idpf='.$idpf.'" title="Retour à la liste des modules"><< Retour</a>'); ?>
	</nav>
	<header class="text-center text-info" style="font-size: 20px">
		Evaluations du module <?php print($module->getLibelle()); ?> pour
		<?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
	</header>

    <form action="" method="post">
        <section class="row">
            <label>Commentaire du module :</label><br />
			<?php
				if (is_null($commentaireModule)){
					$script = '<textarea name="comm_module" style="width: 100%; height: 100px" placeholder="Pas de commentaire"></textarea>';
				}else{
					$script = '<textarea name="comm_module" style="width: 100%; height: 100px">'.$commentaireModule.'</textarea>';
				}
				print ($script);
			?>

        </section>
        <?php
            $script = '';
            foreach ($listeContenusModule as $contenuModule){
                $eval = Evaluation::getById($etudiant->getId(), $module->getIntervenant()->getId(), $contenuModule->getId());
                $acquis = $eval->estAcquis()?' checked="checked"':'';
                $enacquisition = $eval->estEnCoursAcquisition()?' checked="checked"':'';
                $nonacquis = $eval->estNonAcquis()?' checked="checked"':'';

                $script .= '<section class="col-xs-8 col-xs-offset-2 evalcontenumodule">';
                $script .= '<header class="libelle">'.$contenuModule->getLibelle().'</header>';
				if ($eval->getCommentaire() != null){
					$script .= '<label>Commentaire :</label><br /><textarea name="comm_'.$contenuModule->getId().'" style="width: 100%; height: 100px">'.$eval->getCommentaire().'</textarea>';
				}else{
					$script .= '<label>Commentaire :</label><br /><textarea name="comm_'.$contenuModule->getId().'" style="width: 100%; height: 100px" placeholder="Pas de commentaire"></textarea>';
				}
                $script .= '<section class="radio">';

                $radioName = 'btradioeval_'.$contenuModule->getId();
                $radioId = 'btradioeval_'.$eval->getPrimaryKey();
                $script .= '<span><input type="radio" name="'.$radioName.'" id="'.$radioId.'A" value="A"'.$acquis.'><label for="'.$radioId.'A">Acquis</label></span>';
                $script .= '<span><input type="radio" name="'.$radioName.'" id="'.$radioId.'EA" value="EA"'.$enacquisition.'><label for="'.$radioId.'EA">En cours d\'acquisition</label></span>';
                $script .= '<span><input type="radio" name="'.$radioName.'" id="'.$radioId.'NA" value="NA"'.$nonacquis.'><label for="'.$radioId.'NA">Non Acquis</label></span>';
                $script .= '</section>';
                $script .= '</section>';
            }
            print($script);
        ?>
        <section class="formbtn">
            <input type="submit" value="Enregistrer les modifications" />
        </section>
    </form>
</section>
