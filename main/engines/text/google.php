<?php
	function googleText($query, $page) {
		global $config;
		
		$fpage = $page . "0";
		
		if (isset($_COOKIE["tld"])) {
			$tld = $_COOKIE["tld"];
		} else {
			$tld = "com";
		}

		$url = "https://www.google.$tld/search?q=" . urlencode($query) . "&start=" . urlencode($fpage) . "&num=12&filter=0&nfpr=1";

		if ($config['googleAPI']['enabled'] == 'enabled') {
			$apikey = $config['googleAPI']['apiKey'];
			$url = "https://www.googleapis.com/customsearch/v1?key=$apikey&q=" . urlencode($query);
		}
		
		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&hl=" . urlencode($lang) . "&lr=" . urlencode($lang);
		} else {
			$url .= "&hl=en&lr=en";
		}

		if (isset($_COOKIE["safesearch"])) {
			$url .= "&safe=medium";
		} else {
			$url .= "&safe=off";
		}

		// Check if URL exists usinjg headers.

		$headers = @get_headers($url);

		if(strpos( $headers[0], '200') === false) {
			$url = str_replace($tld, "com", $url);
		}
		
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

		curl_close($ch);
		
		googleTextResponse($response);
	}

	function googleTextResponse($response) {
		global $config;

		if ($config['googleAPI']['enabled'] !== 'enabled') {
			if (!empty($response)) {

				if ($config['debugMode'] == 'enabled') {
					echo $response;
				}

				$dom = new DOMDocument();
				@$dom->loadHTML($response);
				$xpath = new DOMXPath($dom);

				$results = $xpath->query('//div[contains(@class, "g")]');
				$uniqueLinks = [];
				$resultNum = 0;

				if ($results) {
					$insTag = $xpath->query('//ins');
					foreach ($results as $result) {
						$title = $xpath->query('.//h3', $result)->item(0);
						@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
						$linkel = $xpath->query('.//div[contains(@class, "yuRUbf")]', $result)->item(0);
						$link = $xpath->query('.//a', $linkel)->item(0);
						@$link = $link->getAttribute("href");
						$description = $xpath->query('.//div[contains(@class, "VwiC3b")]', $result)->item(0);
						@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
						
						$link = cleanUrl($link);
						$blacklist = isDomainBlacklisted($link);

						if ($config["debugMode"] == "enabled") {
							echo $link;
							echo $title;
						}

						if (!preg_match('/^\/search\?q=/', $link) && !in_array($link, $uniqueLinks) && $blacklist === false) {
								echo "<div class=\"text-result\">";
								echo "	<a href=\"$link\">";
								echo "  	<span>$link</span>";
								echo "		<h2>$title</h2>";
								echo "	</a>";
								echo "  <p>$description</p>";
								echo "<span id=\"engine\">Google</span>";
								echo "</div>";
		
								$uniqueLinks[] = $link;
								$resultNum++;
						}
					}

					if ($resultNum == 0 && !$insTag) {
						if ($config["debugMode"] == "enabled") {
							echo $resultNum;
							echo $uniqueLinks;
						}
						echo "<p class=\"noResults\" id=\"noResults\">No results found, try a different query.</p>";
					}
				}
			}
		} else {
			$data = $response;
			if (isset($data['items'])) {
			    foreach ($data['items'] as $item) {
			        $title = htmlspecialchars($item['title']);
			        $link = $item['link'];
			        $description = htmlspecialchars($item['snippet']);
			
			        echo "<div class=\"text-result\">";
					echo "	<a href=\"$link\">";
					echo "  	<span>$link</span>";
					echo "		<h2>$title</h2>";
					echo "	</a>";
					echo "  <p>$description</p>";
					echo "</div>";
			    }
			} else {
			    echo "<p class=\"noResults\" id=\"noResults\">No results found, try a different query.</p>";
			}
		}
	}

?>
