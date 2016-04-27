<?php
	include "variables.php";
	include "authenticate.php";

	$dbh = new PDO("sqlite:".$database_url);
	$itemRows = $dbh->query("SELECT title, buy_it_now_price, description FROM Items");
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
				All-Star Database
			</div>
			
	<br>
	<?php
						
		while($row = $itemRows->fetch(PDO::FETCH_ASSOC)) {
			echo '<div><ul>';
			foreach($row as $key => $var)
			{
				echo '<li>' . $key . ': '. $var;
			}
			#TODO: figure-out URL convention for each individual item. needed to get to item page.
			#		likely pass ID thru URL then parsed for dynamimc pages
			echo '</ul></div>';
		}
?>
			
		</div>
	</body>

</html>




