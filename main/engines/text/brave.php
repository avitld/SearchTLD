<?php
	function braveText($query, $page) {
		global $config;
			
		if (intval($page) > 5) {
			$page = intval($page) - 5;
		}

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
		if ($config['proxyIP'] !== 'disabled') {
			$port = $config['proxyPort'];
			$ip = $config['proxyIP'];
			if ($config['proxyLogIn'] !== "user:password") {
				$userpass = $config['proxyLogIn'];
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $userpass);
			}

			curl_setopt($ch, CURLOPT_PROXY, $ip);
			curl_setopt($ch, CURLOPT_PROXYPORT, $config);
		}
		
		$response = curl_exec($ch);

		braveTextResponse($response);
	}

	function braveTextResponse($response) {
		global $config;

		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

            $results = $xpath->query('//div[contains(@class, "snippet")]');
            $uniqueLinks = [];
			$resultNum = 0;
            
			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//span[contains(@class, "snippet-title")]', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$link = $xpath->query('.//a[contains(@class, "result-header")]', $result)->item(0);
					if ($link) { // Required for some reason... again...
						@$link = $link->getAttribute("href");
						$link = cleanUrl($link);
					}
					$description = $xpath->query('.//p[@class="snippet-description"]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
					
					$blacklist = isDomainBlacklisted($link);

					if ($config["debugMode"] == "enabled") {
						echo $link;
						echo $title;
					}

					if ($title && !in_array($link, $uniqueLinks) && $blacklist === false) {
							echo "<div class=\"text-result\">";
							echo " <a href=\"$link\">";
							echo "  	<span>$link</span>";
							echo "		<h2>$title</h2>";
							echo "	</a>";
							echo "  <p>$description</p>";
							echo "  <span id=\"engine\">Brave</span>";
							echo "</div>";
	
							$uniqueLinks[] = $link;
							$resultNum++;
					}
				}

				if ($resultNum == 0) {
					if ($config["debugMode"] == "enabled") {
						echo $resultNum;
						echo $uniqueLinks;
					}
					echo "<p class=\"noResults\" id=\"noResults\">No results found, try a different query.</p>";
				}
			}
		}
	}
?>
