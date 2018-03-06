<?php

?>
<form action="" method="post" id="frmProfile" enctype="multipart/form-data">
	<header>Profil utilisateur</header>
	<section>
        <header>Informations de connexion</header>
		<div><label for="ttLogin">Login : </label><input type="text" name="ttLogin" id="ttLogin" value="<?php print($user->getUserAuth()->getLogin()); ?>" /></div>
		<div><label for="ttPassword">Password : </label><input type="password" name="ttPassword" id="ttPassword" value="<?php print($user->getUserAuth()->getPassword()); ?>" /></div>
		<div><label for="ttPasswordVerif">Vérification : </label><input type="password" id="ttPasswordVerif" /></div>
	</section>
    <section>
        <header>Informations personnelles</header>
		<div><label for="ttNom">Nom : </label><input type="text" name="ttNom" id="ttNom" value="<?php print($user->getNom()); ?>" /></div>
		<div><label for="ttPrenom">Prénom : </label><input type="text" name="ttPrenom" id="ttPrenom" value="<?php print($user->getPrenom()); ?>" /></div>
		<div><label for="ttEmail">Email : </label><input type="email" name="ttEmail" id="ttEmail" value="<?php print($user->getEmail()); ?>" /></div>
        <?php
            if ($user->estEtudiant()){
                print('<div><label for="ttPhoto">Photo :</label><input type="file" name="ttPhoto" id="ttPhoto" value="'.$user->getPhoto().'" /></div>');
            }
        ?>
	</section>
	<footer class="formbtn">
		<button type="submit" class="btn btn-success">Enregistrer<span class="glyphicon glyphicon-floppy-save"></span></button>
		<button type="reset" class="btn btn-default">Annuler<span class="glyphicon glyphicon-floppy-remove"></span></button>
	</footer>
</form>
<script type="text/javascript">
    var loginOrigine = "<?php print($user->getUserAuth()->getLogin()); ?>";
    var passOrigine = "<?php print($user->getUserAuth()->getPassword()); ?>";
</script>