<?php
	include "variables.php";
	include "authenticate.php";
	
	$dbh = new PDO("sqlite:".$database_url);
	$username = $_COOKIE['username'];

	$sql = 	"SELECT seller_type, revenue, login_name, email, balance, birthday, annual_income, gender, last_name, first_name, street, city, zipcode "
			."FROM ( "	
				."SELECT * "
				."FROM ( "
					."SELECT * " 
					."FROM Sellers " 
					."WHERE login_name='$username' LIMIT 1 "
				.") AS sellers_query "
				."INNER JOIN Users users "
				."ON users.user_id = sellers_query.seller_id "
			.") AS sellers_users_together "
			."INNER JOIN Addresses ads "
			."ON ads.address_id = sellers_users_together.address_id;";

	//echo "\n\n\n\n\n\n".$sql;
	$user_seller_query = $dbh->query($sql);
	$user_seller = $user_seller_query->fetch(PDO::FETCH_ASSOC);

/*	$user_seller = $dbh->query("SELECT * FROM Sellers WHERE login_name='$username' LIMIT 1");
	$seller_id = "";
	$this_user_seller = $user_seller->fetch(PDO::FETCH_ASSOC);
	foreach($this_user_seller as $key => $var)
	{
		if($key == "seller_id")
			$seller_id = $var;//echo '<li>' . $key . ': '. $var;
		if($key == "")
	}

	$user = $dbh->query("SELECT email, balance, birthday, annual_income, gender, last_name, first_name FROM Users WHERE user_id='$seller_id'LIMIT 1" );
*/
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/css/stylesheet.css";?>"/>
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/assets/bootstrap-3.3.6-dist/css/bootstrap.css";?>"/>	
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/css/table_style.css";?>"/>	
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
								
					echo '<div class="table_div">'
						.'<table class="table-striped table-bordered">'
						.'<thead>'
							.'<th>'
								.'Attribute'
							.'</th>'
							.'<th>'
								.'Value'
							.'</th>'
						.'</thead>'
						.'<tbody>';

						foreach($user_seller as $key => $var)
						{
							echo '<tr>' 
									.'<td class="key_td">'
										. $key 
									.'</td>'
									.'<td>'
										. $var
									.'</td>'
								.'</tr>';

						}
				/*		foreach($this_user_seller as $key => $var)
						{
							if( $key != "seller_id")
								echo '<li>' . $key . ': '. $var;
						}
						$this_user = $user->fetch(PDO::FETCH_ASSOC);
						foreach($this_user as $key => $var)
						{
							echo '<li>' . $key . ': '. $var;
						}
				*/
					#TODO: figure-out URL convention for each individual item. needed to get to item page.
					#		likely pass ID thru URL then parsed for dynamimc pages
					echo '</tbody>'
						.'</table>'
						.'</div>';
			//	}
			?>
			
		</div>
	</body>

</html>
