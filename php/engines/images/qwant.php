<?php
	function qwantImage($query, $page) {
		global $config;

		$fpage = $page / 10 + 1;
		
		$ch = curl_init();

		$url = "https://lite.qwant.com/?q=" . urlencode($query) . "&p=" . urlencode($fpage) . "&t=images";

		curl_setopt($ch, CURLOPT_URL, $url);
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
		
		qwantImageResponse($response);
	}

	function qwantImageResponse($response) {
		if (!empty($response)) {
			$dom = new DOMDocument();
			@$dom->loadHTML($response);
			$xpath = new DOMXPath($dom);

			$results = $xpath->query("//a[@rel='noopener']");
			echo "<div class=\"image-container\" id=\"imageContainer\">";
			foreach ($results as $result) {
				$image = $xpath->evaluate(".//img", $result)[0];
				
				if ($image) {
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
					
					echo "<div class=\"image-result\">";
					echo "	<a title=\"$alt\" alt=\"$url\" target=\"_blank\">";
					echo "		<img src=\"proxy-image.php?url=$thumbnail_src&alt=$alt\" alt=\"$alt\">";
					echo "	</a>";
					echo "</div>";
					
				}
			}
			echo "</div>";
		}
	}

?>
