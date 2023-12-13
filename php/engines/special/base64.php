<?php

    function getLastWord($query) {
        $words = explode(" ", $query);
        $lastWord = end($words);
        $result = implode(" ", array($lastWord));
        return htmlspecialchars($result);
    }

    function encodeBase64($query) {
        $result = getLastWord($query);
        $encoded = base64_encode($result);
        echo "<div class=\"text-result\">";
        echo "<h3>$result as Base64: </h3>";
        echo "<p><small>$encoded</small></p>";
        echo "</div>";
    }

    function decodeBase64($query) {
        $result = getLastWord($query);
        $decoded = htmlspecialchars(base64_decode($result));
        echo "<div class=\"text-result\">";
        echo "<h3>Decoded: $decoded</h3>";
        echo "</div>";
    }

?>
