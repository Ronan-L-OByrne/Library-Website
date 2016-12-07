<?php
	echo
	('
		<head>
			<title>Library - Logout</title>
		</head>
	');
    include('session.php');
	
	echo('<h1 align="center">Goodbye '. $_SESSION['login_user'].'</h1>');
	//Ends the session and links back to the Login page
	unset($_SESSION['login_user']);
	echo('<p align = "Center">Thank You for your Time. Press Home to return to the Login Page</p>');
	
	echo('<p align = "center"><a id="link" href="login.php">Home</a></p>');
	
	include_once("Footer.php"); // Includes the Footer at the bottom of the page
?>