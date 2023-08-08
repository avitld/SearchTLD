<?php

    function readJson($json) {
        $data = file_get_contents($json);
        $config = json_decode($data, true);

        return $config;
    }
    
    function detectSpecialQuery($q)
    {
        // Original code by pafefs, modified by avitld
        $modified_query = str_replace(" ","",strtolower($q));


        if (strpos($modified_query,"my") !== false && strpos($modified_query,"what") !== false && strpos($modified_query,"is") !== false ){
            if (strpos($modified_query, "ip")) {
                return 1;
            } elseif (strpos($modified_query, "useragent") || strpos($modified_query, "user-agent") || strpos($modified_query, "ua")) {
                return 2;
            }
        }
        // Code by Avitld
        if (strpos($modified_query, "base64") !== false) {
            if (strpos($modified_query, "how") === false) {
                if (strpos($modified_query, "encode") !== false) {
                    return 3;
                } elseif (strpos($modified_query, "decode") !== false) {
                    return 4;
                }
            }
        }

        if (strpos($modified_query, "weather") !== false || strpos($modified_query, "meteo") !== false) {
            return 5;
        }
    }

    function cleanUrl($url) {
        global $config;

        if ($config['cleanURLs'] == 'enabled') {
            $api = "https://api.cleanlinks.ai/v1/urlclean";

            $data = array(
                'url' => $url
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            $response = curl_exec($ch);

            if ($response !== false) {
                $cleanUrl = json_decode($response)->cleaned_url;
                return $cleanUrl;
            } else {
                return $url;
            }
        } else {
            return $url;
        }
    }

    function isDomainBlacklisted($url) {
        global $config;

        if ($config['blockHarmfulUrls'] == 'enabled') {
            $api = 'http://checkurl.phishtank.com/checkurl/';

            $data = array(
                'url' => $url,
                'format' => 'json',
            );

            $ch = curl_init($api);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($response, true);

            if ($responseData && isset($responseData['results']['valid']) && $responseData['results']['valid']) {
                return true;
            } else {
                return false;
            }
        }
    }

    function showLogo() {
		$theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';
		$lightThemes = array(
            "light",
            "candy"
        );
        if (in_array($theme, $lightThemes)) {
			echo '/static/img/logo_light.png';
		} else {
			echo '/static/img/logo_dark.png';
		}
    }

    function bangSearch($query) {
        $isBang = substr($query, 0, 1) === "!";
        $bangs = readJson('static/json/bangs.json');
        if ($isBang) {
            $searchFor = substr(explode(" ", $query)[0], 1);
            $bangURL = null;

            foreach ($bangs as $bang) {
                if ($bang["t"] == $searchFor) {
                    $bangURL = $bang["u"];
                }
            }

            if ($bangURL) {
                $bangQueryArray = explode("!" . $searchFor, $query);
                $bangQuery = trim(implode("", $bangQueryArray));

                $redirect = str_replace("{{{s}}}", $bangQuery, $bangURL);
                $redirect = checkFrontends($redirect);

                header("Location: $redirect");
                die();
            }
        }
    }

    function checkFrontends($url) {
        global $config;

        if (isset($_COOKIE['enableFrontends']) && $_COOKIE['enableFrontends'] == 'disabled') {
            return $url;
        }

        if ($config['frontendsEnabled'] == 'enabled') {
            foreach ($config['frontends'] as $frontendName => $frontend) {
                if (strpos($url, $frontend['origin']) !== false) {
                    $url = replaceWithFrontend($url, $frontendName, $frontend['origin']);
                    break;
                }
            }
        }
        return $url;
    }

    function replaceWithFrontend($url, $frontend, $origin) {
        global $config;

        $frontends = $config['frontends'];

        if (isset($_COOKIE[$frontend]) || $config['frontendsEnabled'] == 'enabled') {
            if (isset($_COOKIE[$frontend]) && !empty(trim($_COOKIE[$frontend]))) {
                $frontend = $_COOKIE[$frontend];
            } else if (!empty($frontends[$frontend]['link'])) {
                $frontend = $frontends[$frontend]['link'];
            } else {
                return $url;
            }

            if (strpos($url, 'wikipedia.org') !== false) {
                $split = explode('.', $url);

                if (count($split) > 1) {
                    $lang = explode('://', $split[0])[1];
                    $url = $frontend . explode($origin, $url)[1] . (strpos($url, '?') !== false ? '&' : '?') . "lang=$lang";
                }
            } else if (strpos($url, 'gist.github.com') !== false) {
                $gist = explode('gist.github.com', $url)[1];
                $url = "$frontend/gist$gist";
            } else if (strpos($url, 'fandom.com') !== false) {
                $split = explode('.', $url);
                if (count($split) > 1) {
                    $wiki = explode('://', $split[0])[1];
                    $url = "$frontend/$wiki" . explode($origin, $url)[1];
                }
            } else if (strpos($url, 'stackexchange.com') !== false) {
                $stackDomain = explode(".", explode("://", $url)[1])[0];
                $stackPath = explode('stackexchange.com', $url)[1];
                $url = "$frontend/exchange/$stackDomain/$stackPath";
            } else {
                $url = $frontend . explode($origin, $url)[1];
            }
            return trim($url);
        } else {
            return $url;
        }
    }

    function frontendSettings() {
        global $config;

        foreach ($config['frontends'] as $frontendTitle => $frontend) {
            $description = $frontend['desc'];
            $url = $frontend['link'];
            $title = ucfirst($frontendTitle);
            $title = $title == "Piped" ? "Piped / Invidious" : $title;
            echo "<div class=\"frontend-holder\">";
            echo "<label for=\"$frontendTitle\">$title Instance: </label>";
            echo "<input type=\"url\" placeholder=\"$description\" value=\"$url\" id=\"$frontendTitle\" name=\"$frontendTitle\"/>";
            echo "</div>";
        }
    }
?>