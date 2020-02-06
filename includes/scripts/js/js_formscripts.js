function activeSubmitButton(activate = false){
	$("button[type=submit]").attr("disabled", !activate);
}

function testFormFields(withAlert = false, fields = requiredFields){
	let nbNonValidFields = 0;
	fields.forEach(function(value, index){
		if (value.val().trim() === value.data('incorrectvalue')){
			if (withAlert) {
				alert(value.data('msg'));
			}
			nbNonValidFields++;
		}
	});
	return (nbNonValidFields === 0);
}

$(document).ready(function(){
	activeSubmitButton(testFormFields());

	requiredFields.forEach(function (field){
		field.change(function(){
			activeSubmitButton(testFormFields());
		}).blur(function(){
			activeSubmitButton(testFormFields());
		});
	});

	requiredFields[0].focus();

	$("button[type=reset]").click(function(e){
		e.preventDefault();
		e.currentTarget.form.reset();
		activeSubmitButton(testFormFields());
	});
});