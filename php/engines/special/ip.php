<?php

    function echoIP() {
        $ip = $_SERVER["REMOTE_ADDR"];
        echo "<div class=\"text-result\">";
        echo "<h3>Your IP Address is: <strong>$ip</strong></h3>";
        echo "</div>";
    }

?>