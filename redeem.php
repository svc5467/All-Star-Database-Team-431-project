<?php
	include "assets/variables.php";
	include "assets/authenticate.php";

	
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
				<?php echo $this_user_seller["login_name"].", Enter a Giftcard" ?>
			</div>
	
			<div class="redeem_div">
				<form method="get" autocomplete="off" class="form-signin" action="shop.php">
					<fieldset class="form-group">
						<label for="gard_id">Gift Card:</label>
						<input type="text" class="form-control" id="gard_id" required>
						<input type="submit" class="links btn btn-lg btn-primary btn-block" value="Submit" onclick="window.">
					</fieldset>
				</form>
			</div>


		</div>
	</body>

</html>