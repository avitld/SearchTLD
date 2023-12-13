<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require "misc/templates/header.php" ?>
		
		<title>SearchTLD</title>
	</head>
	<?php require "misc/functions/functions.php";
		$config = readJson("config.json");
	?>
	<body>
		<main id="centered-body" class="index-main">
            <h1>SearchTLD and Privacy</h1>
            <h3>Last Change: 03-08-2023</h3>
            <hr>
            <p>
            At SearchTLD, we value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard the data we may gather when you interact with our services.
            <h1>Data Collection and Usage</h1>
            <h2>1.1. No Data Storage:</h2> We do not store any personal data on our servers. Your personal information, including but not limited to name, address, and contact details, is not collected or stored by us.
            <h2>1.2. User Agent and IP Address:</h2> When you access our website, our web server may log your user agent and IP address for security and technical purposes. However, this information is deleted promptly and is not associated with any identifiable individual.
            <h2>1.3. Cookies:</h2> We use non-tracking cookies for specific features such as theming and language settings. These cookies do not store any personal information and are used solely to enhance your experience on our website.
            <h1>Data Protection</h1>
            We prioritize the protection of your privacy and employ reasonable technical and organizational measures to safeguard your data against unauthorized access, alteration, disclosure, or destruction.
            <h1>Third-Party Links</h1>
            Our website may contain links to third-party websites for your convenience. However, we have no control over the content or privacy practices of these external websites. Please review the privacy policies of these third-party sites before providing any personal information.
            <h1>Consent</h1>
            By accessing and using our services, you agree to the terms outlined in this Privacy Policy, including the usage of non-tracking cookies as described in section 1.3.
            <h1>Changes to the Privacy Policy</h1>
            We may update this Privacy Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We encourage you to review this page periodically for any modifications. Your continued use of our services after any updates constitute your acceptance of the revised Privacy Policy.
            </p>
            <br>
            <a href="/"><button id="home">Go Back</button></a>
		</main>
        

		<?php require "misc/templates/footer.php"; ?>

	</body>
</html>