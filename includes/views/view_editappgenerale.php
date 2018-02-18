<?php
$appreciationG = Evaluation::getAppreciationGenerale($etudiant->getId(), $pf->getId());
?>
<nav class="navinterne">
    <?php print('<a href="'.$_SERVER['HTTP_REFERER'].'" title="Retour"><< Retour</a>'); ?>
</nav>
<section id="content_body" class="row formaffiche">
	<header class="text-center text-info" style="font-size: 20px">
		Appréciation générale sur <?php print($etudiant->getNom().' '.$etudiant->getPrenom()); ?>
	</header>
    <form action="" method="post">
        <section class="row">
            <label>Appréciation :</label><br />
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
</section>
