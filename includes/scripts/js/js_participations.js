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
		console.log('Je coche tout !');
		checkAllModules();
	});
	$('span[name=chk_none]').click(function(){
		console.log('Je décoche tout !');
		unchekAllModules();
	});

	$('input[data-name=chkmodule]').change(function(){
		console.log($(this).attr('name') + ' : ' + $(this).prop('checked'));
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
			unchekAllModules();
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

function checkAllModules(){
	$('input[data-name=chkmodule]').prop('checked', true);
}

function unchekAllModules(){
	$('input[data-name=chkmodule]').prop('checked', false);
}
