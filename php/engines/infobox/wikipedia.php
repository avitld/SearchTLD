<?php
function wikipediaInfo($query) {
    global $config;

    if (isset($_COOKIE["lang"])) {
        $lang = trim(htmlspecialchars($_COOKIE["lang"]));
        if (!preg_match('/^[a-z]{2}$/', $lang)) {
            $lang = "en";
        }
    } else {
        $lang = "en";
    }

    $url = "https://$lang.wikipedia.org/w/api.php?action=query&exintro&explaintext&format=json&pithumbsize=500&prop=extracts|pageimages&redirects=1&titles=" . urlencode($query);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (
        isset($data['query']['pages']) &&
        strtolower($query) !== "weather" &&
        strtolower($query) !== "meteo" &&
        count($data['query']['pages']) === 1 &&
        !isset($wikipediaData['query']['pages']['-1'])
        ) {
        $pages = $data['query']['pages'];

        foreach ($pages as $page) {
            if (isset($page['pageid']) && $page['pageid'] > 0) {
                $title = $page['title'];
                $link = "https://$lang.wikipedia.org/wiki/$query";

                if ($_COOKIE['enableFrontends'] !== 'disabled' && $config['frontendsEnabled'] == 'enabled') {
                    $link = checkFrontends($link);
                }

                if (isset($page['thumbnail'])) {
                    $thumbnailUrl = $page['thumbnail']['source'];
                } else {
                	$thumbnailUrl = false;
                }

                $description = substr($page['extract'], 0, 450);
                $description = rtrim($description, " .,;:-");
                if (strlen($page['extract']) > 450) {
                    $description .= "...";
                }

                if (!strpos($description, 'may refer to')) {
                    echo "<div class=\"infobox\">";
                    if ($thumbnailUrl) {
                        echo "<img src=\"$thumbnailUrl\">";
                        echo "<hr>";
                    }
                    echo "<p>" . $description . "</p>";
                    echo "<a href=\"$link\">$title at $link</a>";
                    echo "</div>";
                }
            }
        }
    }
}
?>

