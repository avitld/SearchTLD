
<?php 
	require "other/header.php"; 
	require "search/text.php";
	$query = $_REQUEST["q"];
	$page = $_REQUEST["pg"];
	$type = $_REQUEST["tp"];

	$response = getHTML($query, $page);
?>
		<title><?php echo $query; ?> - SearchTLD</title>
	</head>
	<body>
		<div class="msearch">
			<form autocomplete="off" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<label class="mtitle"><a href="/" style="color: white; text-decoration: none;">Search<span id="blue">TLD</span></a></label>
				<input class="msearch" type="search" name="q" value="<?php echo $query; ?>" required>
				<input type="hidden" name="pg" value="0"> 
				<div class="sbuttons">
					<button <?php if ($type == 0) {
						echo "id=\"active\"";
					} ?> name="tp" value="0">Text</button>
					<button name="tp" <?php if ($type == 1) {
						echo "id=\"active\"";
					} ?> value="1">Images</button>
				</div>
			</form>
		</div>
		<?php 
			if ($type == 0) {
				send_infobox($response);
			}
		?>
		<div class="results">
			<?php
				switch ($type) {
					case 0:
						send_text_response($response);
						break;

					case 1:
						require "search/image.php";
						$response = getiHTML($query, $page);
						send_image_response($response);
						break;
					default:
						send_text_response($response);
						break;
				}
			?>
		</div>

<?php require "other/footer.php"; ?>
