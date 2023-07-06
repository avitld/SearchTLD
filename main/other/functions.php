<?php
    
    function detect_special_query($q)
    {
        // Code by pafefs
        $modified_query = str_replace(" ","",strtolower($q));
        if(strpos($modified_query,"my") !== false)
        {
            if(strpos($modified_query, "ip"))
            {
                return 1;
            }
            elseif(strpos($modified_query, "useragent") || strpos($modified_query, "user-agent") || strpos($modified_query, "ua"))
            {
                return 2;
            }
        }
    }
?>
