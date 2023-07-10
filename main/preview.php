<?php require "other/header.php";

$url = trim($_GET["link"]);
$title = htmlspecialchars($_REQUEST["title"], ENT_QUOTES, 'UTF-8');
$href = htmlspecialchars($_REQUEST["href"], ENT_QUOTES, 'UTF-8');
?>

<title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?> - SearchTLD</title>

<?php require "other/functions.php";
$config = readJson("config.json");
$url = cleanUrl($url);
$url = urlencode($url);
?>

<body>
    <div class="preview" align=center>
        <?php echo "<img src=\"/proxy-image.php?url=$url\" />"; ?>
        <h2><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php echo "<a href=\"/proxy-image.php?url=$url&alt=$title\" download>"; ?><button id="download">Download (Proxy)</button></a>
        <?php echo "<a href=\"". urldecode($url) . "\" download>"; ?><button id="download">Download (Original)</button></a>
        <br/>
        <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"><button id="download">Visit Original Webpage</button></a>
    </div>
    
<?php require "other/footer.php"; ?>


