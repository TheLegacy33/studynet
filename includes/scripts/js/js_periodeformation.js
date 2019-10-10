let cbResponsable = $("#cbResponsable");
let ttDateDebut = $("#ttDateDebut");
let ttDateFin = $("#ttDateFin");
let ttDuree = $("#ttDuree");

ttDateDebut.data('msg', 'Vous devez saisir la date de début de période !').data('incorrectvalue', '');
ttDateFin.data('msg', 'Vous devez saisir la date de fin de période !').data('incorrectvalue', '');
cbResponsable.data('msg', 'Vous devez sélectionner un référent pédagogique !').data('incorrectvalue', '0');

let requiredFields = [cbResponsable, ttDateDebut, ttDateFin];

$("#frmSaisie").submit(function(){
	if (ttDuree.val().trim() === ''){
		ttDuree.val(0);
	}

	let dateDebut = new Date(ttDateDebut.val());
	let dateFin = new Date(ttDateFin.val());
	if (dateFin <= dateDebut){
		alert("Vous devez sélectionner une date de fin postérieure à la date de début !");
		ttDateFin.focus();
		return false;
	}

	return testFormFields(true);
});