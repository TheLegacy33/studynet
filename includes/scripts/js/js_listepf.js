$(document).ready(function(){
	$("select[name=cbactive]").change(function (){
		var href = "index.php?p=periodesformation";
		if ($(this).val() != '0'){
			var href = "index.php?p=periodesformation&active=" + $(this).val();
		}
		location.href = href;
	});
});