<?php
    require "other/functions.php";
    session_start();

    if (isset($_SESSION['rate_limit_triggered']) && $_SESSION['rate_limit_triggered']) {
        if (isset($_POST['submit'])) {
            $captcha_challenge = $_POST['captcha_challenge'];
            if ($captcha_challenge === $_SESSION['captcha_text']) {
                unset($_SESSION['rate_limit_triggered']);
                unset($_SESSION['request_count']);
                header("Location: /");
                exit();
            } else {
                header("Location: /blocked.php");
            }
        }
?>

<?php require "other/header.php"; ?>
		<title>Temporarily Suspended</title>
	</head>
    <?php require "other/background.php"; ?>
    <body>
        <div class="indexform">
            <h1>Suspicious activity detected</h1>
            <p>Please confirm you are a human by completing the captcha below.</p>
            <form method="post">
                <img src="other/captcha.php" alt="CAPTCHA" class="captcha-image">
                <br/>
                <input type="text" id="captcha" name="captcha_challenge" style="padding: 10px;">
                <input type="submit" name="submit" id="verify" value="Verify" style="cursor: pointer;">
            </form>
        </div>
    </body>
<?php require "other/footer.php"; ?>

<?php
} else {
    header("Location: /");
    exit();
}
?>