$(document).ready(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
	$("button[type=submit]").attr("disabled", false);
});

$("button[type=reset]").click(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("#frmSaisie").submit(function(){
	if ($("#ttNom").val().trim() == ''){
		alert("Vous devez saisir le nom !");
		$("#ttNom").focus();
		return false;
	}
	return true;
});