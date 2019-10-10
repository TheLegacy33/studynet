let ttLibelle = $("#ttLibelle");
let cbIntervenant = $("#cbIntervenant");

ttLibelle.data('msg', "Vous devez saisir le libellé !").data('incorrectvalue', '');
cbIntervenant.data('msg', "Vous devez sélectionner un intervenant !").data('incorrectvalue', '0');

let requiredFields = [ttLibelle, cbIntervenant];

let ttCode = $("#ttCode");
ttCode.change(function(){
	$(this).val($(this).val().toLocaleUpperCase());
});

$("#frmSaisie").submit(function(){
	return testFormFields(true);
});


