<?php
	setcookie('username', "", time()-1000);
	header('Location: index.php');
?>