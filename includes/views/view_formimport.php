<nav class="navinterne">
	<?php
		if (isset($urlRetour)){
			print($urlRetour);
		}
	?>
</nav>
<form action="" method="post" id="frmPostFile" enctype="multipart/form-data">
	<div class="panel panel-default text-justify">
		<div class="panel-heading">Envoi d'un fichier</div>
		<div class="panel-body">
			<p class="text-muted">
				Ce écran vous permet d'importer un fichier contenant des informations formatées pour les stocker dans la base de données de l'application.<br />
				<?php
					if (isset($formatAttendu)){
						print($formatAttendu);
					}
				?>
			</p>
			<?php
				if (isset($message)){
					print('<p class="text-danger">'.$message.'</p>');
				}
			?>
			<div class="text-default"><label for="chkEntete">La première ligne contient les en-têtes de colonnes ? </label>
				<input type="checkbox" name="chkEntete[]" id="chkEntete" <?php print($checked?'checked':''); ?> />
			</div>
		</div>
		<div class="panel-footer">
			<p class="text-muted">La taille maximum autorisée est de <?php print(number_format(getMaximumFileUploadSize(), 0, ',', ' ')); ?> octets</p>
			<label for="ttFicRetour">Fichier :</label><input type="file" name="ttFichier" id="ttFichier" />
		</div>
	</div>
	<footer class="formbtn">
		<button type="submit" class="btn btn-success">Envoyer<span class="glyphicon glyphicon-floppy-open"></span></button>
	</footer>
</form>