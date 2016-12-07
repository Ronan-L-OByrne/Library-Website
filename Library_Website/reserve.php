<?php
	include('session.php');
	
	//Checks the books ISBN and reservation status
	$bn = $_GET['id'];
	$s  = $_GET['stat'];
	//Pulls the username
	$u  = $_SESSION['login_user'];
	//Variables to direct the user back to the same page once resrevation is complete
	$p	= $_GET['page'];
	$t	= $_GET['Title'];
	$a	= $_GET['Author'];
	$c	= $_GET['Category'];
	
	//Checks if the book is already reserved
	if(strcmp($s, 'Y') == 0)
	{
		//Changes the books status to not reserved
		$sql1 = "UPDATE books b JOIN reservations r SET b.Reserved = 'N'  WHERE b.ISBN = '$bn' AND r.ISBN = '$bn' AND  b.Reserved = 'Y' AND r.UserName = '$u'";
		mysqli_query($connection, $sql1);
		
		//Deletes the Reservation from the reservations table
		$sql2 = "DELETE FROM reservations WHERE ISBN = '$bn' AND UserName = '$u'";
		mysqli_query($connection, $sql2);
		
		//Checks if the user came from Search.php or CheckR.php
		if($p % 5 != 0)
		{
			header('location: CheckR.php?elements='.($p-1));
		}//end if
		else
		{
			header('location: Search.php?elements='.$p.'&title='.$t.'&author='.$a.'&category='.$c.'');
		}//end else
		return;
	}//end if
	else if(strcmp($s, 'N') == 0)
	{
		//Sets the book reserved status to reserved
		$sql1 = "UPDATE books SET Reserved = 'Y'  WHERE ISBN = '$bn' AND Reserved = 'N'";
		mysqli_query($connection, $sql1);
		
		//Adds the reservation to the reservations table
		$sql2 = "INSERT INTO reservations (ISBN, UserName) VALUES ('$bn', '$u')";
		mysqli_query($connection, $sql2);
		
		//Directs the user back to Search.php
		header('location: Search.php?elements='.$p.'&title='.$t.'&author='.$a.'&category='.$c.'');
	}//end if
?>