<?php
	function getnHTML($query, $page) {
		$fpage = $page . "0";
		
		$url = "https://www.google.com/search?q=" . urlencode($query) . "&start=" . urlencode($fpage) . "&num=12" . "&tbm=nws";

		if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
			$url .= "&hl=" . urlencode($lang) . "&lr=" . urlencode($lang);
		} else {
			$url .= "&hl=en&lr=en";
		}

		if (isset($_COOKIE["safesearch"])) {
			$url .= "&safe=medium";
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

	function send_news_response($response) {
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

				if (!in_array($href, $uniqueLinks)) {
		        		echo "<div class=\"a-result\">";
		        		echo "	<a href=\"$href\" class=\"title\">$title</a>";
		        		echo "  <p class=\"mlink\" style=\"margin-top: 0; padding-top: 0;\">$link</a>";
		        		echo "  <p class=\"description\">$description</a>";
		        		echo "</div>";

		        		$uniqueLinks[] = $link;
				}
			}
		}
	}

	function send_title($response) {
		if ($response) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$result = $xpath->query('//span[contains(@class, "mgAbYb")]');
			if ($result) {
				$span = $xpath->query('.//span[contains(@class, "QXROIe")]', $result->item(0))->item(0);
				@$span = $span->textContent;
				echo "<p class=\"dym\" style=\"margin-left: 15px;\">" . $span . ": </p>";
			}
		}
	}

?>
