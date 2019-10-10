let ttNom = $("#ttNom");

ttNom.data('msg', "Vous devez saisir le nom de l'école !").data('incorrectvalue', '');

let requiredFields = [ttNom];

$("#frmSaisie").submit(function(){
	return testFormFields(true);
});