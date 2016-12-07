<?php
	//Called to establish connection with the database
	define('DB_SERVER'  , 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'secret');
	define('DB_DATABASE', 'assignment');
	$connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	
	if(!$connection)
	{
		die('Cound not connect:' . mysqli_error());
	}//end if
	
	//Links to the style sheet used in every page of the site
	echo ('<link rel="stylesheet" href="Library.css?v3.1" type="text/css">');
	
	session_start()
?>