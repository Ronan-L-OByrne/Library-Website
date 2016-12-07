<?php
	echo
	('
		<head>
			<title>Library - Check Reservations</title>
		</head>
	');
	
	include("Index.php");
	
	$u = $_SESSION['login_user'];
	$p = $_GET['elements'];
	
	//Selects the users reserved books for the current page
	$sql = "SELECT * FROM books b JOIN reservations r ON b.ISBN = r.ISBN WHERE r.UserName LIKE '" . $u . "' LIMIT 6 OFFSET " . $p;
	$result = mysqli_query($connection, $sql);
	$count = mysqli_num_rows($result);
	
	//Makes sure the query doesnt go out of bounds while searching
	
	echo('<table border = 0 align="center" id = "DispTab">');
	
	//Displays if the user has not reserved any books
	if($count == 0 && $p>0)
	{
		header("location: CheckR.php?elements=" . ($p-5));
	}//end if
	else if($count == 0)
	{
		echo('<p align=center>You have no reserved books.</br>You may reserve books from the Search section here:</p>');
		echo('<p align=center><a id="index" href="search.php?elements=0&title=&author=&category=">Search</a></p>');
	}//end else if
	else
	{
		//Header for the table
		echo("<tr><th>ISBN</th>");
		echo("<th>Title</th>");
		echo("<th>Author</th>");
		echo("<th>Edition</th>");
		echo("<th>Year Published</th>");
		echo("<th>Category</th>");
		echo("<th>Reserved</th></tr>");
		
		//Displays the data associated with the users reserved books
		$i = 0;
		while($i<5 && $i<$count)
		{
			$i++;
			$row = mysqli_fetch_row($result);
			echo "<tr><td>";
			echo(htmlentities($row[0]));
			echo("</td><td>");
			echo(htmlentities($row[1]));
			echo("</td><td>");
			echo(htmlentities($row[2]));
			echo("</td><td>\n");
			echo(htmlentities($row[3]));
			echo("</td><td>");
			echo(htmlentities($row[4]));
			echo("</td><td>");
			//Replaces the Category numbers with the category descriptions
			$sqlCateg = "SELECT CategoryDesc FROM Categories WHERE Category =".htmlentities($row[5]);
			$resultCateg = mysqli_query($connection, $sqlCateg);
			$Categ = mysqli_fetch_assoc($resultCateg) ;
			echo($Categ["CategoryDesc"]);
			echo("</td><td>");
			//Allows the user to unreserve the book
			echo('<a id="unRes" href="reserve.php?id='.htmlentities($row[0]).'&stat='.htmlentities($row[6]).'&page='. ($p+1) .'">Unreserve</a>');
			echo("</td><tr>");
		}//end while
		echo("</table>");
		
		echo('<p align = "center">');
		//Checks if there are any more pages before or after the current page
		if($p > 0 && $count > 5)
		{
			echo('<a id="link" href="CheckR.php?elements='.($p-5).'">Back</a> | ' . (($p/5)+1));
			echo(' | <a id="link" href="CheckR.php?elements='.($p+5).'">Forward</a>');
		}//end if
		else if($p == 0 && $count > 5)
		{
			echo('1 |<a id="link" href="CheckR.php?elements='.($p+5).'"> Forward</a>');
		}//end else if
		else if(($p+5) > $count && $p > 0)
		{
			echo('<a id="link" href="CheckR.php?elements='.($p-5).'">Back</a> | '. (($p/5)+1));
		}//end else if
		//Checks if there is only one page
		else if($count <= ($p+5))
		{
			echo('| 1 |');
		}//end else if
		echo('</p>');
	}//end else
	
	include("Footer.php"); // Includes the Footer at the bottom of the page
?>