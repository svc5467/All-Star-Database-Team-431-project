<?php



function get_set_vars()
{
	global $item_id;
	$item_id = intval($_GET['product_id']);
}	

function post_set_vars()
{
	global $item_id;
	$item_id = $_POST["item_id"];
}	

function get_post_independent_stuff_after_vars_set()
{
	global $item_id;
	global $item;
	global $dbh;
	global $is_auction;
	global $is_buy_it_now;
	global $max_bid;

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


function insert_into_db($insert, $dbh, $success_msg, $fail_msg)
{
	$insert_query = $dbh->query($insert);
	if($dbh->errorCode() == '0000' && $insert_query->rowCount() > 0)
	{
		echo  $success_msg;
		return true;
	}else
	{	
		echo $fail_msg."<br>";
		// echo "<br>msg: " . $dbh->errorInfo()[0];
		// echo "<br>msg: " . $dbh->errorInfo()[1];
		// echo "<br>msg: " . $dbh->errorInfo()[2];
		return false;
	}

}

function update_db($update, $dbh, $success_msg, $fail_msg)
{
	$update_query = $dbh->query($update);
	if($update_query->rowCount() > 0)
	{
		echo $success_msg;
		return true;
	}else
	{
		 echo $fail_msg."<br>";
		// echo "<br>msg: " . $dbh->errorInfo()[0];
		// echo "<br>msg: " . $dbh->errorInfo()[1];
		// echo "<br>msg: " . $dbh->errorInfo()[2];
		return false;
	}
}

				


include "assets/variables.php";
include "assets/authenticate.php";
$item_id;
$item;
$dbh;
//$dbh = new PDO("sqlite:".$database_url);
$is_buy_it_now;
$max_bid;




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
		
		// handle displaying data and forms


		get_set_vars();

		get_post_independent_stuff_after_vars_set();

		
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
	
	// sets post variables like item_id
	post_set_vars();

	$seller_id = "";
	
	
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

	get_post_independent_stuff_after_vars_set();



	if( isset($_POST["buy_it_now_pressed"]))
	{
		if($_POST["buy_it_now_pressed"] == "true")
		{
			// buy it now occured
			echo "buy_it_now pushed";
			$insert = "INSERT into Sales(user_id,item_id,sale_date,buy_price,quantity) "
					."VALUES('".$seller_id."',".$item_id.",'". date("Y-m-d H:i:s")."',".$item['Buy It Now Price'].",1)";

			// insert sale into db
			insert_into_db($insert, $dbh, "New Sale Made", "Sale Could Not be made");

			// update items
			$update = "UPDATE items "
						." set current_stock=".(intval($item['Amount Remaining']) - 1)
						." where item_id ='".$item_id."'";
			
			// update items (remove amount from count) and if successful redirect
			if(  update_db($update, $dbh, "Removed 1 from quantity field of item", "Failed to subtract one from quantity field of item"))
			{
				header('Location: product.php?product_id='.$item_id.'&was_bid='.$_POST["bid_button_pressed"].'&was_bin='.$_POST["buy_it_now_pressed"]);
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
				
				// if update was succesful redirect
				if( update_db($update,$dbh,"Bid Updated","Failed to update bid") )
				{
					header('Location: product.php?product_id='.$item_id.'&was_bid='.$_POST["bid_button_pressed"].'&was_bin='.$_POST["buy_it_now_pressed"]);
				
				}
			}
			else
			{
				$insert = "INSERT into Bids(user_id,sales_item_id,time_of_bid,amount) "
						."VALUES('".$seller_id."',".$item_id.",'". date("Y-m-d H:i:s")."',".$_POST['bid_amount'].")";

				if( insert_into_db($insert,$dbh, "New bid Made", "Failed to insert new bid"))
				{
					header('Location: product.php?product_id='.$item_id.'&was_bid='.$_POST["bid_button_pressed"].'&was_bin='.$_POST["buy_it_now_pressed"]);
				}
			}
		}
	} 
	if(isset($_POST["add_to_cart_pressed"]))
	{
		echo "in add_to_cart_pressed isset()";
		if($_POST["add_to_cart_pressed"] == "true")
		{
			$insert = "INSERT into ShoppingCarts(user_id, item_id) "
					." VALUES('".$seller_id."',".$item_id.")";
			echo "<br> ".$insert;
			// insert new record into sales
			if(insert_into_db($insert,$dbh,"<br><br><br><br>Added To Cart","Failed to add to cart"))
			{
				header('Location: cart.php');
			}

		}
		
	}
	else
	{
		echo "error form submission type not found";
	}

	//echo $_POST["add_to_cart_pressed"];
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

	function add_to_cart()
	{
		
		$('[name="add_to_cart_pressed"]').val('true');
		document.buttons_form.submit();
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
		<?php
			include "assets/includes.php";
		?>
		<link rel="Stylesheet" type="text/css" href="css/forms.css"/>
		
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

					echo '<div class="btn_div form-group">';
					
					if($is_buy_it_now)
					{
						echo '<div class="buy_it_now_btn_div form-group">'
								.'<input type="hidden" name="buy_it_now_pressed" value="false">'
								.'<button type="button" name="buy_it_now_button"  id="buy_it_now_button"  class="btn btn-primary btn-lg" onclick="buy_it_now()" value="bid">'
									."Buy It Now"
									.'<br>'
									.'$'.number_format($item["Buy It Now Price"],2)
								.'</button>'
							.'</div>';

					}
					if($is_auction)
					{
							echo '<div class="bid_btn_div form-group">'
									.'<input class="form-control" type="hidden" name="bid_button_pressed" value="false">'
									.'<button type="button" name="bid_button" id="bid_button" class="btn btn-primary btn-lg"  onclick="bid_button_func()"  value="bid" disabled>'
										."bid"
									.'</button>'
									.'<div class="input-group bid_input_div"> '
	        							.'<span class="input-group-addon">$</span> '					
										.'<input type="number" class="bid_input form-control" name="bid_amount" value="'.($max_bid + intval($min_bid_increment)).'" min="'.($max_bid + intval($min_bid_increment)).'" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency" id="c2" >'
									.'</div>'
							.'</div>';

					}
					echo '<div class="Add To Cart form-group">'
							.'<input type="hidden" name="add_to_cart_pressed" value="false">'
							.'<button type="button" name="add_to_cart_button"  id="add_to_cart_button"  class="btn btn-primary btn-lg" onclick="add_to_cart()" value="add_cart">'
								."Add To Cart"
							.'</button>'
						.'</div>';

					echo '</div >';
					
				?>
			
			</form>
			
		</div>
	</body>

</html>
