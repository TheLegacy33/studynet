$(document).ready(function(){
	$("a[data-id=lnkdld]").click(function(){
		if ($(this).attr("tag") === "1"){
            return confirm("L'accès au téléchargement du sujet lancera le décompte du temps restant pour votre rendu. Etes-vous sûr de vouloir télécharger le sujet maintenant ?");
		}
	});
});