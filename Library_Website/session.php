<?php
	include('config.php');
	
	//Checks if there is a session currently set
	if(!isset($_SESSION['login_user']))
	{
		header("location:login.php");
	}//end if
?>