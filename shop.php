<?php
	include "variables.php";
	include "authenticate.php";
	include "assets/list_items.php";
	$dbh =  new PDO("sqlite:".$database_url);

	$itemRows;

	if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_GET["search"]))
		{
			
			$sql = "SELECT item_id, title, buy_it_now_price, description "
					."FROM Items "
					."where title like '%".$_GET["search"]."%' or description like '%".$_GET["search"]."%'" ;

			$itemRows = $dbh->query($sql);
		}else
		{
			// search not set, show all items
			$itemRows = $dbh->query("SELECT item_id, title, buy_it_now_price, description FROM Items");
		}
	}else
	{
		// is post
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<?php
			include "assets/includes.php";
		?>
	</head>
	
	<body>
		<?php include "menu.php"; ?>
		
		<!-- The "info" div is the meat of the body, it contains the general information of the page. -->
		<div class="info">
			<!-- The "heading" div styles the heading of each section to stand out more and make it very readable.-->
			<div class="heading">
				All-Star Database
			</div>
			<?php
				list_items($itemRows,"Items");
			?>
		</div>
	</body>

</html>

