<?php

?>
<nav class="navinterne">
	<?php print('<a href="index.php?p=personnes&a=listepersonnes" title="Retour à la liste des modules"><< Retour</a>'); ?>
</nav>
<form action="" method="post" id="frmProfile" enctype="multipart/form-data">
	<header>Profil Utilisateur</header>
    <section>
        <header>Informations personnelles</header>
		<div><label for="ttNom">Nom : </label><input type="text" name="ttNom" id="ttNom" value="<?php print($personne->getNom()); ?>" /></div>
		<div><label for="ttPrenom">Prénom : </label><input type="text" name="ttPrenom" id="ttPrenom" value="<?php print($personne->getPrenom()); ?>" /></div>
		<div><label for="ttEmail">Email : </label><input type="email" name="ttEmail" id="ttEmail" value="<?php print($personne->getEmail()); ?>" /></div>
        <?php
            if ($personne->estEtudiant()){
                print('<div><label for="ttPhoto">Photo :</label><input type="file" name="ttPhoto" id="ttPhoto" value="'.$personne->getPhoto().'" /></div>');
            }
        ?>
	</section>
	<footer class="formbtn">
		<button type="submit" class="btn btn-success">Enregistrer<span class="glyphicon glyphicon-floppy-save"></span></button>
		<button type="reset" class="btn btn-default">Annuler<span class="glyphicon glyphicon-floppy-remove"></span></button>
		<button type="button" class="btn btn-default" title="Informations de connexion">Authentification<span class="glyphicon glyphicon-lock"></span></button>
	</footer>
	<section id="infosconn">
		<header>Informations de connexion</header>
		<div><label for="ttLogin">Login : </label><input type="text" name="ttLogin" id="ttLogin" value="<?php print($personne->getUserAuth()->getLogin()); ?>" /></div>
		<div><label for="ttPassword">Password : </label><input type="password" name="ttPassword" id="ttPassword" value="<?php print($personne->getUserAuth()->getPassword()); ?>" /></div>
		<div><label for="ttPasswordVerif">Vérification : </label><input type="password" id="ttPasswordVerif" /></div>
	</section>
</form>
<script type="text/javascript">
    var loginOrigine = "<?php print($personne->getUserAuth()->getLogin()); ?>";
    var passOrigine = "<?php print($personne->getUserAuth()->getPassword()); ?>";
</script>