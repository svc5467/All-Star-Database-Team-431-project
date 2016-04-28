<?php

function selecct_query($sql, $dbh)
{
	$user_seller_query = $dbh->query($sql);
	return $user_seller_query->fetch(PDO::FETCH_ASSOC);
}	
function selecct_query_multi($sql,$dbh)
{
	return $dbh->query($sql);
}


	include "variables.php";
	include "authenticate.php";
	
	$dbh = new PDO("sqlite:".$database_url);
	$username = $_COOKIE['username'];


	$seller_query = 	"SELECT * " 
						."FROM Sellers " 
						."WHERE login_name='$username' LIMIT 1 ";
	
	$seller = selecct_query($seller_query, $dbh);
	$is_supplier = (($seller["seller_type"] == "Supplier") ? true : false);


	if($is_supplier)
	{
		// its a supplier

		$sql = 	"SELECT seller_type as 'Account Type', revenue as Revenue, login_name as 'User Name',  street as Street, city as City, zipcode as Zipcode, phone as Phone, name as 'Company Name'"
				."FROM ( "	
					."SELECT * "
					."FROM ( "
						."SELECT * " 
						."FROM Sellers " 
						."WHERE login_name='$username' LIMIT 1 "
					.") AS sellers_query "
					."INNER JOIN Suppliers supplier "
					."ON supplier.supplier_id = sellers_query.seller_id "
				.") AS sellers_users_together "
				."INNER JOIN Addresses ads "
				."ON ads.address_id = sellers_users_together.address_id;";

	}
	else
	{
		// else its a user
		$sql = 	"SELECT seller_type as 'Account Type', revenue as Revenue, login_name as 'User Name', email as Email, balance as Balance, birthday as Birthday, annual_income as 'Annual Income', gender as Gender, last_name as 'Last Name', first_name as 'First Name', street as Street, city as City, zipcode as Zipcode "
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
		$credit_card_sel = "SELECT card_number as 'Card Number', card_type as 'Card Type'" 
							."FROM CreditCards " 
							."WHERE user_id='".$seller['seller_id']."' LIMIT 1 ";

		$items_bought_query = "select * "
							."from ("
								."select * "
								."from Sales "
								."where user_id='".$seller['seller_id']."' "
							.") as s "
							."inner join Items i "
							."where s.item_id = i.item_id";

		
		$items_bought_query = selecct_query_multi($items_bought_query, $dbh);

		$credit_cards = selecct_query_multi($credit_card_sel, $dbh);
	}
	// $items_sold_query = 	"select * "
	// 						."from ("
	// 							."select * "
	// 							."from Sales "
	// 							."where seller_id='".$seller['seller_id']."' "
	// 						.") as s "
	// 						."inner join Items i "
	// 						."where s.item_id = i.item_id";
	
	$items_you_sold_q =  "select * "
							."from ("
								."select * "
								."from items "
								."where seller_id='".$seller['seller_id']."' "
							.") as i "
							."inner join sales s "
							."on s.item_id = i.item_id";

	$selling_query = 	"Select * "
						."from Items "
						."where seller_id = '".$seller['seller_id']."' and current_stock!=0";
	
//	echo $items_sold_query;
	$items_sold = selecct_query_multi($items_you_sold_q, $dbh);
	//echo $selling_query;
	$sellings = selecct_query_multi($selling_query, $dbh);



	$user_seller = selecct_query($sql, $dbh);

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
				
					echo '</tbody>'
						.'</table>'
						.'</div>';
			?>

			<?php
				if( !$is_supplier)
				{
					echo '<div class="table_div">'
						.'<table class="table-striped table-bordered">'
						.'<thead>'
							.'<th>'
								.'Card Number'
							.'</th>'
							.'<th>'
								.'Card Type'
							.'</th>'
						.'</thead>'
						.'<tbody>';

						while(($this_card = $credit_cards->fetch(PDO::FETCH_ASSOC)))
						{
							echo '<tr>' 
									.'<td class="key_td">'
										. $this_card["Card Type"]
									.'</td>'
									.'<td>'
										. $this_card["Card Number"]
									.'</td>'
								.'</tr>';

						}

					echo '</tbody>'
						.'</table>'
						.'</div>';
					
					echo 'Items you Bought '
						.'<div class="table_div">'
						.'<table class="table-striped table-bordered">'
						.'<thead>'
							.'<th>'
								.'Title'
							.'</th>'
							.'<th>'
								.'Sold for'
							.'</th>'
							.'<th>'
								.'Date'
							.'</th>'
						.'</thead>'
						.'<tbody>';

					while(($this_buy = $items_bought_query->fetch(PDO::FETCH_ASSOC)))
					{
						$sell_date = new DateTime($this_buy["sale_date"]);
						echo '<tr>' 
								.'<td class="key_td">'
									. $this_buy["title"]
								.'</td>'
								.'<td>'
									. $this_buy["buy_price"]
								.'</td>'
								.'<td>'
									. $sell_date->format('m/d/Y H:i:s')
								.'</td>'
							.'</tr>';

					}

					echo '</tbody>'
						.'</table>'
						.'</div>';
				

				}

				echo 'Current Items you are selling '
				.'<div class="table_div">'
				.'<table class="table-striped table-bordered">'
				.'<thead>'
					.'<th>'
						.'Title'
					.'</th>'
					.'<th>'
						.'Description'
					.'</th>'
				.'</thead>'
				.'<tbody>';

				while(($this_item = $sellings->fetch(PDO::FETCH_ASSOC)))
				{
					echo '<tr>' 
							.'<td class="key_td">'
								.'<a href="product.php?product_id='.$this_item["item_id"].'" >'
									. $this_item["title"]
								."</a>"
							.'</td>'
							.'<td>'
								.'<a href="product.php?product_id='.$this_item["item_id"].'" >'
									. $this_item["description"]
								."</a>"
							.'</td>'
						.'</tr>';

				}

			echo '</tbody>'
				.'</table>'
				.'</div>';


			echo 'Items you sold '
				.'<div class="table_div">'
				.'<table class="table-striped table-bordered">'
				.'<thead>'
					.'<th>'
						.'Title'
					.'</th>'
					.'<th>'
						.'Sold for'
					.'</th>'
					.'<th>'
						.'Quantity'
					.'</th>'
					.'<th>'
						.'Total'
					.'</th>'
					.'<th>'
						.'Date'
					.'</th>'
				.'</thead>'
				.'<tbody>';

			while(($this_buy = $items_sold->fetch(PDO::FETCH_ASSOC)))
			{
				$sell_date = new DateTime($this_buy["sale_date"]);
				echo '<tr>' 
						.'<td class="key_td">'
							. $this_buy["title"]
						.'</td>'
						.'<td>'
							. $this_buy["buy_price"]
						.'</td>'
						.'<td>'
							. $this_buy["quantity"]
						.'</td>'
						.'<td>'
							.($this_buy["buy_price"]*$this_buy["quantity"])
						.'</td>'
						.'<td>'
							. $sell_date->format('m/d/Y H:i:s')
						.'</td>'
					.'</tr>';

			}

			echo '</tbody>'
				.'</table>'
				.'</div>';
			?>				
		</div>
	</body>

</html>
