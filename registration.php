<?php
	include "assets/variables.php";
	$seller_id = uniqid();
	$unique_id = uniqid();
	$sum = 0;
	
	// convert the uniqueid to an integer
	for($i = 0; $i < strlen($unique_id); $i++)
	{
		if((ord($unique_id[$i])-96) < 0) // integer character
			$sum += intval($unique_id[$i]) * pow(10, $i);
		else
			$sum += (ord($unique_id[$i])-96) * pow(10, $i);
	}
	$address_id = $sum;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>User Registration</title>
	<meta name="description" content="User Registration">
	<meta name="author" content="Brent Riva">
	<?php

		include "assets/includes.php";
	?>
	<!-- <link rel="stylesheet" href="css/stylesheet.css"> -->
	<!--[if lt IE9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<div class="big_container">
		<h1>User Registration:</h1>
		<form id="u_reg" class="registration_form"  action="javascript:register()" method="post" name="registration_form">
			<div class = "form-group">
				<h2>User Information</h2>
				<span class="required_notification">* Denotes Required Field</span>
			</div>
			<div class = "form-group">
				<label for="login_name" required>Username*:</label>
				<input class="form-control" title="S" type="text" name="login_name" required/>
			</div>
			<div class = "form-group">
				<label for="pass" required>Password*:</label>
				<input class="form-control" title="S" type="password" name="pass" required/>
			</div>
			<div class = "form-group">
				<label for="email" required>Email Address*:</label>
				<input class="form-control" title="U" type="email" name="email" placeholder="Doe.John@gmail.com" required/>
			</div>
			<div class = "form-group">
				<label for="first_name">First Name:</label>
				<input class="form-control" title="U" type="text" name="first_name" />
			</div>
			<div class = "form-group">
				<label for="last_name">Last Name:</label>
				<input class="form-control" title="U" type="text" name="last_name" />
			</div>
			<div class = "form-group">
				<label for="birthday" required>Birthday*:</label>
				<input class="form-control" title="U" type="date" name="birthday" placeholder="YYYY-MM-DD"required/>
			</div>
			<div class = "form-group">
				<label for="annual_income">Annual Income:</label>
				<input class="form-control" title="U" type="number" name="annual_income" />
			</div>
			<div class = "form-group">
				<label for="gender">Sex:</label>
				<input class="form-control" title="U" type="text" name="gender" />
			</div>
			<div class = "form-group">
				<h3>Address Info</h3>
			</div>
			<div class = "form-group">
				<label for="street" required>Street*:</label>
				<input class="form-control" title="A" type="text" name="street" required/>
			</div>
			<div class = "form-group">
				<label for="city" required>City*:</label>
				<input class="form-control" title="A" type="text" name="city" required/>
			</div>
			<div class = "form-group">
				<label for="zipcode" required>ZipCode*:</label>
				<input class="form-control" title="A" type="number" name="zipcode" required/>
			</div>
			<div class = "form-group">
				<input class="form-control btn btn-primary btn-lrg"  type="submit" value="Submit"/>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		function register() {			
			//alert("Register was called");
			
			var request;
			
			var i = 0;
			var table = 0;
			var x = document.getElementById("u_reg");
			
			// pull randomly generated IDs from PHP
			var seller_id = "<?php echo $seller_id; ?>;";
			var address_id = <?php echo $address_id; ?>;
			var website_url = "<?php echo $website_url; ?>";
			
			// Queries for: Users, Sellers, Addresses (Respectively)
			var queries = ["INSERT INTO Users(user_id, balance", "INSERT INTO Sellers(seller_id, address_id, seller_type, revenue", "INSERT INTO Addresses (address_id"];
			var queries2 = [") VALUES (", ") VALUES (", ") VALUES ("];
			
			queries2[0] += "'" + seller_id + "', 0"; // "...) VALUES ($seller_id..."
			queries2[1] += "'" + seller_id + "', " + address_id + ", 'User', 0"; // "...) VALUES ($seller_id, $address_id..."
			queries2[2] += "'" + address_id + "'"; // "...) VALUES ($address_id..."
					
			// pull user info from the form.
			for(i = 0; i < x.length; i++)
			{
				if(x.elements[i].title == "U") // add to user fields	
					table = 0;
				else if(x.elements[i].title == "S") // add to seller fields. all required, no need to null check.
					table = 1;
				else if (x.elements[i].title == 'A') // send to address table
					table = 2;
				else  // look at the three cases. USA! woo. haha.
					table = -1 // error, dont add to query.
				
				if (x.elements[i].value != "" && table >= 0)
				{
					queries[table] += ", " + x.elements[i].name;
					if(x.elements[i].type != "number")
						queries2[table] += ", '" + x.elements[i].value + "'";
					else
						queries2[table] += ", " + x.elements[i].value;
				}
			}
			
			// create queries
			var Uquery = queries[0] + queries2[0] + ");";
			var Squery = queries[1] + queries2[1] + ");";
			var Aquery = queries[2] + queries2[2] + ");";

			request = $.ajax({
				url: (website_url+"/registrationScript.php"),
				type: "post",
				data: {q1:Uquery, q2:Squery, q3:Aquery},
				
				success: function(html){
					alert("Successful User Registration!");
					window.location.href = website_url+"/index.php";
				},
				failure: function(html){
					alert(html);
				}
			});
		}
	</script>
</body>
</html>