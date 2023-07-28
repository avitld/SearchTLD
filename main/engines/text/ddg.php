<?php
	function getdHTML($query, $page) {
		if (intval($page) > 1) {
			$page = intval($page) - 1;
		}

		$url = "https://html.duckduckgo.com/html/?q=" . $query . "&s=" . $page;

		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&lr=lang_$lang&hl=$lang";
		} else {
			$url .= "&lr=lang_en&sl=en&hl=en";
		}

		if (isset($_COOKIE["safesearch"])) {
			$url .= "&kp=-2";
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

	function send_text_sec_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

            $results = $xpath->query('//div[contains(@class, "result")]');
            $uniqueLinks = [];
			$resultNum = 0;
            
			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->evaluate('.//h2[contains(@class, "result__title")]', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$link = $xpath->evaluate('.//a[contains(@class, "result__url")]', $result)->item(0);
					if ($link) { // Required for some reason..?
						@$link = $link->textContent;
						$link = "https://" . trim($link);
						$link = cleanUrl($link);
					}
					$description = $xpath->evaluate('.//a[@class="result__snippet"]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
					
					$blacklist = isDomainBlacklisted($link);

					if (!in_array($link, $uniqueLinks) && $title !== null && $blacklist === false) {
							echo "<div class=\"a-result\">";
							echo "	<a href=\"$link\">";
							echo "  	<span>$link</span>";
							echo "		<h2>$title</h2>";
							echo "	</a>";
							echo "  <p>$description</p>";
							echo "</div>";
	
							$uniqueLinks[] = $link;
							$resultNum++;
					}
				}

				if ($resultNum == 0) {
					echo "<p class=\"nores\">No results found, try a different query.</p>";
				}
			} else {
				echo "<p class=\"nores\">No results found, try a different query.</p>";
			}
		}
	}

	function ddgdown($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);
	
			$results = $xpath->query('//div[contains(@class, "result")]');
	
			if (!$results->length > 0) {
				return false;
			}

			foreach ($results as $result) {
				$title = $xpath->evaluate('.//h2[contains(@class, "result__title")]', $result)->item(0);
				@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');

				if ($title->length > 1) {
					return true;
				} else {
					return false;
				}

				break;
			}

		} else {
			return false;
		}
	}
?>
