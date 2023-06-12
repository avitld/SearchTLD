
<?php 
	require "other/header.php"; 
	require "search/text/google.php";
	
	$query = htmlspecialchars($_REQUEST["q"],ENT_QUOTES,'UTF-8');
	$page = $_REQUEST["pg"];
	$type = $_REQUEST["tp"];

	$response = getHTML(htmlspecialchars($query), $page);
?>
		<title><?php echo $query ?> - SearchTLD</title>
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
				<input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
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
						if ($page > 5) {
							require "search/text/brave.php";
							$response = getbHTML($query, $page);
							send_text_th_response($response);
						} elseif ($page < 5 && $page > 1) {
							require "search/text/ddg.php";
							$response = getdHTML($query, $page);
							send_text_sec_response($response);
						} else {
							$fallback = check_for_fallback($response);
							echo $fallback;
							if ($fallback){
								send_correction($response);
								send_text_response($response);
							} else {
								require "search/text/ddg.php";
								$response = getdHTML($query, $page);
								send_text_sec_response($response);
							}
						}
						break;
					case 1:
						require "search/images/qwant.php";
						$response = getiHTML($query, $page);
						send_image_response($response);
						break;
					case 2:
						require "search/videos/invidious.php";
						$response = getvJson($query);
						send_video_response($response);
						break;
					case 3:
						require "search/news/google.php";
						$response = getnHTML($query, $page);
						send_title($response);
						send_news_response($response);
						break;
					default:
						send_text_response($response);
						break;
				}
			?>
			<div class="rbuttons">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
					<input name="q" value="<?php echo $query; ?>" type="hidden">
					<?php
						if (intval($type) !== 1 && intval($type) !== 2) {
							if ($page > 0) {
								echo "<button class=\"pagebtn\" type=\"submit\" name=\"pg\" value=" . intval($page) - 1 . ">Previous Page</button>";
							}
							if ($page < 10) {
								echo "<button class=\"pagebtn\" type=\"submit\" name=\"pg\" value=" . intval($page) + 1 . ">Next Page</button>";
							}
						}
					?>
					<input name="tp" value="<?php echo $type; ?>" type="hidden">
				</form>
			</div>
		</div>

<?php require "other/footer.php"; ?>
