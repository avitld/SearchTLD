<?php
    function quetre($query) {
        $url = "https://quetre.iket.me/api/v1/?q=" . urlencode($query);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        quetreResponse($data);
    }

    function quetreResponse($data) {
        if ($data['status'] == 'success') {
            foreach ($data['results'] as $result) {
                if ($result['type'] == 'question' && $result['isDeleted'] == false) {
                    $title = htmlspecialchars($result['text'][0]['spans'][0]['text'], ENT_QUOTES, 'UTF-8');
                    $url = "https://quora.com" . $result['url'];
                    $desc = "<p>Comments: " . $result['numComments'] . "<br/>Followers: " . $result['numFollowers'];
                    echo "<div class=\"text-result\">";
                    echo "  <span><a href=\"$url\">$url</a></span><br/>";
                    echo "	<a href=\"$url\">";
                    echo "  	<span><img src=\"/static/img/quora.png\" class=\"flogo\" />Quora</span>";
                    echo "		<h2>$title</h2>";
                    echo "	</a>";
                    echo "  <p>$desc</p>";
                    echo "</div>";
                }
            }
        }
    }


?>