$(document).ready(function(){
	$('a[data-name=sendmail]').each(function(){
		$(this).click(function(){
			if (confirm('Etes-vous sûr de vouloir envoyer les informations du profil à l\'utilisateur ?')){
				$.ajax({
					url: 'index.php',
					type: 'get',
					data: {p: 'api', action: 'sendprofile', id: $(this).attr("data-id")},
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
		});
	});

	$("#cbFiltreType").change(function(){
		$(location).attr('href', 'index.php?p=personnes&a=listepersonnes&type=' + $(this).val());
	});
});