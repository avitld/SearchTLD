<?php
	function getbHTML($query, $page) {
		$page = intval($page) - 2;

		$url = "https://search.brave.com/search?q=" . urlencode($query) . "&offset=$page";

		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&language=$lang";
		} else {
			$url .= "&language=en";
		}

		if (isset($_COOKIE["safesearch"])) {
			$url .= "&safe=strict";
		}

        $url .= "&source=web"; // Measure to prevent being blocked
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$response = curl_exec($ch);

		sleep(5);

		return $response;
	}

	function send_text_th_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

            $results = $xpath->query('//div[contains(@class, "snippet")]');
            $uniqueLinks = [];
            
			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//span[contains(@class, "snippet-title")]', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$link = $xpath->query('.//a[contains(@class, "result-header")]', $result)->item(0);
					if ($link) { // Required for some reason... again...
						@$link = $link->getAttribute("href");
					}
					$description = $xpath->query('.//p[@class="snippet-description"]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
	
					if (!in_array($link, $uniqueLinks)) {
							echo "<div class=\"a-result\">";
							echo "	<a href=\"$link\" class=\"title\">$title</a><br>";
							echo "  <a href=\"$link\" class=\"mlink\">$link</a>";
							echo "  <p class=\"description\">$description</a>";
							echo "</div>";
	
							$uniqueLinks[] = $link;
					}
				}
			} else {
				echo "<p class=\"dym\">No results found.</p>";
			}
		}
	}
?>
