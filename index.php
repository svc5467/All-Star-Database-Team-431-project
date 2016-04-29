<?php
	include "assets/variables.php";

	$error = "";
	$redirection_notice = "You must login to access that page";
	$access_denied = "";
	$dbh = new PDO("sqlite:".$database_url);
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		// Checks that the fields were filled.
     	if($password && $username)
     	{
     		// Counts the number of rows that have the same email. The number is stored in variable "count".
			$statement = $dbh->query("SELECT COUNT(*) FROM Sellers WHERE login_name='".$username."'")->fetch();
			$count = $statement[0];
						
			// If the user exists, we continue, if not, error is thrown.
			if($count != 0)
			{
				$result = $dbh->query("SELECT * FROM Sellers WHERE login_name='".$username."' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
				
				$dbpassword = $result['pass'];
			
				if($password == $dbpassword)
				{
					// user as been authenticated
					// set cookie
					setcookie('username', $_POST['username'], time()+60*60);
					header("location: $website_url/shop.php");
				}
				else
				{
					$error = "<font color=\"red\" size=\"2\">Try again.</font>";
				}
			}
			else
			{
				$error = "<font color=\"red\" size=\"2\">Try again.</font>";
			}
     	}
     	else
     	{
	     	$error = "<font color=\"red\" size=\"2\"> Please complete all fields.</font>";
     	}
	}
	else
	{
		if(isset($_GET['access_denied']))
			$access_denied = $_GET['access_denied'];

		$username = "";
		$password = "";
	}
		

?>

<html>
	<head>
	


    <link rel="Stylesheet" href="assets/bootstrap-3.3.6-dist/css/bootstrap.css"/>
	<link href="css/stylesheet.css" rel="stylesheet">
	<link href="css/signin.css" rel="stylesheet">

	</head>
	<body>

		<?php 
			if ($error != "")
			{
				echo '<div class="alert alert-danger">'
						.$error
					."</div>";
			}

			if ($access_denied == "true")
			{
				echo '<div class="alert alert-danger">' 
						.$redirection_notice
					."</div>";
			}
		?>

		
			

		<div class="container">
			<form method="post" autocomplete="off" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h2 class="form-signin-heading">Log Into AllstarDB</h2>
					
                <label for="inputEmail" class="sr-only">Email address</label>
				<input type="text" class="form-control" name="username" maxlength="10" size="15" placeholder="Username"	 autofocus="" required="" value="<?php echo $username;?>">

				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" class="form-control" id="inputPassword" name="password" maxlength="50" size="15" placeholder="Password" required=""> 

				<input type="submit" class="links btn btn-lg btn-primary btn-block" value="Login">

			</form>

            <div class = "create_accounts">

                <a class = "btn btn-primary" type="button" href="registration.php">Create User Account</a>
                <a class = "btn btn-primary" type="button" href="supplierReg.php">Create Supplier Account</a>

            </div>
          


  	  	</div> <!-- /container -->



 
	</body>
</html>

