$(document).ready(function(){
    $("button[type=submit]").attr("disabled", true);
});
var ttResume = $("#ttResume");
var ttLibelle = $("#ttLibelle");
var cbIntervenant = $("#cbIntervenant");
$("input").change(function(){
    $("button[type=submit]").attr("disabled", false);
}).blur(function(){
	$("button[type=submit]").attr("disabled", false);
});

ttResume.change(function(){
	$("button[type=submit]").attr("disabled", false);
});

cbIntervenant.change(function (){
	$("button[type=submit]").attr("disabled", false);
});

$("#cbUniteEnseignement").change(function (){
	$("button[type=submit]").attr("disabled", false);
});

$("button[type=reset]").click(function(){
	$("button[type=submit]").attr("disabled", true);
});

$("#frmSaisie").submit(function(){
    if (ttLibelle.val().trim() === ''){
        alert("Vous devez saisir le libellé !");
		ttLibelle.focus();
        return false;
    }

    if (cbIntervenant.val() === '0'){
    	alert('Vous devez sélectionner un intervenant !');
		cbIntervenant.focus();
    	return false;
	}
    return true;
});


