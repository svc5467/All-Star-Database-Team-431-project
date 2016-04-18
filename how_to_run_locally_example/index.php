<?php

$dbh = new PDO("sqlite:c:/xampp/htdocs/allStarDB/allstar.sqlite");

// Selects the row that contains the user's house.
$giftcardrows = $dbh->query("SELECT * FROM GiftCards");
$addressrows = $dbh->query("SELECT * FROM Addresses");
$bidrows = $dbh->query("SELECT * FROM Bids");
$categoryrows = $dbh->query("SELECT * FROM Categories");
$containrows = $dbh->query("SELECT * FROM Contains");

?>

<html>
	<body>
		<h1>
			Gift Cards
		</h1>
		<table>
			<tr>
				<td> Code </td>
				<td> Amount </td>
			<?php
				while($row = $giftcardrows->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php echo $row["code"]; ?></td>
						<td><?php echo $row["amount"]; ?></td>
					</tr>
					<?php
				}
			?>
		
		</table>
		
		
		<h1>
			Addresses
		</h1>
		<table>
			<tr>
				<td> Address Id </td>
				<td> Street </td>
				<td> City </td>
				<td> Zipcode </td>
			<?php
				while($row = $addressrows->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php echo $row["address_id"]; ?></td>
						<td><?php echo $row["street"]; ?></td>
						<td><?php echo $row["city"]; ?></td>
						<td><?php echo $row["zipcode"]; ?></td>
					</tr>
					<?php
				}
			?>
		
		</table>
		
		
		<h1>
			Bids
		</h1>
		<table>
			<tr>
				<td> User Id </td>
				<td> Sales Item Id </td>
				<td> Time of Bid </td>
				<td> Amount </td>
				<td> Description </td>
			<?php
				while($row = $bidrows->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php echo $row["user_id"]; ?></td>
						<td><?php echo $row["sales_item_id"]; ?></td>
						<td><?php echo $row["time_of_bid"]; ?></td>
						<td><?php echo $row["amount"]; ?></td>
						<td><?php echo $row["description"]; ?></td>
					</tr>
					<?php
				}
			?>
		
		</table>
		
		<h1>
			Categories
		</h1>
		<table>
			<tr>
				<td> Category Id </td>
				<td> Name </td>
				<td> Description </td>
			<?php
				while($row = $categoryrows->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php echo $row["category_id"]; ?></td>
						<td><?php echo $row["name"]; ?></td>
						<td><?php echo $row["description"]; ?></td>
					</tr>
					<?php
				}
			?>
		
		</table>
		
		<h1>
			Contains
		</h1>
		<table>
			<tr>
				<td> Parent Category Id </td>
				<td> Child Category Id </td>
			<?php
				while($row = $containrows->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php echo $row["parent_category_id"]; ?></td>
						<td><?php echo $row["child_category_id"]; ?></td>
					</tr>
					<?php
				}
			?>
		
		</table>
		
		
		
		
	</body>
</html>