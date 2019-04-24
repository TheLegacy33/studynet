<?php
	/**
	 * @var Personne $user
	 */
?>

<section class="container">
	<form action="index.php?p=auth" method="post" id="frmLogin">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Informations</div>
			<div class="card-body">
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
			</div>
			<div class="card-footer formbtn">
				<button type="submit" name="logout" class="btn btn-success mr-3">Déconnexion<span class="fa fa-user"></span></button>
			</div>
		</div>
	</form>
</section>