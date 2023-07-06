<?php require "other/header.php"; ?>
		<title>Home - SearchTLD</title>
	</head>
	<?php require "other/background.php"; ?>
	<?php require "other/placeholder.php"; ?>
	<body>
		<div class="indexform">
			<h1>Search<span id="purple">TLD</span></h1>
			<form <?php
			$method = isset($_COOKIE["querymethod"]) ? $_COOKIE["querymethod"] : 'GET';
			echo "method=\"$method\"";
			?> autocomplete="off" action="search.php">
				<div class="search-container">
					<input type="search" name="q" autofocus required placeholder="<?php $numb = pickRand(); returnArray($numb, $searchPlaceholders); ?> " value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); ?>"><button type="submit">
					<?php
						$theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';
						if ($theme == 'light') {
							echo "<img src=\"/static/img/mag_light.png\" />";
						} else {
							echo "<img src=\"/static/img/mag_dark.png\" />";
						}
					?> Search!</button>
				</div>
				<input type="hidden" name="pg" value="0">
				<input type="hidden" name="tp" value="0">
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
