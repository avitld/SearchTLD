<?php require "other/header.php"; ?>
		<title>Home - SearchTLD</title>
	</head>
	<body>
		<div class="indexform">
			<h1>Search<span id="blue">TLD</span></h1>
			<form method="get" autocomplete="off" action="search.php">
				<input type="search" name="q" autofocus required>
				<input type="hidden" name="pg" value="0">
				<input type="hidden" name="tp" value="0">

				<button type="submit">Search!</button>
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
