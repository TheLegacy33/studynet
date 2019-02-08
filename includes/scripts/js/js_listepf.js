$(document).ready(function(){
	$("select[name=cbactive]").change(function (){
		var href = "index.php?p=periodesformation";
		if ($(this).val() != '0'){
			var href = "index.php?" + window.location.search.split('?')[1] + "&active=" + $(this).val();
		}
		location.href = href;
	});
});