$(document).ready(function(){
    $("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
    $("button[type=submit]").attr("disabled", false);
});

$("#ttResume").change(function(){
	$("button[type=submit]").attr("disabled", false);
});

$("#cbIntervenant").change(function (){
	$("button[type=submit]").attr("disabled", false);
});

$("button[type=reset]").click(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("#frmSaisie").submit(function(){
    if ($("#ttLibelle").val().trim() == ''){
        alert("Vous devez saisir le libellé !");
        $("#ttLibelle").focus();
        return false;
    }
    return true;
});


