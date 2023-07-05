<?php
function send_infobox($query) {
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
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['query']['pages'])) {
        $pages = $data['query']['pages'];

        foreach ($pages as $page) {
            if (isset($page['pageid']) && $page['pageid'] > 0) {
                $title = $page['title'];

                if (isset($page['thumbnail'])) {
                    $thumbnailUrl = $page['thumbnail']['source'];
                }

                $description = substr($page['extract'], 0, 450);
                $description = rtrim($description, " .,;:-");
                if (strlen($page['extract']) > 450) {
                    $description .= "...";
                }

                echo "<div class=\"infobox\">";
                echo "<img src=\"$thumbnailUrl\">";
                echo "<hr>";
                echo "<p>" . $description . "</p>";
                echo "<a href=\"https://$lang.wikipedia.org/wiki/$query\">$title at https://$lang.wikipedia.org/wiki/$query</a>";
                echo "</div>";
            }
        }
    }
}
?>

