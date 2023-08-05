<!DOCTYPE html>
<html>
    <head>
        <?php require "misc/templates/header.php"; ?>
		<title>Settings - SearchTLD</title>
	</head>
    <?php require "misc/functions/functions.php"; ?>
	<body id="has-background cover">
		<main id="centered-body">
            <?php

                $config = readJson("config.json");

                if (isset($_REQUEST["reset"])) {
                    setcookie("lang", "en", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("theme", "dark", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("border", "on", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("querymethod", "GET", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("searcher", "google", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("safesearch", "", time() - 1000);

                    header("Location: /settings");
                    die();
                }

                if (isset($_REQUEST["home"])) {
                    header("Location: /");
                }

                if (isset($_REQUEST["save"])) {
                    foreach($_POST as $key=>$value) {
                        setcookie($key, $value, time() + (10 * 365 * 24 * 60 * 60), '/');
                    }
                    global $config;

                    header("Location: /settings");
                    die();
            }
            ?>
			<h1>SearchTLD Configuration</h1>
			<hr>
			<form method="post" enctype="multipart/form-data">
                <h2>Appearance</h2>
				<label for="theme">Theme:</label>
				<select name="theme">
				<?php
                    $themes = "<option value=\"dark\">Night</option>
                    <option value=\"light\">Light</option>
                    <option value=\"midnight\">Midnight</option>
                    <option value=\"pitchblack\">Pitch-Black</option>
                    <option value=\"alien\">Alien</option>
                    <option value=\"deep\">Deep Sea</option>
                    <option value=\"candy\">Candy</option>
                    <option value=\"galactic\">Galactic</option>";

                    if (isset($_COOKIE["theme"])) {
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

                    if (isset($_COOKIE["border"])) {
                        $border_cookie = $_COOKIE["border"];
                        $borders = str_replace($border_cookie . "\"", $border_cookie . "\" selected", $borders);
                    }

                    echo $borders;
                ?>
                </select><br/><br/>
                <h2>Usage</h2>
                <label for="querymethod">Query Method:</label>
                <select name="querymethod">
				<?php
                    $methods = "<option value=\"GET\">GET</option>
                    <option value=\"POST\">POST</option>";

                    if (isset($_COOKIE["querymethod"])) {
                        $method_cookie = $_COOKIE["querymethod"];
                        $methods = str_replace($method_cookie . "\"", $method_cookie . "\" selected", $methods);
                    }

                    echo $methods;
                ?>
                </select><br/><br/>
                <label for="safesearch">Safe Search:</label>
                <input type="checkbox" name="safesearch" <?php echo isset($_COOKIE["safesearch"]) ? "checked"  : ""; ?>>
				<br/>
				<br/>
                <label for="lang">Search Language:</label>
                <select name="lang">
                <?php
                    require "misc/utils/language.php";
                    foreach ($languages as $language) { 
                        $name = $language['name'];
                        $code = $language['code'];

                        $selected = '';
                        if (isset($_COOKIE['lang'])) {
                            if ($_COOKIE['lang'] === $code) {
                                $selected = 'selected';
                            }
                        } else {
                            if ($code === 'en') {
                                $selected = 'selected';
                            }
                        }

                        echo "<option value=\"$code\" $selected>$name</option>";
                    }
                ?>
                </select>
				<br/>
				<br/>
                <label for="tld">TLD Region:</label>
                <select name="tld">
                    <option value=".com" 
                    <?php
                        if (!isset($_COOKIE['tld'])) {
                            echo "selected";
                        }
                    ?>>All</option>
                <?php
                    require "misc/utils/language.php";
                    foreach ($languages as $language) { 
                        if ($tld) {
                            $name = $language['cname'];
                            $tld = $language['tld'];
    
                            $selected = '';
                            if (isset($_COOKIE['tld'])) {
                                if ($_COOKIE['tld'] === $tld) {
                                    $selected = 'selected';
                                }
                            }
    
                            echo "<option value=\"$tld\" $selected>$name</option>";
                        }
                    }
                ?>
                </select>
                <br/>
                <br/>
                <label for="region">Search Region:</label>
                <select name="region">
                    <option value="" 
                    <?php
                        if (!isset($_COOKIE['region'])) {
                            echo "selected";
                        }
                    ?>>Any</option>
                    <?php
                        require "misc/utils/language.php";
                        foreach ($languages as $language) { 
                            if ($alphacode) {
                                $code = $language['alphacode'];
                                $name = $language['cname'];
        
                                $selected = '';
                                if (isset($_COOKIE['region'])) {
                                    if ($_COOKIE['region'] === $code) {
                                        $selected = 'selected';
                                    }
                                }
        
                                echo "<option value=\"$code\" $selected>$name</option>";
                            }
                        }
                    ?>
                </select>
                <br/>
                <br/>
                <label for="searcher">Primary Search Engine:</label>
				<select name="searcher">
				<?php
                    $engines = "<option value=\"google\">Google</option>
                    <option value=\"ddg\">DuckDuckGo</option>
                    <option value=\"bing\">Bing</option>
                    <option value=\"brave\">Brave Search</option>";

                    if (isset($_COOKIE["searcher"])) {
                        $search_cookie = $_COOKIE["searcher"];
                        $engines = str_replace($search_cookie . "\"", $search_cookie . "\" selected", $engines);
                    }

                    echo $engines;
                ?>
                </select>
                <br/>
                <br/>
                <button type="submit" name="save" id="save">Save</button>
                <button type="submit" name="reset" id="reset">Reset</button>
                <br/>
                <button name="home" id="home">Go Back</button>
			</form>
        </main>
        <?php require "misc/templates/footer.php"; ?>
	</body>
</html>
