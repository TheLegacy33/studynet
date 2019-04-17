<section class="container">
	<form action="index.php?p=auth" method="post" id="frmLogin">
		<div class="card text-justify">
			<div class="card-header text-uppercase">Authentification</div>
			<div class="card-body">
				<div class="form-group">
					<label for="ttLogin">Identifiant</label>
					<input type="text" class="form-control" name="ttLogin" id="ttLogin" aria-describedby="helpIdLogin" placeholder="">
					<small id="helpIdLogin" class="form-text text-muted">Votre identifiant</small>
				</div>
				<div class="form-group">
					<label for="ttPassword">Mot de passe</label>
					<input type="password" class="form-control" name="ttPassword" id="ttPassword" aria-describedby="helpIdPwd" placeholder="">
					<small id="helpIdPwd" class="form-text text-muted">Votre mot de passe</small>
				</div>
			</div>
			<div class="card-footer formbtn">
				<button type="submit" class="btn btn-success mr-3">Connexion<span class="fa fa-user"></span></button>
			</div>
		</div>
	</form>
</section>