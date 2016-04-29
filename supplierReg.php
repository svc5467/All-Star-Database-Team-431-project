<?php
	include "assets/includes.php";
	$seller_id = uniqid();
	$unique_id = uniqid();
	$sum = 0;
	
	// convert the uniqueid to an integer
	for($i = 0; $i < strlen($unique_id); $i++)
	{
		if((ord($unique_id[$i])-96) < 0) // integer character
			$sum += intval($unique_id[$i]) * (10** $i);
		else
			$sum += (ord($unique_id[$i])-96) * (10**$i);
	}
	$address_id = $sum;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Supplier Registration</title>
	<meta name="description" content="Supplier Registration">
	<meta name="author" content="Brent Riva">
	
	<link rel="stylesheet" href="css/stylesheet.css">
	<!--[if lt IE9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<h1>Supplier Registration:</h1>
	<form id="u_reg" class="registration_form" align="center" action="" method="post" name="registration_form">
		<ul>
			<li>
				<h2>Supplier Information</h2>
				<span class="required_notification">* Denotes Required Field</span>
			</li>
			<li>
				<label for="login_name" required>Username*:</label>
				<input title="S" type="text" name="login_name" />
			</li>
			<li>
				<label for="pass" required>Password*:</label>
				<input title="S" type="password" name="pass" />
			</li>
			<li>
				<label for="phone" required>Phone Number*:</label>
				<input title="U" type="tel" name="phone"/>
			</li>
			<li>
				<label for="name" required>Name:</label>
				<input title="U" type="text" name="name" />
			</li>
			<li>			
				<h3>Address Info</h3>
			</li>
			<li>
				<label for="street" required>Street*:</label>
				<input title="A" type="text" name="street" />
			</li>
			<li>
				<label for="city" required>City*:</label>
				<input title="A" type="text" name="city" />
			</li>
			<li>
				<label for="zipcode" required>ZipCode*:</label>
				<input title="A" type="number" name="zipcode" />
			</li>
			<li>
				<input type="button" value="Submit" onclick="register()"/>
			</li>
		</ul>	
	</form>
	
	<script type="text/javascript">
		function register() {			
			alert("Register was called");
			
			var request;
			
			var i = 0;
			var table = 0;
			var x = document.getElementById("u_reg");
			
			// pull randomly generated IDs from PHP
			var seller_id = "<?php echo $seller_id; ?>";
			var address_id = <?php echo $address_id; ?>;
			
			// Queries for: Users, Sellers, Addresses (Respectively)
			var queries = ["INSERT INTO Suppliers(supplier_id", "INSERT INTO Sellers(seller_id, address_id, seller_type, revenue", "INSERT INTO Addresses (address_id"];
			var queries2 = [") VALUES (", ") VALUES (", ") VALUES ("];
			
			queries2[0] += "'" + seller_id + "'"; // "...) VALUES ($seller_id..."
			queries2[1] += "'" + seller_id + "', " + address_id + ", 'Supplier', 0"; // "...) VALUES ($seller_id, $address_id..."
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
			
			alert(Aquery);
			alert(Squery);
			alert(Uquery);
			
			request = $.ajax({
				url: "http://localhost:8080/AllStarDB/registrationScript.php",
				type: "post",
				data: {q1:Uquery, q2:Squery, q3:Aquery},
				
				success: function(html){
					alert("Successful User Registration!");
					alert(html);
				},
				failure: function(html){
					alert(html);
				}
			});
		}
	</script>
</body>
</html>
