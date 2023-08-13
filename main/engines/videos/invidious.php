<?php
    function invidious($query) {
        $url = "https://invidious.tiekoetter.com/api/v1/search?q=" . urlencode($query);
        
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$rawResponse = curl_exec($ch);

		curl_close($ch);

		invidiousVideoResponse($rawResponse);
    }

    function invidiousVideoResponse($rawResponse) {
		global $config;

        $response = json_decode($rawResponse, true);

		$url = "https://invidious.tiekoetter.com";

		if ($response) {
			foreach ($response as $vresponse) {
				if ($vresponse["type"] == "video") {
					$title = htmlspecialchars($vresponse["title"],ENT_QUOTES,'UTF-8');
					$vurl = htmlspecialchars("https://youtube.com/watch?v=" . $vresponse["videoId"]);
					if ($_COOKIE['enableFrontends'] !== 'disabled' && $config['frontendsEnabled'] == 'enabled') {
						$vurl = checkFrontends($vurl);
					}
					$uploader = htmlspecialchars($vresponse["author"],ENT_QUOTES,'UTF-8');
					$views = htmlspecialchars($vresponse["viewCount"],ENT_QUOTES,'UTF-8');
					$uploaded = htmlspecialchars($vresponse["publishedText"],ENT_QUOTES,'UTF-8');
					$thumbnail = htmlspecialchars($url . "/vi/" . explode("/vi/" ,$vresponse["videoThumbnails"][4]["url"])[1]);
	
					echo "<div class=\"text-result video-result\">";
					echo "	<a href=\"$vurl\">";
					echo "		<h2 style=\"padding-bottom: 0; margin-bottom: 0;\">$title</h2><br/>";
					echo "		<img class=\"thumbnail\" src=\"proxy-image.php?url=$thumbnail\" />";
					echo "	</a>";
					echo "  <p class=\"mlink\" style=\"margin-top: 0; padding-top: 0;\">$uploader - $views views - uploaded $uploaded</a>";
					echo "</div><br/>";
				}
			}
		} else {
			echo "<p>Service seems to be down, try again later.</p>";
		}
    }

	function invidiousVideoJSON($query, $page) {
		global $config;

		$res = array();

		$url = "https://invidious.tiekoetter.com/api/v1/search?q=" . urlencode($query);
        
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$rawResponse = curl_exec($ch);

		curl_close($ch);

		$response = json_decode($rawResponse, true);
		$url = "https://invidious.tiekoetter.com";

		if ($response) {
			foreach ($response as $vresponse) {
				if ($vresponse["type"] == "video") {
					$title = htmlspecialchars($vresponse["title"],ENT_QUOTES,'UTF-8');
					$vurl = htmlspecialchars("https://youtube.com/watch?v=" . $vresponse["videoId"]);
					$uploader = htmlspecialchars($vresponse["author"],ENT_QUOTES,'UTF-8');
					$views = htmlspecialchars($vresponse["viewCount"],ENT_QUOTES,'UTF-8');
					$uploaded = htmlspecialchars($vresponse["publishedText"],ENT_QUOTES,'UTF-8');
					$thumbnail = htmlspecialchars($url . "/vi/" . explode("/vi/" ,$vresponse["videoThumbnails"][4]["url"])[1]);

					array_push($res, array(
						"title" => $title,
						"url" => $vurl,
						"uploader" => $uploader,
						"views" => $views,
						"uploadDate" => $uploaded,
						"thumbnailUrl" => $thumbnail
					));
				}
			}

			return json_encode($res);
		} else {
			$result = "Failed";
			return json_encode($result);
		}
	}
?>