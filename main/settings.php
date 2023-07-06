<?php require "other/header.php"; ?>
		<title>Settings - SearchTLD</title>
	</head>
    <?php require "other/background.php"; ?>
	<body>
		<div align="center">
		<?php
            if (isset($_REQUEST["reset"])) {
                setcookie("lang", "en", time() + (10 * 365 * 24 * 60 * 60));
                setcookie("theme", "dark", time() + (10 * 365 * 24 * 60 * 60));
                setcookie("border", "on", time() + (10 * 365 * 24 * 60 * 60));
                setcookie("safesearch", "", time() - 1000);
                header("Location: /settings.php");
                die();
            }

            if (isset($_REQUEST["home"])) {
                header("Location: /");
            }

			if (isset($_REQUEST["save"])) {
            	foreach($_POST as $key=>$value) {
                    setcookie($key, $value, time() + (10 * 365 * 24 * 60 * 60), '/');
                }
                header("Location: /settings.php");
                die();
           }
        ?>
			<h1>SearchTLD Configuration</h1>
			<hr>
			<form method="post" enctype="multipart/form-data">
				<label for="theme">Theme:</label>
				<select name="theme">
				<?php
                    $themes = "<option value=\"dark\">Night</option>
                    <option value=\"light\">Light</option>";

                    if (isset($_COOKIE["theme"]))
                    {
                        $theme_cookie = $_COOKIE["theme"];
                        $themes = str_replace($theme_cookie . "\"", $theme_cookie . "\" selected", $themes);
                    }

                    echo $themes;
                ?>
                </select><br/><br/>
                <label for="border">Result Border:</label>
				<select name="border">
				<?php
                    $borders = "<option value=\"on\">Enabled</option>
                    <option value=\"off\">Disabled</option>";

                    if (isset($_COOKIE["border"]))
                    {
                        $border_cookie = $_COOKIE["border"];
                        $borders = str_replace($border_cookie . "\"", $border_cookie . "\" selected", $borders);
                    }

                    echo $borders;
                ?>
                </select><br/><br/>
                <label for="safesearch">Safe Search:</label>
                <input type="checkbox" name="safesearch" <?php echo isset($_COOKIE["safesearch"]) ? "checked"  : ""; ?>>
				<br/>
				<br/>
                <label for="lang">Search Language:</label>
                <input type="text" name="lang" placeholder="en, gr, cn, fr, etc." value="<?php $eValue = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : '';
                                    echo $eValue; ?>">
				<br/>
				<br/>
                <button type="submit" name="save" id="save">Save</button>
                <button type="submit" name="reset" id="reset">Reset</button>
                <br/>
                <button name="home" id="home">Go Back</button>
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
