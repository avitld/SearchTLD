<?php require "other/header.php"; ?>
		<title>About - SearchTLD</title>
	</head>
	<?php require "other/functions.php";
		$config = readJson("config.json");
	?>
	<body style="background-image: url(/static/img/background_dark.png); background-size: contain;">
        <a href="/" style="color: white;">
            <div class="title-container" style="margin-top: 45px;">
                <img src="/static/img/logo_dark.png">
                <h1 style="font-size: 58px; user-select: none; margin: 0;">Search<span id="purple">TLD</span></a></h1>
            </div>
        </a>
        <p id="descriptor" style="margin-top: 0;">The Search Engine That Doesn't Track You.</p>
        <div class="cards">
            <div class="card" id="card-normal">
                <h1>What is SearchTLD?</h1>
                <p>
                    SearchTLD is a privacy respecting and free (as in freedom) meta-search engine.
                    What that means is we don't have our own index/database to gather results from,
                    rather we gather results for various different sources to provide accurate results.
                    In practice, this means you can enjoy accurate and relevant results from various 
                    sources whether it be a forum or a different search engine.
                    All results are anonymized and cleaned of tracking elements that they might contain.
                    No usage data is being stored on our webserver, <b>the only thing we can see is your IP and UA 
                    making requests to our website</b> this however is <b>not</b> being monitored and is just a 
                    part of the access log.
                </p>
            </div>
            <div class="card" id="card-purple">
                <h1>What makes SearchTLD special?</h1>
                <p>
                    There are many "privacy respecting" meta-search engines out there, but in reality they
                    are just as bad as the tech giants. Privacy on the internet is hard nowadays,
                    because everything is trying to collect as much user data from you as possible.
                    This is where we want to make the difference, a search engine is a tool we use daily but
                    there are very few (almost none!) fully free (as in freedom) and privacy respecting search engines.
                    We are completely transparent about SearchTLD and release the source code of everything being run 
                    behind the scenes on our servers so you can be assured that we do not collect data.
                </p>
            </div>
            <div class="card" id="card-blue">
                <h1>The importance of free software.</h1>
                <p>
                    Free or Libre software is software that respects your rights to freedom of speech and privacy.
                    Proprietary or closed-source software is software that doesn't respect your right to freedom or privacy.
                    Free software ensures that all code in your project is available for modification, reuse and redistribution.
                    Proprietary software is software that doesn't completely allow the above terms, or even completely hide the 
                    source code, thus giving the developer power over the users. We believe that proprietary software must be 
                    eradicated so that users ensure freedom in technology.
                </p>
                <h3>Notable Free Software Projects Include:</h3>
                <ul style="list-style: none; padding: 0;">
                    <li>The GNU+Linux Operating System.</li>
                    <li>The Vim Code Editor.</li>
                    <li>The Firefox web browser.</li>
                </ul>
            </div>
            <div class="card" id="card-white">
                <h1>Contact &amp; Source Code</h1>
                <p>There are many different ways to contact us. If you want an immediate response
                you can join our Matrix server, where you can report any issues or just hang out
                with the other members. You can also email the maintainer at: avitld@disroot.org
                </p><br>
                <h3>Contact Methods:</h3>
                <ul style="padding: 0; list-style: none;">
                    <li><a href="https://matrix.to/#/#schizos:schizo.gr" target="_blank">Matrix Server</a></li>
                    <li><a href="mailto:avitld@disroot.org">E-Mail Maintainer</a></li>
                </ul>
                <p>
                    Our source code is available on 3 different platforms. Codeberg is the most stable,
                    GitHub is the fastest, and our own OneDev instance (CodeAtomic) is the most up to date.
                </p>
                <h3>Source Code:</h3>
                <ul style="padding: 0; list-style: none;">
                    <li><a href="https://codeberg.org/avitld/SearchTLD" target="_blank">Codeberg</a></li>
                    <li><a href="https://github.com/avitld/SearchTLD" target="_blank">GitHub</a></li>
                    <li><a href="https://codeatomic.net/SearchTLD/SearchTLD" target="_blank">CodeAtomic (OneDev)</a></li>
                </ul>
            </div>
        </div>
    </body>
<?php require "other/footer.php"; ?>
