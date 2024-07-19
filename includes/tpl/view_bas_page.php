<?php
/**
 * Définition des éléments de bas de page
 * @var $scriptname
 */
?>
		<footer id="footer" class="row"><span>Copyrights © 2017</span></footer>
	</section>

	<!--<script
		src="https://code.jquery.com/jquery-3.4.1.min.js"
		integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
		crossorigin="anonymous"></script>
	<script
		src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
		integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
		crossorigin="anonymous"></script>

	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
		integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
		crossorigin="anonymous"></script>

	<script
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
		integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
		crossorigin="anonymous"></script>-->
	<script type="application/javascript" src="<?php print(ROOTHTMLLIBS); ?>jquery-3.4.1.js"></script>
	<script type="application/javascript" src="<?php print(ROOTHTMLLIBS); ?>jquery-ui_1.12.1.js"></script>
	<script type="application/javascript" src="<?php print(ROOTHTMLLIBS); ?>popper.min_1.14.7.js"></script>
	<!--<script type="application/javascript" src="<?php print(ROOTHTMLLIBS); ?>bootstrap_4.3.1.js"></script>-->
	<script type="application/javascript" src="<?php print(ROOTHTMLLIBS); ?>bootstrap_4.6.0.js"></script>
	<?php
		if (isset($includeJs) AND $includeJs == true){
			foreach ($scriptname as $script){
				print ('<script type="text/javascript" src="'.ROOTHTMLSCRIPTSJS.$script.'" ></script>');
			}
		}
	?>
	</body>
</html>