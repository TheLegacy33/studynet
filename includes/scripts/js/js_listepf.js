$(document).ready(function(){
	$("select[name=cbactive]").change(function (){
		if ($(this).val() != '0'){
			var href = "index.php?p=periodesformation";
			var query_string = window.location.search.split('?')[1];
			var query_params = query_string.split('&');

			var found = query_params.find(function(element){
				if (element.split('=')[0] === 'active'){
					return element;
				}
			});
			href = "index.php?";

			//console.log(found);

			if (found != undefined){
				//Param trouvé il faut le mettre à jour
				query_params[query_params.indexOf(found)] = "active=" + $(this).val();
			}else{
				query_params.push("active=" + $(this).val());
			}
			href += query_params.join('&');
			//console.log(href);
			location.href = href;
		}
	});
});