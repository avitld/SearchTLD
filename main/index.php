<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require "misc/templates/header.php" ?>
		
		<title>SearchTLD</title>
	</head>
	<?php require "misc/utils/placeholder.php"; ?>
	<?php require "misc/functions/functions.php";
		$config = readJson("config.json");
	?>
	<body id="has-background">
		<main id="centered-body" class="index-main">
			<div class="title-holder">
				<img src="<?php showLogo(); ?>">
				<h1>Search<span id="purple">TLD</span></h1>
			</div>
			<form <?php
			$method = isset($_COOKIE["querymethod"]) ? $_COOKIE["querymethod"] : 'GET';
			echo "method=\"$method\"";
			?> autocomplete="off" action="search">
				<div class="search-container">
					<input type="search" name="q" autofocus required placeholder="<?php
						$numb = pickRand();
						returnArray($numb, $searchPlaceholders);
					?> "
					value="<?php
						echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); 
					?>"> <button type="submit">
					<?php
						$theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';
						if ($theme == 'light' || $theme == 'candy') {
							echo "<img src=\"/static/img/mag_light.png\" />";
						} else {
							echo "<img src=\"/static/img/mag_dark.png\" />";
						}
					?>
					Search!</button>
				</div>
				<input type="hidden" name="pg" value="0">
				<input type="hidden" name="tp" value="0">
			</form>
			<div class="credit">
				<p>Logo by <b><a href="https://github.com/snowfoxsh" target="_blank">snowfoxsh</a></b></p>
			</div>
			<div class="about">
				<div class="card" id="first-card">
					<h1>The Search Engine That Doesn't Track You.</h1>
					<p>
						SearchTLD is a privacy centered meta-search engine.
						We help you take back your right to privacy online.
					</p>
					<br/>
					<h3>SearchTLD is a meta-search engine</h3>
					<p>
						we don't have our own index/database to gather results from,
						rather we gather results for various different sources to
						provide accurate results. In practice, this means you can enjoy
						accurate and relevant results from various sources whether it be
						a forum or a different search engine.
					</p>
				</div>
				<div class="card" id="second-card">
					<h1>Privacy Features</h1>
					<div class="grid-wrapper">
						<div class="card-grid">
							<div class="info-card">
								<img src="/static/img/anonymous.png">
								<h2>Anonymized Results</h2>
								<p>Results are anonymized and cleared of tracking elements.</p>
							</div>

							<div class="info-card">
								<img src="/static/img/targeted.png">
								<h2>No Targeted Advertising</h2>
								<p>We allow no trackers to serve you personalized ads.</p>
							</div>

							<div class="info-card">
								<img src="/static/img/malware.png">
								<h2>Block Harmful Links</h2>
								<p>All links are anonymously checked to ensure they contain no malicious content.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>

		<?php require "misc/templates/footer.php"; ?>

	</body>
</html>