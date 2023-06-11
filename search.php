
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
				<label class="mtitle"><a href="/" style="color: var(--fg-color-m); text-decoration: none;">Search<span id="blue">TLD</span></a></label>
				<input type="search" name="q" value="<?php echo $query; ?>" required>
				<input type="hidden" name="pg" value="0"> 
				<input type="hidden" name="tp" value="<?php echo $type ?>"> 
			</form>
			<form>
				<input type="hidden" name="q" value="<?php echo $query; ?>">
				<input type="hidden" name="pg" value="<?php echo $page; ?>">
				<div class="sbuttons">
					<button <?php if ($type == 0) {
						echo "id=\"active\"";
					} ?> name="tp" value="0"><img src="/static/img/text.png" class="bimage"/>Text</button>
					<button name="tp" <?php if ($type == 1) {
						echo "id=\"active\"";
					} ?> value="1"><img src="/static/img/image.png" class="bimage"/>Images</button>
					<button name="tp" <?php if ($type == 2) {
						echo "id=\"active\"";
					} ?> value="2"><img src="/static/img/video.png" class="bimage"/>Videos</button>
					<button name="tp" <?php if ($type == 3) {
						echo "id=\"active\"";
					} ?> value="3"><img src="/static/img/news.png" class="bimage"/>News</button>
					<!-- <button name="tp" value="4"><img src="/static/img/files.png" class="bimage"/>Files</button> -->
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
						send_correction($response);
						send_text_response($response);
						break;

					case 1:
						require "search/image.php";
						$response = getiHTML($query, $page);
						send_image_response($response);
						break;
					case 2:
						require "search/video.php";
						$response = getvJson($query);
						send_video_response($response);
						break;
					case 3:
						require "search/news.php";
						$response = getnHTML($query, $page);
						send_title($response);
						send_news_response($response);
						break;
					default:
						send_text_response($response);
						break;
				}
			?>
		</div>

<?php require "other/footer.php"; ?>
