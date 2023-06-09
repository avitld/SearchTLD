<?php require "other/header.php"; ?>
		<title>Settings - SearchTLD</title>
	</head>
	<body>
		<div>
		<?php
			if (isset($_REQUEST["save"])) {
            	foreach($_POST as $key=>$value) {
                	if (!empty($value)) {
                    	setcookie($key, $value, time() + (86400 * 90), '/');
                    }
                    else {
                    	setcookie($key, "", time() - 1000);
                    }
                }
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
                        $themeValue = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : '';
                        echo "Current theme: $theme_cookie";
                    }

                    echo $themes;
                ?>
                </select><?php $cValue = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : '';
                                    echo "Current: $cValue"; ?><br/><br/>
                <label for="safesearch">Safe Search:</label>
                <select name="safesearch">
                <?php
                    $opts = "<option value=\"off\">Off</option>
                    <option value=\"on\">On</option>";

                    echo $opts;
                ?>
                </select>
                    <?php $oValue = isset($_COOKIE["safesearch"]) ? $_COOKIE["safesearch"] : '';
                    echo "Current: $oValue"; ?>
				<br/>
				<br/>
                <label for="lang">Search Language:</label>
                <input type="text" name="lang" placeholder="en, gr, cn, fr, etc." value="en"><?php $eValue = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : '';
                                    echo "Current: $eValue"; ?>
				<br/>
				<br/>
                <button type="submit" name="save">Save</button>
                <a href="index.php">Go Back</a>
			</form>
		</div>
	</body>
<?php require "other/footer.php"; ?>
