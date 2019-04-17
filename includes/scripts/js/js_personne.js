var passChanged = false;

$(document).ready(function(){
    $("button[type=submit]").attr("disabled", true);
    $("#infosconn").hide();
    $("#infosconn input").each(function(){
		$(this).attr("disabled", !$("#infosconn").is(":visible"));
	});
});

$("input").change(function(){
    $("button[type=submit]").attr("disabled", false);
});

$("#ttPassword").keypress(function(){
    $("#ttPasswordVerif").attr("disabled", false);
	passChanged = true;
}).change(function(){
    passChanged = true;
});

$("#ttPasswordVerif").change(function(){
    passChanged = true;
});

$("button[type=reset]").click(function(){
	$("button[type=submit]").attr("disabled", true);
	$("#infosconn").hide();
	$("#infosconn input").each(function(){
		$(this).attr("disabled", !$("#infosconn").is(":visible"));
	});
});

$("button[type=button]").click(function(){
	var infos = $("#infosconn");
	if (infos.is(":visible")){
		infos.hide();
	}else{
		infos.show();
	}
	$("#infosconn input").each(function(){
		if ($(this).attr('name') !== $("#ttPasswordVerif").attr('name')){
			$(this).attr("disabled", !infos.is(":visible"));
		}
	});
});

$("#frmProfile").submit(function(){
	if (!confirm("Etes-vous sûr de vouloir valider les modifications ?")){
		return false;
	}

	if ($("#infosconn").is(":visible")){
		if ($("#ttLogin").val().trim() == ''){
			alert("Vous devez saisir un identifiant !");
			$("#ttLogin").focus();
			return false;
		}

		if (passChanged){
			if ($("#ttPassword").val().trim() == ''){
				alert("Vous devez saisir un mot de passe !");
				$("#ttPassword").focus();
				return false;
			}else{
				if ($("#ttPassword").val() != $("#ttPasswordVerif").val()){
					alert("Vous devez saisir le même mot de passe dans la zone de vérification !")
					$("#ttPasswordVerif").focus();
					return false;
				}
			}
		}
	}

    if ($("#ttNom").val().trim() == ''){
        alert("Vous devez saisir un nom !");
        $("#ttNom").focus();
        return false;
    }

    if ($("#ttPrenom").val().trim() == ''){
        alert("Vous devez saisir un prénom !");
        $("#ttPrenom").focus();
        return false;
    }
    return true;
});
