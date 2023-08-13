<?php
	function spNews($query, $page) {
		global $config;
		
		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
		} else {
			$lang = "english";
		}

		$url = "https://www.startpage.com/sp/search?t=device&cat=news&language=$lang&lui=$lang&query=" . urlencode($query);

		if ($_COOKIE['safesearch'] !== 'on') {
			$url .= "&qadf=none";
		}

		$ch = curl_init($url);
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
		
		spNewsResponse($response);
	}

	function spNewsResponse($response) {
		global $config;

		if (!empty($response)) {
			if ($config['debugMode'] == 'enabled') {
				echo $response;
			}

			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query('//div[contains(@class, "article")]');
			$uniqueLinks = [];
			$resultNum = 0;

			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//a[contains(@role, "link")]', $result)->item(0);
					$link = $title ? $title->getAttribute("href") : null;
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$source = $xpath->query('.//span[contains(@class, "source")]', $result)->item(0);
					@$source = $source->textContent;
					$description = $xpath->query('.//div[contains(@class, "description")]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
					
					if (strlen($description) < 1) {
						$description = "No description provided.";
					} else if (strlen($description) > 110) {
						$description = substr($description, 0, 57) . '...';
					}

					if (strpos($title, "@media") !== false) {
						$title = preg_replace('/@media[^}]+}}/', '', $title);
					}

					$link = cleanUrl($link);
					$blacklist = isDomainBlacklisted($link);

					if ($_COOKIE['enableFrontends'] !== 'disabled' && $config['frontendsEnabled'] == 'enabled') {
						$link = checkFrontends($link);
					}

					if ($config["debugMode"] == "enabled") {
						echo $link;
						echo $title;
					}

					if (!in_array($link, $uniqueLinks) && $blacklist === false) {
							
							echo "<div class=\"text-result\">";
							echo "	<a href=\"$link\">";
							echo "  	<span>$source</span>";
							echo "		<h2>$title</h2>";
							echo "	</a>";
							echo "  <p>$description</p>";
							echo "<span id=\"engine\">Startpage</span>";
							echo "<span id=\"cached\"><a href=\"https://web.archive.org/web/$link\">Archive</a></span>";
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

	function spNewsJSON($query, $page, $lang) {
		global $config;
		
		$res = array();

		$url = "https://www.startpage.com/sp/search?t=device&cat=news&language=$lang&lui=$lang&query=" . urlencode($query);

		$ch = curl_init($url);
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

			$results = $xpath->query('//div[contains(@class, "article")]');
			$uniqueLinks = [];
			$resultNum = 0;

			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//a[contains(@role, "link")]', $result)->item(0);
					$link = $title ? $title->getAttribute("href") : null;
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$description = $xpath->query('.//div[contains(@class, "description")]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
					
					if (strlen($description) < 1) {
						$description = "No description provided.";
					} else if (strlen($description) > 110) {
						$description = substr($description, 0, 57) . '...';
					}

					if (strpos($title, "@media") !== false) {
						$title = preg_replace('/@media[^}]+}}/', '', $title);
					}

					$link = cleanUrl($link);
					$blacklist = isDomainBlacklisted($link);

					if (!in_array($link, $uniqueLinks) && $blacklist === false) {
						array_push($res, array(
							"title" => $title,
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
