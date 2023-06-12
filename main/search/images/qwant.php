<?php
	function getiHTML($query, $page) {
		$fpage = $page / 10 + 1;
		
		$ch = curl_init();

		$url = "https://lite.qwant.com/?q=" . urlencode($query) . "&p=" . urlencode($fpage) . "&t=images";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($ch);

		curl_close($ch);
		return $response;
	}

	function send_image_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query("//a[@rel='noopener']");
			echo "<div class=\"image-container\">";
			foreach ($results as $result) {
				$image = $xpath->evaluate(".//img", $result)[0];
				
				if ($image) {
					// Method from LibreX
					$encoded_url = $result->getAttribute("href");
					$encoded_url_sp1 = explode("==/", $encoded_url)[1];
					$encoded_url_sp2 = explode("?position", $encoded_url_sp1)[0];
					$url = urldecode(base64_decode($encoded_url_sp2));
					@$url = htmlspecialchars($url,ENT_QUOTES,'UTF-8');
					
					$alt = $image->getAttribute("alt");
					@$alt = htmlspecialchars($alt,ENT_QUOTES,'UTF-8');
					$thumbnail_src = urlencode($image->getAttribute("src"));
					@$thumbnail_src = urldecode(htmlspecialchars($thumbnail_src));
					@$thumbnail_src = urlencode($thumbnail_src);
					
					echo "<div class=\"i-result\">";
					echo "	<a title=\"$alt\" href=\"$url\" target=\"_blank\">";
					echo "		<img src=\"proxy-image.php?url=$thumbnail_src\">";
					echo "	</a>";
					echo "</div>";
					
				}
			}
			echo "</div>";
		}
	}

	function send_single_image_response($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query("//a[@rel='noopener']");
			$counter = 0;

			foreach ($results as $result) {
				$image = $xpath->evaluate(".//img", $result)[0];
			
				if ($image) {
					$thumbnail_src = urlencode($image->getAttribute("src"));
					@$thumbnail_src = urldecode(htmlspecialchars($thumbnail_src));
					@$thumbnail_src = urlencode($thumbnail_src);
			
					echo "<div class=\"simage-container\">";
					echo "		<img src=\"proxy-image.php?url=$thumbnail_src\">";
					echo "</div>";
			
					$counter++; // Increment the counter
			
					if ($counter >= 1) {
						break; // Break the loop after the first iteration
					}
				}
			}
			
		}
	}

?>
