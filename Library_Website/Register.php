<?php
	include('config.php');
	
	echo
	('
		<head>
			<title>Library - Registration</title>
		</head>
	');
	
	//Registration Form
	echo
	('
		<h1 align=center>Registeration</h1>
		<table align = "center">
		<form method="post">
			<tr><td>User Name:</td>
			<td><input type="text" name="UserName"></td>
			<td></td></tr>
			<tr><td>Password:</td>
			<td><input type="password" name="Password"></td>
			<td align = "left" style="color: Grey;">(6 or more characters)</td></tr>
			<tr><td>Confirm Password:</td>
			<td><input type="password" name="Confirmation"></td>
			<tr><td>First Name:</td>
			<td><input type="text" name="FirstName"></td>
			<td></td></tr>
			<tr><td>Surname:</td>
			<td><input type="text" name="Surname"></td>
			<td></td></tr>
			<tr><td>Address Line 1:</td>
			<td><input type="text" name="AddressLine1"></td>
			<td></td></tr>
			<tr><td>Address Line 2:</td>
			<td><input type="text" name="AddressLine2"></td>
			<td></td></tr>
			<tr><td>City:</td>
			<td><input type="text" name="City"></td>
			<td></td></tr>
			<tr><td>Telephone:</td>
			<td><input type="text" name="Telephone"></td>
			<td align = "left" style="color: Grey;">(10 numbers)</td></tr>
			<tr><td>Mobile:</td>
			<td><input type="text" name="Mobile"></td>
			<td align = "left" style="color: Grey;">(10 numbers)</td></tr>
			<tr><td align = "center"><a href="login.php">Cancel</a></td>
			<td align = "center"><input type="submit" value="Register"/></td>
			</tr>
		</form>
	');
	
	$error = false;
	
	//Checks if the form has been submitted
	if (   isset($_POST['UserName']) && isset($_POST['Password'])     && isset($_POST['FirstName']) 
		&& isset($_POST['Surname'])  && isset($_POST['AddressLine1']) && isset($_POST['AddressLine2'])
		&& isset($_POST['City'])     && isset($_POST['Telephone'])    && isset($_POST['Mobile'])
		&& isset($_POST['Confirmation']))
	{
		//Reads all of the data from the form
		$u  = $_POST['UserName'];
		$p  = $_POST['Password'];
		$conf = $_POST['Confirmation'];
		$f  = $_POST['FirstName'];
		$s  = $_POST['Surname'];
		$a1 = $_POST['AddressLine1'];
		$a2 = $_POST['AddressLine2'];
		$c  = $_POST['City'];
		$t  = $_POST['Telephone'];
		$m  = $_POST['Mobile'];
		
		//Checks to see that all the fields have been filled
		if( strlen($u)==0 || strlen($p)==0 || strlen($f)==0 || strlen($s)==0 ||
		strlen($a1)==0 || strlen($a2)==0 || strlen($c)==0 || strlen($t)==0 || strlen($m)==0)
		{
			echo '<tr><td colspan = 3 align = "Center" style = "color: Red;">Please fill all fields</td></tr>';
			$error = true;
		}//end else if
		//Makes sure the password is more than 6 characters
		if(strlen($p) < 6)
		{
			echo '<tr><td colspan = 3 align = "Center" style = "color: Red;">Password must be 6 or more characters</td></tr>';
			$error = true;
		}//end else if
		//Checks that the Telephone number has 10 numbers and is numeric
		if(strlen($t) != 10 || !is_numeric($t))
		{
			echo '<tr><td colspan = 3 align = "Center" style = "color: Red;">Telephone must be 10 numbers</td></tr>';
			$error = true;
		}//end else if
		//Checks that the Mobile number has 10 numbers and is numeric
		if(strlen($m) != 10 || !is_numeric($m))
		{
			echo '<tr><td colspan = 3 align = "Center" style = "color: Red;">Mobile must be 10 numbers</td></tr>';
			$error = true;
		}//end else if
		
		if(strcmp($p, $conf) != 0)
		{
			echo '<tr><td colspan = 3 align = "Center" style = "color: Red;">Please properly confirm your password</td></tr>';
			$error = true;
		}//end if
		
		//Checks if the given UserName is taken
		$chSql = "SELECT UserName FROM users WHERE UserName LIKE ?";
		$chState = $connection->prepare($chSql);
		$chState->bind_param('s', $u);
		
		$chState->execute();
		$chResult = $chState->get_result();
		if(!$chResult)
		{
			echo 'Could not run query: ' . mysqli_error();
			exit;
		}//end if
		$chCount = mysqli_num_rows($chResult);
		
		if($chCount != 0)
		{
			//Error message if the UserName is taken
			echo '<tr><td colspan = 3 align = "Center" style = "color: Red;">This User Name is taken</td><td></td></tr>';
			$error = true;
		}//end if
		
		//Checks if there has been an error
		if(!$error)
		{
			//Inserts the users data into the database
			$insSql = "INSERT INTO users (UserName, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? )";
			$insState = $connection->prepare($insSql);
			$insState->bind_param('sssssssss', $u, $p, $f, $s, $a1, $a2, $c, $t, $m);
			
			$insState->execute();
			$insResult = $insState->get_result();
			
			if(!$insSql)
			{
				echo 'Could not run query: ' . mysqli_error();
				exit;
			}//end if
			else
			{
				//Begins a session and moves the user to the index page
				$_SESSION['login_user'] = $u;
				header("location: Index.php");
			}//end else
		}//end if
		
		echo('</table>');
	}//end if
	
	include_once("Footer.php"); // Includes the Footer at the bottom of the page
?>