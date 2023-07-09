<?php require "other/header.php";
	$url = $_GET["link"];
	$title = htmlspecialchars($_REQUEST["title"], ENT_QUOTES, 'UTF-8');
	$href = $_REQUEST["href"];


	function proxyImage($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$thumbnail = curl_exec($ch);
		header("Content-Type: image/png");
		echo $thumbnail;
	}
?>
		<title><?php echo $title ?> - SearchTLD</title>
	</head>
	<?php require "other/functions.php";
		$config = readJson("config.json");
	?>
	<body>
		<div class="preview" align=center>
			<?php echo "<img src=\"$url\" />" ?>
			<h2><?php echo $title ?></h2>
			<?php echo "<a href=\"$url\" download title=\"$title.png\">"; ?><button id="download">Download</button></a>
			<a href="<?php echo $href ?>" ><button id="download">Visit Original Webpage</button></a>
		</div>
<?php require "other/footer.php"; ?>
