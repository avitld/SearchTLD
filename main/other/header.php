<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Free & Privacy respecting meta-search engine.">
		<link rel="stylesheet" type="text/css" href="static/css/styles.css">
		<meta name="referrer" content="no-referrer">
		<link rel="search" type="application/opensearchdescription+xml" title="SearchTLD" href="/opensearch.xml">

		<link rel="icon" type="image/png" href="<?php 
			$theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';
			if ($theme === 'light') {
				echo 'static/img/logo_light.png';
			} else {
				echo 'static/img/logo_dark.png';
			}
		?>">

		<link rel="stylesheet" type="text/css" href="<?php
			$theme = isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : 'dark';
			if ($theme === 'light') {
				echo 'static/css/light.css';
			} else {
				echo 'static/css/dark.css';
			}
				?>">
