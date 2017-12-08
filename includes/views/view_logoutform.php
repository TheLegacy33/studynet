<?php

?>
<form action="index.php?p=auth" method="post" id="frmLogin">
	<header>Authentification</header>
	<?php
	var_dump($user);
	?>
	<section>
		Bienvenue $user->getNomComplet(),<br />
		Vous êtes actuellement connecté en tant que $user->getLogin().<br />

	</section>
	<footer class="formbtn">
		<input type="submit" name="logout" value="Déconnecter" />
	</footer>
</form>