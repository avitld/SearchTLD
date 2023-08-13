<?php
    require "misc/functions/functions.php";

    $config = readJson('config.json');

    $query = urlencode(htmlspecialchars($_REQUEST['q'], ENT_QUOTES, 'utf-8'));
    $page = $_REQUEST['pg'];
    $type = $_REQUEST['tp'];
    $lang = $_REQUEST['lg'] ?? "en";
    $region = $_REQUEST['rg'] ?? "us";
    
    if (
        !isset($_REQUEST['q']) ||
        !isset($_REQUEST['tp']) ||
        !isset($_REQUEST['tp']) ||
        $type == 1 ||
        $type > 3 ||
        strlen($region) > 3 ||
        strlen($lang) > 3 ||
        !is_string($region) ||
        !is_string($lang)
    ) {
        echo "
            <!DOCTYPE html>
            <html>
                <head> ";
        require "misc/templates/header.php";
        echo    "   <title>API Usage Guide</title>";
        echo    "    </head>";
        echo "
                <body>
                    <main id=\"centered-body\">
                        <h1>API Usage Guide</h1>
                        <p>If you are seeing this it means you didn't properly make a request
                        to the API</p>
                        <hr/>
                        <h2>How to use the API</h2>
                        <h3>Parameters:</h3>
                        <ul style=\"list-style: none; padding: 0;\">
                            <li>
                                <code>q</code>: Query
                            </li>
                            <li>
                                <code>pg</code>: Page
                            </li>
                            <li>
                                <code>tp</code>: Type
                            </li>
                            <li>
                                <code>lg</code>: Language
                            </li>
                            <li>
                                <code>rg</code>: Region
                            </li>
                        </ul>
                        <p>By default, Language is set to English and Region is set to US</p>
                        <h3>Parameter Usage</h3>
                        <p>
                            The types are the same as in regular SearchTLD, however the API does not support
                            images (type 2) and forums (type 5). However the types are different in the API,
                            type 0 is text, type 2 is video and type 3 is news.
                            <br/>
                            For the language parameter, you just use the short code of your specified language (e.g.: English -> en)
                            <br/>and the same thing for region (e.g.: United Kingdom -> uk).
                        </p>
                        <h3>Example</h3>
                        <a href=\"/api?q=trisquel+gnu+linux&pg=0&tp=0&lg=el&rg=gr\">api?q=trisquel+gnu+linux&pg=0&tp=0&lg=el&rg=gr</a>
                        <p> ^ What this will do is search for Trisquel GNU Linux on the first page of text results in Greek.</p>
                    </main>
                </body>
            </html>
        ";
        die();
    }

    switch ($type) {
        case 0: 
            require "engines/text/google.php";
            $results = googleTextJSON($query, $page, $lang, $region);
            if ($results == "Failed") {
                require "engines/text/ddg.php";
                $results = ddgTextJSON($query, $page, $lang, $region);
                if ($results == "Failed") {
                    require "engines/text/brave.php";
                    $results = braveTextJSON($query, $page, $lang, $region);
                }
            }
            break;
        case 2:
            require "engines/videos/invidious.php";
            $results = invidiousVideoJSON($query, $page);
            break;
        case 3:
            require "engines/news/startpage.php";
            $results = spNewsJSON($query, $page, $lang);
            if ($results == "Failed") {
                $results = json_encode(
                    array(
                        "response" => "Failed to get response. Try again later."
                    )
                );
            }
            break;
        default:
            $results = json_encode(
                array(
                    "response" => "Bad Request."
                )
            );
            break;
    }

    header("Content-Type: application/json");
    echo $results;
?>