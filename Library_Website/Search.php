<?php
	echo
	('
		<head>
			<title>Library - Search</title>
		</head>
	');
	
	include("Index.php"); // Includes the banner and search bar at the top of the page

	
	// Used to determin which page is being displayed
	$p  = $_GET['elements'];
	
	echo '<table border="0" align="center" id = "DispTab" >';
	
	//Checks if the data was set in the form or passed to the page
	if ((isset($_POST['BookTitle']) || isset($_POST['Author']) || isset($_POST['Category']))) 
	{
		$p = 0; // Resets the page to 0
		$t  = '%' . $_POST['BookTitle'] . '%'; // Adds wildcards because I cant in the sql
		$a  = '%' . $_POST['Author'] . '%'; // Adds wildcards because I cant in the sql
		$c  = $_POST['Category'];
	}//end if
	else
	{
		$t = $_GET['title'];
		//This will add wildcard characters to either end of the title if they are not already present
		if(strstr($t, '%') == false)
		{
			$t = '%' . $_GET['title'] . '%'; // Adds wildcards because I cant in the sql
		}//end if
		
		$a = $_GET['author'];
		//This will add wildcard characters to either end of the author if they are not already present
		if(strstr($a, '%') == false)
		{
			$a = '%' . $_GET['author'] . '%'; // Adds wildcards because I cant in the sql
		}//end if
		
		$c = $_GET['category'];
	}//end else
	
	$catSql = "SELECT * FROM categories";
	$catResult = mysqli_query($connection, $catSql);
	
	$catCount = mysqli_num_rows($catResult);
	
	//Checks if the category field is set
	if($c > 0 && $c <= $catCount)
	{
		//Selects the 5 elements that will be displayed on the current page
		$seaSql  = "SELECT * FROM books WHERE BookTitle LIKE ? AND Author LIKE ? AND Category = ? LIMIT 6 OFFSET ?";
		$seaState  = $connection->prepare($seaSql);
		$seaState->bind_param('ssss', $t , $a , $c , $p );
	}//end if
	else
	{
		//Selects the 5 elements that will be displayed on the current page
		$seaSql = "SELECT * FROM books WHERE BookTitle LIKE ? AND Author LIKE ? LIMIT 6 OFFSET ?";
		$seaState = $connection->prepare($seaSql);
		$seaState->bind_param('sss', $t, $a, $p);
	}//end else
	
	//executes the set sql
	$seaState->execute();
	$seaResult = $seaState->get_result();
	
	// Checks that the result is sucessful
	if(!$seaResult)
	{
		die('Cound not connect:' . mysqli_error());
		exit;
	}//end if
	
	// Counts the number of books found in the current query and page
	$seaCount = mysqli_num_rows($seaResult);
	
	if($seaCount == 0)
	{
		//Tells the user if there are no books in the query
		echo('<p align=center>There are no books that match this query.</br>Please edit your search or click here:</p>');
		echo('<p align=center><a id="index" href="search.php?elements=0&title=&author=&category=">Search</a></p>');
	}//end if
	else
	{
		//Header for the table
		echo('<tr><th>ISBN</th>');
		echo("<th>Title</th>");
		echo("<th>Author</th>");
		echo("<th>Edition</th>");
		echo("<th>Year Published</th>");
		echo("<th>Category</th>");
		echo("<th>Reserve</th></tr>");
		
		// Used to check if the book is reserved by the current user
		$u = $_SESSION['login_user'];
		//Displays the data in the current table
		$i = 0;
		while($i<5 && $i<$seaCount)
		{
			$i++;
			$row = mysqli_fetch_row($seaResult);
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
			
			//Replaces the Category number with the category description
			$sqlCateg = "SELECT CategoryDesc FROM Categories WHERE Category =".htmlentities($row[5]);
			$resultCateg = mysqli_query($connection, $sqlCateg);
			$Categ = mysqli_fetch_assoc($resultCateg) ;
			echo($Categ["CategoryDesc"]);
			
			echo("</td><td>");
			//Checks if the book is reserved
			if(htmlentities($row[6]) == 'N')
			{
				echo('<a href="reserve.php?id='.htmlentities($row[0]).'&stat='.htmlentities($row[6]).
				'&page='.$p.'&Title='.$t.'&Author='.$a.'&Category='.$c.'">Reserve</a>');
			}//end if
			else
			{
				//Checks if the current user has reserved the book
				$sqlRes = "SELECT * FROM reservations WHERE UserName = '". $u ."' AND ISBN = '". htmlentities($row[0]) ."'";
				$resultRes = mysqli_query($connection, $sqlRes);
				$countRes = mysqli_num_rows($resultRes);
				
				if($countRes == 0)
				{
					//Reserved by other user
					echo('Reserved');
				}//end if
				else
				{
					//Reserved by current user
					echo('<a id="unRes" href="reserve.php?id='.htmlentities($row[0]).'&stat='.htmlentities($row[6]).
					'&page='.$p.'&Title='.$t.'&Author='.$a.'&Category='.$c.'">Unreserve</a>');
				}//end else
			}//end else
			echo("</td><tr>");
		}//end while
		echo("</table>");
		
		echo('<p align = "center">');
		//Checks if there are any more pages before or after the current page
		if($p > 0 && $seaCount > 5)
		{
			echo('<a id="link" href="Search.php?elements='.($p-5).'&title='.$t.'&author='.$a.'&category='.$c.'">Back</a> | '. (($p/5)+1));
			echo(' | <a id="link" href="Search.php?elements='.($p+5).'&title='.$t.'&author='.$a.'&category='.$c.'"> Forward</a>');
		}//end if
		else if($p == 0 && $seaCount > 5)
		{
			echo('1 | <a id="link" href="Search.php?elements='.($p+5).'&title='.$t.'&author='.$a.'&category='.$c.'">Forward </a>');
		}//end else
		else if(($p+5) > $seaCount && $p > 0)
		{
			echo('<a id="link" href="Search.php?elements='.($p-5).'&title='.$t.'&author='.$a.'&category='.$c.'">Back</a> | '. (($p/5)+1));
		}//end else
		//Checks if there is only one page
		else if($seaCount <= 5)
		{
			echo('| 1 |');
		}//end else
	}//end else
	echo('</p>');
	
	//Displays the search form used to filter the results
	echo
	('<p> <table border = 0 align="center" > <form method = "post">
		<tr><td><label>Title:	</label></td><td><input type = "text" name = "BookTitle"/></td>
		</tr><tr><td><label>Author:	</label></td><td><input type = "text" name = "Author"/></td>
		</tr><tr><td><label>Category:	</label></td><td><select name="Category">
	<option value="NULL"></option>'
	);
	
	// Category values are dynamic
	while ( $row = mysqli_fetch_row($catResult) )
	{
		echo('<option value='.htmlentities($row[0]).'>'.htmlentities($row[1]).'</option>');
	}//end while
	
	echo('</select></td></tr><tr><td colspan = "2" align="center" ><input type = "submit" value = "Search"/>');
	echo('</td></tr></form></table></p>');
	
	include("Footer.php"); // Includes the Footer at the bottom of the page
?>