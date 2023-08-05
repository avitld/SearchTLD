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

                header("Location: $redirect");
                die();
            }
        }
    }
?>
