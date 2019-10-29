let ttNom = $("#ttNom");

ttNom.data('msg', "Vous devez saisir le nom de l'école !").data('incorrectvalue', '');

let requiredFields = [ttNom];

ttNom.change(function(){
	$(this).val($(this).val().trim());
});

$("#frmSaisie").submit(function(){
	return testFormFields(true);
});