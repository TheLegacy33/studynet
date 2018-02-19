$(document).ready(function(){
	var lastIndex = 0;
	var newIndex = 0;
	var selectedId = 0;
    $('#tbetudiants .lignedata').click(function(){
    	if (selectedId != 0){
    		$('.lignedata[data-id=' + selectedId + ']').attr('class', 'lignedata');
		}
		selectedId =  $(this).attr('data-id');
		$(this).attr('class', 'lignedata active');
		refreshModules(selectedId);
	});

    $('span[name=chk_all]').click(function(){
    	if (selectedId != 0){
			console.log('Je coche tout !');
			checkAllModules();
        }
	});
	$('span[name=chk_none]').click(function(){
        if (selectedId != 0) {
            console.log('Je décoche tout !');
            unchekAllModules();
        }
	});

	$('input[data-name=chkmodule]').change(function(){
		updateAffectation(selectedId, $(this));
	});
});

function refreshModules(idEtudiant){
	$.ajax({
		url: 'index.php',
		type: 'get',
		data: {p: 'api', action: 'getmodulesforstudent', idetudiant: idEtudiant, idpf: idPf},
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		dataType: 'text',
		success: function (reponse, statut) {
            unchekAllModules(false);
			var tabIdModules = JSON.parse(reponse);
			for (var i = 0; i < tabIdModules.length; i++){
				var selector = 'input[name=chk_'+tabIdModules[i]+']';
				$(selector).prop('checked', true);
			}
		},
		error: function (reponse, statut, erreur) {
			console.error(reponse.status + ' : ' + reponse.statusText);

		}
	});
}

function updateAffectation(idetudiant, chkmodule){
	if (idetudiant != 0){
        var idmodule = $(chkmodule).attr('name').split('_')[1];
        var checked = $(chkmodule).prop('checked');

        $.ajax({
            url: 'index.php',
            type: 'get',
            data: {p: 'api', action: 'setmodulesforstudent', idetudiant: idetudiant, idpf: idPf, idmodule: idmodule, participe: checked},
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            dataType: 'text',
            success: function (reponse, statut) {
                console.log(reponse);
            },
            error: function (reponse, statut, erreur) {
                console.error(reponse.status + ' : ' + reponse.statusText);
            }
        });

	}else{
		console.warn("Pas d'étudiant sélectionné !");
	}

}

function checkAllModules(withTrigger = true){
	$('input[data-name=chkmodule]').prop('checked', true).each(function(){
        if (withTrigger) $(this).trigger('change');
	});
}

function unchekAllModules(withTrigger = true){
	$('input[data-name=chkmodule]').prop('checked', false).each(function(){
        if (withTrigger) $(this).trigger('change');
    });
}
