<?php

    $theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';

    if ($theme == 'light') {
        echo "<style>";
        echo "body {";
        echo "background-image: url(/static/img/background_light.png)";
        echo "}";
        echo "</style>";
    } else {
        echo "<style>";
        echo "body {";
        echo "background-image: url(/static/img/background_dark.png)";
        echo "}";
        echo "</style>";
    }

?>