$(document).ready(function(){
    $("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
    $("button[type=submit]").attr("disabled", false);
});

$("#ttNom").change(function(){
	$("button[type=submit]").attr("disabled", false);
	$(this).val($(this).val().toUpperCase().trim());
});

$("#ttPrenom").change(function (){
	$("button[type=submit]").attr("disabled", false);
	$(this).val($(this).val().substr(0, 1).toUpperCase().trim() + $(this).val().substr(1).toLowerCase().trim());
});

$("#ttEmail").change(function (){
	$("button[type=submit]").attr("disabled", false);
});

$("button[type=reset]").click(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("#frmSaisie").submit(function(){
    if ($("#ttNom").val().trim() == ''){
        alert("Vous devez saisir le nom !");
        $("#ttNom").focus();
        return false;
    }

	if ($("#ttPrenom").val().trim() == ''){
		alert("Vous devez saisir le prénom !");
		$("#ttPrenom").focus();
		return false;
	}

    return true;
});


