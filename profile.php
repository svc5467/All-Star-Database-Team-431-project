<?php
	include "variables.php";
	include "authenticate.php";
	
	$dbh = new PDO("sqlite:".$database_url);
	$username = $_COOKIE['username'];
	$user_seller = $dbh->query("SELECT * FROM Sellers WHERE login_name='$username' LIMIT 1");
	$seller_id = "";
	$this_user_seller = $user_seller->fetch(PDO::FETCH_ASSOC);
	foreach($this_user_seller as $key => $var)
	{
		if($key == "seller_id")
			$seller_id = $var;//echo '<li>' . $key . ': '. $var;
	}

	$user = $dbh->query("SELECT * FROM Users WHERE user_id='$seller_id'LIMIT 1" );
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/css/stylesheet.css";?>"/>
		
	</head>
	
	<body>
		<?php include "menu.php"; ?>
		
		<!-- The "info" div is the meat of the body, it contains the general information of the page. -->
		<div class="info">
			<!-- The "heading" div styles the heading of each section to stand out more and make it very readable.-->
			<div class="heading">
				
			</div>
			
			<br>
			<?php
								
					echo '<div><ul>';
			
						foreach($this_user_seller as $key => $var)
						{
							echo '<li>' . $key . ': '. $var;
						}
						$this_user = $user->fetch(PDO::FETCH_ASSOC);
						foreach($this_user as $key => $var)
						{
							echo '<li>' . $key . ': '. $var;
						}
					#TODO: figure-out URL convention for each individual item. needed to get to item page.
					#		likely pass ID thru URL then parsed for dynamimc pages
					echo '</ul></div>';
			//	}
			?>
			
		</div>
	</body>

</html>
