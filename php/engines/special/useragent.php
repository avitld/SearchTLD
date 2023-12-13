<?php

    function echoUA() {
        $ua = $_SERVER["HTTP_USER_AGENT"];
        echo "<div class=\"text-result\">";
        echo "<h3>Your User Agent is: <code>$ua</code></h3>";
        echo "</div>";
    }

?>