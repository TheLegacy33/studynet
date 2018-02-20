<?php
/**
 * Définition des éléments de bas de page
 */
?>
		<footer id="footer" class="row">Copyrights © 2017</footer>
		</section>
	</body>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->

<script
	src="https://code.jquery.com/jquery-2.2.4.js"
	integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
	crossorigin="anonymous"></script>

<script
	src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
	integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
	crossorigin="anonymous"></script>

<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
			integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<?php
    if (isset($includeJs) AND $includeJs == true){
    	foreach ($scriptname as $script){
        	print ('<script type="text/javascript" src="'.ROOTHTMLSCRIPTSJS.$script.'" ></script>');
		}
    }
?>
</html>

