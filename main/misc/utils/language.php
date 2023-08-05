<?php
    $url = 'https://restcountries.com/v2/all';
    $languages = array();

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    foreach ($data as $country) {
        $code = trim($country['languages'][0]['iso639_1']);
        $name = trim($country['languages'][0]['name']);
        $tld = trim(str_replace('.', '', $country['topLevelDomain'][0]));
        $cname = trim($country['name']);
        $alphacode = trim($country['alpha2Code']);

        $existingName = array_column($languages, 'name');
        if (in_array($name, $existingName)) {
            if ($name == "English" || $name == "Arabic" || $name == "French") {
                continue;
            } else {
                $name = "$name ($cname)";
            }
        }

        $languages[] = array(
            'code' => $code,
            'name' => $name,
            'tld' => $tld,
            'cname' => $cname,
            'alphacode' => $alphacode
        );
    }

?>