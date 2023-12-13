<?php
    require "../functions/functions.php";
    $config = readJson('../../config.json');

    $method = $_REQUEST["me"];
    $query = htmlspecialchars($_REQUEST["q"],ENT_QUOTES,'UTF-8');
    $page = $_REQUEST["pg"];
    $type = $_REQUEST["type"];

    switch ($type) {
        case 'text':
            switch ($method) {
                case 'brave':
                    require "../../engines/text/brave.php";
                    braveText($query, $page);
                    break;
                case 'google':
                    require "../../engines/text/google.php";
                    googleText($query, $page);
                    break;
                case 'bing':
                    require "../../engines/text/bing.php";
                    bingText($query, $page);
                    break;
                default:
                    echo "Invalid method. (JS Fallback)";
                    break;
            }
            break;
        case 'news':
            require "../../engines/news/startpage.php";
            spNews($query, $page);
            break;
    }
?>