<?php
	$url = htmlspecialchars($_REQUEST["url"],ENT_QUOTES,'UTF-8');
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$thumbnail = curl_exec($ch);
	header("Content-Type: image/png");
	if (startsWith($url, 'https://') || startsWith($url, 'http://')) {
		echo $thumbnail;
	}

	function startsWith($base, $word) {
    	return substr($base, 0, strlen($word)) === $word;
	}
?>
