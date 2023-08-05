<?php
    function weather() {
        // $_SERVER["REMOTE_ADDR"]
        $url = "https://wttr.in/@" . "79.130.143.214" . "?format=j1";

        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        curl_close($ch);

        parseWeather($response);
    }

    function parseWeather($response) {
        $data = json_decode($response, true);

        if ($data) {
            $currentWeather = $data["current_condition"][0]["weatherDesc"][0]["value"];
            $tempC = $data["current_condition"][0]["temp_C"];
            $tempF = $data["current_condition"][0]["temp_F"];

            $city = $data["nearest_area"][0]["areaName"][0]["value"];
            $country = $data["nearest_area"][0]["country"][0]["value"];
            
            $sunny = [
                "Clear",
                "Partly Cloudy"
            ];
            $rainy = [
                "Mist",
                "Rain",
                "Drizzle",
                "Thunderstorm"
            ];
            $snowy = [
                "Snow",
                "Sleet",
                "Hail",
                "Snow Showers",
                "Heavy Snow"
            ];

            if (in_array($currentWeather, $sunny)) {
                $thumbnail = '/static/img/sunny.png';
            } elseif (in_array($currentWeather, $rainy)) {
                $thumbnail = '/static/img/rainy.png';
            } elseif (in_array($currentWeather, $snowy)) {
                $thumbnail = '/static/img/rainy.png';
            } else {
                $thumbnail = '/static/img/cloudy.png';
            }

            echo "<div class=\"infobox\">";
            echo "<img src=\"$thumbnail\">";
            echo "<h3>$currentWeather</h3>";
            echo "<hr>";
            echo "<p>$tempC °C | $tempF °F</p>";
            echo "<p>Current weather in $city, $country.</p>";
            echo "<a href=\"https://wttr.in\">More info on https://wttr.in</a>";
            echo "</div>";
        }
    }
?>