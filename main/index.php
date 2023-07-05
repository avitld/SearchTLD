<?php require "other/header.php"; ?>
		<title>Home - SearchTLD</title>
	</head>
	<?php require "other/background.php"; ?>
	<body>
		<div class="indexform">
			<h1>Search<span id="purple">TLD</span></h1>
			<form method="get" autocomplete="off" action="search.php">
				<input type="search" name="q" autofocus required placeholder="What will you search for today?" value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); ?>">
				<input type="hidden" name="pg" value="0">
				<input type="hidden" name="tp" value="0">
				<br/>
				<button type="submit">Search!</button>
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
