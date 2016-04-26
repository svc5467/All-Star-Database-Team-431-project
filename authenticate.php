<?php
	if (!isset($_COOKIE['username'] ) )
	{
     	header('Location: index.php?access_denied=true');
	}
?>