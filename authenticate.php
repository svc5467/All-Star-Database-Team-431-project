<?php
	if (!isset($_COOKIE['username'] ) )
	{
     	header('Location: index.php?access_denied=true');
	}
	$dbh =  new PDO("sqlite:".$database_url);
	
	$login_name =$_COOKIE['username'];
	$sql = "SELECT * "
			."FROM Sellers "
			."WHERE login_name='$login_name' LIMIT 1";
	
	$user_seller = $dbh->query($sql);
	$this_user_seller = $user_seller->fetch(PDO::FETCH_ASSOC);
?>