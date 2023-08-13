<?php
	function googleNews($query, $page) {
		global $config;

		$fpage = $page . "0";
		
		$url = "https://www.google.com/search?q=" . urlencode($query) . "&start=" . urlencode($fpage) . "&num=12" . "&tbm=nws";

		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&hl=" . urlencode($lang) . "&lr=" . urlencode($lang);
		} else {
			$url .= "&hl=en&lr=en";
		}

		if ($_COOKIE['safesearch'] == 'on') {
			$url .= "&safe=medium";
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
		googleNewsResponse($response);
	}

	function googleNewsResponse($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query('//a[contains(@class, "WlydOe")]');
			$uniqueLinks = [];

			foreach ($results as $result) {
				$title = $xpath->query('.//div[contains(@class, "n0jPhd")]', $result)->item(0);
				@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
				$link = $xpath->query('.//div[contains(@class, "MgUUmf")]//span', $result)->item(0);
				@$link = $link->textContent;
				$description = $xpath->query('.//div[contains(@class, "GI74Re")]', $result)->item(0);
				@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
				@$href = $result->getAttribute("href");

				$link = cleanUrl($link);

				if (!in_array($href, $uniqueLinks)) {
						echo "<div class=\"text-result\">";
						echo "	<a href=\"$href\">";
						echo "  	<span>$link</span>";
						echo "		<h2>$title</h2>";
						echo "	</a>";
						echo "  <p>$description</p>";
						echo "	<span id=\"engine\">Google</span>";
						echo "	<span id=\"cached\"><a href=\"https://web.archive.org/web/$link\">Archive</a></span>";
						echo "</div>";

		        		$uniqueLinks[] = $link;
				}
			}
		}
	}
?>
