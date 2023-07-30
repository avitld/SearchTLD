<?php
	function getbgHTML($query, $page) {
		global $config;
		
		$fpage = $page . "0";

		$url = "https://www.bing.com/search?q=" . urlencode($query) . "&form=QBLH&sp=-1&lq=0&pq=&sc=10-4&qs=n&sk=&cvid=C6422AEC94DF49E590A95EDAA8E46FB4&ghsh=0&ghacc=0&ghpl=&first=$page";
		
		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&mkt=" . urlencode($lang) . "-" . urlencode($lang);
		} else {
			$url .= "&mkt=en-US";
		}

		if (isset($_COOKIE["safesearch"])) {
			$url .= "&adlt=strict";
		} else {
			$url .= "&adlt=off";
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
		return $response;
	}

	function send_text_bing_response($response) {
		global $config;

		if (!empty($response)) {
			if ($config['debugMode'] == 'enabled') {
				echo $response;
			}

			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query('//ol[@id="b_results"]//li[contains(@class, "b_algo")]');
			$uniqueLinks = [];
			$resultNum = 0;
			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('//h2//a', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$link = $xpath->query('.//div[contains(@class, "b_attribution")]//cite', $result)->item(0);
					@$link = $link->textContent;
					$description = $xpath->query('.//div[contains(@class, "b_caption")]//p', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
					
					$link = cleanUrl($link);
					$blacklist = isDomainBlacklisted($link);
					if ($config["debugMode"] == "enabled") {
						echo $link;
						echo $title;
					}
					if (str_contains($link, "...") && !in_array($link, $uniqueLinks) && $blacklist === false && $title) {
							echo "<div class=\"a-result\">";
							echo "	<a href=\"$link\">";
							echo "  	<span>$link</span>";
							echo "		<h2>$title</h2>";
							echo "	</a>";
							echo "  <p>$description</p>";
							echo "<span id=\"engine\">Bing</span>";
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
					echo "<p class=\"nores\" id=\"nores\">No results found, try a different query.</p>";
				}
			}
		}
	}

?>
