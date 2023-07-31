<?php
	$config = readJson("config.json");
?>

		<div class="footer">
			<a href="https://codeatomic.net/SearchTLD/SearchTLD">Source Code</a>
			<a href="/settings<?php if ($config['hide_extension'] !== 'enabled') {
				echo ".php";
			}?>">Settings</a>
			<a target="_blank" href="https://schizo.gr/support/">Support Us</a>
			<a target="_blank" href="https://blog.searchtld.com">Changelog</a>
			<?php
				if ($config["tor"]["enabled"] == "enabled") {
					$torInstance = $config["tor"]["instanceURL"];
					echo "<a href=\"$torInstance\">Tor</a>";
				}
			?>
		</div>
	</body>
</html>
