<?php
	$Uquery = $_POST["Uquery"];
	$Squery = $_POST["Squery"];
	$Aquery = $_POST["Aquery"];
	
	$dbh = new PDO("sqlite:c:/xampp/htdocs/allStarDB/allstar.sqlite");
	$AResult = $dbh->exec($Aquery);
	$SResult = $dbh->exec($Squery);
	$UResult = $dbh->exec($Uquery);
	
	echo $AResult . $SResult . $UResult
?>