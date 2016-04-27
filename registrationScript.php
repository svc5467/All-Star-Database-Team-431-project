<?php
	$Uquery = $_POST["q1"];
	$Squery = $_POST["q2"];
	$Aquery = $_POST["q3"];
	
	$dbh = new PDO("sqlite:".$database_url);
	$AResult = $dbh->query($Aquery);
	//$SResult = $dbh->exec($Squery);
	//$UResult = $dbh->exec($Uquery);
	
	echo $dbh->errorCode();
?>