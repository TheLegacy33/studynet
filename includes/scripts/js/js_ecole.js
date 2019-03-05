$(document).ready(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
	if ($("#ttNom").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}
}).blur(function(){
	if ($("#ttNom").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}
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