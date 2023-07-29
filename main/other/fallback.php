<?php
    require "functions.php";
    $config = readJson('../config.json');

    $method = $_REQUEST["me"];
    $query = htmlspecialchars($_REQUEST["q"],ENT_QUOTES,'UTF-8');
    $page = $_REQUEST["pg"];

    if ($method == "brave") {
        require "../engines/text/brave.php";
        $response = getbHTML($query, $page);
                                    
        send_text_th_response($response);
    } elseif ($method == "duck") {
        require "../engines/text/ddg.php";
        $response = getdHTML($query, $page);
                                    
        send_text_sec_response($response);
    } else {
        echo "Invalid method. (JS Fallback)";
    }
?>