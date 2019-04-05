$(document).ready(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
	if ($("#ttDateDebut").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}

	if ($("#ttDateFin").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}

	if ($("#ttDuree").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}
}).blur(function(){
	if ($("#ttDateDebut").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}

	if ($("#ttDateFin").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}

	if ($("#ttDuree").val().trim() != ''){
		$("button[type=submit]").attr("disabled", false);
	}else{
		$("button[type=submit]").attr("disabled", true);
	}
});

$("#cbResponsable").change(function (){
	$("button[type=submit]").attr("disabled", false);
});

$("#cbStatut").change(function (){
	$("button[type=submit]").attr("disabled", false);
});

$("button[type=reset]").click(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("#frmSaisie").submit(function(){
	if ($("#ttDateDebut").val().trim() == ''){
		alert("Vous devez saisir la date de début de la période !");
		$("#ttDateDebut").focus();
		return false;
	}

	if ($("#ttDateFin").val().trim() == ''){
		alert("Vous devez saisir la date de fin de la période !");
		$("#ttDateFin").focus();
		return false;
	}

	if ($("#ttDuree").val().trim() == ''){
		alert("Vous devez saisir la durée !");
		$("#ttDuree").focus();
		return false;
	}

	var dateDebut = $("#ttDateDebut").val().trim();
	var datefin = $("#ttDateFin").val().trim();
	if (datefin <= dateDebut){
		alert("Vous devez sélectionner une date de fin postérieure à la date de début !");
		$("#ttDateFin").focus();
		return false;
	}

	return true;
});