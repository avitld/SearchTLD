<?php
	function ddgText($query, $page) {
		global $config;

		if (intval($page) > 1) {
			$page = intval($page) - 1;
		}

		if (isset($_COOKIE['region'])) {
			$region = $_COOKIE['region'];
		} else {
			$region = "us";
		}

		$url = "https://html.duckduckgo.com/html/?q=" . urlencode($query) . "&s=" . $page;

		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));

			$trimlang = $region . "-" . $lang;
			$trimlangrev = $lang . "_" . strtoupper($region);

			$url .= "&lr=lang_$lang&hl=$lang&kl=$trimlang";
		} else {

			$trimlang = "us" . "-" . "en";
			$trimlangrev = "en" . "_" . "US";

			$url .= "&lr=lang_en&sl=en&hl=en&kl=us-en";
		}

		if ($_COOKIE['safesearch'] == 'on') {
			$url .= "&kp=-2";
		}
		
		$cookies = "l=$trimlang;ad=$trimlangrev";
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
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
		ddgTextResponse($response);
	}

	function ddgTextResponse($response) {
		global $config;

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

					if ($config["debugMode"] == "enabled") {
						echo $link;
						echo $title;
					}

					if (strlen($description) < 1) {
						$description = "No description provided.";
					} else if (strlen($description) > 110) {
						$description = substr($description, 0, 57) . '...';
					}

					if ($_COOKIE['enableFrontends'] !== 'disabled' && $config['frontendsEnabled'] == 'enabled') {
						$link = checkFrontends($link);
					}

					if (!in_array($link, $uniqueLinks) && $title && $link && $blacklist === false) {
							echo "<div class=\"text-result\">";
							echo "	<a href=\"$link\">";

							$oglink = urldecode($link);
							$oglink = str_replace('https://', '', $oglink);
							$oglink = htmlspecialchars($oglink);
							$oglink = str_replace('/', ' › ', $oglink);
	
							$segments = explode(' › ', $link);
							if (count($segments) > 2) {
								$oglink = $segments[0] . ' › ' . $segments[1];
							}

							if (strlen($oglink) >= 50) {
								$oglink = substr($oglink, 0, 47) . '...';
							}

							echo "  	<span>$oglink</span>";
							echo "		<h2>$title</h2>";
							echo "	</a>";
							echo "  <p>$description</p>";
							echo "  <span id=\"engine\">DuckDuckGo</span>";
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

	function ddgTextJSON($query, $page, $lang, $region) {
		global $config;

		$res = array();
		
		$url = "https://html.duckduckgo.com/html/?q=" . urlencode($query) . "&s=" . $page;

		$trimlang = $region . "-" . $lang;
		$trimlangrev = $lang . "_" . strtoupper($region);

		$url .= "&lr=lang_$lang&hl=$lang&kl=$trimlang";

		$cookies = "l=$trimlang;ad=$trimlangrev";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
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
					
					if (strlen($description) < 1) {
						$description = "No description provided.";
					} else if (strlen($description) > 110) {
						$description = substr($description, 0, 57) . '...';
					}

					$blacklist = isDomainBlacklisted($link);

					if (!in_array($link, $uniqueLinks) && $title && $link && $blacklist === false) {
						array_push($res, array(
							"title" => trim($title),
							"link" => $link,
							"description" => $description
						));

						$uniqueLinks[] = $link;
						$resultNum++;
					}
				}		

				if ($resultNum == 0) {
					$result = "Failed";
					return $result;
				}

				return json_encode($res);
			} else {
				$result = "Failed";
				return $result;
			}
		} else {
			$result = "Failed";
			return $result;
		}
	}
?>
