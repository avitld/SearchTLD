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
        if ($result && isset($result['matches'])) {
            $replacements = [];

            foreach ($result['matches'] as $match) {
                $suggestedCorrection = $match['replacements'][0]['value'];

                $offset = $match['offset'];
                $length = $match['length'];
                $misspelledWord = substr($query, $offset, $length);
                $replacement = $match['replacements'][0]['value'];
                $replacements[$misspelledWord] = $replacement; 
            }

            $words = explode(" ", $query);
            $correctedQuery = [];
            foreach ($words as $word) {
                $correctedWord = $replacements[$word] ?? $word;
                $correctedQuery[] = $correctedWord;
            }

            $updatedSentence = implode(" ", $correctedQuery);

            if ($updatedSentence !== $query) {
                echo "<div class=\"dym\"><small>Did you mean: <strong><a href=\"/search.php?q=" . urlencode($updatedSentence) . "&pg=0&tp=0\">$updatedSentence</a></strong></small></div>";
            }
        }
    }
?>
