<?php
	include "variables.php";
	include "authenticate.php";
	
	$dbh =  new PDO("sqlite:".$database_url);

	$login_name =$_COOKIE['username'];
	$sql = "SELECT * "
			."FROM Sellers "
			."WHERE login_name='$login_name' LIMIT 1";
	
	$user_seller = $dbh->query($sql);
	$this_user_seller = $user_seller->fetch(PDO::FETCH_ASSOC);
	$seller_id = $this_user_seller["seller_id"];


	$sql2 = "SELECT * "
			."From ("
				."SELECT * "
				."FROM ShoppingCarts "
				."where user_id='".$seller_id."' "
			.") As my_cart "
			."JOIN items i "
			."on i.item_id=my_cart.item_id ";
	//echo $sql2;
	$itemRows = $dbh->query($sql2);

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

