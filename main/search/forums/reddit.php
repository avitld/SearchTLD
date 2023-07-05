<?php
	function getrHTML($query) {
		$url = "https://www.reddit.com/search/?q=" . urlencode($query);

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

	function send_red_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

            $results = $xpath->query('//div[contains(@class, "_2mO8vClBdPxiJ30y_C6od2")]//div//div');
            $uniqueLinks = [];
			$num = 0;
			if ($results) {
				foreach ($results as $result) {
					$title = $xpath->query('.//h3[contains(@class, "_eYtD2XCVieq6emjKBH3m")]', $result)->item(0);
					@$title = htmlspecialchars($title->textContent,ENT_QUOTES,'UTF-8');
					$link = $xpath->query('.//div[contains(@data-adclicklocation, "title")]//div//a', $result)->item(0);
					if ($link) { // Required for some reason..?
						@$link = $link->getAttribute('href');
						$link = "https://reddit.com$link";
					}

                    $sub = '';
                    $pattern = '/\/r\/([^\/]+)/';

                    if (preg_match($pattern, $link, $matches)) {
                        $sub = $matches[1];
                        $sub = "r/" . $sub;
                        $sublink = "https://reddit.com/" . $sub;
                    }

                    if ($title && strpos($link, "reddit.com") !== false && !in_array($link, $uniqueLinks) && !($num >= 7)) {
					    echo "<div class=\"a-result\">";
						echo "  <span><a href=\"$sublink\">$sub</a></span><br/>";
						echo "	<a href=\"$link\">";
                        echo "  	<span><img src=\"/static/img/reddit.png\" class=\"flogo\" />Reddit</span>";
				    	echo "		<h2>$title</h2>";
						echo "	</a>";
				    	echo "</div>";
                        $uniqueLinks[] = $link;
						$num++;
                    }
				}
			} else {
				echo "<p class=\"dym\">No results found.</p>";
			}
		}
	}
?>
