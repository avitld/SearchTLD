<?php
    $query = $_REQUEST['q'];
    $query = htmlspecialchars($query);
    $query = urlencode($query);
    
    $url = "https://duckduckgo.com/ac/?q=$query&type=list";

    $json = file_get_contents($url);

    header('Content-Type: application/json');
    echo $json;
?>