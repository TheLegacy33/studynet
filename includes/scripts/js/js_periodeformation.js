function activeSubmitButton(activate = false){
	$("button[type=submit]").attr("disabled", !activate);
}

function testFormFields(fields = []){
	var nbNonValidFields = 0;
//TODO : Break on alert
	fields.forEach(function(value, index){
		if (value.val().trim() === ''){
			alert(value.data('msg'));
			nbNonValidFields++;
			return false;
		}
	});

	return (nbNonValidFields === 0);
}

$(document).ready(function(){
	activeSubmitButton();
});

var cbResponsable = $("#cbResponsable");
var ttDateDebut = $("#ttDateDebut");
var ttDateFin = $("#ttDateFin");
var ttDuree = $("#ttDuree");

ttDateDebut.data('msg', 'Vous devez saisir la date de début de période !');
ttDateFin.data('msg', 'Vous devez saisir la date de fin de période !');
cbResponsable.data('msg', 'Vous devez sélectionner un référent pédagogique !');

var requiredFields = [cbResponsable, ttDateDebut, ttDateFin, ttDuree];

$("input").change(function(){
	activeSubmitButton(testFormFields(requiredFields));
}).blur(function(){
	activeSubmitButton(testFormFields(requiredFields));
});

$("select").change(function (){
	activeSubmitButton(testFormFields(requiredFields));
});

$("button[type=reset]").click(function(){
	activeSubmitButton();
});

$("#frmSaisie").submit(function(){
	if (ttDateDebut.val().trim() === ''){
		alert("Vous devez saisir la date de début de la période !");
		ttDateDebut.focus();
		return false;
	}

	if (ttDateFin.val().trim() === ''){
		alert("Vous devez saisir la date de fin de la période !");
		ttDateFin.focus();
		return false;
	}

	if (ttDuree.val().trim() === ''){
		ttDuree.val(0);
	}

	if (cbResponsable.val() === '0'){
		alert("Vous devez sélectionner un référent pédagogique !");
		cbResponsable.focus();
		return false;
	}

	var dateDebut = new Date(ttDateDebut.val());
	var dateFin = new Date(ttDateFin.val());
	if (dateFin <= dateDebut){
		alert("Vous devez sélectionner une date de fin postérieure à la date de début !");
		ttDateFin.focus();
		return false;
	}

	return true;
});