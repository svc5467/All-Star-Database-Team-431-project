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

?>
