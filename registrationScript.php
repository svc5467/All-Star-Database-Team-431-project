<?php
	include "assets/variables";

	$Uquery = $_POST["q1"];
	$Squery = $_POST["q2"];
	$Aquery = $_POST["q3"];
	
	$dbh =  new PDO("sqlite:".$database_url);
	$AResult = $dbh->query($Aquery);
	echo $dbh->errorCode();
	$SResult = $dbh->exec($Squery);
	echo $dbh->errorCode();
	$UResult = $dbh->exec($Uquery);
	echo $dbh->errorCode();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Supplier Registration</title>
	<meta name="description" content="Supplier Registration">
	<meta name="author" content="Brent Riva">
	<?php

		include "assets/includes.php";
	?>
	<!-- <link rel="stylesheet" href="css/stylesheet.css"> -->
	<!--[if lt IE9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
</body>
</html>