<?php
	function getHTML($query, $page) {
		$fpage = $page . "0";
		
		$url = "https://www.google.com/search?q=" . urlencode($query) . "&start=" . urlencode($fpage) . "&num=12";

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

	function send_text_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query('//div[contains(@class, "g")]');
			$uniqueLinks = [];

			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//h3', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$linkel = $xpath->query('.//div[contains(@class, "yuRUbf")]', $result)->item(0);
					$link = $xpath->query('.//a', $linkel)->item(0);
					@$link = $link->getAttribute("href");
					$description = $xpath->query('.//div[contains(@class, "VwiC3b")]', $result)->item(0);
					@$description = htmlspecialchars($description->textContent,ENT_QUOTES,'UTF-8');
	
					if (!preg_match('/^\/search\?q=/', $link) && !in_array($link, $uniqueLinks)) {
							echo "<div class=\"a-result\">";
							echo "	<a href=\"$link\" class=\"title\">$title</a><br>";
							echo "  <a href=\"$link\" class=\"mlink\">$link</a>";
							echo "  <p class=\"description\">$description</a>";
							echo "</div>";
	
							$uniqueLinks[] = $link;
					}
				}
			} else {
				echo "<p class=\"dym\">No results found.</p>";
			}
		}
	}

	function check_for_fallback($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);
	
			$results = $xpath->query('//div[contains(@class, "g")]');
	
			if ($results->length > 0) {
				$gotresponse = true;
				return $gotresponse;
			} else {
				$gotresponse = false;
				return $gotresponse;
			}
		} else {
			$gotresponse = false;
			return $gotresponse;
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
				$desc = $xpath->query('.//div[contains(@class, "kno-rdesc")]//span', $result->item(0))->item(0);
				@$desc = $desc->textContent;

				if (strpos($title, $desc) === false) {
					$nextH2 = $title->nextSibling;
					while ($nextH2 !== null && $nextH2->nodeName !== "h2") {
						$nextH2 = $nextH2->nextSibling;
					}
					
					if ($nextH2 !== null) {
						$title = $nextH2->textContent;
					} else {
						$spanTitle = $xpath->query('.//span[contains(@class, "yKMVIe")]', $result->item(0))->item(0);
						if ($spanTitle !== null) {
							$title = $spanTitle->textContent;
						}
					}
				}

				if ($title !== null && $desc !== null) {	
					$smalltitle = $xpath->query('.//div[contains(@class, "wwUB2c")]//span', $result->item(0))->item(0);
					@$smalltitle = $smalltitle->textContent;
					
					if ($desc !== Null) {	
						echo "<div class=\"infobox\">";
						echo "<div class=\"txtholder\">";
						echo "<h2 class=\"infotitle\">" . $title . "</h2>";
						echo "<span class=\"minititle\">" . $smalltitle . "</span>";
						echo "</div>";
						if ($title !== "GNU") {
							require "search/images/qwant.php";
							$iresponse = getiHTML($title, 0);
							send_single_image_response($iresponse);
						} else {
							echo "<div class=\"simage-container\">";
							echo "	<img src=\"/static/img/gnu.png\">";
							echo "</div>";
						}
						echo "<hr>";
						echo "<p class=\"infodesc\">" . $desc . "</p>";
						echo "</div>";
					}
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
				$span = $xpath->query('//span[contains(@class, "gL9Hy")]', $result->item(0))->item(0);
				@$span = $span->textContent;
				$corr = $xpath->query('.//i', $result->item(0))->item(0);
				@$corr = $corr->textContent;
				
				if ($span !== NULL && $corr !== NULL) {
					echo "<div class=\"correction-div\">";
					echo "<p class=\"dym\">" . $span;
					echo "<a class=\"correction\" href=\"/search.php?q=$corr&pg=0&tp=0\"> $corr</span>";
					echo "</p>";
					echo "</div>";
				}
			}
		}
	}

?>
