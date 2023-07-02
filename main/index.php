<?php require "other/header.php"; 
	require "other/functions.php";
?>
		<title>Home - SearchTLD</title>
	</head>
	<body>
		<?php
			$config = readJSON("internalconfig.json");
		?>
		<div class="indexform">
			<h1>Search<span id="blue">TLD</span></h1>
			<form method="<?php echo $config['querymethod']; ?>" autocomplete="off" action="search.php">
				<input type="search" name="q" autofocus required value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); ?>">
				<input type="hidden" name="pg" value="0">
				<input type="hidden" name="tp" value="0">

				<button type="submit">Search!</button>
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
