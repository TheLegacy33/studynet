$(document).ready(function(){
	$('a[data-name=dropmodule]').each(function(){
		$(this).click(function(){
			alert('La suppression d\'un module n\'est pas encore active !');
			return;
			if (confirm('Etes-vous sûr de vouloir supprimer ce module ?')){
				$.ajax({
					url: 'index.php',
					type: 'get',
					data: {p: 'ajax', a: 'dropmodule', id: $(this).attr("data-id")},
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
			}
		});
	});
});