$(document).ready(function(){
	$('a[data-name=sendmail]').each(function(){
		$(this).click(function(){
			if (confirm('Etes-vous sûr de vouloir envoyer les informations du profil à l\'utilisateur ?')){
				$.ajax({
					url: 'index.php',
					type: 'get',
					data: {p: 'ajax', a: 'sendprofile', id: $(this).attr("data-id")},
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					dataType: 'text',
					success: function (reponse, statut) {
						console.log(reponse);
					},
					error: function (reponse, statut, erreur) {
						console.error(reponse.status + ' : ' + reponse.statusText);

					}
				});
			}else{
				return false;
			}
		});
	});

	$("#cbFiltreType").change(function(){
		$(location).attr('href', 'index.php?p=personnes&a=listepersonnes&type=' + $(this).val());
	});

	$('a[data-name=renewpassword]').each(function(){
		$(this).click(function(){
			if (confirm('Etes-vous sûr de vouloir réinitialiser le mot de passe de l\'utilisateur ?')){
				var idPers = $(this).attr("data-id");
				$.ajax({
					url: 'index.php',
					type: 'get',
					data: {p: 'ajax', a: 'renewpassword', id: idPers},
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					dataType: 'text',
					success: function (reponse, statut) {
						console.log(reponse);
						if (reponse == 1){
							if (confirm('Souhaitez-vous transmettre cette modification à l\'utilisateur par email ?')){
								$.ajax({
									url: 'index.php',
									type: 'get',
									data: {p: 'ajax', a: 'sendprofile', id: idPers},
									contentType: "application/x-www-form-urlencoded;charset=UTF-8",
									dataType: 'text',
									success: function (reponse, statut) {
										console.log(reponse);
									},
									error: function (reponse, statut, erreur) {
										console.error(reponse.status + ' : ' + reponse.statusText);

									}
								});
							}
						}
					},
					error: function (reponse, statut, erreur) {
						console.error(reponse.status + ' : ' + reponse.statusText);

					}
				});
			}else{
				return false;
			}
		});
	});

	$('a[data-name=dropuserauth]').each(function(){
		$(this).click(function(){
			if (confirm('Etes-vous sûr de vouloir supprimer les informations d\'authentification de cette personne ?')){
				$.ajax({
					url: 'index.php',
					type: 'get',
					data: {p: 'ajax', a: 'dropuserauth', id: $(this).attr("data-id")},
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					dataType: 'text',
					success: function (reponse, statut) {
						console.log(reponse);
						if (reponse == 1){
							location.reload(true);
						}
					},
					error: function (reponse, statut, erreur) {
						console.error(reponse.status + ' : ' + reponse.statusText);

					}
				});
			}else{
				return false;
			}
		});
	});
});