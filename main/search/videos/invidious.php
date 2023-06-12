<?php
    function getvJson($query) {
        $url = "https://yt.revvy.de/api/v1/search?q=" . urlencode($query);
        
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$raw_response = curl_exec($ch);

		curl_close($ch);

		return $raw_response;
    }

    function send_video_response($raw_response) {
        $response = json_decode($raw_response, true);
		$url = "https://yt.revvy.de";
		if ($response) {
			foreach ($response as $vresponse) {
				if ($vresponse["type"] == "video") {
					$title = htmlspecialchars($vresponse["title"],ENT_QUOTES,'UTF-8');
					$vurl = htmlspecialchars("https://yewtu.be/watch?v=" . $vresponse["videoId"]);
					$uploader = htmlspecialchars($vresponse["author"],ENT_QUOTES,'UTF-8');
					$views = htmlspecialchars($vresponse["viewCount"],ENT_QUOTES,'UTF-8');
					$uploaded = htmlspecialchars($vresponse["publishedText"],ENT_QUOTES,'UTF-8');
					$thumbnail = htmlspecialchars($url . "/vi/" . explode("/vi/" ,$vresponse["videoThumbnails"][4]["url"])[1]);
	
					echo "<div class=\"a-result\">";
					echo "	<a href=\"$vurl\" class=\"title\">$title</a><br/>";
					echo "	<img class=\"vidimg\" src=\"proxy-image.php?url=$thumbnail\" />";
					echo "  <p class=\"mlink\" style=\"margin-top: 0; padding-top: 0;\">$uploader - $views views - uploaded $uploaded</a>";
					echo "</div><br/>";
				}
			}
		} else {
			echo "<p>Service seems to be down, try again later.</p>";
		}
    }
?>