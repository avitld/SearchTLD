<?php

    function getGrammar($query) {
        $url = "https://api.languagetoolplus.com/v2/check";

        if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
		} else {
			$lang = "en-US";
		}

        $data = [
            "text" => ucfirst($query),
            "language" => "en-US",
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
    
    function echoCorrection($response, $query) {
        $query = ucfirst($query);
        $result = json_decode($response, true);
        $match = $result['matches'][0];
        if ($result && isset($result['matches'])) {
            if (isset($match['replacements'][0]['value'])) {
                $suggestedCorrection = $match['replacements'][0]['value'];

                $offset = $match['offset'];
                $length = $match['length'];
                $misspelledWord = mb_substr($query, $offset, $length);

                $updatedSentence = str_replace($misspelledWord, $suggestedCorrection, $query);
                echo "<div class=\"dym\"><small>Did you mean: <strong><a href=\"/search.php?q=" . urlencode($updatedSentence) . "&pg=0&tp=0\">$updatedSentence</a></strong></small></div>";
            }
        }
    }
?>
