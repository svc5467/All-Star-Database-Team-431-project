<?php
	include "variables.php";
	include "authenticate.php";
	
	if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_GET['was_bid']))
		{
			if($_GET['was_bid'] == "true")
			{
				echo "<div class='alert alert-success'> "
						."<strong>Bid Successful!</strong> You successfully made a bid"
					."</div>";
			}
		}
		if(isset($_GET['was_bin']))
		{
			if($_GET['was_bin'] == "true")
			{
				echo "<div class='alert alert-success'> "
						."<strong>Buy Successful!</strong> You successfully made a buy-it-now purchase"
					."</div>";
			}
		}

		if(isset($_GET['product_id']))
		{
			$item_id = $_GET['product_id'];

			$dbh = new PDO("sqlite:".$database_url);
			$sql = 	"SELECT i.title AS 'Title', i.buy_it_now_price as 'Buy It Now Price', i.minimum_price AS 'Minimum Price', i.current_stock AS 'Amount Remaining', i.auction_length AS 'Auction Length', i.date_posted AS 'Date Posted', i.description AS 'description', cats.name AS 'Category' "
					."FROM ( "
						."SELECT * " 
						."FROM Items " 
						."WHERE item_id='$item_id' LIMIT 1 "
						.") as i "
					."INNER JOIN Categories As cats "
					."ON cats.category_id = i.category_id ";

			$items_query = $dbh->query($sql);
			$item = $items_query->fetch(PDO::FETCH_ASSOC);

			$is_auction = TRUE;
			$max_bid = $item['Minimum Price'];
			
			if($item["Auction Length"] == 0)
			{
				$is_auction = FALSE;
			}
			// else is auction
			else
			{
				$sql2 = "SELECT bids.sales_item_id AS item_id, MAX(bids.amount) as current_bid "
						."FROM ( "
							."SELECT * " 
							."FROM Items " 
							."WHERE item_id='$item_id' LIMIT 1 "
							.") AS i "
						."INNER JOIN Bids As bids "
						."ON bids.sales_item_id = i.item_id "
						."GROUP BY bids.sales_item_id ";

	

				$highest_bid_query = $dbh->query($sql2);
				if($highest_bid_query)
				{
						$highest_bid = $highest_bid_query->fetch(PDO::FETCH_ASSOC);
										
						//echo $highest_bid["current_bid"];
	
						if( isset($highest_bid["current_bid"]))
						{
							if($max_bid < $highest_bid["current_bid"])
							{
								$max_bid=$highest_bid["current_bid"];
							}	
						}
				}else
				{
					echo "query error";
				}

			}
				
			$is_buy_it_now = TRUE;
			if($item['Buy It Now Price'] == 0)
				$is_buy_it_now = FALSE;

		}
		else
		{
			header('Location: shop.php?item_not_found=true');

		}
	
	} 
	// else its a post
	else
	{
		// in post

		$seller_id = "";
		$item_id = $_POST["item_id"];
		
		$dbh = new PDO("sqlite:".$database_url);
		
		if (!isset($_COOKIE['username'] ) )
		{
	     	header('Location: index.php?access_denied=true');
		}
		else
		{
			$login_name =$_COOKIE['username'];
			$user_seller = $dbh->query("SELECT * FROM Sellers WHERE login_name='$login_name' LIMIT 1");
			$this_user_seller = $user_seller->fetch(PDO::FETCH_ASSOC);
			$seller_id = $this_user_seller["seller_id"];
		}

		$sql = 	"SELECT i.title AS 'Title', i.buy_it_now_price as 'Buy It Now Price', i.minimum_price AS 'Minimum Price', i.current_stock AS 'Amount Remaining', i.auction_length AS 'Auction Length', i.date_posted AS 'Date Posted', i.description AS 'description', cats.name AS 'Category' "
				."FROM ( "
					."SELECT * " 
					."FROM Items " 
					."WHERE item_id='$item_id' LIMIT 1 "
					.") as i "
				."INNER JOIN Categories As cats "
				."ON cats.category_id = i.category_id ";

		$items_query = $dbh->query($sql);
		$item = $items_query->fetch(PDO::FETCH_ASSOC);

		$is_auction = TRUE;
		$max_bid = $item['Minimum Price'];
		
		if($item["Auction Length"] == 0)
		{
			$is_auction = FALSE;
		}
		$is_buy_it_now = TRUE;
		if($item['Buy It Now Price'] == 0)
			$is_buy_it_now = FALSE;


		if( isset($_POST["buy_it_now_pressed"]))
		{
			if($_POST["buy_it_now_pressed"] == "true")
			{
				// buy it now occured
				echo "buy_it_now pushed";
				$insert = "INSERT into Sales(user_id,item_id,sale_date,buy_price,quantity) "
						."VALUES('".$seller_id."',".$item_id.",'". date("Y-m-d H:i:s")."',".$item['Buy It Now Price'].",1)";
				// insert new record into sales
				$insert_query = $dbh->query($insert);
				if($dbh->errorCode() == '0000' && $insert_query->rowCount() > 0)
				{
					echo "New Sale Made";
				}else
				{
					 echo "Error: ". $dbh->errorCode();
				}


				echo "<br>";
				$update = "UPDATE items "
							." set current_stock=".(intval($item['Amount Remaining']) - 1)
							." where item_id ='".$item_id."'";
				$update_query = $dbh->query($update);
				if($update_query->rowCount() > 0)
				{
					echo "Item removed from quantity field";
					header('Location: product.php?product_id='.$item_id.'&was_bid='.$_POST["bid_button_pressed"].'&was_bin='.$_POST["buy_it_now_pressed"]);
				}else
				{
					 echo "Error: " . $dbh->errorCode();
				}
			
			}
		}

		if( isset($_POST["bid_button_pressed"]))
		{
			if($_POST["bid_button_pressed"] == "true")
			{
				// a bid occured -> insert bid
				echo "bid_button pushed";
				$bid_if_exists = "Select * from Bids where user_id ='".$seller_id."' AND sales_item_id=".$item_id;
				$existstance_query =  $dbh->query($bid_if_exists);
				$result = $existstance_query->fetch(PDO::FETCH_ASSOC);

				if(isset($result['sales_item_id']))
				{
					$update = 	"Update Bids "
								."set user_id='".$seller_id."', sales_item_id=".$item_id.",time_of_bid='". date("Y-m-d H:i:s")."', amount=".$_POST['bid_amount']." "
								." where user_id ='".$seller_id."' AND sales_item_id=".$item_id;
					//echo "Error: " . $dbh->errorCode();
					echo "<br>".$update;
					$update_query = $dbh->query($update);
					//$update_result = $update_query->fetch(PDO::FETCH_ASSOC);
					echo "<br>";
					if( $update_query->rowCount() > 0)
					{
						echo "Bid Updated";
						header('Location: product.php?product_id='.$item_id.'&was_bid='.$_POST["bid_button_pressed"].'&was_bin='.$_POST["buy_it_now_pressed"]);
					}else
					{
						 echo "Error: " . $dbh->errorCode();
					}
				}
				else
				{
					$insert = "INSERT into Bids(user_id,sales_item_id,time_of_bid,amount) "
							."VALUES('".$seller_id."',".$item_id.",'". date("Y-m-d H:i:s")."',".$_POST['bid_amount'].")";

					echo "<br> ".$insert;
					// insert new record into sales
					$insert_query = $dbh->query($insert);
					if($insert_query->rowCount() > 0)
					{
						echo "New Bid Made";
						header('Location: product.php?product_id='.$item_id.'&was_bid='.$_POST["bid_button_pressed"].'&was_bin='.$_POST["buy_it_now_pressed"]);
					}else
					{
						 echo "Error: " . $dbh->errorCode();
					}

				}


			}
		}

		echo "request faild";
		
		//header('Location: shop.php');
	}

function set_up_table_echo()
{
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
}

function set_up_end_table_echo()
{
	echo 		'</tbody>'
			.'</table>'
		.'</div>';
}

function print_table_row($key,$var)
{
echo 					'<tr>' 
							.'<td class="key_td">'
								. $key 
							.'</td>'
							.'<td>'
								. $var
							.'</td>'
						.'</tr>';

}

?>

<script type="text/javascript">
	function buy_it_now()
	{
		$('[name="buy_it_now_pressed"]').val('true');
		document.buttons_form.submit();
	}
	function bid_button_func()
	{
		max_bid = <?php echo $max_bid; ?>;
		if($(".bid_input").val() > max_bid)
		{
			$('[name="bid_button_pressed"]').val('true');
			document.buttons_form.submit();
		}
	}

	window.onload = function()
	{
		$(".bid_input").click(function()
		{
			$("#bid_button").prop("disabled", false);
		})
	};

</script>

<!DOCTYPE html>
<html>
	<head>
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/css/stylesheet.css";?>"/>
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/assets/bootstrap-3.3.6-dist/css/bootstrap.css";?>"/>	
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/css/table_style.css";?>"/>	
		<link rel="Stylesheet" type="text/css" href="<?php echo $website_url."/css/table_style.css";?>"/>	
		<script src="<?php echo $website_url."/assets/jquery-1.12.3.min.js";?>"></script>
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
				if($is_auction)
				{

					set_up_table_echo();

					foreach($item as $key => $var)
					{
						if($key == 'Auction Length')
						{
							//echo $var;
							$end_date = new DateTime($item["Date Posted"]);
							//echo ('PT'.intval($var)."S" );
							$end_date->add(new DateInterval('PT'.intval($var)."S" ));
							//echo $end_date->format('Y-m-d H:i:s');
							print_table_row("Auction End", $end_date->format('m/d/Y H:i:s'));
						}
						else if($key == 'Date Posted')
						{
							$date_posted = new DateTime($item["Date Posted"]);
							print_table_row("Date Posted", $date_posted->format('m/d/Y H:i:s'));

						}else if( $key == 'Buy It Now Price' && $is_buy_it_now)
						{
							print_table_row($key,'$'.number_format( $var, 2 ));
						}else if( $key == "Minimum Price")
						{
							print_table_row($key,'$'.number_format( $var, 2 ));
						}else
						{
							print_table_row($key, $var);
						}
					}

					print_table_row("Current Bid",'$'.number_format( $max_bid, 2 ));

					set_up_end_table_echo();


				}
				else
				{
					set_up_table_echo();

					foreach($item as $key => $var)
					{
						print_table_row($key, $var);

					}

					set_up_end_table_echo();

				}

			?>

			<form method="post" id="buttons_form" name="buttons_form" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?product_id='.$item_id); ?> >
				<input type="hidden" name="item_id" value = <?php echo $item_id ?> >
				<?php
					/// add buy it now button if there is a buy-it-now price

					if($is_buy_it_now)
					{
						echo '<div class="buy_it_now_btn_div">'
								.'<input type="hidden" name="buy_it_now_pressed" value="false">'
								.'<button type="button" name="buy_it_now_button"  id="buy_it_now_button"  class="btn btn-primary btn-lg" onclick="buy_it_now()" value="b" value="bid">'
									."Buy It Now"
									.'<br>'
									.'$'.number_format($item["Buy It Now Price"],2)
								.'</button>'
							.'</div>';

					}
					if($is_auction)
					{
							echo '<div class="bid_btn_div">'
								.'<input type="hidden" name="bid_button_pressed" value="false">'
								.'<button type="button" name="bid_button" id="bid_button" class="btn btn-primary btn-lg"  onclick="bid_button_func()"  value="bid" disabled>'
									."bid"
								.'</button>'
								.'<div class="input-group"> '
        							.'<span class="input-group-addon">$</span> '					
									.'<input type="number" class="bid_input" name="bid_amount" value="'.($max_bid + intval($min_bid_increment)).'" min="'.($max_bid + intval($min_bid_increment)).'" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency" id="c2" >'
								.'</div>'
							.'</div>';

					}
				?>
			
			</form>
			
		</div>
	</body>

</html>
