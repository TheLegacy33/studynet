<?php
	/**
	 * @var Etudiant $etudiant
	 * @var Module $module
	 * @var Periodeformation $pf
	 * @var $listeContenusModule
	 */
    $commentaireModule = Evaluation::getAppreciationModule($etudiant->getId(), $module->getIntervenant()->getId(), $module->getId());
?>
<section id="content_body" class="row formaffiche">
	<nav class="navinterne">
		<?php print('<a href="index.php?p=periodesformation&a=listemodules&idetudiant='.$etudiant->getId().'&idpf='.$pf->getId().'" title="Retour à la liste des modules"><< Retour</a>'); ?>
	</nav>
	<header class="col-12 text-center text-info" style="font-size: 20px">
		Evaluations du module <?php print($module->getLibelle()); ?> pour
		<?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
	</header>

    <form action="" method="post" class="offset-2 col-8">
        <section class="col-12">
            <label>Commentaire du module :</label><br />
			<?php
				if (is_null($commentaireModule)){
					$script = '<textarea name="comm_module" style="width: 98%; height: 100px" placeholder="Pas de commentaire"></textarea>';
				}else{
					$script = '<textarea name="comm_module" style="width: 98%; height: 100px">'.$commentaireModule.'</textarea>';
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

                $script .= '<section class="evalcontenumodule">';
                $script .= '<header class="libelle pb-4">'.$contenuModule->getLibelle().'</header>';
				$script .= '<label class="ml-2">Commentaire :</label>';
                $script .= '<section class="text-center">';
				if ($eval->getCommentaire() != null){
					$script .= '<textarea name="comm_'.$contenuModule->getId().'" class="" style="width: 98%; height: 100px;">'.$eval->getCommentaire().'</textarea>';
				}else{
					$script .= '<textarea name="comm_'.$contenuModule->getId().'" class="" style="width: 98%; height: 100px;" placeholder="Pas de commentaire"></textarea>';
				}
				$script .= '</section>';
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
