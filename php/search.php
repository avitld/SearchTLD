<!DOCTYPE html>

<?php 

	$query = htmlspecialchars($_REQUEST["q"],ENT_QUOTES,'UTF-8');
	$page = htmlspecialchars($_REQUEST["pg"],ENT_QUOTES,'UTF-8');
	$type = htmlspecialchars($_REQUEST["tp"],ENT_QUOTES,'UTF-8');

	if ($type < 0 || $type > 5 || $page < 0 || $page > 10 || empty($query)) {
		header("Location: /");
	}
?>

<html lang="en">
	<head>
		<?php require "misc/templates/header.php"; ?>

		<?php
		 $border = isset($_COOKIE["border"]) ? $_COOKIE["border"] : 'on';
		 if ($border == 'on') {
			echo "<style>";
			echo ".text-result {";
			echo "background: var(--background-secondary);";
			echo "border: 1px solid var(--border-color);";
			echo "border-radius: 8px;";
			echo "}";
			echo "</style>";
		 }
		?>

		<title><?php echo $query; ?> - SearchTLD</title>
	</head>

<?php 
	require "engines/special/grammar.php";
	require "misc/functions/functions.php";
	require "engines/infobox/wikipedia.php";

	$config = readJson('config.json');

	bangSearch($query);

	if ($config['ratelimit'] === "enabled") {
		require "misc/utils/ratelimit.php";
	}
?>
	<body>
		<header>
			<form autocomplete="off" <?php $method = isset($_COOKIE["querymethod"]) ? $_COOKIE["querymethod"] : 'GET';
			echo "method=\"$method\""; ?>>
				<a href="/"><img id="image-mobile" src="<?php showLogo(); ?>"></a>
				<input type="search" name="q" value="<?php echo $query; ?>" required>
				<input type="hidden" name="pg" value="0"> 
				<input type="hidden" name="tp" value="<?php echo $type ?>"> 
			</form>
			<a href="/settings">
				<button class="settings">
					<img src="/static/img/cog.png" />
				</button>
			</a>
			<form>
				<input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
				<input type="hidden" name="pg" value="<?php echo $page; ?>">
				<div class="type-selectors">
					<button name="tp"
					<?php if ($type == 0) {
						echo "id=\"active\"";
					} ?>
					value="0"><img src="/static/img/text.png" />Text</button>
					<button name="tp"
					<?php if ($type == 1) {
						echo "id=\"active\"";
					} ?>
					value="1"><img src="/static/img/image.png" />Images</button>
					<button name="tp"
					<?php if ($type == 2) {
						echo "id=\"active\"";
					} ?>
					value="2"><img src="/static/img/video.png" />Videos</button>
					<button name="tp"
					<?php if ($type == 3) {
						echo "id=\"active\"";
					} ?>
					value="3"><img src="/static/img/news.png" />News</button>
					<button name="tp"
					<?php if ($type == 4) {
						echo "id=\"active\"";
					} ?>
					value="4"><img src="/static/img/forums.png" />Forums</button>
				</div>
			</form>
		</header>
		<div class="overlay" id="overlay">
			<div class="main-image-holder">
				<button id="close-button" onclick="hideOverlay()">X</button>
				<a id="visitLink"><h2>Image Title</h2></a>
				<img src="/static/img/mag_dark.png" />
				<hr>
				<a id="downloader" download><button id="download">Download</button></a><br/>
			</div>
		</div>
		<?php 
			if ($type == 0) {
			
				wikipediaInfo($query);
				
				$gresponse = getGrammar($query);
				if ($gresponse) {
					echoCorrection($gresponse, $query);
				}

				$special_result = detectSpecialQuery($query);
				echo "<div id=\"special\">";
				switch ($special_result) {
					case 1:
						require "engines/special/ip.php";
						echoIP();
						break;
					case 2:
						require "engines/special/useragent.php";
						echoUA();
						break;
					case 3:
						require "engines/special/base64.php";
						encodeBase64($query);
						break;
					case 4:
						require "engines/special/base64.php";
						decodeBase64($query);
						break;
					case 5:
						require "engines/special/weather.php";
						weather();
				}
				echo "</div>";
			}
		?>
		<div class="results" id="results">
<?php
				switch ($type) {
					case 0:
						if ($page > 5) {
							require "engines/text/brave.php";
							braveText($query, $page);
						} elseif ($page < 5 && $page > 1) {
							$secondarySearcher = isset($_COOKIE['secondarysearch']) ? $_COOKIE['secondarysearch'] : 'brave';
							switch ($secondarySearcher) {
								case 'ddg':
									require "engines/text/ddg.php";
									ddgText($query, $page);
									break;
								case 'brave':
									require "engines/text/brave.php";
									braveText($query, $page);
									break;
								case 'bing':
									require "engines/text/bing.php";
									bingText($query, $page);
									break;
								case 'yahoo':
									require "engines/text/yahoo.php";
									yahooText($query, $page);
									break;
								case 'google':
									require "engines/text/google.php";
									googleText($query, $page);
									break;
							}
						} else {
							$searcher = isset($_COOKIE['searcher']) ? $_COOKIE['searcher'] : 'ddg';
							switch ($searcher) {
								case 'ddg':
									require "engines/text/ddg.php";
									ddgText($query, $page);
									break;
								case 'brave':
									require "engines/text/brave.php";
									braveText($query, $page);
									break;
								case 'bing':
									require "engines/text/bing.php";
									bingText($query, $page);
									break;
								case 'yahoo':
									require "engines/text/yahoo.php";
									yahooText($query, $page);
									break;
								case 'google':
									require "engines/text/google.php";
									googleText($query, $page);
									break;
								default:
									require "engines/text/ddg.php";
									ddgText($query, $page);
							}
						}
						break;
					case 1:
						require "engines/images/qwant.php";
						qwantImage($query, $page);
						break;
					case 2:
						require "engines/videos/invidious.php";
						invidious($query);
						break;
					case 3:
						require "engines/news/google.php";
						googleNews($query, $page);
						break;
					case 4:
						require "engines/forums/reddit.php";
						require "engines/forums/stackexchange.php";
						require "engines/forums/quora.php";
						reddit($query);
						quetre($query);
						stackEx($query);
						break;
					default:
						ddgText($query, $page);
						break;
				}
				/* The below is used for JavaScript so it can access parameters in both POST and GET requests */
				echo "<input type=\"hidden\" id=\"query-info\" value=\"$query\" />";
				echo "<input type=\"hidden\" id=\"page-info\" value=\"$page\" />";
				echo "<input type=\"hidden\" id=\"type-info\" value=\"$type\" />";
			?>
			<script src="scripts/fallback-text.js"></script>
			<script src="scripts/preview-image.js"></script>
		</div>
		<div class="page-buttons">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
					<input name="q" value="<?php echo $query; ?>" type="hidden">
					<?php
						if (intval($type) !== 1 && intval($type) !== 2 && intval($type) !== 4) {
							if ($page > 0) {
								echo "<button class=\"page-button\" type=\"submit\" name=\"pg\" value=" . intval($page) - 1 . ">Previous Page</button>";
							}
							if ($page < 10) {
								echo "<button class=\"page-button\" type=\"submit\" name=\"pg\" value=" . intval($page) + 1 . ">Next Page</button>";
							}
						}
					?>
					<input name="tp" value="<?php echo $type; ?>" type="hidden">
				</form>
		</div>
		
		<?php require "misc/templates/footer.php"; ?>

	</body>
</html>