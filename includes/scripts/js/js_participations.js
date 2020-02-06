$(document).ready(function(){
	var lastIndex = 0;
	var newIndex = 0;
	var selectedId = 0;
    $('#tbetudiants .lignedata').click(function(){
    	if (selectedId !== 0){
    		if (selectedId === parseInt($(this).data('id'))){
				$(this).removeClass('active');
				selectedId = 0;
			}else{
				$('.lignedata[data-id=' + selectedId + ']').removeClass('active');
				$(this).addClass('active');
				selectedId =  $(this).attr('data-id');
			}
		}else{
			$(this).addClass('active');
			selectedId =  $(this).attr('data-id');
		}
		refreshModules(selectedId);
	});

    $('span[data-id=chk_all]').click(function(){
		checkAllModules();
	});
	$('span[data-id=chk_none]').click(function(){
		unchekAllModules();
	});

	$('input[data-name=chkmodule]').change(function(){
		updateAffectation(selectedId, $(this));
	});
});

function refreshModules(idEtudiant){
	if (idEtudiant !== 0){
		$.ajax({
			url: 'index.php',
			type: 'get',
			data: {p: 'ajax', a: 'getmodulesforstudent', idetudiant: idEtudiant, idpf: idPf},
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
	}else{
		unchekAllModules(false);
	}
}

function updateAffectation(idetudiant, chkmodule){
	if (idetudiant != 0){
        var idmodule = $(chkmodule).attr('name').split('_')[1];
        var checked = $(chkmodule).prop('checked');

        $.ajax({
            url: 'index.php',
            type: 'get',
            data: {p: 'ajax', a: 'setmodulesforstudent', idetudiant: idetudiant, idpf: idPf, idmodule: idmodule, participe: checked},
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            dataType: 'text',
            success: function (reponse, statut) {
                console.log(reponse);
            },
            error: function (reponse, statut, erreur) {
                console.error(reponse.status + ' : ' + reponse.statusText);
            }
        });
	}
}

function checkAllModules(withTrigger){
	if (withTrigger == undefined){withTrigger = true;}
	$('input[data-name=chkmodule]').prop('checked', true).each(function(){
        if (withTrigger) $(this).trigger('change');
	});
}

function unchekAllModules(withTrigger){
	if (withTrigger == undefined){withTrigger = true;}
	$('input[data-name=chkmodule]').prop('checked', false).each(function(){
        if (withTrigger) $(this).trigger('change');
    });
}
