let ttLibelle = $("#ttLibelle");

ttLibelle.data('msg', "Vous devez saisir le libellé !").data('incorrectvalue', '');

let requiredFields = [ttLibelle];

$("#frmSaisie").submit(function(){
	return testFormFields(true);
});


