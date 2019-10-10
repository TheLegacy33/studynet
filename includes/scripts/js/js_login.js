let ttLogin = $("#ttLogin");
let ttPassword = $("#ttPassword");
let requiredFields = [ttLogin, ttPassword];

ttLogin.data('msg', 'Vous devez saisir votre identifiant !').data('incorrectvalue', '');
ttPassword.data('msg', 'Vous devez saisir votre mot de passe !').data('incorrectvalue', '');

$("#frmLogin").submit(function(){
	return testFormFields(true);
});