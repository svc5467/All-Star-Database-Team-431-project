<?php
	include "variables.php";
	
	// setcookie('username', "", time()-1000);
	// echo 'Location: '.$website_url.'/index.php';
	unset($_COOKIE['username']);
    setcookie('username', null, -1, '/');
	header('Location: '.$website_url.'/index_orig.php');
?>