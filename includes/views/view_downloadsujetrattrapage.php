<section id="content_body" class="row">
	<div class="card card-warning text-justify">
		<div class="card-header text-uppercase">Téléchargement du sujet de rattrapage</div>
		<div class="card-body">
			<p class="text-danger">
                L'accès à cette page a déclenché le compteur de temps pour la limite donnée pour ce sujet qui est de <mark><?php print($rattrapage->getDelai()) ?></mark>.<br />
                Vous devrez transmettre votre réponse avant le temps imparti faute de quoi votre rattrapage ne sera pas validé.
			</p>
			<p class="text-center">
				<mark class="lead">Date attendue pour votre rendu : <?php print($dateRenduAttendue); ?></mark>
			</p>
            <?php if (!$firstdld){ ?>
                <p class="text-muted">Le sujet de rattrapage pour le module de <code><?php print($rattrapage->getModule()->getLibelle()); ?></code>
                    est encore disponible <a id="lnksujet" download="<?php print(basename($rattrapage->getFicSujet())); ?>" href="<?php print(ROOTHTMLSUJETS.$rattrapage->getFicSujet()) ?>" title="Sujet">ici</a>.</p>
            <?php } else{ ?>
                <p class="text-muted">Le sujet de rattrapage pour le module de <code><?php print($rattrapage->getModule()->getLibelle()); ?></code> va se télécharger dans quelques secondes.</p>
                <p class="text-muted">Si le téléchargement ne commence pas d'ici 10 secondes, cliquez
                    <a id="lnksujet" download="<?php print(basename($rattrapage->getFicSujet())); ?>" href="<?php print(ROOTHTMLSUJETS.$rattrapage->getFicSujet()) ?>" title="Sujet">ici</a>.
                </p>
            <?php }	?>
		</div>
	</div>
</section>