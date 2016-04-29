<?php
	include "variables.php";
	include "authenticate.php";
	$dbh =  new PDO("sqlite:".$database_url);
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
				<?php echo $this_user_seller["login_name"]."'s Shopping Cart" ?>
			</div>
			<br>
	
			<ul class = "product_grid">
				<?php
					while($row = $itemRows->fetch(PDO::FETCH_ASSOC)) 
					{
				?>
					<a class = "item_link" href = "<?php echo $website_url; ?>/product.php?product_id=<?php echo $row['item_id'];?>">
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