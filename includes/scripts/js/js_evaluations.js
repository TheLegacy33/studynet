$(document).ready(function(){
	var selectedId = 0;

    $('#tbetudiants .lignedata').click(function(){
    	if (selectedId != 0){
    		$('.lignedata[data-id=' + selectedId + ']').attr('class', 'lignedata');
		}
		selectedId =  $(this).attr('data-id');
		$(this).addClass('active');
	});

    $('select[name=cbstatut]').focus(function(){
		var selector = '.lignedata[data-id=' + $(this).data('idstudent') + ']';
		$(selector).trigger('click');
	});

	$('input[name=ttnotes]').focus(function(){
		var selector = '.lignedata[data-id=' + $(this).data('idstudent') + ']';
		$(selector).trigger('click');
	});

	$('select[name=cbstatut] option:selected').each(function(){
		var idStudent = $(this).parent().data('idstudent');
		if ($(this).text() == 'Présent'){
			$('input[data-idstudent='+idStudent+']').prop('disabled', false);
		}else{
			$('input[data-idstudent='+idStudent+']').prop('disabled', true);
		}
	});

	$('select[name=cbstatut]').change(function(){
		//var idStudent = $(this).parent().data('idstudent');
		var idStudent = $(this).data('idstudent');
		var text = $('select[data-idstudent=' + idStudent + '] option:selected').text();
		if (text == 'Présent'){
			$('input[data-idstudent='+idStudent+']').prop('disabled', false);
			$('input[data-idstudent='+idStudent+']').focus();
			$('input[data-idstudent='+idStudent+']').select();
		}else{
			$('input[data-idstudent='+idStudent+']').prop('disabled', true);
			$('input[data-idstudent='+idStudent+']').val('0.00');
		}
		updateStudentNote(idStudent, $('input[data-idstudent='+idStudent+']').data('ideval'), $(this).val(), $('input[data-idstudent='+idStudent+']').val());
	});

	$('input[name=ttnotes]').blur(function(){
		$(this).css('border-color', '');
		if (verifFormat($(this))){
			updateStudentNote($(this).data('idstudent'), $(this).data('ideval'), $('select[data-idstudent=' + $(this).data('idstudent') + ']').val(), $(this).val());
		}else{
			$(this).css('border-color', 'red');
		}
	});

	$('#helpstatut').mouseover(function(event){
		var contenuInfo = '<u>Status disponibles :</u><br />';
		contenuInfo += '<i>Présent</i> : Etudiant présent et noté<br />';
		contenuInfo += '<i>Absent justifié</i> : Etudiant absent avec justificatif, la note ne compte pas dans la moyenne<br />';
		contenuInfo += '<i>Absent non justifié</i> : Etudiant absent sans justificatif, la note compte dans la moyenne<br />';
		contenuInfo += '<i>Non rendu</i> : Travail non rendu<br />';
		contenuInfo += '<i>Non évalué</i> : Etudiant non évalué<br />';
		createToolTip(event, contenuInfo);
	}).mouseout(function(){
		$('div.infobulle').fadeOut('slow');
	});

	$('#btAct button').click(function(){
		var newStatut = $(this).data('id');
	});
});

function verifFormat(champ){
	var valeur = $(champ).val().replace(',', '.').trim();
	var ok = true;
	if (isNaN(valeur)){
		valeur = 0.00;
		ok = false;
	}else{
		valeur = Math.abs(parseFloat(valeur));
		if (valeur < 0 || valeur > 20){
			valeur = 0.00;
			ok = false;
		}
	}
	$(champ).val(valeur.toFixed(2));
	return ok;
}

function updateStudentNote(idEtudiant, idEval, statut, note){
	/*console.log(idEtudiant, idEval, statut, note);*/
	$.ajax({
		url: 'index.php',
		type: 'get',
		data: {p: 'ajax', a: 'setStudentNote', idetudiant: idEtudiant, idevaluation: idEval, idstatut: statut, note: note},
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

function createToolTip(event, contenu){
	var tooltip = $('<div class="infobulle">' + contenu + '</div>');
	$(tooltip).fadeIn("slow");
	$('body').append(tooltip);
	positionToolTip(event);
}

function positionToolTip(event){
	var tPosX = event.pageX - 300;
	var tPosY = event.pageY + 10;
	$('div.infobulle').css({'font-size': '11px', 'position': 'absolute', 'top': tPosY, 'left': tPosX, 'width': '600px', 'text-align': 'left', 'padding-left': '10px'});
}