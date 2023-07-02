<?php
	function getHTML($query, $page) {
		$config = readJSON('/internalconfig.json');

		$fpage = $page . "0";
		
		$url = "https://www.google.com/search?q=" . urlencode($query) . "&start=" . urlencode($fpage) . "&num=12&filter=0&nfpr=1";

		if (isset($_COOKIE["lang"]) && !(strlen($config['hardlang']) < 2)) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&hl=" . urlencode($lang) . "&lr=" . urlencode($lang);
		} else if (strlen($config['hardlang'] == 2)) {
			$url .= "&hl=en&lr=en";
		}

		if (isset($_COOKIE["safesearch"])) {
			$url .= "&safe=medium";
		} else {
			$url .= "&safe=off";
		}
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$response = curl_exec($ch);

		curl_close($ch);
		return $response;
	}

	function send_text_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query('//div[contains(@class, "g")]');
			$uniqueLinks = [];

			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//h3', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$linkel = $xpath->query('.//div[contains(@class, "yuRUbf")]', $result)->item(0);
					$link = $xpath->query('.//a', $linkel)->item(0);
					@$link = $link->getAttribute("href");
					$description = $xpath->query('.//div[contains(@class, "VwiC3b")]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
	
					if (!preg_match('/^\/search\?q=/', $link) && !in_array($link, $uniqueLinks)) {
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

	function check_for_fallback($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);
	
			$results = $xpath->query('//div[contains(@class, "g")]');
	
			if (!$results->length > 0) {
				return false;
			}

			foreach ($results as $result) {
				$linkel = $xpath->query('.//div[contains(@class, "yuRUbf")]', $result)->item(0);
				$link = $xpath->query('.//a', $linkel)->item(0);
				@$link = $link->getAttribute("href");

				if ($link == "#") {
					return false;
				} else {
					return true;
				}

				break;
			}

		} else {
			return false;
		}
	}

	function send_correction($response) {
		if ($response) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$result = $xpath->query('//p[contains(@class, "gqLncc")]');
			if ($result) {
				$span = $xpath->query('//span[contains(@class, "gL9Hy")]', $result->item(0))->item(0);
				@$span = $span->textContent;
				$corr = $xpath->query('.//i', $result->item(0))->item(0);
				@$corr = $corr->textContent;
				
				if ($span !== NULL && $corr !== NULL) {
					echo "<div class=\"correction-div\">";
					echo "<p class=\"dym\">" . $span;
					echo "<a class=\"correction\" href=\"/search.php?q=$corr&pg=0&tp=0\"> $corr</span>";
					echo "</p>";
					echo "</div>";
				}
			}
		}
	}

?>
