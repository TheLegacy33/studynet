<nav class="navinterne">
	<a href="index.php" title="Retour à l'accueil"><< Retour</a>
</nav>
<form action="" method="post" id="frmPostFile" enctype="multipart/form-data">
	<div class="card text-justify">
		<div class="card-header text-uppercase">Envoi de votre réponse au sujet de rattrapage</div>
		<div class="card-body">
			<p class="text-muted">
				Envoyez votre réponse au sujet de rattrapage pour le module de <code><?php print($rattrapage->getModule()->getLibelle()); ?></code>.<br />
				Si vous rencontrez des soucis, faites en part à l'intervenant directement concerné par le rattrapage et transmettez lui votre réponse par email.
			</p>
			<?php
				if (isset($message)){
					print('<p class="text-danger">'.$message.'</p>');
				}
			?>
		</div>
		<?php if ($cansend) { ?>
		<div class="card-body">
			<p class="text-muted">La taille maximum autorisée est de <?php print(number_format(getMaximumFileUploadSize(), 0, ',', ' ')); ?> octets</p>
			<label for="ttFicRetour">Réponse :</label><input type="file" name="ttFicRetour" id="ttFicRetour" />
		</div>
		<?php } ?>
	</div>
	<?php if ($cansend) { ?>
	<footer class="card-footer formbtn">
		<button type="submit" class="btn btn-success">Envoyer<span class="fa fa-save"></span></button>
	</footer>
	<?php } ?>
</form>