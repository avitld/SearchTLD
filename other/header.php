<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Free & Privacy respecting meta-search engine.">
		<link rel="stylesheet" type="text/css" href="static/css/styles.css">
		<meta name="referrer" content="no-referrer">
		<link rel="icon" type="x-image/icon" href="static/img/favicon.ico">
		<link rel="stylesheet" type="text/css" href="<?php
			echo "static/css/"; 
			$cookieValue = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : '';
			if (isset($_COOKIE["theme"])) {
				echo $cookieValue . ".css";
			} else {
				echo "dark.css";
			}
			
				?>">
