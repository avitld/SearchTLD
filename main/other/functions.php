<?php
    function readJSON($file) {
        $json = file_get_contents($file);
        $data = json_decode($json, true);

        return $data;
    }

    
?>