<?php
	$Uquery = $_POST["q1"];
	$Squery = $_POST["q2"];
	$Aquery = $_POST["q3"];
	
	$dbh = new PDO("sqlite:c:/xampp/htdocs/allStarDB/allstar.sqlite");
	$AResult = $dbh->query($Aquery);
	echo $dbh->errorCode();
	$SResult = $dbh->exec($Squery);
	echo $dbh->errorCode();
	$UResult = $dbh->exec($Uquery);
	echo $dbh->errorCode();
?>