<?php
	include "variables.php";
	include "authenticate.php";
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
	
			<div class="form-group">
				  <label for="gard_id">Gift Card:</label>
				  <input type="text" class="form-control" id="gard_id">
			</div>
		</div>
	</body>

</html>