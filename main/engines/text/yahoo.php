<?php
	function yahooText($query, $page) {
		global $config;

		$url = "https://search.yahoo.com/search?p=" . urlencode($query) . "&ei=UTF-8&fp=1&save=0&b=8&pz=7&bct=0&pstart=$page";

		
		$cookies = "thamba=1;";
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_COOKIE, $cookies);
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
		yahooTextResponse($response);
	}

	function yahooTextResponse($response) {
		global $config;

		if (!empty($response)) {

			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

            $results = $xpath->query('//div[contains(@class, "dd")]');
            $uniqueLinks = [];
			$resultNum = 0;
            
			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->evaluate('.//h3[contains(@class, "title")]//a', $result)->item(0);
					$link = $xpath->evaluate('.//span[contains(@class, "d-ib")]', $title)->item(0)->textContent;
					$origlink = $link;
					$link = str_replace(['›', ' '], ['/', ''], $link);
					$link = "https://$link";
					$link = cleanUrl($link);
					$description = htmlspecialchars($xpath->evaluate('.//span[contains(@class, "fc-falcon")]', $result)->item(0)->textContent, ENT_QUOTES, 'UTF-8');
					$title = $title->textContent;
					$title = str_replace($origlink, '', $title);
					if ($config["debugMode"] == "enabled") {
						echo $link;
						echo $title;
					}

					$oglink = $link;

					if (strlen($description) < 1) {
						$description = "No description provided.";
					}

					if ($_COOKIE['enableFrontends'] !== 'disabled' && $config['frontendsEnabled'] == 'enabled') {
						$link = checkFrontends($link);
					}

					if (!in_array($link, $uniqueLinks) && $title && $link && $link !== "All") {
							echo "<div class=\"text-result\">";
							echo "	<a href=\"$link\">";

							$link = urldecode($oglink);
							$link = htmlspecialchars($link);
							$link = str_replace('https://', '', $link);
							$link = str_replace('/', ' › ', $link);
	
							$segments = explode(' › ', $link);
							if (count($segments) > 2) {
								$link = $segments[0] . ' › ' . $segments[1];
							}

							if (strlen($link) >= 50) {
								$link = substr($link, 0, 47) . '...';
							}

							echo "  	<span>$link</span>";
							echo "		<h2>$title</h2>";
							echo "	</a>";
							echo "  <p>$description</p>";
							echo "  <span id=\"engine\">Yahoo</span>";
							echo "	<span id=\"cached\"><a href=\"https://web.archive.org/web/$link\">Archive</a></span>";
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
