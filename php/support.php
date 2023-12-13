<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require "misc/templates/header.php" ?>
		
		<title>Support - SearchTLD</title>
	</head>
	<?php require "misc/functions/functions.php";
		$config = readJson("config.json");
	?>
	<body id="has-background">
		<main id="centered-body" class="index-main">
			<h1>Support the SearchTLD Project.</h1>
			<p>
				Support doesn't just mean to donate. It is also to use our software,
				recommend it to others and help with the development. All ways of supporting us
				are greatly appreciated.
			</p>
			<h3>Support us on social media.</h3>
			<p>
				Of course, <em>most</em> of these are proprietary, but they are the biggest ones
				and it's much easier to reach out to people there. If you don't have an account
				on these, we recommend you do not make one just to show your support, for the sake
				of your own privacy.
			</p>
			<ul style="list-style: none; padding: 0;">
				<li><a href="https://twitter.com/searchtld">Twitter</a></li>
				<li><a href="https://threads.net/@searchtld">Threads</a></li>
				<li><a href="https://youtube.com/@searchtld">YouTube</a></li>
				<li><a href="https://mastodon.social/@searchtld">Mastodon</a></li>
			</ul>
			<h3>Donate</h3>
			<p>
				You can donate to the maintainer (and any future developers) through
				their links here.
			</p>
			<h4>Avitld, Maintainer</h4>
			<ul style="list-style: none; padding: 0;">
				<li><a href="https://liberapay.com/avitld/">Liberapay</a></li>
			</ul>
			<a href="/"><button id="home">Go Back</button></a>
		</main>

		<?php require "misc/templates/footer.php"; ?>

	</body>
</html>