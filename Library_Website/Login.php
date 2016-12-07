<?php
	include("Config.php");
	
	echo
	('
		<head>
			<title>Library - Login</title>
		</head>
	');
	
	//Login form
	echo
	('
		<head>
			<title>Login</title>
		</head>
		
		<h1 align="center">Library Login</h1>
		<table align="center" >
			<tr><td>
			<form method = "post">
				<label>UserName  :</label></td><td>
				<input type = "text" name = "UserName"/></td><tr><td>
				<label>Password   :</label></td><td>
				<input type = "password" name = "Password"/></td><tr><td>
				<a href="Register.php">Register as new User</a></td><td align="center" >
				<input type = "submit" value = "Submit"/><td></tr>
			</form>
	');
	
	//Checks if a UserName and Password have been entered
	if (isset($_POST['UserName']) && isset($_POST['Password'])) 
	{
		$u  = $_POST['UserName'];
		$p  = $_POST['Password'];
		
		//Checks if there is an account with that username and password
		//Reading in Values from a form using this method avoids SQL injection
		$logSql = "SELECT UserName FROM users WHERE UserName = ? AND Password = ?";
		$logState = $connection->prepare($logSql);
		$logState->bind_param('ss', $u, $p);
		
		$logState->execute();
		$logResult = $logState->get_result();
		$logCount = mysqli_num_rows($logResult);
		
		if($logCount == 1)
		{
			//Begins a session and moves the user to the index page
			$_SESSION['login_user'] = $u;
			header("location: Index.php");
		}//end if
		else
		{
			//Tells the user to re-enter their details
			echo('<tr></tr><tr><td colspan = 2 align = "Center" style = "color: Red;">
			Invalid UserName or Password</tr></td>');
		}//end else
	}//end if
	echo('</table>');
	
	include_once("Footer.php"); // Includes the Footer at the bottom of the page
?>