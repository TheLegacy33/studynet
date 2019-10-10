let ttNom = $("#ttNom");
let ttPrenom = $("#ttPrenom");

ttNom.data('msg', "Vous devez saisir le nom de l'étudiant !").data('incorrectvalue', '');
ttPrenom.data('msg', "Vous devez saisir le prénom de l'étudiant!").data('incorrectvalue', '');

let requiredFields = [ttNom, ttPrenom];

ttNom.change(function(){
	$(this).val($(this).val().toUpperCase().trim());
});

ttPrenom.change(function (){
	$(this).val($(this).val().trim().substr(0, 1).toUpperCase().trim() + $(this).val().trim().substr(1).toLowerCase().trim());
});

$("#frmSaisie").submit(function(){
    return testFormFields(true);
});


