<?php
	include "assets/variables.php";
	
	// echo 'Location: '.$website_url.'/index.php';
	unset($_COOKIE['username']);
    setcookie('username', null, -1, '/');
	setcookie('username', "", time()-1000);
	header('Location: '.$website_url.'/index.php');
?>