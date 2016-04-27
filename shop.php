<?php
	$dbh = new PDO("sqlite:/pass/users/a/s/asm5453/www/allstar.sqlite");
	$itemRows = $dbh->query("SELECT item_id, title, buy_it_now_price, description FROM Items");
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="Stylesheet" type="text/css" href="http://www.personal.psu.edu/asm5453/css/stylesheet.css"/>
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
	
			<ul class = "product_grid">
				<?php
					while($row = $itemRows->fetch(PDO::FETCH_ASSOC)) 
					{
				?>
					<a class = "item_link" href = "http://www.personal.psu.edu/asm5453/product?product_id=<?php echo $row['item_id'];?>">
						<li>
							Title: <?php echo $row['title'];?> <br>
							Price: <?php echo $row['buy_it_now_price'];?> <br>
							Description: <?php echo $row['description'];?> 
						</li>
					</a>
				<?php
					}
				?>
			</ul>
		<br>
		<br>
		</div>
	</body>

</html>

