<?php
	$seller_id = uniq_id();
	$address_id = uniq_id();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>User Registration</title>
	<meta name="description" content="User Registration">
	<meta name="author" content="Brent Riva">
	
	<link rel="stylesheet" href="css/styles.css?v=1.0">
	<!--[if lt IE9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<script src="js/scripts.js"></script>
	<h1>User Registration:</h1>
	<br>
	<br>
	<form id="u_reg" class="registration_form" action="" method="post" name="registration_form">
		<ul>
			<li>
				<h2>User Information</h2>
				<span class="required_notification">* Denotes Required Field</span>
			</li>
			<li>
				<label for="login_name" required>*Username:</label>
				<input title="S" type="text" name="login_name" />
			</li>
			<li>
				<label for="pass" required>*Password:</label>
				<input title="S" type="password" name="pass" />
			</li>
			<li>
				<label for="email" required>*Email Address:</label>
				<input title="U" type="email" name="email" placeholder="Doe.John@gmail.com" />
			</li>
			<li>
				<label for="first_name">First Name:</label>
				<input title="U" type="text" name="first_name" />
			</li>
			<li>
				<label for="last_name">Last Name:</label>
				<input title="U" type="text" name="last_name" />
			</li>
			<li>
				<label for="birthday" required>*Birthday:</label>
				<input title="U" type="date" name="birthday" />
			</li>
			<li>
				<label for="annual_income">Annual Income:</label>
				<input title="U" type="number" name="annual_income" />
			</li>
			<li>
				<label for="gender">Sex:</label>
				<input title="U" type="text" name="gender" />
			</li>
			<li>			
				<h3>*Address Info</h3>
			</li>
			<li>
				<label for="street" required>*Street:</label>
				<input title="A" type="text" name="street" />
			</li>
			<li>
				<label for="city" required>*City:</label>
				<input title="A" type="text" name="city" />
			</li>
			<li>
				<label for="zipcode" required>*ZipCode:</label>
				<input title="A" type="number" name="zipcode" />
			</li>
			<li>
				<button onclick="register()" class="submit" type="submit">Register</button>
			</li>
		</ul>	
	</form>
	
	<script type="text/javascript">
		function register() {
			var i = 0;
			var table = 0;
			var x = document.getElementById("u_reg");
			
			// pull randomly generated IDs from PHP
			var seller_id = "<?php echo $seller_id; ?>";
			var address_id = "<?php echo $address_id; ?>";\
			
			// Queries for: Users, Sellers, Addresses (Respectively)
			var queries = ["INSERT INTO Users(user_id", "INSERT INTO Sellers(seller_id, address_id", "INSERT INTO Addresses (address_id"];
			var queries2 = [") VALUES (", ") VALUES (", ") VALUES ("];
			
			queries2[0] += + seller_id; // "...) VALUES ($seller_id..."
			queries2[1] += + seller_id + ", " + address_id; // "...) VALUES ($seller_id, $address_id..."
			queries2[2] += + seller_id; // "...) VALUES ($seller_id..."
					
			// pull user info from the form.
			for(i = 0; i < x.length; i++)
			{
				if(x.elements[i].title = "U") // add to user fields	
					table = 0;
				else if(x.elements[i].title = "S") // add to seller fields. all required, no need to null check.
					table = 1;
				else // send to address table
					table = 2;
					
				if (x.elements[i].value != "")
				{
					queries[table] += ", " + x.elements[i].name;
					queries2[table] += ", " + x.elements[i].value;
				}
			}
			
			// create queries
			val Uquery = queries[0] + queries2[0] + ");";
			val Squery = queries[1] + queries2[1] + ");";
			val Aquery = queries[2] + queries2[2] + ");";
			
			$.post('registrationScript.php', {Uquery, Squery, Aquery}, function(data){
				alert(data); // hopefully is 111. (1 row for each query)
			});
		}
	</script>
</body>
</html>