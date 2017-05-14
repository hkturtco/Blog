<!DOCTYPE html>
<html lang = "en">
	<head>	
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<title>Geeks' Boxes</title>
		<link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
		<link rel="stylesheet" href="../style/main.css?version=1.541">
		<link rel="stylesheet" href="../style/pagination.css">
		<link rel="stylesheet" href="../style/lightbox.css">
		<link href="https://fonts.googleapis.com/css?family=Abel|Patua+One|Raleway" rel="stylesheet">
		<script src="../js/jquery-3.2.1.min.js"></script>
		<script src="../js/lightbox.js"></script>
		<script src="../js/menubar.js"></script>
		<script src="../js/main.js"></script>
	</head>
	
	<body>

	<!--------------------- Navigation Bar -------------------------->
		<div class="menubar" id="blogmenubar">
			<a  href="../" class="menulogo"><img src='../images/logo.png'> Geeks' Boxes</a>
			<?php
				$blog->get_blog_cat_list();
			?>

			<!--------------------- Search Bar -------------------------->
			<div id="searchbar">
				<form action="searchpage.php" method="GET" class="search">
					<input id="searchtextbox" type="text" name="query" onfocus="dimming()" onfocusout="dimming()" onkeyup="searchprompt()" maxlength="20" placeholder="Search" />
					<input type="submit" value="Search" />
				</form>
			</div>

			<a href="javascript:void(0);" class="icon" onclick="navbar()">&#9776;</a>
		</div>

		<div id='searchprompt'></div>
		<div id='searchprompt2'></div>

	<!--------------------- Header -------------------------->
		<div class="dimming">
			
			<div id="headerbg" class="parallax">
				Geeks' Boxes 
				<h1 class="subdes">Site Address: http://blog.recognize.tech</h1>
			</div>

