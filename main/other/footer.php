<?php
	$config = readJson("config.json");
?>

		<div class="footer">
			<a href="https://git.schizo.gr/Avitld/SearchTLD">Source</a>
			<a href="/settings<?php if ($config['hide_extension'] !== 'enabled') {
				echo ".php";
			}?>">Config</a>
			<a target="_blank" href="https://schizo.gr/support/">Donate</a>
		</div>
	</body>
</html>
