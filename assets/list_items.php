<?php

function display_single_item($row)
{
	global $website_url;

	$onclick_func = '"document.location=\'product.php?product_id='.$row['item_id'].'\'"';
	echo //"document.location = '."'product.php?product_id='".$row['item_id'].'
			'<tr class="clickable-item-row" onclick='.$onclick_func.' >'
				.'<td>'
					.$row['title']
				.'</td>'
				.'<td>'
					.'$'.number_format($row['buy_it_now_price'],2)
				.'</td>'
				.'<td>'
					.$row['description'] 
				.'</td>'
			.'</tr>';
}

function list_items($itemRows,$table_header)
{
	echo '<script type="text/javascript" src="js/item_list_links.js"></script>'
		.'<div class = "product_grid">'
			.'<table class = "product_table table table-striped table-hover">'
				.'<thead >'
					.'<tr>'
						.'<th colspan="3">'
							.$table_header
						.'</th>'
					.'</tr>'
					.'<tr>'
						.'<th>'
							.'Name'
						.'</th>'
						.'<th>'
							.'Price'
						.'</th>'
						.'<th>'
							.'Description'
						.'</th>'
					.'</tr>'
				.'</thead>'
				.'<tbody>';

				while($row = $itemRows->fetch(PDO::FETCH_ASSOC)) 
				{
					display_single_item($row);
					
				}		

	echo 		'</tbody>'
			.'</table>'
		.'</div>';
}

function display_single_sale($this_buy)
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
					. $sell_date->format('m/d/Y H:i:s')
				.'</td>'
			.'</tr>';
}

function list_sale($items_sold, $header)
{
	echo '<div class="table_div">'
					.'<table class="table table-striped">'
						.'<thead>'
							.'<tr >'
								.'<th colspan="4">'
									.$header
								.'</th>'
							.'</tr>'
							.'<tr>'
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
									.'Date'
								.'</th>'
							.'</tr>'
						.'</thead>'
					.'<tbody>';

			while(($this_buy = $items_sold->fetch(PDO::FETCH_ASSOC)))
			{
				display_single_sale($this_buy);
			}

			echo 		'</tbody>'
					.'</table>'
				.'</div>';
						
}

?>
