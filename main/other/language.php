<?php
    $url = 'https://restcountries.com/v2/all';
    $languages = array();

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    foreach ($data as $country) {
        if (isset($country['languages'])) {
            $code = trim($country['languages'][0]['iso639_1']);
            $name = trim($country['languages'][0]['name']);

            $existingName = array_column($languages, 'name');
            if (in_array($name, $existingName)) {
                continue;
            }

            $languages[] = array(
                'code' => $code,
                'name' => $name
            );
        }
    }

?>