<?php
	include "assets/includes.php";
	$dbh = new PDO("sqlite:c:/xampp/htdocs/allStarDB/allstar.sqlite");
	$itemRows = $dbh->query("SELECT title, buy_it_now_price, description FROM Items");
	
	if(!$itemRows) {
			die('Invalid query: ' . mysql_error());
		}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Search Results</title>
	<meta name="description" content="Search Query Results">
	<meta name="author" content="Brent Riva">
	
	<?php
		include "assets/includes.php";
	?>
	<!--[if lt IE9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<script src="js/scripts.js"></script>
	<h1>Search results:</h1>
	<br>
	<br>
	<?php
						
		while($row = $itemRows->fetch(PDO::FETCH_ASSOC)) {
			echo '<div><ul>';
			foreach($row as $key => $var)
			{
				echo '<li>' . $key . ': '. $var;
			}
			#TODO: figure-out URL convention for each individual item. needed to get to item page.
			#		likely pass ID thru URL then parsed for dynamimc pages
			echo '</ul></div>';
		}
?>

</body>
</html>
