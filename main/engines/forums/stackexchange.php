<?php
	function stackEx($query) {
		global $config;

		$url = "https://stackexchange.com/search?q=" . urlencode($query);

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
		
		stackExResponse($response);
	}

	function stackExResponse($response) {
		global $config;

		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

            $results = $xpath->query('//div[contains(@class, "summary")]');
            $uniqueLinks = [];
            $num = 0;
			if ($results) {
				foreach ($results as $result) {
					$link = $xpath->query('.//div[contains(@class, "result-link")]//span//a', $result)->item(0);
					@$title = htmlspecialchars($link->textContent,ENT_QUOTES,'UTF-8');
                    if ($link) { // Required for some reason..?
						@$link = $link->getAttribute('href');
						$link = cleanUrl($link);
					}
                    $date = $xpath->query('.//span[contains(@class, "relativetime")]', $result)->item(0);
                    @$date = htmlspecialchars($date->textContent);
                    $desc = $xpath->query('.//div[contains(@class, "excerpt")]', $result)->item(0);
                    @$desc = htmlspecialchars($desc->textContent);

					if ($_COOKIE['enableFrontends'] !== 'disabled' && $config['frontendsEnabled'] == 'enabled') {
						$link = checkFrontends($link);
					}

                    if (!in_array($link, $uniqueLinks) && !($num >= 3)) {
					    echo "<div class=\"text-result\">";
                        echo "  <a href=\"$link\" class=\"mlink\"><img src=\"/static/img/stackex.png\" class=\"flogo\" />StackExchange</a><br/>";
				    	echo "	<a href=\"$link\" class=\"title\">$title</a><br>";
                        echo "  <p class=\"mlink\">$date</p>";
                        echo "  <p class=\"description\">$desc</p>";
				    	echo "</div>";
                        $uniqueLinks[] = $link;
                        $num++;
                    }
				}
			} else {
				echo "<p class=\"correction\">No results found.</p>";
			}
		}
	}
?>
