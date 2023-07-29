
<?php 
	require "other/header.php"; 
	require "engines/text/google.php";
	require "engines/infobox/wikipedia.php";
	require "engines/special/grammar.php";
	require "other/functions.php";

	$config = readJson('config.json');

	if ($config['ratelimit'] === "enabled") {
		require "other/ratelimit.php";
	}
	
	$query = htmlspecialchars($_REQUEST["q"],ENT_QUOTES,'UTF-8');
	$page = htmlspecialchars($_REQUEST["pg"],ENT_QUOTES,'UTF-8');
	$type = htmlspecialchars($_REQUEST["tp"],ENT_QUOTES,'UTF-8');

	$response = getHTML(htmlspecialchars($query), $page);
?>
		<title><?php echo $query ?> - SearchTLD</title>
	</head>
	<body>
		<?php
		 $border = isset($_COOKIE["border"]) ? $_COOKIE["border"] : 'on';
		 if ($border == 'on') {
			echo "<style>";
			echo ".a-result {";
			echo "background: var(--info-bg);";
			echo "border: 1px solid var(--border-color);";
			echo "border-radius: 8px;";
			echo "}";
			echo "</style>";
		 }
		?>
		<div class="msearch">
			<form autocomplete="off" <?php $method = isset($_COOKIE["querymethod"]) ? $_COOKIE["querymethod"] : 'GET';
			echo "method=\"$method\""; ?>>
				<a href="/"><img class="mobimg" <?php
					$theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';
					if ($theme === 'light') {
						echo 'src="/static/img/logo_light.png"';
					} else {
						echo 'src="/static/img/logo_dark.png"';
					}
				?>></a>
				<input type="search" name="q" value="<?php echo $query; ?>" required>
				<input type="hidden" name="pg" value="0"> 
				<input type="hidden" name="tp" value="<?php echo $type ?>"> 
			</form>
			<form>
				<input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
				<input type="hidden" name="pg" value="<?php echo $page; ?>">
				<div class="sbuttons">
					<button name="tp" <?php if ($type == 0) {
						echo "id=\"active\"";
					} ?> value="0"><img src="/static/img/text.png" class="bimage"/>Text</button>
					<button name="tp" <?php if ($type == 1) {
						echo "id=\"active\"";
					} ?> value="1"><img src="/static/img/image.png" class="bimage"/>Images</button>
					<button name="tp" <?php if ($type == 2) {
						echo "id=\"active\"";
					} ?> value="2"><img src="/static/img/video.png" class="bimage"/>Videos</button>
					<button name="tp" <?php if ($type == 3) {
						echo "id=\"active\"";
					} ?> value="3"><img src="/static/img/news.png" class="bimage"/>News</button>
					<button name="tp" <?php if ($type == 4) {
						echo "id=\"active\"";
					} ?> value="4"><img src="/static/img/forums.png" class="bimage"/>Forums</button>
				</div>
			</form>
		</div>
		<?php 
			if ($type == 0) {
			
				send_infobox($query);
				
				$gresponse = getGrammar($query);
				if ($gresponse) {
					echoCorrection($gresponse, $query);
				}

				$special_result = detect_special_query($query);
				if ($special_result == 1) {
					require "engines/special/ip.php";
					echoIP();
				} else if ($special_result == 2) {
					require "engines/special/useragent.php";
					echoUA();
				} else if ($special_result == 3) {
					require "engines/special/base64.php";
					encodeBase64($query);
				} else if ($special_result == 4) {
					require "engines/special/base64.php";
					decodeBase64($query);
				}
			}
		?>
		<div class="results" id="results">
			<?php
				switch ($type) {
					case 0:
						if ($page > 5) {
							require "engines/text/brave.php";
							$response = getbHTML($query, $page);
							
							send_text_th_response($response);
						} elseif ($page < 5 && $page > 1) {
							require "engines/text/ddg.php";
							$response = getdHTML($query, $page);
							
							send_text_sec_response($response);
						} else {
							send_text_response($response);
						}
						break;
					case 1:
						require "engines/images/qwant.php";
						$response = getiHTML($query, $page);
						
						send_image_response($response);
						break;
					case 2:
						require "engines/videos/invidious.php";
						$response = getvJson($query);
						
						send_video_response($response);
						break;
					case 3:
						require "engines/news/google.php";
						$response = getnHTML($query, $page);
						
						send_news_response($response);
						break;
					case 4:
						require "engines/forums/reddit.php";
						require "engines/forums/stackexchange.php";
						require "engines/forums/quora.php";
						$response = getrHTML($query);
						 
						send_red_response($response);
						
						$response = getQuetreRes($query);
						echoQResponse($response);

						$response = getstHTML($query);
						send_stack_response($response);
						break;
					default:
						send_text_response($response);
						break;
				}
			?>
			<script src="scripts/fallback-text.js"></script>
		</div>
		<div class="rbuttons">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
					<input name="q" value="<?php echo $query; ?>" type="hidden">
					<?php
						if (intval($type) !== 1 && intval($type) !== 2 && intval($type) !== 4) {
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

<?php require "other/footer.php"; ?>
