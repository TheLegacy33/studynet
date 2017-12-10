<?php
?>
<form action="index.php?p=auth" method="post" id="frmLogin">
	<header>Authentification</header>
	<section>
		Bienvenue <?php print($user); ?>,<br />
		Votre identifiant de connexion est : <?php print($user->getUserAuth()->getLogin()); ?>.<br />
        <?php
            if (!$user->estVisiteur()){
                print('Votre profil est : '.get_class($user));
                print('Vous pouvez accéder à votre profil <a href="index.php?p=users&a=profile" title="Votre profil">ici</a>.');
            }
        ?>
	</section>
	<footer class="formbtn">
		<input type="submit" name="logout" value="Déconnecter" />
	</footer>
</form>