<?php
?>
<form action="index.php?p=auth" method="post" id="frmLogin">
	<header>Informations</header>
	<section>
		Bienvenue <?php print($user); ?>,<br />
		Votre identifiant de connexion est : <?php print($user->getUserAuth()->getLogin()); ?>.<br />
        <?php
            if (!$user->estVisiteur()){
                print('Votre profil est : '.$user->get_class().'</br>');
                print('Vous pouvez accéder à votre profil <a href="index.php?p=users&a=profile" title="Votre profil">ici</a>.<br />');
            }
            if ($user->isAdmin()){
            	print("Vous disposez des droits d'administration de l'application !<br />");
			}
        ?>
	</section>
	<footer class="formbtn">
		<input type="submit" name="logout" value="Déconnecter" />
	</footer>
</form>