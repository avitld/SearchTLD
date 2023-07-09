<?php require "other/header.php";
	$url = $_REQUEST["link"];
	$title = htmlspecialchars($_REQUEST["title"], ENT_QUOTES, 'UTF-8');
	$href = $_REQUEST["href"];

?>
		<title><?php echo $title ?> - SearchTLD</title>
	</head>
	<?php require "other/functions.php";
		$config = readJson("config.json");
	?>
	<body>
		<div class="preview" align=center>
			<?php echo "<img src=\"proxy-image.php?url=$url\" />" ?>
			<h2><?php echo $title ?></h2>
			<?php echo "<a href=\"proxy-image.php?url=$url\" download title=\"$title.png\">"; ?><button id="download">Download</button></a>
			<a href="<?php echo $href ?>" ><button id="download">Visit Original Webpage</button></a>
		</div>
<?php require "other/footer.php"; ?>
