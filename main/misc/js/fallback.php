<?php
    require "../functions/functions.php";
    $config = readJson('../../config.json');

    $method = $_REQUEST["me"];
    $query = htmlspecialchars($_REQUEST["q"],ENT_QUOTES,'UTF-8');
    $page = $_REQUEST["pg"];

    if ($method == "brave") {
        require "../../engines/text/brave.php";
        braveText($query, $page);
    } elseif ($method == "google") {
        require "../../engines/text/google.php";
        googleText($query, $page);
    } elseif ($method == "bing") {
        require "../../engines/text/bing.php";
        bingText($query, $page);
    } else {
        echo "Invalid method. (JS Fallback)";
    }
?>