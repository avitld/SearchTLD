<?php
	function getHTML($query, $page) {
		$fpage = $page . "0";
		
		$lang = trim(htmlspecialchars($_COOKIE["lang"]));

		$url = "https://www.google.com/search?q=" . urlencode($query) . "&start=" . urlencode($fpage) . "&hl=" . urlencode($lang) . "&lr" . urlencode($lang) . "&num=12";

		if (isset($_COOKIE["disable_safe"])) {
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

	function send_text_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query('//div[contains(@class, "g")]');
			$uniqueLinks = [];

			foreach ($results as $result) {
				$title = $xpath->query('.//h3', $result)->item(0);
				@$title = $title->textContent;
				$linkel = $xpath->query('.//div[contains(@class, "yuRUbf")]', $result)->item(0);
				$link = $xpath->query('.//a', $linkel)->item(0);
				@$link = $link->getAttribute("href");
				$description = $xpath->query('.//div[contains(@class, "VwiC3b")]', $result)->item(0);
				@$description = $description->textContent;

				if (!preg_match('/^\/search\?q=/', $link) && !in_array($link, $uniqueLinks)) {
		        		echo "<div class=\"a-result\">";
		        		echo "	<a href=\"$link\" class=\"title\">$title</a><br>";
		        		echo "  <a href=\"$link\" class=\"mlink\">$link</a>";
		        		echo "  <p class=\"description\">$description</a>";
		        		echo "</div>";

		        		$uniqueLinks[] = $link;
				}
			}
		}
	}

	function send_infobox($response) {
		if ($response) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$result = $xpath->query('//div[contains(@class, "I6TXqe")]');
			if ($result) {
				$title = $xpath->query('.//h2', $result->item(0))->item(0);
				@$title = $title->textContent;
				$smalltitle = $xpath->query('.//div[contains(@class, "wwUB2c")]//span', $result->item(0))->item(0);
				@$smalltitle = $smalltitle->textContent;
				$desc = $xpath->query('.//div[contains(@class, "kno-rdesc")]//span', $result->item(0))->item(0);
				@$desc = $desc->textContent;
				
				if ($title !== "Complementary results" && $title !== "Local results" && $title !== "Web result with site links" && $title !== Null) {			
					echo "<div class=\"infobox\">";
					echo "<h2 class=\"infotitle\">" . $title . "</h2>";
					echo "<span class=\"minititle\">" . $smalltitle . "</span>";
					echo "<hr>";
					echo "<p class=\"infodesc\">" . $desc . "</p>";
					echo "</div>";
				}
			}
		}
	}

	function send_correction($response) {
		if ($response) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$result = $xpath->query('//p[contains(@class, "gqLncc")]');
			if ($result) {
				@$span = $result->textContent;
				$corr = $xpath->query('.//i', $result->item(0))->item(0);
				@$corr = $corr->textContent;
				
				echo "<div class=\"correction-div\">";
				echo "<p class=\"dym\">" . $span;
				echo "<a class=\"correction\" href=\"/search?q=\" . $corr . \"&pg=0&tp=0\">" . $corr . "</span>";
				echo "</p>";
				echo "<hr>";
				echo "</div>";
			}
		}
	}

?>
