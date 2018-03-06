<?php

?>
<form action="index.php?p=auth" method="post" id="frmLogin">
	<header>Authentification</header>
	<div>
		<label for="ttLogin">Login : </label><input type="text" name="ttLogin" id="ttLogin" />
	</div>
	<div>
		<label for="ttPassword">Password : </label><input type="password" name="ttPassword" id="ttPassword" />
	</div>
	<footer class="formbtn">
		<input type="submit" value="Connexion" />
	</footer>
</form>