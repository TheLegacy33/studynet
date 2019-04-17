$(document).ready(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
	eventsChamps();
}).blur(function(){
	eventsChamps();
});

$("#frmLogin").submit(function(){
	return verifChamps();
});

function verifChamps(){
	var ttLogin = $("#ttLogin");
	var ttPassword = $("#ttPassword");
	if (ttLogin.val().trim() === ''){
		alert("Vous devez saisir votre identifiant !");
		ttLogin.focus();
		return false;
	}
	if (ttPassword.val().trim() === ''){
		alert("Vous devez saisir votre mot de passe !");
		ttPassword.focus();
		return false;
	}
	return true;
}

function eventsChamps(){
	var ttLogin = $("#ttLogin");
	var ttPassword = $("#ttPassword");
	if (ttLogin.val().trim() === '' || ttPassword.val().trim() === ''){
		$("button[type=submit]").attr("disabled", true);
	}else{
		$("button[type=submit]").attr("disabled", false);
	}
}