<?php
	$config = readJson("config.json");
?>

<footer>
	<ul>
		<li><a href="https://codeatomic.net/SearchTLD/SearchTLD">Source Code</a></li>
		<li><a href="/privacy">Privacy Policy</a>
		<li><a target="_blank" href="https://schizo.gr/support/">Support Us</a></li>
		<li><a href="about">About Us</a></li>
		<li><a target="_blank" href="https://blog.searchtld.com">Blog</a></li>
		<?php
			if ($config["tor"]["enabled"] == "enabled") {
				$torInstance = $config["tor"]["instanceURL"];
				echo "<li><a href=\"$torInstance\">Tor Instance</a></li>";
			}
		?>
	</ul>
	<div class="footer-sidebar">
		<div class="image-holder">
			<img src="<?php showLogo(); ?>">
			<h3>SearchTLD</h3>
		</div>
		<div class="footer-text">
			<p class="copyright-notice">
				© 2023 SearchTLD. All rights reserved.
			</p>
			<p class="license-notice">
				This software is provided as-is, without any warranty or guarantee of any kind.<br/>
				SearchTLD is free software, licensed under the GNU AGPL-3.0.
			</p>
		</div>
	</div>
</footer>
