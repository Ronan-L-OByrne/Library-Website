<?php
	include('session.php');
	
	echo
	('
		<head>
			<title>Library - Index</title>
		</head>
	');
	
	//Provides a banner and idex used to navigate the site
	echo('<h1 style="text-align:center;" id = "Banner"> Library Website <span style="float:right;">| '. $_SESSION['login_user'].' |</h1>');
	echo('<h2 align = center><a id="index" href="logout.php">Logout</a> | ');
	echo('<a id="index" href="search.php?elements=0&title=&author=&category=">Search</a> | ');
	echo('<a id="index" href="CheckR.php?elements=0">Check Reserved</a></h2>');
	
	include_once("Footer.php"); // Includes the Footer at the bottom of the page
?>