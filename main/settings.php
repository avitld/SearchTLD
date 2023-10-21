<!DOCTYPE html>
<html>
    <head>
        <?php require "misc/templates/header.php"; ?>
		<title>Settings - SearchTLD</title>
	</head>
    <?php require "misc/functions/functions.php"; ?>
	<body>
        <header class="tabs" id="centered-body">
            <h1>SearchTLD Configuration</h1>
            <button class="tab-link" onclick="openTab('general-tab')">General</button>
            <button class="tab-link" onclick="openTab('usage-tab')">Usage</button>
            <button class="tab-link" onclick="openTab('privacy-tab')">Privacy</button>
        </header>

		<main id="centered-body" class="padded-main">
            <?php

                $config = readJson("config.json");

                if (isset($_REQUEST["reset"])) {
                    setcookie("lang", "en", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("theme", "dark", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("border", "on", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("querymethod", "GET", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("searcher", "google", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("safesearch", "off", time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("enableFrontends", "enabled", time() + (10 * 365 * 24 * 60 * 60));

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

                    header("Location: /settings");
                    die();
            }
            ?>
            <form method="post" enctype="multipart/form-data">
            <div id="general-tab" class="tab-content">
                    <label for="theme">Theme:</label>
                    
                    <select name="theme">
                        <?php
                            $themes = "<option value=\"dark\">Night</option>
                            <option value=\"light\">Light</option>
                            <option value=\"nord\">Nord</option>
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
                    </select>

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
                    </select>

                    <div class="frontend-select">
                        <h3>Frontend settings</h3>
                        <?php frontendSettings(); ?>
                        <br/>
                        <label for="enableFrontends">Frontends:</label>
                        <select name="enableFrontends">
                            <?php
                                $options = "<option value=\"enabled\">Enabled</option>
                                <option value=\"disabled\">Disabled</option>";

                                if (isset($_COOKIE["enableFrontends"])) {
                                    $frontend_cookie = $_COOKIE["enableFrontends"];
                                    $options = str_replace($frontend_cookie . "\"", $frontend_cookie . "\" selected", $options);
                                }

                                echo $options;
                            ?>
                        </select>
                    </div>
            </div>

            <div id="usage-tab" class="tab-content">
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
                    </select>

                    <label for="safesearch">Safe Search:</label>
                    <select name="safesearch">
                        <?php
                            $safeSearch = "<option value=\"off\">Off</option>
                            <option value=\"on\">On</option>";

                            if (isset($_COOKIE["safesearch"])) {
                                $safe_cookie = $_COOKIE["safesearch"];
                                $safeSearch = str_replace($safe_cookie . "\"", $safe_cookie . "\" selected", $safeSearch);
                            }

                            echo $safeSearch;
                        ?>
                    </select>


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

                    <label for="tld">Google TLD:</label>
                    
                    <select name="tld">
                        <option value=".com" 
                        <?php
                            if (!isset($_COOKIE['tld'])) {
                                echo "selected";
                            }
                        ?>>.com</option>
                    <?php
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
        
                                echo "<option value=\"$tld\" $selected>.$tld</option>";
                            }
                        }
                    ?>
                    </select>

                    <label for="region">Search Region:</label>
                    
                    <select name="region">
                        <option value="" 
                        <?php
                            if (!isset($_COOKIE['region'])) {
                                echo "selected";
                            }
                        ?>>Any</option>
                        <?php
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
                    
                    <label for="searcher">Primary Search Engine:</label>
                    
                    <select name="searcher">
                        <?php
                            $engines = "<option value=\"google\">Google</option>
                            <option value=\"ddg\">DuckDuckGo</option>
                            <option value=\"yahoo\">Yahoo</option>
                            <option value=\"bing\">Bing</option>
                            <option value=\"brave\">Brave Search</option>";

                            if (isset($_COOKIE["searcher"])) {
                                $search_cookie = $_COOKIE["searcher"];
                                $engines = str_replace($search_cookie . "\"", $search_cookie . "\" selected", $engines);
                            }

                            echo $engines;
                        ?>
                    </select>

                    <label for="secondarysearch">Secondary Search Engine:</label>
                    
                    <select name="secondarysearch">
                        <?php
                            $engines = "<option value=\"ddg\">DuckDuckGo</option>
                            <option value=\"google\">Google</option>
                            <option value=\"yahoo\">Yahoo</option>
                            <option value=\"bing\">Bing</option>
                            <option value=\"brave\">Brave Search</option>";

                            if (isset($_COOKIE["secondarysearch"])) {
                                $search_cookie = $_COOKIE["secondarysearch"];
                                $engines = str_replace($search_cookie . "\"", $search_cookie . "\" selected", $engines);
                            }

                            echo $engines;
                        ?>
                    </select>
            </div>

            <div id="privacy-tab" class="tab-content">
                <form method="post" enctype="multipart/form-data">
                    <label for="suggestions">Search Suggestions:</label>
                        
                    <select name="suggestions">
                        <?php
                            $options = "<option value=\"on\">On</option>
                            <option value=\"off\">Off</option>";

                            if (isset($_COOKIE["suggestions"])) {
                                $suggestion_cookie = $_COOKIE["suggestions"];
                                $engines = str_replace($suggestion_cookie . "\"", $suggestion_cookie . "\" selected", $options);
                            }

                            echo $options;
                        ?>
                    </select>
                <br/>
            </div>
            <hr/>
            <div id="settings-buttons">
                    <button type="submit" name="save" id="save">Save</button>
                    <button type="submit" name="reset" id="reset">Reset</button>
                    <br/>
                    <button name="home" id="home">Go Back</button>
                    <br/><br/>
                    <a href="/settings-old"<button id="home">Settings (No JavaScript)</button></a>
            </div>
            </form>

            <script src="scripts/settings.js"></script>
        </main>
        <?php require "misc/templates/footer.php"; ?>
	</body>
</html>
