var needLogout = false;
var passChanged = false;

$(document).ready(function(){
    $("#ttPasswordVerif").attr("disabled", true);
    $("button[type=submit]").attr("disabled", true);
});

$("input").change(function(){
    $("button[type=submit]").attr("disabled", false);
});

$("#ttLogin").change(function(){
    needLogout = true;
});

$("#ttPassword").keypress(function(ev){
    $("#ttPasswordVerif").attr("disabled", false);
    needLogout = true;
});

$("#ttPassword").change(function(){
    passChanged = true;
});

$("#ttPasswordVerif").change(function(){
    passChanged = true;
});

$("#frmProfile").submit(function(){
    if (needLogout){
        alert("Les modifications apportées nécessitent que vous vous déconnectiez.");
        if (!confirm("Etes-vous sûr de vouloir valider les modifications ?")){
            $("#ttLogin").val(loginOrigine);
            $("#ttPassword").val(loginOrigine);
            needLogout = false;
            return false;
        }
    }

    if ($("#ttLogin").val().trim() == ''){
        alert("Vous devez saisir votre identifiant !");
        $("#ttLogin").focus();
        return false;
    }

    if (passChanged){
        if ($("#ttPassword").val().trim() == ''){
            alert("Vous devez saisir votre mot de passe !");
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


    if ($("#ttNom").val().trim() == ''){
        alert("Vous devez saisir votre nom !");
        $("#ttNom").focus();
        return false;
    }

    if ($("#ttPrenom").val().trim() == ''){
        alert("Vous devez saisir votre prénom !");
        $("#ttPrenom").focus();
        return false;
    }
    return true;
});

$("#frmProfile").on("reset", function (){
    needLogout = false;
});
