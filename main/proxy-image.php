<?php
	$url = $_REQUEST["url"];
	$alt = $_REQUEST["alt"];
	
	if (strpos($url, 'localhost') === false && strpos($url, '127.0.0.1') === false) {
		
		if (!function_exists('cleanUrl')) {
			require 'other/functions.php';
			$config = readJson('config.json');
		}

		$url = cleanUrl($url);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$response = curl_exec($ch);
		
		$contentType = getContent($url);
		

		$validTypes = array(
			'image/jpeg',
			'image/png',
			'image/apng',
			'image/x-icon',
			'image/webp',
			'image/svg+xml',
			'image/gif',
			'image/bmp'
		);

		if (in_array($contentType, $validTypes)) {
			header("Content-Type: $contentType");
			header("Content-Disposition: attachment; filename=\"$alt\"");
			if (startsWith($url, 'https://') || startsWith($url, 'http://')) {
				echo $response;
			}
		}
	}

	function startsWith($base, $word) {
		return substr($base, 0, strlen($word)) === $word;
	}

	function getContent($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_exec($ch);
		$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);

		return $contentType;
	} 
?>
