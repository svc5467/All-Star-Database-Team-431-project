<?php

<<<<<<< HEAD
//$dbh = new PDO("sqlite:/pass/users/a/s/asm5453/www/allstar.sqlite");
$dbh = new PDO("sqlite:c:/xampp/htdocs/AllstarDB/allstar.sqlite");
=======
	$dbh = new PDO("sqlite:/pass/users/a/s/asm5453/www/allstar.sqlite");
	
	$error = "";
>>>>>>> 39a78e5a8ac5146f1f2a07c44f1d92fc17459ca0

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
					header("location: http://php.scripts.psu.edu/asm5453/shop.php");
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
		$username = "";
		$password = "";
	}
		
		

?>

<html>
	<body>
		<h1>
			Login
		</h1>
		<h6>
			<?php echo $error; ?>
		</h6>
		
		<form method="post" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<table>
				<tr>
					<td>
						<input type="text" name="username" maxlength="10" size="15" placeholder="Username"
						 value="<?php echo $username;?>">
					</td>
				</tr>
				<tr>
					<td>
						<input type="password" name="password" maxlength="50" size="15" placeholder="Password"> 
					</td>
				</tr>
			</table>
			<p>
				<!--<a href="javascript:history.go(-1)" class="links">&#8592; Back</a>-->
				<input type="submit" class="links" value="Login">
			</p>
		</form>
	</body>
</html>