<?php require "other/header.php"; ?>
		<title>Settings - SearchTLD</title>
	</head>
	<body>
		<div align="center">
		<?php
            if (isset($_REQUEST["reset"])) {
                setcookie("lang", "en", time() + (10 * 365 * 24 * 60 * 60));
                setcookie("theme", "dark", time() + (10 * 365 * 24 * 60 * 60));
                setcookie("safesearch", "", time() - 1000);
                header("Location: /");
                die();
            }

			if (isset($_REQUEST["save"])) {
            	foreach($_POST as $key=>$value) {
                    setcookie($key, $value, time() + (10 * 365 * 24 * 60 * 60), '/');
                }
                header("Location: /");
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
                <label for="safesearch">Safe Search:</label>
                <input type="checkbox" name="safesearch" <?php echo isset($_COOKIE["safesearch"]) ? "checked"  : ""; ?>>
				<br/>
				<br/>
                <label for="lang">Search Language:</label>
                <input type="text" name="lang" placeholder="en, gr, cn, fr, etc." value="<?php $eValue = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : '';
                                    echo $eValue; ?>">
				<br/>
				<br/>
                <button type="submit" name="save">Save</button>
                <button type="submit" name="reset" style="border: 2px solid red;">Reset</button>
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
